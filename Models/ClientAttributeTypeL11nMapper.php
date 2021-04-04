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
final class ClientAttributeTypeL11nMapper extends DataMapperAbstract
{
    /**
     * Columns.
     *
     * @var array<string, array{name:string, type:string, internal:string, autocomplete?:bool, readonly?:bool, writeonly?:bool, annotations?:array}>
     * @since 1.0.0
     */
    protected static array $columns = [
        'clientmgmt_attr_type_l11n_id'        => ['name' => 'clientmgmt_attr_type_l11n_id',       'type' => 'int',    'internal' => 'id'],
        'clientmgmt_attr_type_l11n_title'     => ['name' => 'clientmgmt_attr_type_l11n_title',    'type' => 'string', 'internal' => 'title', 'autocomplete' => true],
        'clientmgmt_attr_type_l11n_type'      => ['name' => 'clientmgmt_attr_type_l11n_type',      'type' => 'int',    'internal' => 'type'],
        'clientmgmt_attr_type_l11n_lang'      => ['name' => 'clientmgmt_attr_type_l11n_lang', 'type' => 'string', 'internal' => 'language'],
    ];

    /**
     * Primary table.
     *
     * @var string
     * @since 1.0.0
     */
    protected static string $table = 'clientmgmt_attr_type_l11n';

    /**
     * Primary field name.
     *
     * @var string
     * @since 1.0.0
     */
    protected static string $primaryField = 'clientmgmt_attr_type_l11n_id';
}
