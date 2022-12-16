<?php
/**
 * Karaka
 *
 * PHP Version 8.1
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace Modules\ClientManagement\tests\Models;

use Modules\ClientManagement\Models\ClientAttributeType;
use Modules\ClientManagement\Models\ClientAttributeTypeL11n;

/**
 * @internal
 */
final class ClientAttributeTypeTest extends \PHPUnit\Framework\TestCase
{
    private ClientAttributeType $type;

    /**
     * {@inheritdoc}
     */
    protected function setUp() : void
    {
        $this->type = new ClientAttributeType();
    }

    /**
     * @covers Modules\ClientManagement\Models\ClientAttributeType
     * @group module
     */
    public function testDefault() : void
    {
        self::assertEquals(0, $this->type->getId());
        self::assertEquals('', $this->type->getL11n());
    }

    /**
     * @covers Modules\ClientManagement\Models\ClientAttributeType
     * @group module
     */
    public function testL11nInputOutput() : void
    {
        $this->type->setL11n('Test');
        self::assertEquals('Test', $this->type->getL11n());

        $this->type->setL11n(new ClientAttributeTypeL11n(0, 'NewTest'));
        self::assertEquals('NewTest', $this->type->getL11n());
    }

    /**
     * @covers Modules\ClientManagement\Models\ClientAttributeType
     * @group module
     */
    public function testSerialize() : void
    {
        $this->type->name                = 'Title';
        $this->type->fields              = 2;
        $this->type->custom              = true;
        $this->type->validationPattern   = '\d*';
        $this->type->isRequired          = true;

        self::assertEquals(
            [
                'id'                => 0,
                'name'              => 'Title',
                'fields'            => 2,
                'custom'            => true,
                'validationPattern' => '\d*',
                'isRequired'        => true,
            ],
            $this->type->jsonSerialize()
        );
    }
}
