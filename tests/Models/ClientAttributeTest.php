<?php
/**
 * Karaka
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

use Modules\ClientManagement\Models\ClientAttribute;

/**
 * @internal
 */
final class ClientAttributeTest extends \PHPUnit\Framework\TestCase
{
    private ClientAttribute $attribute;

    /**
     * {@inheritdoc}
     */
    protected function setUp() : void
    {
        $this->attribute = new ClientAttribute();
    }

    /**
     * @covers Modules\ClientManagement\Models\ClientAttribute
     * @group module
     */
    public function testDefault() : void
    {
        self::assertEquals(0, $this->attribute->getId());
        self::assertInstanceOf('\Modules\ClientManagement\Models\ClientAttributeType', $this->attribute->type);
        self::assertInstanceOf('\Modules\ClientManagement\Models\ClientAttributeValue', $this->attribute->value);
    }

    /**
     * @covers Modules\ClientManagement\Models\ClientAttribute
     * @group module
     */
    public function testSerialize() : void
    {
        $serialized = $this->attribute->jsonSerialize();

        self::assertEquals(
            [
                'id',
                'client',
                'type',
                'value',
            ],
            \array_keys($serialized)
        );
    }
}
