<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   Modules
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.2
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

use Modules\ClientManagement\Controller\ApiController;
use Modules\ClientManagement\Models\PermissionCategory;
use phpOMS\Account\PermissionType;
use phpOMS\Router\RouteVerb;

return [
    '^.*/client/find(\?.*$|$)' => [
        [
            'dest'       => '\Modules\ClientManagement\Controller\ApiController:apiClientFind',
            'verb'       => RouteVerb::GET,
            'csrf'       => true,
            'active'     => true,
            'permission' => [
                'module' => ApiController::NAME,
                'type'   => PermissionType::READ,
                'state'  => PermissionCategory::CLIENT,
            ],
        ],
    ],
    '^.*/client(\?.*|$)$' => [
        [
            'dest'       => '\Modules\ClientManagement\Controller\ApiController:apiClientCreate',
            'verb'       => RouteVerb::PUT,
            'csrf'       => true,
            'active'     => true,
            'permission' => [
                'module' => ApiController::NAME,
                'type'   => PermissionType::CREATE,
                'state'  => PermissionCategory::CLIENT,
            ],
        ],
        [
            'dest'       => '\Modules\ClientManagement\Controller\ApiController:apiClientUpdate',
            'verb'       => RouteVerb::SET,
            'csrf'       => true,
            'active'     => true,
            'permission' => [
                'module' => ApiController::NAME,
                'type'   => PermissionType::MODIFY,
                'state'  => PermissionCategory::CLIENT,
            ],
        ],
    ],
    '^.*/client/attribute(\?.*|$)$' => [
        [
            'dest'       => '\Modules\ClientManagement\Controller\ApiAttributeController:apiClientAttributeCreate',
            'verb'       => RouteVerb::PUT,
            'csrf'       => true,
            'active'     => true,
            'permission' => [
                'module' => ApiController::NAME,
                'type'   => PermissionType::CREATE,
                'state'  => PermissionCategory::CLIENT,
            ],
        ],
        [
            'dest'       => '\Modules\ClientManagement\Controller\ApiAttributeController:apiClientAttributeUpdate',
            'verb'       => RouteVerb::SET,
            'csrf'       => true,
            'active'     => true,
            'permission' => [
                'module' => ApiController::NAME,
                'type'   => PermissionType::MODIFY,
                'state'  => PermissionCategory::CLIENT,
            ],
        ],
    ],
    '^.*/client/attribute/type(\?.*|$)$' => [
        [
            'dest'       => '\Modules\ClientManagement\Controller\ApiAttributeController:apiClientAttributeTypeCreate',
            'verb'       => RouteVerb::PUT,
            'csrf'       => true,
            'active'     => true,
            'permission' => [
                'module' => ApiController::NAME,
                'type'   => PermissionType::READ,
                'state'  => PermissionCategory::ATTRIBUTE,
            ],
        ],
        [
            'dest'       => '\Modules\ClientManagement\Controller\ApiAttributeController:apiClientAttributeTypeUpdate',
            'verb'       => RouteVerb::SET,
            'csrf'       => true,
            'active'     => true,
            'permission' => [
                'module' => ApiController::NAME,
                'type'   => PermissionType::READ,
                'state'  => PermissionCategory::ATTRIBUTE,
            ],
        ],
    ],
    '^.*/client/attribute/type/l11n(\?.*|$)$' => [
        [
            'dest'       => '\Modules\ClientManagement\Controller\ApiAttributeController:apiClientAttributeTypeL11nCreate',
            'verb'       => RouteVerb::PUT,
            'csrf'       => true,
            'active'     => true,
            'permission' => [
                'module' => ApiController::NAME,
                'type'   => PermissionType::CREATE,
                'state'  => PermissionCategory::ATTRIBUTE,
            ],
        ],
        [
            'dest'       => '\Modules\ClientManagement\Controller\ApiAttributeController:apiClientAttributeTypeL11nUpdate',
            'verb'       => RouteVerb::SET,
            'csrf'       => true,
            'active'     => true,
            'permission' => [
                'module' => ApiController::NAME,
                'type'   => PermissionType::MODIFY,
                'state'  => PermissionCategory::ATTRIBUTE,
            ],
        ],
    ],
    '^.*/client/attribute/value(\?.*|$)$' => [
        [
            'dest'       => '\Modules\ClientManagement\Controller\ApiAttributeController:apiClientAttributeValueCreate',
            'verb'       => RouteVerb::PUT,
            'csrf'       => true,
            'active'     => true,
            'permission' => [
                'module' => ApiController::NAME,
                'type'   => PermissionType::CREATE,
                'state'  => PermissionCategory::CLIENT,
            ],
        ],
        [
            'dest'       => '\Modules\ClientManagement\Controller\ApiAttributeController:apiClientAttributeValueUpdate',
            'verb'       => RouteVerb::SET,
            'csrf'       => true,
            'active'     => true,
            'permission' => [
                'module' => ApiController::NAME,
                'type'   => PermissionType::MODIFY,
                'state'  => PermissionCategory::CLIENT,
            ],
        ],
    ],
    '^.*/client/attribute/value/l11n(\?.*|$)$' => [
        [
            'dest'       => '\Modules\ClientManagement\Controller\ApiAttributeController:apiClientAttributeValueL11nCreate',
            'verb'       => RouteVerb::PUT,
            'csrf'       => true,
            'active'     => true,
            'permission' => [
                'module' => ApiController::NAME,
                'type'   => PermissionType::CREATE,
                'state'  => PermissionCategory::CLIENT,
            ],
        ],
        [
            'dest'       => '\Modules\ClientManagement\Controller\ApiAttributeController:apiClientAttributeValueL11nUpdate',
            'verb'       => RouteVerb::SET,
            'csrf'       => true,
            'active'     => true,
            'permission' => [
                'module' => ApiController::NAME,
                'type'   => PermissionType::MODIFY,
                'state'  => PermissionCategory::CLIENT,
            ],
        ],
    ],
    '^.*/client/l11n(\?.*|$)$' => [
        [
            'dest'       => '\Modules\ClientManagement\Controller\ApiController:apiClientL11nCreate',
            'verb'       => RouteVerb::PUT,
            'csrf'       => true,
            'active'     => true,
            'permission' => [
                'module' => ApiController::NAME,
                'type'   => PermissionType::CREATE,
                'state'  => PermissionCategory::CLIENT,
            ],
        ],
        [
            'dest'       => '\Modules\ClientManagement\Controller\ApiController:apiClientL11nUpdate',
            'verb'       => RouteVerb::SET,
            'csrf'       => true,
            'active'     => true,
            'permission' => [
                'module' => ApiController::NAME,
                'type'   => PermissionType::MODIFY,
                'state'  => PermissionCategory::CLIENT,
            ],
        ],
    ],
    '^.*/client/l11n/type(\?.*|$)$' => [
        [
            'dest'       => '\Modules\ClientManagement\Controller\ApiController:apiClientL11nTypeCreate',
            'verb'       => RouteVerb::PUT,
            'csrf'       => true,
            'active'     => true,
            'permission' => [
                'module' => ApiController::NAME,
                'type'   => PermissionType::CREATE,
                'state'  => PermissionCategory::CLIENT,
            ],
        ],
        [
            'dest'       => '\Modules\ClientManagement\Controller\ApiController:apiClientL11nTypeUpdate',
            'verb'       => RouteVerb::SET,
            'csrf'       => true,
            'active'     => true,
            'permission' => [
                'module' => ApiController::NAME,
                'type'   => PermissionType::MODIFY,
                'state'  => PermissionCategory::CLIENT,
            ],
        ],
    ],
];
