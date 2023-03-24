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

use Modules\Admin\Models\Account;
use Modules\Admin\Models\Address;
use Modules\Admin\Models\NullAddress;
use Modules\Editor\Models\EditorDoc;
use Modules\Media\Models\Media;
use Modules\Media\Models\NullMedia;
use Modules\Payment\Models\Payment;
use Modules\Profile\Models\ContactElement;
use Modules\Profile\Models\NullContactElement;
use Modules\Profile\Models\Profile;

/**
 * Client class.
 *
 * @package Modules\ClientManagement\Models
 * @license OMS License 2.0
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

    public string $info = '';

    public \DateTimeImmutable $createdAt;

    public Account $account;

    /**
     * Attributes.
     *
     * @var ClientAttribute[]
     * @since 1.0.0
     */
    private array $attributes = [];

    /**
     * Payments.
     *
     * @var Payment[]
     * @since 1.0.0
     */
    private array $payments = [];

    /**
     * Files.
     *
     * @var EditorDoc[]
     * @since 1.0.0
     */
    private array $notes = [];

    /**
     * Files.
     *
     * @var Media[]
     * @since 1.0.0
     */
    private array $files = [];

    private array $contactElements = [];

    public Address $mainAddress;

    private array $address = [];

    private array $partners = [];

    public ?Profile $salesRep = null;

    public int $advertisementMaterial = 0;

    public ?Address $defaultDeliveryAddress = null;

    public ?Address $defaultInvoiceAddress = null;

    /**
     * Unit
     *
     * @var null|int
     * @since 1.0.0
     */
    public ?int $unit = null;

    /**
     * Constructor.
     *
     * @since 1.0.0
     */
    public function __construct()
    {
        $this->createdAt   = new \DateTimeImmutable('now');
        $this->account     = new Account();
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
     * Add partner
     *
     * @param Account $partner Partner
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function addPartner(Account $partner) : void
    {
        $this->partners[] = $partner;
    }

    /**
     * Get partners
     *
     * @return Account[]
     *
     * @since 1.0.0
     */
    public function getPartners() : array
    {
        return $this->partners;
    }

    /**
     * Add attribute to client
     *
     * @param ClientAttribute $attribute Attribute
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function addAttribute(ClientAttribute $attribute) : void
    {
        $this->attributes[] = $attribute;
    }

    /**
     * Get attributes
     *
     * @return ClientAttribute[]
     *
     * @since 1.0.0
     */
    public function getAttributes() : array
    {
        return $this->attributes;
    }

    /**
     * Get attribute
     *
     * @param string $attrName Attribute name
     *
     * @return null|ClientAttribute
     *
     * @since 1.0.0
     */
    public function getAttribute(string $attrName) : ?ClientAttribute
    {
        foreach ($this->attributes as $attribute) {
            if ($attribute->type->name === $attrName) {
                return $attribute->value;
            }
        }

        return null;
    }

    /**
     * Get payments
     *
     * @return Payment[]
     *
     * @since 1.0.0
     */
    public function getPayments() : array
    {
        return $this->payments;
    }

    /**
     * Get payments
     *
     * @param int $type Payment type
     *
     * @return array
     *
     * @since 1.0.0
     */
    public function getPaymentsByType(int $type) : array
    {
        $payments = [];

        foreach ($this->payments as $payment) {
            if ($payment->getType() === $type) {
                $payments[] = $payment;
            }
        }

        return $payments;
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
     * Get files
     *
     * @return Media[]
     *
     * @since 1.0.0
     */
    public function getFiles() : array
    {
        return $this->files;
    }

    /**
     * Get media file by type
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
            if ($file->hasMediaTypeId($type)) {
                return $file;
            }
        }

        return new NullMedia();
    }

    /**
     * Get all media files by type name
     *
     * @param string $type Media type
     *
     * @return Media
     *
     * @since 1.0.0
     */
    public function getFileByTypeName(string $type) : Media
    {
        foreach ($this->files as $file) {
            if ($file->hasMediaTypeName($type)) {
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
