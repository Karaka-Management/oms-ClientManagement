<?php
/**
 * Karaka
 *
 * PHP Version 8.1
 *
 * @package   Modules\ClientManagement\Models
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace Modules\ClientManagement\Models;

use phpOMS\DataStorage\Database\Mapper\DataMapperFactory;

/**
 * Client mapper class.
 *
 * @package Modules\ClientManagement\Models
 * @license OMS License 1.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
final class ClientAttributeTypeL11nMapper extends DataMapperFactory
{
    /**
     * Columns.
     *
     * @var array<string, array{name:string, type:string, internal:string, autocomplete?:bool, readonly?:bool, writeonly?:bool, annotations?:array}>
     * @since 1.0.0
     */
    public const COLUMNS = [
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
    public const TABLE = 'clientmgmt_attr_type_l11n';

    /**
     * Primary field name.
     *
     * @var string
     * @since 1.0.0
     */
    public const PRIMARYFIELD ='clientmgmt_attr_type_l11n_id';
}
