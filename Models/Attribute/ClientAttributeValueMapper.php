<?php
/**
 * Jingga
 *
 * PHP Version 8.1
 *
 * @package   Modules\ClientManagement\Models\Attribute
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace Modules\ClientManagement\Models\Attribute;

use Modules\Attribute\Models\AttributeValue;
use phpOMS\DataStorage\Database\Mapper\DataMapperFactory;

/**
 * Client mapper class.
 *
 * @package Modules\ClientManagement\Models\Attribute
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 *
 * @template T of AttributeValue
 * @extends DataMapperFactory<T>
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
        'clientmgmt_attr_value_id'                => ['name' => 'clientmgmt_attr_value_id',       'type' => 'int',    'internal' => 'id'],
        'clientmgmt_attr_value_default'           => ['name' => 'clientmgmt_attr_value_default',  'type' => 'bool', 'internal' => 'isDefault'],
        'clientmgmt_attr_value_valueStr'          => ['name' => 'clientmgmt_attr_value_valueStr', 'type' => 'string', 'internal' => 'valueStr'],
        'clientmgmt_attr_value_valueInt'          => ['name' => 'clientmgmt_attr_value_valueInt', 'type' => 'int', 'internal' => 'valueInt'],
        'clientmgmt_attr_value_valueDec'          => ['name' => 'clientmgmt_attr_value_valueDec', 'type' => 'float', 'internal' => 'valueDec'],
        'clientmgmt_attr_value_valueDat'          => ['name' => 'clientmgmt_attr_value_valueDat', 'type' => 'DateTime', 'internal' => 'valueDat'],
        'clientmgmt_attr_value_unit'              => ['name' => 'clientmgmt_attr_value_unit', 'type' => 'string', 'internal' => 'unit'],
        'clientmgmt_attr_value_deptype'           => ['name' => 'clientmgmt_attr_value_deptype', 'type' => 'int', 'internal' => 'dependingAttributeType'],
        'clientmgmt_attr_value_depvalue'          => ['name' => 'clientmgmt_attr_value_depvalue', 'type' => 'int', 'internal' => 'dependingAttributeValue'],
    ];

    /**
     * Has many relation.
     *
     * @var array<string, array{mapper:class-string, table:string, self?:?string, external?:?string, column?:string}>
     * @since 1.0.0
     */
    public const HAS_MANY = [
        'l11n' => [
            'mapper'   => ClientAttributeValueL11nMapper::class,
            'table'    => 'clientmgmt_attr_value_l11n',
            'self'     => 'clientmgmt_attr_value_l11n_value',
            'column'   => 'content',
            'external' => null,
        ],
    ];

    /**
     * Model to use by the mapper.
     *
     * @var class-string<T>
     * @since 1.0.0
     */
    public const MODEL = AttributeValue::class;

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
    public const PRIMARYFIELD = 'clientmgmt_attr_value_id';
}
