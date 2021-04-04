<?php
/**
 * Orange Management
 *
 * PHP Version 8.0
 *
 * @package   Modules\ClientManagement\Models
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace Modules\ClientManagement\Models;

use phpOMS\Stdlib\Base\Enum;

/**
 * Attribute value type enum.
 *
 * @package Modules\ClientManagement\Models
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 */
abstract class AttributeValueType extends Enum
{
    public const _INT = 0;

    public const _STRING = 1;

    public const _FLOAT = 2;

    public const _DATETIME = 3;
}
