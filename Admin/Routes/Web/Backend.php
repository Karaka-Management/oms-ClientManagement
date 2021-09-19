<?php
/**
 * Orange Management
 *
 * PHP Version 8.0
 *
 * @package   Modules
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

use Modules\ClientManagement\Controller\BackendController;
use Modules\ClientManagement\Models\PermissionState;
use phpOMS\Account\PermissionType;
use phpOMS\Router\RouteVerb;

return [
    '^.*/sales/client/list.*$' => [
        [
            'dest'       => '\Modules\ClientManagement\Controller\BackendController:viewClientManagementClientList',
            'verb'       => RouteVerb::GET,
            'permission' => [
                'module' => BackendController::MODULE_NAME,
                'type'   => PermissionType::READ,
                'state'  => PermissionState::CLIENT,
            ],
        ],
    ],
    '^.*/sales/client/create.*$' => [
        [
            'dest'       => '\Modules\ClientManagement\Controller\BackendController:viewClientManagementClientCreate',
            'verb'       => RouteVerb::GET,
            'permission' => [
                'module' => BackendController::MODULE_NAME,
                'type'   => PermissionType::CREATE,
                'state'  => PermissionState::CLIENT,
            ],
        ],
    ],
    '^.*/sales/client/profile.*$' => [
        [
            'dest'       => '\Modules\ClientManagement\Controller\BackendController:viewClientManagementClientProfile',
            'verb'       => RouteVerb::GET,
            'permission' => [
                'module' => BackendController::MODULE_NAME,
                'type'   => PermissionType::READ,
                'state'  => PermissionState::CLIENT,
            ],
        ],
    ],
    '^.*/sales/client/analysis.*$' => [
        [
            'dest'       => '\Modules\ClientManagement\Controller\BackendController:viewClientManagementClientAnalysis',
            'verb'       => RouteVerb::GET,
            'permission' => [
                'module' => BackendController::MODULE_NAME,
                'type'   => PermissionType::READ,
                'state'  => PermissionState::ANALYSIS,
            ],
        ],
    ],
    '^.*/sales/analysis/client(\?.*|$)$' => [
        [
            'dest'       => '\Modules\ClientManagement\Controller\BackendController:viewClientAnalysis',
            'verb'       => RouteVerb::GET,
            'permission' => [
                'module' => BackendController::MODULE_NAME,
                'type'   => PermissionType::READ,
                'state'  => PermissionState::ANALYSIS,
            ],
        ],
    ],
];
