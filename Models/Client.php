<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
 *
 * @package   Modules\ClientManagement\Models
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace Modules\ClientManagement\Models;

use Modules\Admin\Models\Address;
use Modules\Admin\Models\NullAddress;
use Modules\Media\Models\Media;
use Modules\Profile\Models\ContactElement;
use Modules\Profile\Models\NullContactElement;
use Modules\Profile\Models\Profile;

/**
 * Account class.
 *
 * @package Modules\ClientManagement\Models
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 */
class Client
{
    protected int $id = 0;

    private string $number = '';

    private string $numberReverse = '';

    private int $status = 0;

    private int $type = 0;

    private array $ids = [];

    private string $info = '';

    private \DateTimeImmutable $createdAt;

    private Profile $profile;

    private array $files = [];

    private array $contactElements = [];

    private $mainAddress = null;

    private array $address = [];

    private array $partners = [];

    private $salesRep = null;

    private int $advertisementMaterial = 0;

    private $defaultDeliveryAddress = null;

    private $defaultInvoiceAddress = null;

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
     * Get number.
     *
     * @return string
     *
     * @since 1.0.0
     */
    public function getNumber() : string
    {
        return $this->number;
    }

    /**
     * Set number.
     *
     * @param string $number Number
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function setNumber(string $number) : void
    {
        $this->number = $number;
    }

    /**
     * Get reverse number.
     *
     * @return string
     *
     * @since 1.0.0
     */
    public function getReverseNumber() : string
    {
        return $this->numberReverse;
    }

    /**
     * Set reverse number.
     *
     * @param string $numberReverse Reverse number
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function setReverseNumber(string $numberReverse) : void
    {
        if (!\is_scalar($numberReverse)) {
            throw new \Exception();
        }

        $this->numberReverse = $numberReverse;
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
     * Get tax id.
     *
     * @return int
     *
     * @since 1.0.0
     */
    public function getTaxId() : int
    {
        return $this->taxId;
    }

    /**
     * Set tax id.
     *
     * @param int $taxId Tax id
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function setTaxId(int $taxId) : void
    {
        $this->taxId = $taxId;
    }

    /**
     * Set default delivery address.
     *
     * @param mixed $deliveryAddress Delivery address
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function setDefaultDeliveryAddress($deliveryAddress) : void
    {
        $this->defaultDeliveryAddress = $deliveryAddress;
    }

    /**
     * Get default delivery address.
     *
     * @return mixed
     *
     * @since 1.0.0
     */
    public function getDefaultDeliveryAddress()
    {
        return $this->defaultDeliveryAddress;
    }

    /**
     * Set default invoice address.
     *
     * @param mixed $invoiceAddress Invoice address
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function setDefaultInvoiceAddress($invoiceAddress) : void
    {
        $this->defaultTnvoiceAddress = $invoiceAddress;
    }

    /**
     * Get default invoice address.
     *
     * @return mixed
     *
     * @since 1.0.0
     */
    public function getDefaultInvoiceAddress()
    {
        return $this->defaultInvoiceAddress;
    }

    /**
     * Get info.
     *
     * @return string
     *
     * @since 1.0.0
     */
    public function getInfo() : string
    {
        return $this->info;
    }

    /**
     * Set info.
     *
     * @param string $info Info
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function setInfo(string $info) : void
    {
        $this->info = $info;
    }

    /**
     * Get created at date time
     *
     * @return \DateTimeImmutable
     *
     * @since 1.0.0
     */
    public function getCreatedAt() : \DateTimeImmutable
    {
        return $this->createdAt;
    }

    /**
     * Get profile.
     *
     * @return Profile
     *
     * @since 1.0.0
     */
    public function getProfile() : Profile
    {
        return $this->profile;
    }

    /**
     * Set profile.
     *
     * @param Profile $profile Profile
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function setProfile(Profile $profile) : void
    {
        $this->profile = $profile;
    }

    /**
     * Set main address
     *
     * @param int|Address $address Address
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function setMainAddress($address) : void
    {
        $this->mainAddress = $address;
    }

    /**
     * Get main address
     *
     * @return int|Address
     *
     * @since 1.0.0
     */
    public function getMainAddress()
    {
        return $this->mainAddress;
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
        return $a->getOrder() <=> $b->getOrder();
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
}
