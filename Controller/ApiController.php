<?php
/**
 * Jingga
 *
 * PHP Version 8.1
 *
 * @package   Modules\ClientManagement
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace Modules\ClientManagement\Controller;

use Modules\Admin\Models\Account;
use Modules\Admin\Models\AccountMapper;
use Modules\Admin\Models\AddressMapper;
use Modules\Admin\Models\NullAccount;
use Modules\Auditor\Models\Audit;
use Modules\Auditor\Models\AuditMapper;
use Modules\ClientManagement\Models\Attribute\ClientAttributeTypeMapper;
use Modules\ClientManagement\Models\Client;
use Modules\ClientManagement\Models\ClientL11nMapper;
use Modules\ClientManagement\Models\ClientL11nTypeMapper;
use Modules\ClientManagement\Models\ClientMapper;
use Modules\ClientManagement\Models\PermissionCategory;
use Modules\ClientManagement\Models\SettingsEnum;
use Modules\Media\Models\MediaMapper;
use Modules\Media\Models\PathSettings;
use Modules\Organization\Models\UnitMapper;
use phpOMS\Account\PermissionType;
use phpOMS\Api\EUVAT\EUVATVies;
use phpOMS\Localization\BaseStringL11n;
use phpOMS\Localization\BaseStringL11nType;
use phpOMS\Localization\ISO3166CharEnum;
use phpOMS\Localization\ISO639x1Enum;
use phpOMS\Localization\NullBaseStringL11nType;
use phpOMS\Message\Http\HttpRequest;
use phpOMS\Message\Http\HttpResponse;
use phpOMS\Message\Http\RequestStatusCode;
use phpOMS\Message\NotificationLevel;
use phpOMS\Message\RequestAbstract;
use phpOMS\Message\ResponseAbstract;
use phpOMS\Model\Message\FormValidation;
use phpOMS\Utils\StringUtils;
use phpOMS\Validation\Finance\EUVat;

/**
 * ClientManagement class.
 *
 * @package Modules\ClientManagement
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 *
 * @todo Import client prices from csv/excel sheet
 *      https://github.com/Karaka-Management/oms-ClientManagement/issues/17
 *
 * @todo Perform inflation increase on all client prices
 *      https://github.com/Karaka-Management/oms-ClientManagement/issues/18
 */
final class ApiController extends Controller
{
    /**
     * Find client by account
     *
     * @param int $account Account id
     * @param int $unit    Unit id
     *
     * @return null|Client
     *
     * @since 1.0.0
     */
    public function findClientForAccount(int $account, ?int $unit = null) : ?Client
    {
        $clientMapper = ClientMapper::get()
            ->where('account', $account);

        if (!empty($unit)) {
            $clientMapper->where('unit', $unit);
        }

        /** @var \Modules\ClientManagement\Models\Client $client */
        $client = $clientMapper->execute();

        return $client->id === 0 ? null : $client;
    }

    public function setVAT(RequestAbstract $request, Client $client) : void
    {
        /** @var \Modules\Attribute\Models\AttributeType $type */
        $type = ClientAttributeTypeMapper::get()->with('defaults')->where('name', 'vat_id')->execute();

        $internalRequest  = new HttpRequest();
        $internalResponse = new HttpResponse();

        $internalRequest->header->account = $request->header->account;
        $internalRequest->setData('ref', $client->id);
        $internalRequest->setData('type', $type->id);
        $internalRequest->setData('value', $request->getDataString('vat_id'));

        $this->app->moduleManager->get('ClientManagement', 'ApiAttribute')
            ->apiClientAttributeCreate($internalRequest, $internalResponse);
    }

    public function validateVAT(RequestAbstract $request, Client $client) : array
    {
        /** @var \Modules\Organization\Models\Unit $unit */
        $unit = UnitMapper::get()
            ->with('attributes')
            ->where('id', $this->app->unitId)
            ->execute();

        $validate = ['status' => -1];

        // Has to be in EU and match VAT pattern before we perform external API request
        if (\in_array($client->mainAddress->country, ISO3166CharEnum::getRegion('eu'))
            && EUVat::isValid($request->getDataString('vat_id') ?? '')
        ) {
            $validate = EUVATVies::validateQualified(
                $request->getDataString('vat_id') ?? '',
                $unit->getAttribute('vat_id')->value->valueStr ?? '',
                $client->account->name1,
                $client->mainAddress->city,
                $client->mainAddress->postal,
                $client->mainAddress->address
            );
        }

        $audit = new Audit(
            new NullAccount($request->header->account),
            null,
            (string) ($validate['status'] ?? ''),
            StringUtils::intHash(EUVATVies::class),
            'vat_validation',
            self::NAME,
            (string) $client->id,
            (string) \json_encode($validate),
            (int) \ip2long($request->getOrigin())
        );

        AuditMapper::create()->execute($audit);

        return $validate;
    }

    public function defineTaxCode(RequestAbstract $request, Client $client) : void
    {
        /** @var \Modules\Organization\Models\Unit $unit */
        $unit = UnitMapper::get()
            ->with('mainAddress')
            ->where('id', $this->app->unitId)
            ->execute();

        /** @var \Modules\Attribute\Models\AttributeType $type */
        $type = ClientAttributeTypeMapper::get()
            ->with('defaults')
            ->where('name', 'sales_tax_code')
            ->execute();

        $value = $this->app->moduleManager->get('Billing', 'ApiTax')
            ->getClientTaxCode($client, $unit->mainAddress);

        $internalRequest  = new HttpRequest();
        $internalResponse = new HttpResponse();

        $internalRequest->header->account = $request->header->account;
        $internalRequest->setData('ref', $client->id);
        $internalRequest->setData('type',  $type->id);
        $internalRequest->setData('value_id', $value->id);

        $this->app->moduleManager->get('ClientManagement', 'ApiAttribute')
            ->apiClientAttributeCreate($internalRequest, $internalResponse, ['type' => $type]);
    }

    /**
     * Api method to create news article
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param array            $data     Generic data
     *
     * @return void
     *
     * @api
     *
     * @since 1.0.0
     */
    public function apiClientCreate(RequestAbstract $request, ResponseAbstract $response, array $data = []) : void
    {
        if (!empty($val = $this->validateClientCreate($request))) {
            $response->header->status = RequestStatusCode::R_400;
            $this->createInvalidCreateResponse($request, $response, $val);

            return;
        }

        $client = $this->createClientFromRequest($request);
        $this->createModel($request->header->account, $client, ClientMapper::class, 'client', $request->getOrigin());
        $this->createModelRelation(
            $client->account->id,
            $client->account->id,
            [$client->mainAddress->id],
            AccountMapper::class,
            'addresses',
            'account',
            $request->getOrigin()
        );

        // Create stock
        if ($this->app->moduleManager->isActive('WarehouseManagement')) {
            $internalResponse = new HttpResponse();
            $internalRequest  = new HttpRequest($request->uri);

            $internalRequest->header->account = $request->header->account;
            $internalRequest->setData('name', $client->number);
            $internalRequest->setData('client', $client->id);

            $this->app->moduleManager->get('WarehouseManagement', 'Api')
                ->apiStockCreate($internalRequest, $internalResponse);
        }

        // Set VAT Id
        if ($request->hasData('vat_id')) {
            $validate = $this->validateVAT($request, $client);

            if (($validate['status'] === 0
                    && $validate['vat'] === 'A'
                    && $validate['name'] === 'A'
                    && $validate['city'] === 'A')
                || $validate['status'] !== 0 // Api out of order -> accept it -> test it during invoice creation
            ) {
                $this->setVAT($request, $client);
            }
        }

        // Find and set tax code
        if ($this->app->moduleManager->isActive('Billing')) {
            $this->defineTaxCode($request, $client);
        }

        $this->createClientSegmentation($request, $response, $client);

        $this->createStandardCreateResponse($request, $response, $client);
    }

    private function createClientSegmentation(RequestAbstract $request, ResponseAbstract $response, Client $client) : void
    {
        /** @var \Model\Setting $settings */
        $settings = $this->app->appSettings->get(null, SettingsEnum::DEFAULT_SEGMENTATION);

        $segmentation = \json_decode($settings->content, true);
        if ($segmentation === false || $segmentation === null) {
            return;
        }

        $types = ClientAttributeTypeMapper::getAll()
            ->where('name', \array_keys($segmentation), 'IN')
            ->execute();

        foreach ($types as $type) {
            $internalResponse = clone $response;
            $internalRequest  = new HttpRequest();

            $internalRequest->header->account = $request->header->account;
            $internalRequest->setData('ref', $client->id);
            $internalRequest->setData('type', $type->id);
            $internalRequest->setData('value_id', $segmentation[$type->name]);

            $this->app->moduleManager->get('ClientManagement', 'ApiAttribute')->apiClientAttributeCreate($internalRequest, $internalResponse);
        }
    }

    /**
     * Method to create news article from request.
     *
     * @param RequestAbstract $request Request
     *
     * @return Client
     *
     * @since 1.0.0
     */
    private function createClientFromRequest(RequestAbstract $request) : Client
    {
        $account = null;
        if (!$request->hasData('account')) {
            $account        = new Account();
            $account->name1 = $request->getDataString('name1') ?? '';
            $account->name2 = $request->getDataString('name2') ?? '';
        } else {
            $account = new NullAccount((int) $request->getData('account'));
        }

        // @feature Create a way to let admins create a default account format for clients/suppliers
        //      https://github.com/Karaka-Management/oms-ClientManagement/issues/19

        $client          = new Client();
        $client->number  = $request->getDataString('number') ?? '';
        $client->account = $account;
        $client->unit    = $request->getDataInt('unit') ?? $this->app->unitId;

        $request->setData('name', null, true);
        $client->mainAddress = $this->app->moduleManager->get('Admin', 'Api')->createAddressFromRequest($request);

        return $client;
    }

    /**
     * Validate news create request
     *
     * @param RequestAbstract $request Request
     *
     * @return array<string, bool>
     *
     * @since 1.0.0
     */
    private function validateClientCreate(RequestAbstract $request) : array
    {
        $val = [];
        if (($val['number'] = !$request->hasData('number'))
            || ($val['account'] = !$request->hasData('name1') && !$request->hasData('account'))
        ) {
            return $val;
        }

        return [];
    }

    /**
     * Api method to update an account
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param array            $data     Generic data
     *
     * @return void
     *
     * @api
     *
     * @since 1.0.0
     */
    public function apiMainAddressUpdate(RequestAbstract $request, ResponseAbstract $response, array $data = []) : void
    {
        if (!empty($val = $this->validateMainAddressUpdate($request))) {
            $response->data['client_main_address'] = new FormValidation($val);
            $response->header->status              = RequestStatusCode::R_400;

            return;
        }

        $clientMapper = ClientMapper::get()
            ->with('mainAddress');

        if ($request->hasData('account')) {
            $clientMapper->where('account', $request->getDataInt('account'))
                ->where('unit', $request->getDataInt('unit'));
        } elseif ($request->hasData('client')) {
            $clientMapper->where('id', $request->getDataInt('client'));
        } else {
            $clientMapper->where('account', $request->header->account)
                ->where('unit', $request->getDataInt('unit'));
        }

        /** @var \Modules\ClientManagement\Models\Client $client */
        $client = $clientMapper->execute();

        $old = $client->mainAddress;
        $new = $this->app->moduleManager->get('Admin', 'Api')->updateAddressFromRequest($request, clone $old);

        $this->updateModel($request->header->account, $old, $new, AddressMapper::class, 'address', $request->getOrigin());
        $this->createStandardUpdateResponse($request, $response, $new);
    }

    /**
     * Validate news create request
     *
     * @param RequestAbstract $request Request
     *
     * @return array<string, bool>
     *
     * @since 1.0.0
     */
    private function validateMainAddressUpdate(RequestAbstract $request) : array
    {
        $val = [];
        if (($val['client'] = (!$request->hasData('client') && !$request->hasData('account')))
        ) {
            return $val;
        }

        return [];
    }

    /**
     * Api method to create client l11n
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param array            $data     Generic data
     *
     * @return void
     *
     * @api
     *
     * @since 1.0.0
     */
    public function apiClientL11nCreate(RequestAbstract $request, ResponseAbstract $response, array $data = []) : void
    {
        if (!empty($val = $this->validateClientL11nCreate($request))) {
            $response->header->status = RequestStatusCode::R_400;
            $this->createInvalidCreateResponse($request, $response, $val);

            return;
        }

        $clientL11n = $this->createClientL11nFromRequest($request);
        $this->createModel($request->header->account, $clientL11n, ClientL11nMapper::class, 'client_l11n', $request->getOrigin());
        $this->createStandardCreateResponse($request, $response, $clientL11n);
    }

    /**
     * Method to create client l11n from request.
     *
     * @param RequestAbstract $request Request
     *
     * @return BaseStringL11n
     *
     * @since 1.0.0
     */
    private function createClientL11nFromRequest(RequestAbstract $request) : BaseStringL11n
    {
        $clientL11n           = new BaseStringL11n();
        $clientL11n->ref      = $request->getDataInt('client') ?? 0;
        $clientL11n->type     = new NullBaseStringL11nType($request->getDataInt('type') ?? 0);
        $clientL11n->language = ISO639x1Enum::tryFromValue($request->getDataString('language')) ?? $request->header->l11n->language;
        $clientL11n->content  = $request->getDataString('description') ?? '';

        return $clientL11n;
    }

    /**
     * Validate client l11n create request
     *
     * @param RequestAbstract $request Request
     *
     * @return array<string, bool>
     *
     * @since 1.0.0
     */
    private function validateClientL11nCreate(RequestAbstract $request) : array
    {
        $val = [];
        if (($val['client'] = !$request->hasData('client'))
            || ($val['type'] = !$request->hasData('type'))
            || ($val['description'] = !$request->hasData('description'))
        ) {
            return $val;
        }

        return [];
    }

    /**
     * Api method to create client l11n type
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param array            $data     Generic data
     *
     * @return void
     *
     * @api
     *
     * @since 1.0.0
     */
    public function apiClientL11nTypeCreate(RequestAbstract $request, ResponseAbstract $response, array $data = []) : void
    {
        if (!empty($val = $this->validateClientL11nTypeCreate($request))) {
            $response->header->status = RequestStatusCode::R_400;
            $this->createInvalidCreateResponse($request, $response, $val);

            return;
        }

        $clientL11nType = $this->createClientL11nTypeFromRequest($request);
        $this->createModel($request->header->account, $clientL11nType, ClientL11nTypeMapper::class, 'client_l11n_type', $request->getOrigin());
        $this->createStandardCreateResponse($request, $response, $clientL11nType);
    }

    /**
     * Method to create client l11n type from request.
     *
     * @param RequestAbstract $request Request
     *
     * @return BaseStringL11nType
     *
     * @since 1.0.0
     */
    private function createClientL11nTypeFromRequest(RequestAbstract $request) : BaseStringL11nType
    {
        $clientL11nType             = new BaseStringL11nType();
        $clientL11nType->title      = $request->getDataString('title') ?? '';
        $clientL11nType->isRequired = $request->getDataBool('is_required') ?? false;

        return $clientL11nType;
    }

    /**
     * Validate client l11n type create request
     *
     * @param RequestAbstract $request Request
     *
     * @return array<string, bool>
     *
     * @since 1.0.0
     */
    private function validateClientL11nTypeCreate(RequestAbstract $request) : array
    {
        $val = [];
        if (($val['title'] = !$request->hasData('title'))) {
            return $val;
        }

        return [];
    }

    /**
     * Api method to create client files
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param array            $data     Generic data
     *
     * @return void
     *
     * @api
     *
     * @since 1.0.0
     */
    public function apiFileCreate(RequestAbstract $request, ResponseAbstract $response, array $data = []) : void
    {
        $uploadedFiles = $request->files;

        if (empty($uploadedFiles)) {
            $this->fillJsonResponse($request, $response, NotificationLevel::ERROR, 'Client', 'Invalid file', $uploadedFiles);
            $response->header->status = RequestStatusCode::R_400;

            return;
        }

        $uploaded = $this->app->moduleManager->get('Media', 'Api')->uploadFiles(
            names: $request->getDataList('names'),
            fileNames: $request->getDataList('filenames'),
            files: $uploadedFiles,
            account: $request->header->account,
            basePath: __DIR__ . '/../../../Modules/Media/Files/Modules/ClientManagement/' . ($request->getData('client') ?? '0'),
            virtualPath: '/Modules/ClientManagement/' . ($request->getData('client') ?? '0'),
            pathSettings: PathSettings::FILE_PATH
        );

        if ($request->hasData('type')) {
            foreach ($uploaded as $file) {
                $this->createModelRelation(
                    $request->header->account,
                    $file->id,
                    $request->getDataInt('type'),
                    MediaMapper::class,
                    'types',
                    '',
                    $request->getOrigin()
                );
            }
        }

        if (empty($uploaded)) {
            $this->createInvalidAddResponse($request, $response, []);

            return;
        }

        $this->createModelRelation(
            $request->header->account,
            (int) $request->getData('client'),
            \reset($uploaded)->id,
            ClientMapper::class, 'files', '', $request->getOrigin()
        );

        $this->createStandardCreateResponse($request, $response, $uploaded);
    }

    /**
     * Api method to create client files
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param array            $data     Generic data
     *
     * @return void
     *
     * @api
     *
     * @since 1.0.0
     */
    public function apiNoteCreate(RequestAbstract $request, ResponseAbstract $response, array $data = []) : void
    {
        $request->setData('virtualpath', '/Modules/ClientManagement/' . ((int) $request->getData('id')), true);
        $this->app->moduleManager->get('Editor')->apiEditorCreate($request, $response, $data);

        $responseData = $response->getDataArray($request->uri->__toString());
        if (!\is_array($responseData)) {
            return;
        }

        $model = $responseData['response'];
        $this->createModelRelation($request->header->account, (int) $request->getData('id'), $model->id, ClientMapper::class, 'notes', '', $request->getOrigin());
    }

    /**
     * Api method to update Note
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param array            $data     Generic data
     *
     * @return void
     *
     * @api
     *
     * @since 1.0.0
     */
    public function apiNoteUpdate(RequestAbstract $request, ResponseAbstract $response, array $data = []) : void
    {
        $accountId = $request->header->account;
        if (!$this->app->accountManager->get($accountId)->hasPermission(
            PermissionType::MODIFY, $this->app->unitId, $this->app->appId, self::NAME, PermissionCategory::CLIENT_NOTE, $request->getDataInt('id'))
        ) {
            $this->fillJsonResponse($request, $response, NotificationLevel::HIDDEN, '', '', []);
            $response->header->status = RequestStatusCode::R_403;

            return;
        }

        $this->app->moduleManager->get('Editor', 'Api')->apiEditorUpdate($request, $response, $data);
    }

    /**
     * Api method to delete Note
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param array            $data     Generic data
     *
     * @return void
     *
     * @api
     *
     * @since 1.0.0
     */
    public function apiNoteDelete(RequestAbstract $request, ResponseAbstract $response, array $data = []) : void
    {
        $accountId = $request->header->account;
        if (!$this->app->accountManager->get($accountId)->hasPermission(
            PermissionType::DELETE, $this->app->unitId, $this->app->appId, self::NAME, PermissionCategory::CLIENT_NOTE, $request->getDataInt('id'))
        ) {
            $this->fillJsonResponse($request, $response, NotificationLevel::HIDDEN, '', '', []);
            $response->header->status = RequestStatusCode::R_403;

            return;
        }

        $this->app->moduleManager->get('Editor', 'Api')->apiEditorDelete($request, $response, $data);
    }
}
