<?php
/**
 * Karaka
 *
 * PHP Version 8.0
 *
 * @package   Modules\ClientManagement\Models
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://karaka.app
 */
declare(strict_types=1);

namespace Modules\ClientManagement\Models;

use phpOMS\DataStorage\Database\Mapper\DataMapperFactory;

/**
 * Client mapper class.
 *
 * @package Modules\ClientManagement\Models
 * @license OMS License 1.0
 * @link    https://karaka.app
 * @since   1.0.0
 */
final class ClientAttributeValueMapper extends DataMapperFactory
{
    /**
     * Columns.
     *
     * @var array<string, array{name:string, type:string, internal:string, autocomplete?:bool, readonly?:bool, writeonly?:bool, annotations?:array}>
     * @since 1.0.0
     */
    public const COLUMNS = [
        'clientmgmt_attr_value_id'       => ['name' => 'clientmgmt_attr_value_id',       'type' => 'int',    'internal' => 'id'],
        'clientmgmt_attr_value_default'  => ['name' => 'clientmgmt_attr_value_default',  'type' => 'bool', 'internal' => 'isDefault'],
        'clientmgmt_attr_value_type'     => ['name' => 'clientmgmt_attr_value_type',     'type' => 'int',    'internal' => 'type'],
        'clientmgmt_attr_value_valueStr' => ['name' => 'clientmgmt_attr_value_valueStr', 'type' => 'string', 'internal' => 'valueStr'],
        'clientmgmt_attr_value_valueInt' => ['name' => 'clientmgmt_attr_value_valueInt', 'type' => 'int', 'internal' => 'valueInt'],
        'clientmgmt_attr_value_valueDec' => ['name' => 'clientmgmt_attr_value_valueDec', 'type' => 'float', 'internal' => 'valueDec'],
        'clientmgmt_attr_value_valueDat' => ['name' => 'clientmgmt_attr_value_valueDat', 'type' => 'DateTime', 'internal' => 'valueDat'],
        'clientmgmt_attr_value_lang'     => ['name' => 'clientmgmt_attr_value_lang',     'type' => 'string', 'internal' => 'language'],
        'clientmgmt_attr_value_country'  => ['name' => 'clientmgmt_attr_value_country',  'type' => 'string', 'internal' => 'country'],
    ];

    /**
     * Primary table.
     *
     * @var string
     * @since 1.0.0
     */
    public const TABLE = 'clientmgmt_attr_value';

    /**
     * Primary field name.
     *
     * @var string
     * @since 1.0.0
     */
    public const PRIMARYFIELD ='clientmgmt_attr_value_id';
}
