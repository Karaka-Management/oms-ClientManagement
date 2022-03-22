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

use phpOMS\Contract\ArrayableInterface;
use phpOMS\Localization\ISO639x1Enum;

/**
 * Client class.
 *
 * @package Modules\ClientManagement\Models
 * @license OMS License 1.0
 * @link    https://karaka.app
 * @since   1.0.0
 */
class ClientAttributeTypeL11n implements \JsonSerializable, ArrayableInterface
{
    /**
     * ID.
     *
     * @var int
     * @since 1.0.0
     */
    protected int $id = 0;

    /**
     * Client ID.
     *
     * @var int|ClientAttributeType
     * @since 1.0.0
     */
    public int | ClientAttributeType $type = 0;

    /**
     * Language.
     *
     * @var string
     * @since 1.0.0
     */
    protected string $language = ISO639x1Enum::_EN;

    /**
     * Title.
     *
     * @var string
     * @since 1.0.0
     */
    public string $title = '';

    /**
     * Constructor.
     *
     * @param int|ClientAttributeType $type     Attribute type
     * @param string                  $title    Localized title
     * @param string                  $language Language
     *
     * @since 1.0.0
     */
    public function __construct(int | ClientAttributeType $type = 0, string $title = '', string $language = ISO639x1Enum::_EN)
    {
        $this->type     = $type;
        $this->title    = $title;
        $this->language = $language;
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
     * Get language
     *
     * @return string
     *
     * @since 1.0.0
     */
    public function getLanguage() : string
    {
        return $this->language;
    }

    /**
     * Set language
     *
     * @param string $language Language
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function setLanguage(string $language) : void
    {
        $this->language = $language;
    }

    /**
     * {@inheritdoc}
     */
    public function toArray() : array
    {
        return [
            'id'       => $this->id,
            'title'    => $this->title,
            'type'     => $this->type,
            'language' => $this->language,
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
