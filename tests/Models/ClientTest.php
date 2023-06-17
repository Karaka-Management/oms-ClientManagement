<?php
/**
 * Jingga
 *
 * PHP Version 8.1
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace Modules\ClientManagement\tests\Models;

use Modules\ClientManagement\Models\Client;
use Modules\ClientManagement\Models\ClientStatus;
use Modules\Editor\Models\EditorDoc;
use Modules\Media\Models\Media;
use Modules\Profile\Models\ContactElement;

/**
 * @internal
 */
final class ClientTest extends \PHPUnit\Framework\TestCase
{
    private Client $client;

    /**
     * {@inheritdoc}
     */
    protected function setUp() : void
    {
        $this->client = new Client();
    }

    /**
     * @covers Modules\ClientManagement\Models\Client
     * @group module
     */
    public function testDefault() : void
    {
        self::assertEquals(0, $this->client->id);
        self::assertEquals('', $this->client->number);
        self::assertEquals('', $this->client->numberReverse);
        self::assertEquals('', $this->client->info);
        self::assertEquals(ClientStatus::ACTIVE, $this->client->getStatus());
        self::assertEquals(0, $this->client->getType());
        self::assertEquals([], $this->client->getNotes());
        self::assertEquals([], $this->client->files);
        self::assertEquals([], $this->client->getAddresses());
        self::assertEquals([], $this->client->getContactElements());
        self::assertEquals((new \DateTime('now'))->format('Y-m-d'), $this->client->createdAt->format('Y-m-d'));
        self::assertInstanceOf('\Modules\Admin\Models\Account', $this->client->account);
        self::assertInstanceOf('\Modules\Admin\Models\Address', $this->client->mainAddress);
        self::assertInstanceOf('\Modules\Profile\Models\NullContactElement', $this->client->getMainContactElement(0));
    }

    /**
     * @covers Modules\ClientManagement\Models\Client
     * @group module
     */
    public function testStatusInputOutput() : void
    {
        $this->client->setStatus(ClientStatus::INACTIVE);
        self::assertEquals(ClientStatus::INACTIVE, $this->client->getStatus());
    }

    /**
     * @covers Modules\ClientManagement\Models\Client
     * @group module
     */
    public function testTypeInputOutput() : void
    {
        $this->client->setType(2);
        self::assertEquals(2, $this->client->getType());
    }

    /**
     * @covers Modules\ClientManagement\Models\Client
     * @group module
     */
    public function testFileInputOutput() : void
    {
        $this->client->addFile($temp = new Media());
        self::assertCount(1, $this->client->files);
    }

    /**
     * @covers Modules\ClientManagement\Models\Client
     * @group module
     */
    public function testContactElementInputOutput() : void
    {
        $this->client->addContactElement($temp = new ContactElement());
        self::assertCount(1, $this->client->getContactElements());
        self::assertEquals($temp, $this->client->getMainContactElement(0));
    }

    /**
     * @covers Modules\ClientManagement\Models\Client
     * @group module
     */
    public function testNoteInputOutput() : void
    {
        $this->client->addNote(new EditorDoc());
        self::assertCount(1, $this->client->getNotes());
    }

    /**
     * @covers Modules\ClientManagement\Models\Client
     * @group module
     */
    public function testSerialize() : void
    {
        $this->client->number        = '123456';
        $this->client->numberReverse = '654321';
        $this->client->setStatus(ClientStatus::INACTIVE);
        $this->client->setType(2);
        $this->client->info = 'Test info';

        self::assertEquals(
            [
                'id'            => 0,
                'number'        => '123456',
                'numberReverse' => '654321',
                'status'        => ClientStatus::INACTIVE,
                'type'          => 2,
                'info'          => 'Test info',
            ],
            $this->client->jsonSerialize()
        );
    }
}
