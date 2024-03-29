<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   Modules
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

use Modules\ClientManagement\Controller\BackendController;
use Modules\ClientManagement\Models\PermissionCategory;
use phpOMS\Account\PermissionType;
use phpOMS\Router\RouteVerb;

return [
    '^/sales/client/attribute/type/list(\?.*$|$)' => [
        [
            'dest'       => '\Modules\ClientManagement\Controller\BackendController:viewClientManagementAttributeTypeList',
            'verb'       => RouteVerb::GET,
            'permission' => [
                'module' => BackendController::NAME,
                'type'   => PermissionType::READ,
                'state'  => PermissionCategory::ATTRIBUTE,
            ],
        ],
    ],
    '^/sales/client/attribute/type/view(\?.*$|$)' => [
        [
            'dest'       => '\Modules\ClientManagement\Controller\BackendController:viewClientManagementAttributeType',
            'verb'       => RouteVerb::GET,
            'permission' => [
                'module' => BackendController::NAME,
                'type'   => PermissionType::READ,
                'state'  => PermissionCategory::ATTRIBUTE,
            ],
        ],
    ],
    '^/sales/client/attribute/type/create(\?.*$|$)' => [
        [
            'dest'       => '\Modules\ClientManagement\Controller\BackendController:viewClientManagementAttributeType',
            'verb'       => RouteVerb::GET,
            'permission' => [
                'module' => BackendController::NAME,
                'type'   => PermissionType::CREATE,
                'state'  => PermissionCategory::ATTRIBUTE,
            ],
        ],
    ],
    '^/sales/client/attribute/value/view(\?.*$|$)' => [
        [
            'dest'       => '\Modules\ClientManagement\Controller\BackendController:viewClientManagementAttributeValue',
            'verb'       => RouteVerb::GET,
            'permission' => [
                'module' => BackendController::NAME,
                'type'   => PermissionType::READ,
                'state'  => PermissionCategory::ATTRIBUTE,
            ],
        ],
    ],
    '^/sales/client/attribute/value/create(\?.*$|$)' => [
        [
            'dest'       => '\Modules\ClientManagement\Controller\BackendController:viewClientManagementAttributeValueCreate',
            'verb'       => RouteVerb::GET,
            'permission' => [
                'module' => BackendController::NAME,
                'type'   => PermissionType::CREATE,
                'state'  => PermissionCategory::ATTRIBUTE,
            ],
        ],
    ],
    '^/sales/client/list(\?.*$|$)' => [
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
    '^/sales/client/create(\?.*$|$)' => [
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
    '^/sales/client/view(\?.*$|$)' => [
        [
            'dest'       => '\Modules\ClientManagement\Controller\BackendController:viewClientManagementClientView',
            'verb'       => RouteVerb::GET,
            'permission' => [
                'module' => BackendController::NAME,
                'type'   => PermissionType::READ,
                'state'  => PermissionCategory::CLIENT,
            ],
        ],
    ],
    '^/sales/client/analysis(\?.*$|$)' => [
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
];
