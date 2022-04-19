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

use Modules\Admin\Models\AddressMapper;
use Modules\Editor\Models\EditorDocMapper;
use Modules\Media\Models\MediaMapper;
use Modules\Profile\Models\ContactElementMapper;
use Modules\Profile\Models\ProfileMapper;
use phpOMS\DataStorage\Database\Mapper\DataMapperFactory;

/**
 * Client mapper class.
 *
 * @package Modules\ClientManagement\Models
 * @license OMS License 1.0
 * @link    https://karaka.app
 * @since   1.0.0
 */
final class ClientMapper extends DataMapperFactory
{
    /**
     * Columns.
     *
     * @var array<string, array{name:string, type:string, internal:string, autocomplete?:bool, readonly?:bool, writeonly?:bool, annotations?:array}>
     * @since 1.0.0
     */
    public const COLUMNS = [
        'clientmgmt_client_id'         => ['name' => 'clientmgmt_client_id',         'type' => 'int',      'internal' => 'id'],
        'clientmgmt_client_no'         => ['name' => 'clientmgmt_client_no',         'type' => 'string',   'internal' => 'number'],
        'clientmgmt_client_no_reverse' => ['name' => 'clientmgmt_client_no_reverse', 'type' => 'string',   'internal' => 'numberReverse'],
        'clientmgmt_client_status'     => ['name' => 'clientmgmt_client_status',     'type' => 'int',      'internal' => 'status'],
        'clientmgmt_client_type'       => ['name' => 'clientmgmt_client_type',       'type' => 'int',      'internal' => 'type'],
        'clientmgmt_client_info'       => ['name' => 'clientmgmt_client_info',       'type' => 'string',   'internal' => 'info'],
        'clientmgmt_client_created_at' => ['name' => 'clientmgmt_client_created_at', 'type' => 'DateTimeImmutable', 'internal' => 'createdAt', 'readonly' => true],
        'clientmgmt_client_profile'    => ['name' => 'clientmgmt_client_profile',    'type' => 'int',      'internal' => 'profile'],
        'clientmgmt_client_address'    => ['name' => 'clientmgmt_client_address',    'type' => 'int',      'internal' => 'mainAddress'],
    ];

    /**
     * Primary table.
     *
     * @var string
     * @since 1.0.0
     */
    public const TABLE = 'clientmgmt_client';

    /**
     * Primary field name.
     *
     * @var string
     * @since 1.0.0
     */
    public const PRIMARYFIELD ='clientmgmt_client_id';

    /**
     * Created at column
     *
     * @var string
     * @since 1.0.0
     */
    public const CREATED_AT = 'clientmgmt_client_created_at';

    /**
     * Has one relation.
     *
     * @var array<string, array{mapper:string, external:string, by?:string, column?:string, conditional?:bool}>
     * @since 1.0.0
     */
    public const OWNS_ONE = [
        'profile' => [
            'mapper'   => ProfileMapper::class,
            'external' => 'clientmgmt_client_profile',
        ],
        'mainAddress' => [
            'mapper'   => AddressMapper::class,
            'external' => 'clientmgmt_client_address',
        ],
    ];

    /**
     * Has many relation.
     *
     * @var array<string, array{mapper:string, table:string, self?:?string, external?:?string, column?:string}>
     * @since 1.0.0
     */
    public const HAS_MANY = [
        'files'           => [
            'mapper'   => MediaMapper::class,              /* mapper of the related object */
            'table'    => 'clientmgmt_client_media',       /* table of the related object, null if no relation table is used (many->1) */
            'external' => 'clientmgmt_client_media_dst',
            'self'     => 'clientmgmt_client_media_src',
        ],
        'notes'           => [
            'mapper'   => EditorDocMapper::class,              /* mapper of the related object */
            'table'    => 'clientmgmt_client_note',       /* table of the related object, null if no relation table is used (many->1) */
            'external' => 'clientmgmt_client_note_dst',
            'self'     => 'clientmgmt_client_note_src',
        ],
        'contactElements' => [
            'mapper'   => ContactElementMapper::class,
            'table'    => 'clientmgmt_client_contactelement',
            'external' => 'clientmgmt_client_contactelement_dst',
            'self'     => 'clientmgmt_client_contactelement_src',
        ],
        'attributes' => [
            'mapper'      => ClientAttributeMapper::class,
            'table'       => 'clientmgmt_client_attr',
            'self'        => 'clientmgmt_client_attr_client',
            'conditional' => true,
            'external'    => null,
        ],
    ];
}
