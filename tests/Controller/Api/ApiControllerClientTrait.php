<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.2
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace Modules\ClientManagement\tests\Controller\Api;

use phpOMS\Localization\ISO3166TwoEnum;
use phpOMS\Message\Http\HttpRequest;
use phpOMS\Message\Http\HttpResponse;
use phpOMS\Message\Http\RequestStatusCode;
use phpOMS\System\MimeType;
use phpOMS\Utils\TestUtils;

trait ApiControllerClientTrait
{
    public static function tearDownAfterClass() : void
    {
        if (\is_file(__DIR__ . '/m_icon_tmp.png')) {
            \unlink(__DIR__ . '/m_icon_tmp.png');
        }

        if (\is_file(__DIR__ . '/Test file_tmp.txt')) {
            \unlink(__DIR__ . '/Test file_tmp.txt');
        }
    }

    /**
     * @covers \Modules\ClientManagement\Controller\ApiController
     */
    #[\PHPUnit\Framework\Attributes\Group('module')]
    public function testApiClientCreate() : void
    {
        $response = new HttpResponse();
        $request  = new HttpRequest();

        $request->header->account = 1;
        $request->setData('number', '123456');
        $request->setData('name1', 'Name1');
        $request->setData('name2', 'Name2');
        $request->setData('info', 'Info text');
        $request->setData('address', 'Address');
        $request->setData('postal', 'Postal');
        $request->setData('city', 'City');
        $request->setData('country', ISO3166TwoEnum::_USA);

        $this->module->apiClientCreate($request, $response);
        self::assertGreaterThan(0, $response->getDataArray('')['response']->id);
    }

    /**
     * @covers \Modules\ClientManagement\Controller\ApiController
     */
    #[\PHPUnit\Framework\Attributes\Group('module')]
    public function testApiClientCreateInvalidData() : void
    {
        $response = new HttpResponse();
        $request  = new HttpRequest();

        $request->header->account = 1;
        $request->setData('invalid', '1');

        $this->module->apiClientCreate($request, $response);
        self::assertEquals(RequestStatusCode::R_400, $response->header->status);
    }

    /**
     * @covers \Modules\ClientManagement\Controller\ApiController
     */
    #[\PHPUnit\Framework\Attributes\Group('module')]
    public function testApiClientProfileImageCreate() : void
    {
        $response = new HttpResponse();
        $request  = new HttpRequest();

        \copy(__DIR__ . '/m_icon.png', __DIR__ . '/m_icon_tmp.png');

        $request->header->account = 1;
        $request->setData('name', '123456 backend');
        $request->setData('ref', 1);
        $request->setData('tag', '1');

        TestUtils::setMember($request, 'files', [
            'file1' => [
                'name'     => '123456.png',
                'type'     => MimeType::M_PNG,
                'tmp_name' => __DIR__ . '/m_icon_tmp.png',
                'error'    => \UPLOAD_ERR_OK,
                'size'     => \filesize(__DIR__ . '/m_icon_tmp.png'),
            ],
        ]);

        $this->module->apiFileCreate($request, $response);
        $file = $response->getDataArray('')['response'];
        self::assertGreaterThan(0, \reset($file)->id);
    }

    /**
     * @covers \Modules\ClientManagement\Controller\ApiController
     */
    #[\PHPUnit\Framework\Attributes\Group('module')]
    public function testApiClientFileCreate() : void
    {
        $response = new HttpResponse();
        $request  = new HttpRequest();

        \copy(__DIR__ . '/Test file.txt', __DIR__ . '/Test file_tmp.txt');

        $request->header->account = 1;
        $request->setData('name', 'test file backend');
        $request->setData('ref', 1);

        TestUtils::setMember($request, 'files', [
            'file1' => [
                'name'     => 'Test file.txt',
                'type'     => MimeType::M_TXT,
                'tmp_name' => __DIR__ . '/Test file_tmp.txt',
                'error'    => \UPLOAD_ERR_OK,
                'size'     => \filesize(__DIR__ . '/Test file_tmp.txt'),
            ],
        ]);

        $this->module->apiFileCreate($request, $response);
        $file = $response->getDataArray('')['response'];
        self::assertGreaterThan(0, \reset($file)->id);
    }

    /**
     * @covers \Modules\ClientManagement\Controller\ApiController
     */
    #[\PHPUnit\Framework\Attributes\Group('module')]
    public function testApiClientNoteCreate() : void
    {
        $response = new HttpResponse();
        $request  = new HttpRequest();

        $request->header->account = 1;

        $MARKDOWN = "# Test Title\n\nThis is **some** text.";

        $request->setData('ref', 1);
        $request->setData('title', \trim(\strtok($MARKDOWN, "\n"), ' #'));
        $request->setData('plain', \preg_replace('/^.+\n/', '', $MARKDOWN));

        $this->module->apiNoteCreate($request, $response);
        self::assertGreaterThan(0, $response->getDataArray('')['response']->id);
    }

    /**
     * @covers \Modules\ClientManagement\Controller\ApiController
     */
    #[\PHPUnit\Framework\Attributes\Group('module')]
    public function testApiFileCreateInvalidData() : void
    {
        $response = new HttpResponse();
        $request  = new HttpRequest();

        $request->header->account = 1;
        $request->setData('invalid', '1');

        $this->module->apiFileCreate($request, $response);
        self::assertEquals(RequestStatusCode::R_400, $response->header->status);
    }
}
