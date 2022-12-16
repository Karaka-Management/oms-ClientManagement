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

use Modules\Admin\Models\Address;
use Modules\Admin\Models\NullAddress;
use Modules\Editor\Models\EditorDoc;
use Modules\Media\Models\Media;
use Modules\Media\Models\NullMedia;
use Modules\Profile\Models\ContactElement;
use Modules\Profile\Models\NullContactElement;
use Modules\Profile\Models\Profile;

/**
 * Account class.
 *
 * @package Modules\ClientManagement\Models
 * @license OMS License 1.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
class Client
{
    protected int $id = 0;

    public string $number = '';

    public string $numberReverse = '';

    private int $status = ClientStatus::ACTIVE;

    private int $type = 0;

    private array $ids = [];

    public string $info = '';

    public \DateTimeImmutable $createdAt;

    public Profile $profile;

    /**
     * Attributes.
     *
     * @var int[]|ClientAttribute[]
     * @since 1.0.0
     */
    private array $attributes = [];

    /**
     * Files.
     *
     * @var EditorDoc[]
     * @since 1.0.0
     */
    private array $notes = [];

    private array $files = [];

    private array $contactElements = [];

    public Address $mainAddress;

    private array $address = [];

    private array $partners = [];

    private ?Profile $salesRep = null;

    private int $advertisementMaterial = 0;

    public ?Address $defaultDeliveryAddress = null;

    public ?Address $defaultInvoiceAddress = null;

    /**
     * Constructor.
     *
     * @since 1.0.0
     */
    public function __construct()
    {
        $this->createdAt   = new \DateTimeImmutable('now');
        $this->profile     = new Profile();
        $this->mainAddress = new NullAddress();
    }

    /**
     * Get id.
     *
     * @return int Model id
     *
     * @since 1.0.0
     */
    public function getId() : int
    {
        return $this->id;
    }

    /**
     * Get status.
     *
     * @return int
     *
     * @since 1.0.0
     */
    public function getStatus() : int
    {
        return $this->status;
    }

    /**
     * Set status.
     *
     * @param int $status Status
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function setStatus(int $status) : void
    {
        $this->status = $status;
    }

    /**
     * Get type.
     *
     * @return int
     *
     * @since 1.0.0
     */
    public function getType() : int
    {
        return $this->type;
    }

    /**
     * Set type.
     *
     * @param int $type Type
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function setType(int $type) : void
    {
        $this->type = $type;
    }

    /**
     * Add doc to item
     *
     * @param EditorDoc $note Note
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function addNote(EditorDoc $note) : void
    {
        $this->notes[] = $note;
    }

    /**
     * Get notes
     *
     * @return EditorDoc[]
     *
     * @since 1.0.0
     */
    public function getNotes() : array
    {
        return $this->notes;
    }

    /**
     * Get media.
     *
     * @return array
     *
     * @since 1.0.0
     */
    public function getFiles() : array
    {
        return $this->files;
    }

    /**
     * Add media.
     *
     * @param Media $file Media
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function addFile(Media $file) : void
    {
        $this->files[] = $file;
    }

    /**
     * Get addresses.
     *
     * @return array
     *
     * @since 1.0.0
     */
    public function getAddresses() : array
    {
        return $this->address;
    }

    /**
     * Get contacts.
     *
     * @return array
     *
     * @since 1.0.0
     */
    public function getContactElements() : array
    {
        return $this->contactElements;
    }

    /**
     * Order contact elements
     *
     * @param ContactElement $a Element
     * @param ContactElement $b Element
     *
     * @return int
     *
     * @since 1.0.0
     */
    private function orderContactElements(ContactElement $a, ContactElement $b) : int
    {
        return $a->order <=> $b->order;
    }

    /**
     * Get the main contact element by type
     *
     * @param int $type Contact element type
     *
     * @return ContactElement
     *
     * @since 1.0.0
     */
    public function getMainContactElement(int $type) : ContactElement
    {
        \uasort($this->contactElements, [$this, 'orderContactElements']);

        foreach ($this->contactElements as $element) {
            if ($element->getType() === $type) {
                return $element;
            }
        }

        return new NullContactElement();
    }

    /**
     * Add contact element
     *
     * @param int|ContactElement $element Contact element
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function addContactElement($element) : void
    {
        $this->contactElements[] = $element;
    }

    /**
     * Get all media files by type
     *
     * @param null|int $type Media type
     *
     * @return Media[]
     *
     * @since 1.0.0
     */
    public function getFilesByType(int $type = null) : array
    {
        $files = [];
        foreach ($this->files as $file) {
            if ($file->type === $type) {
                $files[] = $file;
            }
        }

        return $files;
    }

    /**
     * Get all media files by type
     *
     * @param int $type Media type
     *
     * @return Media
     *
     * @since 1.0.0
     */
    public function getFileByType(int $type) : Media
    {
        foreach ($this->files as $file) {
            if ($file->type === $type) {
                return $file;
            }
        }

        return new NullMedia();
    }

    /**
     * {@inheritdoc}
     */
    public function toArray() : array
    {
        return [
            'id'            => $this->id,
            'number'        => $this->number,
            'numberReverse' => $this->numberReverse,
            'status'        => $this->status,
            'type'          => $this->type,
            'info'          => $this->info,
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
