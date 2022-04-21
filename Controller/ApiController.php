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
 * @link      https://karaka.app
 */
declare(strict_types=1);

namespace Modules\ClientManagement\Controller;

use Modules\Admin\Models\Account;
use Modules\Admin\Models\Address;
use Modules\ClientManagement\Models\AttributeValueType;
use Modules\ClientManagement\Models\Client;
use Modules\ClientManagement\Models\ClientAttribute;
use Modules\ClientManagement\Models\ClientAttributeMapper;
use Modules\ClientManagement\Models\ClientAttributeType;
use Modules\ClientManagement\Models\ClientAttributeTypeL11n;
use Modules\ClientManagement\Models\ClientAttributeTypeL11nMapper;
use Modules\ClientManagement\Models\ClientAttributeTypeMapper;
use Modules\ClientManagement\Models\ClientAttributeValue;
use Modules\ClientManagement\Models\ClientAttributeValueMapper;
use Modules\ClientManagement\Models\ClientMapper;
use Modules\ClientManagement\Models\NullClientAttributeType;
use Modules\ClientManagement\Models\NullClientAttributeValue;
use Modules\Media\Models\PathSettings;
use Modules\Profile\Models\ContactElementMapper;
use Modules\Profile\Models\Profile;
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
 * @link    https://karaka.app
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
        $account->name1 = $request->getData('name1') ?? '';
        $account->name2 = $request->getData('name2') ?? '';

        $profile = new Profile($account);

        $client          = new Client();
        $client->number  = $request->getData('number') ?? '';
        $client->profile = $profile;

        $addr          = new Address();
        $addr->address = $request->getData('address') ?? '';
        $addr->postal  = $request->getData('postal') ?? '';
        $addr->city    = $request->getData('city') ?? '';
        $addr->setCountry($request->getData('country') ?? '');
        $addr->state         = $request->getData('state') ?? '';
        $client->mainAddress = $addr;

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
     * Routing end-point for application behaviour.
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
    public function apiContactElementCreate(RequestAbstract $request, ResponseAbstract $response, mixed $data = null) : void
    {
        $profileModule = $this->app->moduleManager->get('Profile');

        if (!empty($val = $profileModule->validateContactElementCreate($request))) {
            $response->set('contact_element_create', new FormValidation($val));
            $response->header->status = RequestStatusCode::R_400;

            return;
        }

        $contactElement = $profileModule->createContactElementFromRequest($request);

        $this->createModel($request->header->account, $contactElement, ContactElementMapper::class, 'client-contactElement', $request->getOrigin());
        $this->createModelRelation(
            $request->header->account,
            (int) $request->getData('account'),
            $contactElement->getId(),
            ClientMapper::class, 'contactElements', '', $request->getOrigin()
        );
        $this->fillJsonResponse($request, $response, NotificationLevel::OK, 'Contact Element', 'Contact element successfully created', $contactElement);
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
     * @return ClientAttributeTypeL11n
     *
     * @since 1.0.0
     */
    private function createClientAttributeTypeL11nFromRequest(RequestAbstract $request) : ClientAttributeTypeL11n
    {
        $attrL11n       = new ClientAttributeTypeL11n();
        $attrL11n->type = (int) ($request->getData('type') ?? 0);
        $attrL11n->setLanguage((string) (
            $request->getData('language') ?? $request->getLanguage()
        ));
        $attrL11n->title = (string) ($request->getData('title') ?? '');

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
        $attrType = new ClientAttributeType();
        $attrType->setL11n((string) ($request->getData('title') ?? ''), $request->getData('language') ?? ISO639x1Enum::_EN);
        $attrType->fields = (int) ($request->getData('fields') ?? 0);
        $attrType->custom = (bool) ($request->getData('custom') ?? false);

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
        if (($val['title'] = empty($request->getData('title')))) {
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
                (int) $request->getData('attributetype'),
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
        $attrValue = new ClientAttributeValue();

        $type = $request->getData('type') ?? 0;
        if ($type === AttributeValueType::_INT) {
            $attrValue->valueInt = (int) $request->getData('value');
        } elseif ($type === AttributeValueType::_STRING) {
            $attrValue->valueStr = (string) $request->getData('value');
        } elseif ($type === AttributeValueType::_FLOAT) {
            $attrValue->valueDec = (float) $request->getData('value');
        } elseif ($type === AttributeValueType::_DATETIME) {
            $attrValue->valueDat = new \DateTime($request->getData('value') ?? '');
        }

        $attrValue->type      = $type;
        $attrValue->isDefault = (bool) ($request->getData('default') ?? false);

        if ($request->hasData('language')) {
            $attrValue->setLanguage((string) ($request->getData('language') ?? $request->getLanguage()));
        }

        if ($request->hasData('country')) {
            $attrValue->setCountry((string) ($request->getData('country') ?? $request->header->l11n->getCountry()));
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
            $request->getDataList('names'),
            $request->getDataList('filenames'),
            $uploadedFiles,
            $request->header->account,
            __DIR__ . '/../../../Modules/Media/Files/Modules/ClientManagement/' . ($request->getData('client') ?? '0'),
            '/Modules/ClientManagement/' . ($request->getData('client') ?? '0'),
            $request->getData('type', 'int'),
            pathSettings: PathSettings::FILE_PATH
        );

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

        $model = $response->get($request->uri->__toString())['response'];
        $this->createModelRelation($request->header->account, $request->getData('id'), $model->getId(), ClientMapper::class, 'notes', '', $request->getOrigin());
    }
}
