<?php
/**
 * Karaka
 *
 * PHP Version 8.1
 *
 * @package   Modules
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

use Modules\ClientManagement\Controller\BackendController;
use Modules\ClientManagement\Models\PermissionCategory;
use phpOMS\Account\PermissionType;
use phpOMS\Router\RouteVerb;

return [
    '^.*/sales/client/attribute/type/list.*$' => [
        [
            'dest'       => '\Modules\ClientManagement\Controller\BackendController:viewClientManagementAttributeTypeList',
            'verb'       => RouteVerb::GET,
            'permission' => [
                'module' => BackendController::NAME,
                'type'   => PermissionType::READ,
                'state'  => PermissionCategory::CLIENT,
            ],
        ],
    ],
    '^.*/sales/client/attribute/type\?.*$' => [
        [
            'dest'       => '\Modules\ClientManagement\Controller\BackendController:viewClientManagementAttributeType',
            'verb'       => RouteVerb::GET,
            'permission' => [
                'module' => BackendController::NAME,
                'type'   => PermissionType::READ,
                'state'  => PermissionCategory::CLIENT,
            ],
        ],
    ],
    '^.*/sales/client/list.*$' => [
        [
            'dest'       => '\Modules\ClientManagement\Controller\BackendController:viewClientManagementClientList',
            'verb'       => RouteVerb::GET,
            'permission' => [
                'module' => BackendController::NAME,
                'type'   => PermissionType::READ,
                'state'  => PermissionCategory::CLIENT,
            ],
        ],
    ],
    '^.*/sales/client/create.*$' => [
        [
            'dest'       => '\Modules\ClientManagement\Controller\BackendController:viewClientManagementClientCreate',
            'verb'       => RouteVerb::GET,
            'permission' => [
                'module' => BackendController::NAME,
                'type'   => PermissionType::CREATE,
                'state'  => PermissionCategory::CLIENT,
            ],
        ],
    ],
    '^.*/sales/client/profile.*$' => [
        [
            'dest'       => '\Modules\ClientManagement\Controller\BackendController:viewClientManagementClientProfile',
            'verb'       => RouteVerb::GET,
            'permission' => [
                'module' => BackendController::NAME,
                'type'   => PermissionType::READ,
                'state'  => PermissionCategory::CLIENT,
            ],
        ],
    ],
    '^.*/sales/client/analysis.*$' => [
        [
            'dest'       => '\Modules\ClientManagement\Controller\BackendController:viewClientManagementClientAnalysis',
            'verb'       => RouteVerb::GET,
            'permission' => [
                'module' => BackendController::NAME,
                'type'   => PermissionType::READ,
                'state'  => PermissionCategory::ANALYSIS,
            ],
        ],
    ],
    '^.*/sales/analysis/client(\?.*|$)$' => [
        [
            'dest'       => '\Modules\ClientManagement\Controller\BackendController:viewClientAnalysis',
            'verb'       => RouteVerb::GET,
            'permission' => [
                'module' => BackendController::NAME,
                'type'   => PermissionType::READ,
                'state'  => PermissionCategory::ANALYSIS,
            ],
        ],
    ],
];
