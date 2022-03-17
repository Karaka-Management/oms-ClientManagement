<?php
/**
 * Karaka
 *
 * PHP Version 8.0
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://karaka.app
 */
declare(strict_types=1);

namespace Modules\ClientManagement\tests\Controller\Api;

use Modules\ClientManagement\Models\AttributeValueType;
use phpOMS\Localization\ISO3166TwoEnum;
use phpOMS\Localization\ISO639x1Enum;
use phpOMS\Message\Http\HttpRequest;
use phpOMS\Message\Http\HttpResponse;
use phpOMS\Message\Http\RequestStatusCode;
use phpOMS\Uri\HttpUri;

trait ApiControllerAttributeTrait
{
    /**
     * @covers Modules\ClientManagement\Controller\ApiController
     * @group module
     */
    public function testApiClientAttributeTypeCreate() : void
    {
        $response = new HttpResponse();
        $request  = new HttpRequest(new HttpUri(''));

        $request->header->account = 1;
        $request->setData('title', 'EN:1');
        $request->setData('language', ISO639x1Enum::_EN);

        $this->module->apiClientAttributeTypeCreate($request, $response);
        self::assertGreaterThan(0, $response->get('')['response']->getId());
    }

    /**
     * @covers Modules\ClientManagement\Controller\ApiController
     * @group module
     */
    public function testApiClientAttributeTypeL11nCreate() : void
    {
        $response = new HttpResponse();
        $request  = new HttpRequest(new HttpUri(''));

        $request->header->account = 1;
        $request->setData('title', 'DE:2');
        $request->setData('type', '1');
        $request->setData('language', ISO639x1Enum::_DE);

        $this->module->apiClientAttributeTypeL11nCreate($request, $response);
        self::assertGreaterThan(0, $response->get('')['response']->getId());
    }

    /**
     * @covers Modules\ClientManagement\Controller\ApiController
     * @group module
     */
    public function testApiClientAttributeValueIntCreate() : void
    {
        $response = new HttpResponse();
        $request  = new HttpRequest(new HttpUri(''));

        $request->header->account = 1;
        $request->setData('default', '1');
        $request->setData('attributetype', '1');
        $request->setData('type', AttributeValueType::_INT);
        $request->setData('value', '1');
        $request->setData('language', ISO639x1Enum::_DE);
        $request->setData('country', ISO3166TwoEnum::_DEU);

        $this->module->apiClientAttributeValueCreate($request, $response);
        self::assertGreaterThan(0, $response->get('')['response']->getId());
    }

    /**
     * @covers Modules\ClientManagement\Controller\ApiController
     * @group module
     */
    public function testApiClientAttributeValueStrCreate() : void
    {
        $response = new HttpResponse();
        $request  = new HttpRequest(new HttpUri(''));

        $request->header->account = 1;
        $request->setData('type', AttributeValueType::_STRING);
        $request->setData('value', '1');
        $request->setData('language', ISO639x1Enum::_DE);
        $request->setData('country', ISO3166TwoEnum::_DEU);

        $this->module->apiClientAttributeValueCreate($request, $response);
        self::assertGreaterThan(0, $response->get('')['response']->getId());
    }

    /**
     * @covers Modules\ClientManagement\Controller\ApiController
     * @group module
     */
    public function testApiClientAttributeValueFloatCreate() : void
    {
        $response = new HttpResponse();
        $request  = new HttpRequest(new HttpUri(''));

        $request->header->account = 1;
        $request->setData('type', AttributeValueType::_FLOAT);
        $request->setData('value', '1.1');
        $request->setData('language', ISO639x1Enum::_DE);
        $request->setData('country', ISO3166TwoEnum::_DEU);

        $this->module->apiClientAttributeValueCreate($request, $response);
        self::assertGreaterThan(0, $response->get('')['response']->getId());
    }

    /**
     * @covers Modules\ClientManagement\Controller\ApiController
     * @group module
     */
    public function testApiClientAttributeValueDatCreate() : void
    {
        $response = new HttpResponse();
        $request  = new HttpRequest(new HttpUri(''));

        $request->header->account = 1;
        $request->setData('type', AttributeValueType::_DATETIME);
        $request->setData('value', '2020-08-02');
        $request->setData('language', ISO639x1Enum::_DE);
        $request->setData('country', ISO3166TwoEnum::_DEU);

        $this->module->apiClientAttributeValueCreate($request, $response);
        self::assertGreaterThan(0, $response->get('')['response']->getId());
    }

    /**
     * @covers Modules\ClientManagement\Controller\ApiController
     * @group module
     */
    public function testApiClientAttributeCreate() : void
    {
        $response = new HttpResponse();
        $request  = new HttpRequest(new HttpUri(''));

        $request->header->account = 1;
        $request->setData('client', '1');
        $request->setData('value', '1');
        $request->setData('type', '1');

        $this->module->apiClientAttributeCreate($request, $response);
        self::assertGreaterThan(0, $response->get('')['response']->getId());
    }

    /**
     * @covers Modules\ClientManagement\Controller\ApiController
     * @group module
     */
    public function testApiClientAttributeValueCreateInvalidData() : void
    {
        $response = new HttpResponse();
        $request  = new HttpRequest(new HttpUri(''));

        $request->header->account = 1;
        $request->setData('invalid', '1');

        $this->module->apiClientAttributeValueCreate($request, $response);
        self::assertEquals(RequestStatusCode::R_400, $response->header->status);
    }

    /**
     * @covers Modules\ClientManagement\Controller\ApiController
     * @group module
     */
    public function testApiClientAttributeTypeCreateInvalidData() : void
    {
        $response = new HttpResponse();
        $request  = new HttpRequest(new HttpUri(''));

        $request->header->account = 1;
        $request->setData('invalid', '1');

        $this->module->apiClientAttributeTypeCreate($request, $response);
        self::assertEquals(RequestStatusCode::R_400, $response->header->status);
    }

    /**
     * @covers Modules\ClientManagement\Controller\ApiController
     * @group module
     */
    public function testApiClientAttributeTypeL11nCreateInvalidData() : void
    {
        $response = new HttpResponse();
        $request  = new HttpRequest(new HttpUri(''));

        $request->header->account = 1;
        $request->setData('invalid', '1');

        $this->module->apiClientAttributeTypeL11nCreate($request, $response);
        self::assertEquals(RequestStatusCode::R_400, $response->header->status);
    }

    /**
     * @covers Modules\ClientManagement\Controller\ApiController
     * @group module
     */
    public function testApiClientAttributeCreateInvalidData() : void
    {
        $response = new HttpResponse();
        $request  = new HttpRequest(new HttpUri(''));

        $request->header->account = 1;
        $request->setData('invalid', '1');

        $this->module->apiClientAttributeCreate($request, $response);
        self::assertEquals(RequestStatusCode::R_400, $response->header->status);
    }
}