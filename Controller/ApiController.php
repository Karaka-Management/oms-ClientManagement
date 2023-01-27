<?php
/**
 * Karaka
 *
 * PHP Version 8.1
 *
 * @package   Modules\ClientManagement
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace Modules\ClientManagement\Controller;

use Modules\Admin\Models\Account;
use Modules\Admin\Models\Address;
use Modules\ClientManagement\Models\Client;
use Modules\ClientManagement\Models\ClientAttribute;
use Modules\ClientManagement\Models\ClientAttributeMapper;
use Modules\ClientManagement\Models\ClientAttributeType;
use Modules\ClientManagement\Models\ClientAttributeTypeL11nMapper;
use Modules\ClientManagement\Models\ClientAttributeTypeMapper;
use Modules\ClientManagement\Models\ClientAttributeValue;
use Modules\ClientManagement\Models\ClientAttributeValueL11nMapper;
use Modules\ClientManagement\Models\ClientAttributeValueMapper;
use Modules\ClientManagement\Models\ClientL11n;
use Modules\ClientManagement\Models\ClientL11nMapper;
use Modules\ClientManagement\Models\ClientL11nType;
use Modules\ClientManagement\Models\ClientL11nTypeMapper;
use Modules\ClientManagement\Models\ClientMapper;
use Modules\ClientManagement\Models\NullClientAttributeType;
use Modules\ClientManagement\Models\NullClientAttributeValue;
use Modules\ClientManagement\Models\NullClientL11nType;
use Modules\Media\Models\MediaMapper;
use Modules\Media\Models\PathSettings;
use Modules\Profile\Models\ContactElementMapper;
use Modules\Profile\Models\Profile;
use phpOMS\Localization\BaseStringL11n;
use phpOMS\Localization\ISO639x1Enum;
use phpOMS\Message\Http\RequestStatusCode;
use phpOMS\Message\NotificationLevel;
use phpOMS\Message\RequestAbstract;
use phpOMS\Message\ResponseAbstract;
use phpOMS\Model\Message\FormValidation;

/**
 * ClientManagement class.
 *
 * @package Modules\ClientManagement
 * @license OMS License 1.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
final class ApiController extends Controller
{
    /**
     * Api method to create news article
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param mixed            $data     Generic data
     *
     * @return void
     *
     * @api
     *
     * @since 1.0.0
     */
    public function apiClientCreate(RequestAbstract $request, ResponseAbstract $response, mixed $data = null) : void
    {
        if (!empty($val = $this->validateClientCreate($request))) {
            $response->set('client_create', new FormValidation($val));
            $response->header->status = RequestStatusCode::R_400;

            return;
        }

        $client = $this->createClientFromRequest($request);
        $this->createModel($request->header->account, $client, ClientMapper::class, 'client', $request->getOrigin());
        $this->fillJsonResponse($request, $response, NotificationLevel::OK, 'Client', 'Client successfully created', $client);
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
        $account        = new Account();
        $account->name1 = (string) ($request->getData('name1') ?? '');
        $account->name2 = (string) ($request->getData('name2') ?? '');

        $profile = new Profile($account);

        $client          = new Client();
        $client->number  = (string) ($request->getData('number') ?? '');
        $client->profile = $profile;

        $addr          = new Address();
        $addr->address = (string) ($request->getData('address') ?? '');
        $addr->postal  = (string) ($request->getData('postal') ?? '');
        $addr->city    = (string) ($request->getData('city') ?? '');
        $addr->setCountry($request->getData('country') ?? '');
        $addr->state         = (string) ($request->getData('state') ?? '');
        $client->mainAddress = $addr;

        $client->unit = $request->getData('unit', 'int');

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
        if (($val['number'] = empty($request->getData('number')))
            || ($val['name1'] = empty($request->getData('name1')))
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
     * @param mixed            $data     Generic data
     *
     * @return void
     *
     * @api
     *
     * @since 1.0.0
     */
    public function apiClientL11nCreate(RequestAbstract $request, ResponseAbstract $response, mixed $data = null) : void
    {
        if (!empty($val = $this->validateClientL11nCreate($request))) {
            $response->set('client_l11n_create', new FormValidation($val));
            $response->header->status = RequestStatusCode::R_400;

            return;
        }

        $clientL11n = $this->createClientL11nFromRequest($request);
        $this->createModel($request->header->account, $clientL11n, ClientL11nMapper::class, 'client_l11n', $request->getOrigin());
        $this->fillJsonResponse($request, $response, NotificationLevel::OK, 'Client localization', 'Client localization successfully created', $clientL11n);
    }

    /**
     * Method to create client l11n from request.
     *
     * @param RequestAbstract $request Request
     *
     * @return ClientL11n
     *
     * @since 1.0.0
     */
    private function createClientL11nFromRequest(RequestAbstract $request) : ClientL11n
    {
        $clientL11n         = new ClientL11n();
        $clientL11n->client = (int) ($request->getData('client') ?? 0);
        $clientL11n->type   = new NullClientL11nType((int) ($request->getData('type') ?? 0));
        $clientL11n->setLanguage((string) (
            $request->getData('language') ?? $request->getLanguage()
        ));
        $clientL11n->description = (string) ($request->getData('description') ?? '');

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
        if (($val['client'] = empty($request->getData('client')))
            || ($val['type'] = empty($request->getData('type')))
            || ($val['description'] = empty($request->getData('description')))
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
     * @param mixed            $data     Generic data
     *
     * @return void
     *
     * @api
     *
     * @since 1.0.0
     */
    public function apiClientL11nTypeCreate(RequestAbstract $request, ResponseAbstract $response, mixed $data = null) : void
    {
        if (!empty($val = $this->validateClientL11nTypeCreate($request))) {
            $response->set('client_l11n_type_create', new FormValidation($val));
            $response->header->status = RequestStatusCode::R_400;

            return;
        }

        $clientL11nType = $this->createClientL11nTypeFromRequest($request);
        $this->createModel($request->header->account, $clientL11nType, ClientL11nTypeMapper::class, 'client_l11n_type', $request->getOrigin());
        $this->fillJsonResponse($request, $response, NotificationLevel::OK, 'Client localization type', 'Client localization type successfully created', $clientL11nType);
    }

    /**
     * Method to create client l11n type from request.
     *
     * @param RequestAbstract $request Request
     *
     * @return ClientL11nType
     *
     * @since 1.0.0
     */
    private function createClientL11nTypeFromRequest(RequestAbstract $request) : ClientL11nType
    {
        $clientL11nType             = new ClientL11nType();
        $clientL11nType->title      = (string) ($request->getData('title') ?? '');
        $clientL11nType->isRequired = (bool) ($request->getData('is_required') ?? false);

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
        if (($val['title'] = empty($request->getData('title')))) {
            return $val;
        }

        return [];
    }

    /**
     * Api method to create client attribute
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param mixed            $data     Generic data
     *
     * @return void
     *
     * @api
     *
     * @since 1.0.0
     */
    public function apiClientAttributeCreate(RequestAbstract $request, ResponseAbstract $response, mixed $data = null) : void
    {
        if (!empty($val = $this->validateClientAttributeCreate($request))) {
            $response->set('attribute_create', new FormValidation($val));
            $response->header->status = RequestStatusCode::R_400;

            return;
        }

        $attribute = $this->createClientAttributeFromRequest($request);
        $this->createModel($request->header->account, $attribute, ClientAttributeMapper::class, 'attribute', $request->getOrigin());
        $this->fillJsonResponse($request, $response, NotificationLevel::OK, 'Attribute', 'Attribute successfully created', $attribute);
    }

    /**
     * Method to create client attribute from request.
     *
     * @param RequestAbstract $request Request
     *
     * @return ClientAttribute
     *
     * @since 1.0.0
     */
    private function createClientAttributeFromRequest(RequestAbstract $request) : ClientAttribute
    {
        $attribute         = new ClientAttribute();
        $attribute->client = (int) $request->getData('client');
        $attribute->type   = new NullClientAttributeType((int) $request->getData('type'));
        $attribute->value  = new NullClientAttributeValue((int) $request->getData('value'));

        return $attribute;
    }

    /**
     * Validate client attribute create request
     *
     * @param RequestAbstract $request Request
     *
     * @return array<string, bool>
     *
     * @since 1.0.0
     */
    private function validateClientAttributeCreate(RequestAbstract $request) : array
    {
        $val = [];
        if (($val['type'] = empty($request->getData('type')))
            || ($val['value'] = empty($request->getData('value')))
            || ($val['client'] = empty($request->getData('client')))
        ) {
            return $val;
        }

        return [];
    }

    /**
     * Api method to create client attribute l11n
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param mixed            $data     Generic data
     *
     * @return void
     *
     * @api
     *
     * @since 1.0.0
     */
    public function apiClientAttributeTypeL11nCreate(RequestAbstract $request, ResponseAbstract $response, mixed $data = null) : void
    {
        if (!empty($val = $this->validateClientAttributeTypeL11nCreate($request))) {
            $response->set('attr_type_l11n_create', new FormValidation($val));
            $response->header->status = RequestStatusCode::R_400;

            return;
        }

        $attrL11n = $this->createClientAttributeTypeL11nFromRequest($request);
        $this->createModel($request->header->account, $attrL11n, ClientAttributeTypeL11nMapper::class, 'attr_type_l11n', $request->getOrigin());
        $this->fillJsonResponse($request, $response, NotificationLevel::OK, 'Attribute type localization', 'Attribute type localization successfully created', $attrL11n);
    }

    /**
     * Method to create client attribute l11n from request.
     *
     * @param RequestAbstract $request Request
     *
     * @return BaseStringL11n
     *
     * @since 1.0.0
     */
    private function createClientAttributeTypeL11nFromRequest(RequestAbstract $request) : BaseStringL11n
    {
        $attrL11n      = new BaseStringL11n();
        $attrL11n->ref = (int) ($request->getData('type') ?? 0);
        $attrL11n->setLanguage((string) (
            $request->getData('language') ?? $request->getLanguage()
        ));
        $attrL11n->content = (string) ($request->getData('title') ?? '');

        return $attrL11n;
    }

    /**
     * Validate client attribute l11n create request
     *
     * @param RequestAbstract $request Request
     *
     * @return array<string, bool>
     *
     * @since 1.0.0
     */
    private function validateClientAttributeTypeL11nCreate(RequestAbstract $request) : array
    {
        $val = [];
        if (($val['title'] = empty($request->getData('title')))
            || ($val['type'] = empty($request->getData('type')))
        ) {
            return $val;
        }

        return [];
    }

    /**
     * Api method to create client attribute type
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param mixed            $data     Generic data
     *
     * @return void
     *
     * @api
     *
     * @since 1.0.0
     */
    public function apiClientAttributeTypeCreate(RequestAbstract $request, ResponseAbstract $response, mixed $data = null) : void
    {
        if (!empty($val = $this->validateClientAttributeTypeCreate($request))) {
            $response->set('attr_type_create', new FormValidation($val));
            $response->header->status = RequestStatusCode::R_400;

            return;
        }

        $attrType = $this->createClientAttributeTypeFromRequest($request);
        $this->createModel($request->header->account, $attrType, ClientAttributeTypeMapper::class, 'attr_type', $request->getOrigin());

        $this->fillJsonResponse($request, $response, NotificationLevel::OK, 'Attribute type', 'Attribute type successfully created', $attrType);
    }

    /**
     * Method to create client attribute from request.
     *
     * @param RequestAbstract $request Request
     *
     * @return ClientAttributeType
     *
     * @since 1.0.0
     */
    private function createClientAttributeTypeFromRequest(RequestAbstract $request) : ClientAttributeType
    {
        $attrType                    = new ClientAttributeType($request->getData('name') ?? '');
        $attrType->datatype          = (int) ($request->getData('datatype') ?? 0);
        $attrType->custom            = (bool) ($request->getData('custom') ?? false);
        $attrType->isRequired        = (bool) ($request->getData('is_required') ?? false);
        $attrType->validationPattern = (string) ($request->getData('validation_pattern') ?? '');
        $attrType->setL11n((string) ($request->getData('title') ?? ''), $request->getData('language') ?? ISO639x1Enum::_EN);
        $attrType->setFields((int) ($request->getData('fields') ?? 0));

        return $attrType;
    }

    /**
     * Validate client attribute create request
     *
     * @param RequestAbstract $request Request
     *
     * @return array<string, bool>
     *
     * @since 1.0.0
     */
    private function validateClientAttributeTypeCreate(RequestAbstract $request) : array
    {
        $val = [];
        if (($val['title'] = empty($request->getData('title')))
            || ($val['name'] = empty($request->getData('name')))
        ) {
            return $val;
        }

        return [];
    }

    /**
     * Api method to create client attribute value
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param mixed            $data     Generic data
     *
     * @return void
     *
     * @api
     *
     * @since 1.0.0
     */
    public function apiClientAttributeValueCreate(RequestAbstract $request, ResponseAbstract $response, mixed $data = null) : void
    {
        if (!empty($val = $this->validateClientAttributeValueCreate($request))) {
            $response->set('attr_value_create', new FormValidation($val));
            $response->header->status = RequestStatusCode::R_400;

            return;
        }

        $attrValue = $this->createClientAttributeValueFromRequest($request);
        $this->createModel($request->header->account, $attrValue, ClientAttributeValueMapper::class, 'attr_value', $request->getOrigin());

        if ($attrValue->isDefault) {
            $this->createModelRelation(
                $request->header->account,
                (int) $request->getData('type'),
                $attrValue->getId(),
                ClientAttributeTypeMapper::class, 'defaults', '', $request->getOrigin()
            );
        }

        $this->fillJsonResponse($request, $response, NotificationLevel::OK, 'Attribute value', 'Attribute value successfully created', $attrValue);
    }

    /**
     * Method to create client attribute value from request.
     *
     * @param RequestAbstract $request Request
     *
     * @return ClientAttributeValue
     *
     * @since 1.0.0
     */
    private function createClientAttributeValueFromRequest(RequestAbstract $request) : ClientAttributeValue
    {
        /** @var ClientAttributeType $type */
        $type = ClientAttributeTypeMapper::get()
            ->where('id', (int) ($request->getData('type') ?? 0))
            ->execute();

        $attrValue            = new ClientAttributeValue();
        $attrValue->isDefault = (bool) ($request->getData('default') ?? false);
        $attrValue->setValue($request->getData('value'), $type->datatype);

        if ($request->getData('title') !== null) {
            $attrValue->setL11n($request->getData('title'), $request->getData('language') ?? ISO639x1Enum::_EN);
        }

        return $attrValue;
    }

    /**
     * Validate client attribute value create request
     *
     * @param RequestAbstract $request Request
     *
     * @return array<string, bool>
     *
     * @since 1.0.0
     */
    private function validateClientAttributeValueCreate(RequestAbstract $request) : array
    {
        $val = [];
        if (($val['type'] = empty($request->getData('type')))
            || ($val['value'] = empty($request->getData('value')))
        ) {
            return $val;
        }

        return [];
    }

    /**
     * Api method to create client attribute l11n
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param mixed            $data     Generic data
     *
     * @return void
     *
     * @api
     *
     * @since 1.0.0
     */
    public function apiClientAttributeValueL11nCreate(RequestAbstract $request, ResponseAbstract $response, mixed $data = null) : void
    {
        if (!empty($val = $this->validateClientAttributeValueL11nCreate($request))) {
            $response->set('attr_value_l11n_create', new FormValidation($val));
            $response->header->status = RequestStatusCode::R_400;

            return;
        }

        $attrL11n = $this->createClientAttributeValueL11nFromRequest($request);
        $this->createModel($request->header->account, $attrL11n, ClientAttributeValueL11nMapper::class, 'attr_value_l11n', $request->getOrigin());
        $this->fillJsonResponse($request, $response, NotificationLevel::OK, 'Attribute type localization', 'Attribute type localization successfully created', $attrL11n);
    }

    /**
     * Method to create Client attribute l11n from request.
     *
     * @param RequestAbstract $request Request
     *
     * @return BaseStringL11n
     *
     * @since 1.0.0
     */
    private function createClientAttributeValueL11nFromRequest(RequestAbstract $request) : BaseStringL11n
    {
        $attrL11n      = new BaseStringL11n();
        $attrL11n->ref = (int) ($request->getData('value') ?? 0);
        $attrL11n->setLanguage((string) (
            $request->getData('language') ?? $request->getLanguage()
        ));
        $attrL11n->content = (string) ($request->getData('title') ?? '');

        return $attrL11n;
    }

    /**
     * Validate Client attribute l11n create request
     *
     * @param RequestAbstract $request Request
     *
     * @return array<string, bool>
     *
     * @since 1.0.0
     */
    private function validateClientAttributeValueL11nCreate(RequestAbstract $request) : array
    {
        $val = [];
        if (($val['title'] = empty($request->getData('title')))
            || ($val['value'] = empty($request->getData('value')))
        ) {
            return $val;
        }

        return [];
    }

    /**
     * Api method to create client files
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param mixed            $data     Generic data
     *
     * @return void
     *
     * @api
     *
     * @since 1.0.0
     */
    public function apiFileCreate(RequestAbstract $request, ResponseAbstract $response, mixed $data = null) : void
    {
        $uploadedFiles = $request->getFiles();

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
                    $file->getId(),
                    $request->getData('type', 'int'),
                    MediaMapper::class,
                    'types',
                    '',
                    $request->getOrigin()
                );
            }
        }

        $this->createModelRelation(
            $request->header->account,
            (int) $request->getData('client'),
            \reset($uploaded)->getId(),
            ClientMapper::class, 'files', '', $request->getOrigin()
        );

        $this->fillJsonResponse($request, $response, NotificationLevel::OK, 'File', 'File successfully updated', $uploaded);
    }

    /**
     * Api method to create client files
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param mixed            $data     Generic data
     *
     * @return void
     *
     * @api
     *
     * @since 1.0.0
     */
    public function apiNoteCreate(RequestAbstract $request, ResponseAbstract $response, mixed $data = null) : void
    {
        $request->setData('virtualpath', '/Modules/ClientManagement/' . $request->getData('id'), true);
        $this->app->moduleManager->get('Editor')->apiEditorCreate($request, $response, $data);

        $responseData = $response->get($request->uri->__toString());
        if (!\is_array($responseData)) {
            return;
        }

        $model = $responseData['response'];
        $this->createModelRelation($request->header->account, $request->getData('id'), $model->getId(), ClientMapper::class, 'notes', '', $request->getOrigin());
    }
}
