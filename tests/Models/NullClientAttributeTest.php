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

use Modules\ClientManagement\Models\NullClientAttribute;

/**
 * @internal
 */
final class NullClientAttributeTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @covers Modules\ClientManagement\Models\NullClientAttribute
     * @group framework
     */
    public function testNull() : void
    {
        self::assertInstanceOf('\Modules\ClientManagement\Models\ClientAttribute', new NullClientAttribute());
    }

    /**
     * @covers Modules\ClientManagement\Models\NullClientAttribute
     * @group framework
     */
    public function testId() : void
    {
        $null = new NullClientAttribute(2);
        self::assertEquals(2, $null->getId());
    }
}
