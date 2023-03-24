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

use Modules\ClientManagement\Models\NullClientAttributeType;

/**
 * @internal
 */
final class NullClientAttributeTypeTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @covers Modules\ClientManagement\Models\NullClientAttributeType
     * @group framework
     */
    public function testNull() : void
    {
        self::assertInstanceOf('\Modules\ClientManagement\Models\ClientAttributeType', new NullClientAttributeType());
    }

    /**
     * @covers Modules\ClientManagement\Models\NullClientAttributeType
     * @group framework
     */
    public function testId() : void
    {
        $null = new NullClientAttributeType(2);
        self::assertEquals(2, $null->getId());
    }
}
