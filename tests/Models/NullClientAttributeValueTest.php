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

use Modules\ClientManagement\Models\NullClientAttributeValue;

/**
 * @internal
 */
final class NullClientAttributeValueTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @covers Modules\ClientManagement\Models\NullClientAttributeValue
     * @group framework
     */
    public function testNull() : void
    {
        self::assertInstanceOf('\Modules\ClientManagement\Models\ClientAttributeValue', new NullClientAttributeValue());
    }

    /**
     * @covers Modules\ClientManagement\Models\NullClientAttributeValue
     * @group framework
     */
    public function testId() : void
    {
        $null = new NullClientAttributeValue(2);
        self::assertEquals(2, $null->getId());
    }
}
