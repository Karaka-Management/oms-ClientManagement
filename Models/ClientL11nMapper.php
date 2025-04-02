<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   Modules\ClientManagement\Models
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.2
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace Modules\ClientManagement\Models;

use phpOMS\DataStorage\Database\Mapper\DataMapperFactory;
use phpOMS\Localization\BaseStringL11n;

/**
 * Client mapper class.
 *
 * @package Modules\ClientManagement\Models
 * @license OMS License 2.2
 * @link    https://jingga.app
 * @since   1.0.0
 *
 * @template T of BaseStringL11n
 * @extends DataMapperFactory<T>
 */
final class ClientL11nMapper extends DataMapperFactory
{
    /**
     * Columns.
     *
     * @var array<string, array{name:string, type:string, internal:string, autocomplete?:bool, readonly?:bool, writeonly?:bool, annotations?:array}>
     * @since 1.0.0
     */
    public const COLUMNS = [
        'clientmgmt_client_l11n_id'          => ['name' => 'clientmgmt_client_l11n_id',          'type' => 'int',    'internal' => 'id'],
        'clientmgmt_client_l11n_description' => ['name' => 'clientmgmt_client_l11n_description', 'type' => 'string', 'internal' => 'content', 'autocomplete' => true],
        'clientmgmt_client_l11n_client'      => ['name' => 'clientmgmt_client_l11n_client',        'type' => 'int',    'internal' => 'ref'],
        'clientmgmt_client_l11n_lang'        => ['name' => 'clientmgmt_client_l11n_lang',        'type' => 'string', 'internal' => 'language'],
        'clientmgmt_client_l11n_typeref'     => ['name' => 'clientmgmt_client_l11n_typeref',     'type' => 'int',    'internal' => 'type'],
    ];

    /**
     * Has one relation.
     *
     * @var array<string, array{mapper:class-string, external:string, by?:string, column?:string, conditional?:bool}>
     * @since 1.0.0
     */
    public const OWNS_ONE = [
        'type' => [
            'mapper'   => ClientL11nTypeMapper::class,
            'external' => 'clientmgmt_client_l11n_typeref',
        ],
    ];

    /**
     * Primary table.
     *
     * @var string
     * @since 1.0.0
     */
    public const TABLE = 'clientmgmt_client_l11n';

    /**
     * Primary field name.
     *
     * @var string
     * @since 1.0.0
     */
    public const PRIMARYFIELD = 'clientmgmt_client_l11n_id';

    /**
     * Model to use by the mapper.
     *
     * @var class-string<T>
     * @since 1.0.0
     */
    public const MODEL = BaseStringL11n::class;
}
