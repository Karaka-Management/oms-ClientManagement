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
final class ClientAttributeMapper extends DataMapperFactory
{
    /**
     * Columns.
     *
     * @var array<string, array{name:string, type:string, internal:string, autocomplete?:bool, readonly?:bool, writeonly?:bool, annotations?:array}>
     * @since 1.0.0
     */
    public const COLUMNS = [
        'clientmgmt_client_attr_id'      => ['name' => 'clientmgmt_client_attr_id',    'type' => 'int', 'internal' => 'id'],
        'clientmgmt_client_attr_client'  => ['name' => 'clientmgmt_client_attr_client',  'type' => 'int', 'internal' => 'client'],
        'clientmgmt_client_attr_type'    => ['name' => 'clientmgmt_client_attr_type',  'type' => 'int', 'internal' => 'type'],
        'clientmgmt_client_attr_value'   => ['name' => 'clientmgmt_client_attr_value', 'type' => 'int', 'internal' => 'value'],
    ];

    /**
     * Has one relation.
     *
     * @var array<string, array{mapper:string, external:string, by?:string, column?:string, conditional?:bool}>
     * @since 1.0.0
     */
    public const OWNS_ONE = [
        'type' => [
            'mapper'            => ClientAttributeTypeMapper::class,
            'external'          => 'clientmgmt_client_attr_type',
        ],
        'value' => [
            'mapper'            => ClientAttributeValueMapper::class,
            'external'          => 'clientmgmt_client_attr_value',
        ],
    ];

    /**
     * Primary table.
     *
     * @var string
     * @since 1.0.0
     */
    public const TABLE = 'clientmgmt_client_attr';

    /**
     * Primary field name.
     *
     * @var string
     * @since 1.0.0
     */
    public const PRIMARYFIELD ='clientmgmt_client_attr_id';
}
