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
 * @link      https://karaka.app
 */
declare(strict_types=1);

namespace Modules\ClientManagement\tests\Models;

use Modules\ClientManagement\Models\NullClientAttributeTypeL11n;

/**
 * @internal
 */
final class NullClientAttributeTypeL11nTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @covers Modules\ClientManagement\Models\NullClientAttributeTypeL11n
     * @group framework
     */
    public function testNull() : void
    {
        self::assertInstanceOf('\Modules\ClientManagement\Models\ClientAttributeTypeL11n', new NullClientAttributeTypeL11n());
    }

    /**
     * @covers Modules\ClientManagement\Models\NullClientAttributeTypeL11n
     * @group framework
     */
    public function testId() : void
    {
        $null = new NullClientAttributeTypeL11n(2);
        self::assertEquals(2, $null->getId());
    }
}
