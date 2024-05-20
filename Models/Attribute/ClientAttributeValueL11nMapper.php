<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   Modules\ClientManagement\Models\Attribute
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.2
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace Modules\ClientManagement\Models\Attribute;

use phpOMS\DataStorage\Database\Mapper\DataMapperFactory;
use phpOMS\Localization\BaseStringL11n;

/**
 * Client mapper class.
 *
 * @package Modules\ClientManagement\Models\Attribute
 * @license OMS License 2.2
 * @link    https://jingga.app
 * @since   1.0.0
 *
 * @template T of BaseStringL11n
 * @extends DataMapperFactory<T>
 */
final class ClientAttributeValueL11nMapper extends DataMapperFactory
{
    /**
     * Columns.
     *
     * @var array<string, array{name:string, type:string, internal:string, autocomplete?:bool, readonly?:bool, writeonly?:bool, annotations?:array}>
     * @since 1.0.0
     */
    public const COLUMNS = [
        'clientmgmt_attr_value_l11n_id'    => ['name' => 'clientmgmt_attr_value_l11n_id',    'type' => 'int',    'internal' => 'id'],
        'clientmgmt_attr_value_l11n_title' => ['name' => 'clientmgmt_attr_value_l11n_title', 'type' => 'string', 'internal' => 'content', 'autocomplete' => true],
        'clientmgmt_attr_value_l11n_value' => ['name' => 'clientmgmt_attr_value_l11n_value',  'type' => 'int',    'internal' => 'ref'],
        'clientmgmt_attr_value_l11n_lang'  => ['name' => 'clientmgmt_attr_value_l11n_lang',  'type' => 'string', 'internal' => 'language'],
    ];

    /**
     * Primary table.
     *
     * @var string
     * @since 1.0.0
     */
    public const TABLE = 'clientmgmt_attr_value_l11n';

    /**
     * Primary field name.
     *
     * @var string
     * @since 1.0.0
     */
    public const PRIMARYFIELD = 'clientmgmt_attr_value_l11n_id';

    /**
     * Model to use by the mapper.
     *
     * @var class-string<T>
     * @since 1.0.0
     */
    public const MODEL = BaseStringL11n::class;
}
