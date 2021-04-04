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

use phpOMS\Contract\ArrayableInterface;

/**
 * Client class.
 *
 * @package Modules\ClientManagement\Models
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 */
class ClientAttribute implements \JsonSerializable, ArrayableInterface
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
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize()
    {
        return $this->toArray();
    }
}
