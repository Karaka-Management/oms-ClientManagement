<?php
/**
 * Karaka
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
use Modules\Organization\Models\UnitMapper;
use phpOMS\Api\EUVAT\EUVATVies;
use phpOMS\Localization\BaseStringL11n;
use phpOMS\Localization\ISO3166CharEnum;
use phpOMS\Localization\ISO3166TwoEnum;
use phpOMS\Localization\ISO639x1Enum;
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

        // Set VAT Id
        if ($request->hasData('vat_id')) {
            /** @var \Modules\Organization\Models\Unit $unit */
            $unit = UnitMapper::get()
                ->with('attributes')
                ->where('id', $this->app->unitId)
                ->execute();

            $validate = ['status' => -1];

            if (\in_array($client->mainAddress->getCountry(), ISO3166CharEnum::getRegion('eu'))) {
                $validate = EUVATVies::validateQualified(
                    $unit->getAttribute('vat_id')?->value->getValue() ?? '',
                    $request->getData('vat_id'),
                    $client->account->name1,
                    $client->mainAddress->city,
                    $client->mainAddress->postal,
                    $client->mainAddress->address
                );
            }

            $audit = new Audit(
                new NullAccount($request->header->account),
                null,
                (string) $validate['status'],
                StringUtils::intHash(EUVATVies::class),
                'vat_validation',
                self::NAME,
                (string) $client->getId(),
                \json_encode($validate),
                (int) \ip2long($request->getOrigin())
            );

            AuditMapper::create()->execute($audit);

            if (($validate['status'] === 0
                    && $validate['vat'] === true
                    && $validate['name'] === true
                    && $validate['city'] === true)
                || $validate['status'] !== 0 // Api out of order -> accept it -> @todo: test it during invoice creation
            ) {
                /** @var \Modules\ClientManagement\Models\ClientAttributeType $type */
                $type = ClientAttributeTypeMapper::get()->where('name', 'vat_id')->execute();

                $internalRequest  = new HttpRequest(new HttpUri(''));
                $internalResponse = new HttpResponse();

                $internalRequest->header->account = $request->header->account;
                $internalRequest->setData('client', $client->getId());
                $internalRequest->setData('type',  $type->getId());
                $internalRequest->setData('custom',  $request->hasData('vat_id'));

                $this->apiClientAttributeCreate($internalRequest, $internalResponse);
            }
        }

        // Find tax code
        if ($this->app->moduleManager->isActive('Billing')) {
            /** @var \Modules\Organization\Models\Unit $unit */
            $unit = UnitMapper::get()
                ->with('mainAddress')
                ->where('id', $this->app->unitId)
                ->execute();

            /** @var \Modules\ClientManagement\Models\ClientAttributeType $type */
            $type = ClientAttributeTypeMapper::get()
                ->where('name', 'sales_tax_code')
                ->execute();

            $value = $this->app->moduleManager->get('Billing', 'ApiTax')->getClientTaxCode($client, $unit->mainAddress);

            $internalRequest  = new HttpRequest(new HttpUri(''));
            $internalResponse = new HttpResponse();

            $internalRequest->header->account = $request->header->account;
            $internalRequest->setData('client', $client->getId());
            $internalRequest->setData('type',  $type->getId());
            $internalRequest->setData('value', $value->getId());

            $this->apiClientAttributeCreate($internalRequest, $internalResponse);
        }

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
        $addr->state         = $request->getDataString('state') ?? '';
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
        if (($val['number'] = empty($request->getData('number')))
            || ($val['account'] = empty($request->getData('name1')) && empty($request->getData('account')))
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
     * @param mixed            $data     Generic data
     *
     * @return void
     *
     * @api
     *
     * @since 1.0.0
     */
    public function apiMainAddressUpdate(RequestAbstract $request, ResponseAbstract $response, mixed $data = null) : void
    {
        if (!empty($val = $this->validateMainAddressUpdate($request))) {
            $response->set('client_main_address', new FormValidation($val));
            $response->header->status = RequestStatusCode::R_400;

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

        $client = $clientMapper->execute();

        $old = $client->mainAddress;

        $new = $this->updateMainAddressFromRequest($request, clone $old);
        $this->updateModel($request->header->account, $old, $new, AddressMapper::class, 'address', $request->getOrigin());

        $this->fillJsonResponse(
            $request,
            $response,
            NotificationLevel::OK,
            '',
            $this->app->l11nManager->getText($response->getLanguage(), '0', '0', 'SuccessfulUpdate'),
            $new
        );
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
        if (($val['client'] = (empty($request->getData('client') && empty($request->getData('account')))))
        ) {
            return $val;
        }

        return [];
    }

    /**
     * Method to update an account from a request
     *
     * @param RequestAbstract $request       Request
     * @param Address         $address       Address
     * @param bool            $allowPassword Allow to change password
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
        $address->setCountry($request->getDataString('country') ?? $address->getCountry());
        $address->state = $request->getDataString('state') ?? $address->state;

        return $address;
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
        $this->fillJsonResponse($request, $response, NotificationLevel::OK, 'Localization', 'Localization successfully created', $clientL11n);
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
        $clientL11n->client = $request->getDataInt('client') ?? 0;
        $clientL11n->type   = new NullClientL11nType($request->getDataInt('type') ?? 0);
        $clientL11n->setLanguage(
            $request->getDataString('language') ?? $request->getLanguage()
        );
        $clientL11n->description = $request->getDataString('description') ?? '';

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
        $this->fillJsonResponse($request, $response, NotificationLevel::OK, 'Localization type', 'Localization type successfully created', $clientL11nType);
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
        $clientL11nType->title      = $request->getDataString('title') ?? '';
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

        if ($request->hasData('value')) {
            $attribute->value = new NullClientAttributeValue((int) $request->getData('value'));
        } else {
            $newRequest = clone $request;
            $newRequest->setData('value', $request->getData('custom'), true);

            $value = $this->createClientAttributeValueFromRequest($newRequest);

            $attribute->value = $value;
        }

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
            || ($val['value'] = (empty($request->getData('value')) && empty($request->getData('custom'))))
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
        $this->fillJsonResponse($request, $response, NotificationLevel::OK, 'Localization', 'Localization successfully created', $attrL11n);
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
        $attrL11n->ref = $request->getDataInt('type') ?? 0;
        $attrL11n->setLanguage(
            $request->getDataString('language') ?? $request->getLanguage()
        );
        $attrL11n->content = $request->getDataString('title') ?? '';

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
        $attrType                    = new ClientAttributeType($request->getDataString('name') ?? '');
        $attrType->datatype          = $request->getDataInt('datatype') ?? 0;
        $attrType->custom            = $request->getDataBool('custom') ?? false;
        $attrType->isRequired        = (bool) ($request->getData('is_required') ?? false);
        $attrType->validationPattern = $request->getDataString('validation_pattern') ?? '';
        $attrType->setL11n($request->getDataString('title') ?? '', $request->getDataString('language') ?? ISO639x1Enum::_EN);
        $attrType->setFields($request->getDataInt('fields') ?? 0);

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
            ->where('id', $request->getDataInt('type') ?? 0)
            ->execute();

        $attrValue            = new ClientAttributeValue();
        $attrValue->isDefault = $request->getDataBool('default') ?? false;
        $attrValue->setValue($request->getData('value'), $type->datatype);

        if ($request->hasData('title')) {
            $attrValue->setL11n($request->getDataString('title') ?? '', $request->getDataString('language') ?? ISO639x1Enum::_EN);
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
        $this->fillJsonResponse($request, $response, NotificationLevel::OK, 'Localization', 'Localization successfully created', $attrL11n);
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
        $attrL11n->ref = $request->getDataInt('value') ?? 0;
        $attrL11n->setLanguage(
            $request->getDataString('language') ?? $request->getLanguage()
        );
        $attrL11n->content = $request->getDataString('title') ?? '';

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
                    $request->getDataInt('type'),
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
