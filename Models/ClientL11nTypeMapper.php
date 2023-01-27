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
final class ClientL11nTypeMapper extends DataMapperFactory
{
    /**
     * Columns.
     *
     * @var array<string, array{name:string, type:string, internal:string, autocomplete?:bool, readonly?:bool, writeonly?:bool, annotations?:array}>
     * @since 1.0.0
     */
    public const COLUMNS = [
        'clientmgmt_client_l11n_type_id'       => ['name' => 'clientmgmt_client_l11n_type_id',    'type' => 'int',    'internal' => 'id'],
        'clientmgmt_client_l11n_type_title'    => ['name' => 'clientmgmt_client_l11n_type_title', 'type' => 'string', 'internal' => 'title'],
        'clientmgmt_client_l11n_type_required' => ['name' => 'clientmgmt_client_l11n_type_required', 'type' => 'bool', 'internal' => 'isRequired'],
    ];

    /**
     * Primary table.
     *
     * @var string
     * @since 1.0.0
     */
    public const TABLE = 'clientmgmt_client_l11n_type';

    /**
     * Primary field name.
     *
     * @var string
     * @since 1.0.0
     */
    public const PRIMARYFIELD ='clientmgmt_client_l11n_type_id';
}
