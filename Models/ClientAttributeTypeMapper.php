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

use phpOMS\DataStorage\Database\DataMapperAbstract;

/**
 * Client mapper class.
 *
 * @package Modules\ClientManagement\Models
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 */
final class ClientAttributeTypeMapper extends DataMapperAbstract
{
    /**
     * Columns.
     *
     * @var array<string, array{name:string, type:string, internal:string, autocomplete?:bool, readonly?:bool, writeonly?:bool, annotations?:array}>
     * @since 1.0.0
     */
    protected static array $columns = [
        'clientmgmt_attr_type_id'       => ['name' => 'clientmgmt_attr_type_id',     'type' => 'int',    'internal' => 'id'],
        'clientmgmt_attr_type_name'     => ['name' => 'clientmgmt_attr_type_name',   'type' => 'string', 'internal' => 'name', 'autocomplete' => true],
        'clientmgmt_attr_type_fields'   => ['name' => 'clientmgmt_attr_type_fields', 'type' => 'int',    'internal' => 'fields'],
        'clientmgmt_attr_type_custom'   => ['name' => 'clientmgmt_attr_type_custom', 'type' => 'bool', 'internal' => 'custom'],
        'clientmgmt_attr_type_pattern'  => ['name' => 'clientmgmt_attr_type_pattern', 'type' => 'string', 'internal' => 'validationPattern'],
        'clientmgmt_attr_type_required' => ['name' => 'clientmgmt_attr_type_required', 'type' => 'bool', 'internal' => 'isRequired'],
    ];

    /**
     * Has many relation.
     *
     * @var array<string, array{mapper:string, table:string, self?:?string, external?:?string, column?:string}>
     * @since 1.0.0
     */
    protected static array $hasMany = [
        'l11n' => [
            'mapper'            => ClientAttributeTypeL11nMapper::class,
            'table'             => 'clientmgmt_attr_type_l11n',
            'self'              => 'clientmgmt_attr_type_l11n_type',
            'column'            => 'title',
            'conditional'       => true,
            'external'          => null,
        ],
        'defaults' => [
            'mapper'            => ClientAttributeValueMapper::class,
            'table'             => 'clientmgmt_client_attr_default',
            'self'              => 'clientmgmt_client_attr_default_type',
            'external'          => 'clientmgmt_client_attr_default_value',
            'conditional'       => false,
        ],
    ];

    /**
     * Primary table.
     *
     * @var string
     * @since 1.0.0
     */
    protected static string $table = 'clientmgmt_attr_type';

    /**
     * Primary field name.
     *
     * @var string
     * @since 1.0.0
     */
    protected static string $primaryField = 'clientmgmt_attr_type_id';
}
