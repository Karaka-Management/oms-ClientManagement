<?php
/**
 * Jingga
 *
 * PHP Version 8.2
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
use Modules\Editor\Models\EditorDoc;
use Modules\Payment\Models\Payment;
use Modules\Profile\Models\Profile;
use Modules\Sales\Models\SalesRep;
use phpOMS\Stdlib\Base\Address;
use phpOMS\Stdlib\Base\NullAddress;

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
    /**
     * ID value.
     *
     * @var int
     * @since 1.0.0
     */
    public int $id = 0;

    /**
     * Number value.
     *
     * @var string
     * @since 1.0.0
     */
    public string $number = '';

    /**
     * Reversed number value.
     *
     * @var string
     * @since 1.0.0
     */
    public string $numberReverse = '';

    /**
     * Status value.
     *
     * @var int
     * @since 1.0.0
     */
    public int $status = ClientStatus::ACTIVE;

    /**
     * Type value.
     *
     * @var int
     * @since 1.0.0
     */
    public int $type = 0;

    /**
     * Additional information.
     *
     * @var string
     * @since 1.0.0
     */
    public string $info = '';

    public ?SalesRep $rep = null;

    /**
     * Creation date and time.
     *
     * @var \DateTimeImmutable
     * @since 1.0.0
     */
    public \DateTimeImmutable $createdAt;

    /**
     * Account associated with the client.
     *
     * @var Account
     * @since 1.0.0
     */
    public Account $account;

    /**
     * Payments.
     *
     * @var Payment[]
     * @since 1.0.0
     */
    public array $payments = [];

    /**
     * Contact elements.
     *
     * @var array
     * @since 1.0.0
     */
    public array $contactElements = [];

    /**
     * Main address.
     *
     * @var Address
     * @since 1.0.0
     */
    public Address $mainAddress;

    /**
     * Address.
     *
     * @var array
     * @since 1.0.0
     */
    public array $address = [];

    /**
     * Partners.
     *
     * @var array
     * @since 1.0.0
     */
    public array $partners = [];

    /**
     * Advertisement material.
     *
     * @var int
     * @since 1.0.0
     */
    public int $advertisementMaterial = 0;

    /**
     * Default delivery address.
     *
     * @var Address|null
     * @since 1.0.0
     */
    public ?Address $defaultDeliveryAddress = null;

    /**
     * Default invoice address.
     *
     * @var Address|null
     * @since 1.0.0
     */
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
            if ($payment->type === $type) {
                $payments[] = $payment;
            }
        }

        return $payments;
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

    use \Modules\Media\Models\MediaListTrait;
    use \Modules\Editor\Models\EditorDocListTrait;
    use \Modules\Attribute\Models\AttributeHolderTrait;
}
