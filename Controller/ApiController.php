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
use Modules\Admin\Models\Address;
use Modules\Admin\Models\AddressMapper;
use Modules\Admin\Models\NullAccount;
use Modules\Auditor\Models\Audit;
use Modules\Auditor\Models\AuditMapper;
use Modules\ClientManagement\Models\Attribute\ClientAttributeTypeMapper;
use Modules\ClientManagement\Models\Client;
use Modules\ClientManagement\Models\ClientL11nMapper;
use Modules\ClientManagement\Models\ClientL11nTypeMapper;
use Modules\ClientManagement\Models\ClientMapper;
use Modules\Media\Models\MediaMapper;
use Modules\Media\Models\PathSettings;
use Modules\Organization\Models\UnitMapper;
use phpOMS\Api\EUVAT\EUVATVies;
use phpOMS\Api\Geocoding\Nominatim;
use phpOMS\Localization\BaseStringL11n;
use phpOMS\Localization\BaseStringL11nType;
use phpOMS\Localization\ISO3166CharEnum;
use phpOMS\Localization\ISO3166TwoEnum;
use phpOMS\Localization\NullBaseStringL11nType;
use phpOMS\Message\Http\HttpRequest;
use phpOMS\Message\Http\HttpResponse;
use phpOMS\Message\Http\RequestStatusCode;
use phpOMS\Message\NotificationLevel;
use phpOMS\Message\RequestAbstract;
use phpOMS\Message\ResponseAbstract;
use phpOMS\Model\Message\FormValidation;
use phpOMS\Uri\HttpUri;
use phpOMS\Utils\StringUtils;

/**
 * ClientManagement class.
 *
 * @package Modules\ClientManagement
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
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
    public function findClientForAccount(int $account, int $unit = null) : ?Client
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

        // Set VAT Id
        // @todo: move to separate function
        if ($request->hasData('vat_id')) {
            /** @var \Modules\Organization\Models\Unit $unit */
            $unit = UnitMapper::get()
                ->with('attributes')
                ->where('id', $this->app->unitId)
                ->execute();

            $validate = ['status' => -1];

            if (\in_array($client->mainAddress->getCountry(), ISO3166CharEnum::getRegion('eu'))) {
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

            if (($validate['status'] === 0
                    && $validate['vat'] === 'A'
                    && $validate['name'] === 'A'
                    && $validate['city'] === 'A')
                || $validate['status'] !== 0 // Api out of order -> accept it -> @todo: test it during invoice creation
            ) {
                /** @var \Modules\Attribute\Models\AttributeType $type */
                $type = ClientAttributeTypeMapper::get()->where('name', 'vat_id')->execute();

                $internalRequest  = new HttpRequest(new HttpUri(''));
                $internalResponse = new HttpResponse();

                $internalRequest->header->account = $request->header->account;
                $internalRequest->setData('ref', $client->id);
                $internalRequest->setData('type', $type->id);
                $internalRequest->setData('value', $request->getDataString('vat_id'));

                $this->app->moduleManager->get('ClientManagement', 'ApiAttribute')
                    ->apiClientAttributeCreate($internalRequest, $internalResponse);
            }
        }

        // Find tax code
        if ($this->app->moduleManager->isActive('Billing')) {
            /** @var \Modules\Organization\Models\Unit $unit */
            $unit = UnitMapper::get()
                ->with('mainAddress')
                ->where('id', $this->app->unitId)
                ->execute();

            /** @var \Modules\Attribute\Models\AttributeType $type */
            $type = ClientAttributeTypeMapper::get()
                ->where('name', 'sales_tax_code')
                ->execute();

            $value = $this->app->moduleManager->get('Billing', 'ApiTax')
                ->getClientTaxCode($client, $unit->mainAddress);

            $internalRequest  = new HttpRequest(new HttpUri(''));
            $internalResponse = new HttpResponse();

            $internalRequest->header->account = $request->header->account;
            $internalRequest->setData('ref', $client->id);
            $internalRequest->setData('type',  $type->id);
            $internalRequest->setData('value', $value->id);

            $this->app->moduleManager->get('ClientManagement', 'ApiAttribute')
                ->apiClientAttributeCreate($internalRequest, $internalResponse);
        }

        $this->createStandardCreateResponse($request, $response, $client);
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

        $client          = new Client();
        $client->number  = $request->getDataString('number') ?? '';
        $client->account = $account;

        $addr          = new Address();
        $addr->address = $request->getDataString('address') ?? '';
        $addr->postal  = $request->getDataString('postal') ?? '';
        $addr->city    = $request->getDataString('city') ?? '';
        $addr->setCountry($request->getDataString('country') ?? ISO3166TwoEnum::_XXX);
        $addr->state = $request->getDataString('state') ?? '';

        $geocoding = Nominatim::geocoding($addr->country, $addr->city, $addr->address);
        if ($geocoding === ['lat' => 0.0, 'lon' => 0.0]) {
            $geocoding = Nominatim::geocoding($addr->country, $addr->city);
        }

        $addr->lat = $geocoding['lat'];
        $addr->lon = $geocoding['lon'];

        $client->mainAddress = $addr;

        $client->unit = $request->getDataInt('unit');

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

        $clientMapper = $client = ClientMapper::get()
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

        $new = $this->updateMainAddressFromRequest($request, clone $old);
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
     * Method to update an account from a request
     *
     * @param RequestAbstract $request Request
     * @param Address         $address Address
     *
     * @return Address
     *
     * @since 1.0.0
     */
    private function updateMainAddressFromRequest(RequestAbstract $request, Address $address) : Address
    {
        $address->address = $request->getDataString('address') ?? $address->address;
        $address->postal  = $request->getDataString('postal') ?? $address->postal;
        $address->city    = $request->getDataString('city') ?? $address->city;
        $address->state   = $request->getDataString('state') ?? $address->state;
        $address->setCountry($request->getDataString('country') ?? $address->getCountry());

        return $address;
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
        $clientL11n       = new BaseStringL11n();
        $clientL11n->ref  = $request->getDataInt('client') ?? 0;
        $clientL11n->type = new NullBaseStringL11nType($request->getDataInt('type') ?? 0);
        $clientL11n->setLanguage(
            $request->getDataString('language') ?? $request->header->l11n->language
        );
        $clientL11n->content = $request->getDataString('description') ?? '';

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

        $uploaded = $this->app->moduleManager->get('Media')->uploadFiles(
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
        // @todo: check permissions
        $this->app->moduleManager->get('Editor', 'Api')->apiEditorDocUpdate($request, $response, $data);
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
        // @todo: check permissions
        $this->app->moduleManager->get('Editor', 'Api')->apiEditorDocDelete($request, $response, $data);
    }
}
