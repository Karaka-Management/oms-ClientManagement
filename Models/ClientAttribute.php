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

/**
 * Client class.
 *
 * @package Modules\ClientManagement\Models
 * @license OMS License 1.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
class ClientAttribute implements \JsonSerializable
{
    /**
     * Id.
     *
     * @var int
     * @since 1.0.0
     */
    protected int $id = 0;

    /**
     * Client this attribute belongs to
     *
     * @var int
     * @since 1.0.0
     */
    public int $client = 0;

    /**
     * Attribute type the attribute belongs to
     *
     * @var ClientAttributeType
     * @since 1.0.0
     */
    public ClientAttributeType $type;

    /**
     * Attribute value the attribute belongs to
     *
     * @var ClientAttributeValue
     * @since 1.0.0
     */
    public ClientAttributeValue $value;

    /**
     * Constructor.
     *
     * @since 1.0.0
     */
    public function __construct()
    {
        $this->type  = new ClientAttributeType();
        $this->value = new ClientAttributeValue();
    }

    /**
     * Get id
     *
     * @return int
     *
     * @since 1.0.0
     */
    public function getId() : int
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function toArray() : array
    {
        return [
            'id'      => $this->id,
            'client'  => $this->client,
            'type'    => $this->type,
            'value'   => $this->value,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize() : mixed
    {
        return $this->toArray();
    }
}
