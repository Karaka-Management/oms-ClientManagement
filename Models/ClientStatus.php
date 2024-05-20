<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   Modules\ClientManagement\Models
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.2
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace Modules\ClientManagement\Models;

use phpOMS\Stdlib\Base\Enum;

/**
 * Client status enum.
 *
 * @package Modules\ClientManagement\Models
 * @license OMS License 2.2
 * @link    https://jingga.app
 * @since   1.0.0
 */
abstract class ClientStatus extends Enum
{
    public const ACTIVE = 1;

    public const INACTIVE = 2;

    public const BANNED = 4;
}
