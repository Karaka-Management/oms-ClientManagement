<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   Modules\ClientManagement
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

use Modules\ClientManagement\Controller\SearchController;
use Modules\ClientManagement\Models\PermissionCategory;
use phpOMS\Account\PermissionType;
use phpOMS\Router\RouteVerb;

return [
    '^(?!:).+.*?' => [
        [
            'dest'       => '\Modules\ClientManagement\Controller\SearchController:searchGeneral',
            'verb'       => RouteVerb::ANY,
            'permission' => [
                'module' => SearchController::NAME,
                'type'   => PermissionType::READ,
                'state'  => PermissionCategory::CLIENT,
            ],
        ],
    ],
];
