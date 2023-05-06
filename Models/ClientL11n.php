<?php
/**
 * Karaka
 *
 * PHP Version 8.1
 *
 * @package   Modules\ClientManagement\Models
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace Modules\ClientManagement\Models;

use phpOMS\Localization\ISO639x1Enum;

/**
 * Localization of the client class.
 *
 * @package Modules\ClientManagement\Models
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
class ClientL11n implements \JsonSerializable
{
    /**
     * Article ID.
     *
     * @var int
     * @since 1.0.0
     */
    public int $id = 0;

    /**
     * Client ID.
     *
     * @var int
     * @since 1.0.0
     */
    public int $client = 0;

    /**
     * Client ID.
     *
     * @var ClientL11nType
     * @since 1.0.0
     */
    public ClientL11nType $type;

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
    public string $description = '';

    /**
     * Constructor.
     *
     * @param ClientL11nType $type        Client localization type
     * @param string         $description Description/content
     * @param string         $language    Language
     *
     * @since 1.0.0
     */
    public function __construct(ClientL11nType $type = null, string $description = '', string $language = ISO639x1Enum::_EN)
    {
        $this->type        = $type ?? new ClientL11nType();
        $this->description = $description;
        $this->language    = $language;
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
            'id'             => $this->id,
            'description'    => $this->description,
            'client'           => $this->client,
            'language'       => $this->language,
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
