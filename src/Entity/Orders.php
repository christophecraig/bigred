<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use \DateTimeInterface;

/**
 * Orders
 *
 * @ORM\Table(name="orders", indexes={@ORM\Index(name="FK_CLIENT_ID", columns={"client_id"})})
 * @ORM\Entity(repositoryClass="App\Repository\OrdersRepository")
 */
class Orders
{
    const PAYMENT_CASH = 0;
    const PAYMENT_DIRECT_CREDIT = 1;
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="order_date", type="datetime", nullable=false, options={"default"="CURRENT_TIMESTAMP"})
     */
    // TODO: Switch to \DateTimeImmutable ?
    private $orderDate = 'CURRENT_TIMESTAMP';

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="confirmation_date", type="datetime", nullable=true)
     */
    private $confirmationDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="delivery_date", type="date", nullable=false)
     */
    private $deliveryDate;

    /**
     * @var string
     *
     * @ORM\Column(name="delivery_time", type="string", length=0, nullable=false)
     */
    private $deliveryTime;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=0, nullable=false, options={"default"="pending"})
     */
    private $status = 'pending';

    /**
     * @var string
     *
     * @ORM\Column(name="payment_status", type="string", length=0, nullable=false, options={"default"="waiting"})
     */
    private $paymentStatus = 'waiting';

    /**
     * @var Clients
     *
     * @ORM\ManyToOne(targetEntity="Clients", inversedBy="orders")
     * @ORM\JoinColumn(nullable=false)
     */
    private $client;

    /**
     * @ORM\Column(type="integer")
     */
    private $paymentType;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $comment;

    public function __construct()
    {
        $this->orderDate = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOrderDate(): DateTimeInterface
    {
        return $this->orderDate;
    }

    public function setOrderDate(\DateTimeInterface $orderDate): self
    {
        $this->orderDate = $orderDate;

        return $this;
    }

    public function getConfirmationDate(): ?\DateTimeInterface
    {
        return $this->confirmationDate;
    }

    public function setConfirmationDate(
        ?\DateTimeInterface $confirmationDate
    ): self {
        $this->confirmationDate = $confirmationDate;

        return $this;
    }

    public function getDeliveryDate(): ?\DateTimeInterface
    {
        return $this->deliveryDate;
    }

    public function setDeliveryDate(\DateTimeInterface $deliveryDate): self
    {
        $this->deliveryDate = $deliveryDate;

        return $this;
    }

    public function getDeliveryTime(): ?string
    {
        return $this->deliveryTime;
    }

    public function setDeliveryTime(string $deliveryTime): self
    {
        $this->deliveryTime = $deliveryTime;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getPaymentStatus(): ?string
    {
        return $this->paymentStatus;
    }

    public function setPaymentStatus(string $paymentStatus): self
    {
        $this->paymentStatus = $paymentStatus;

        return $this;
    }

    public function getClient(): ?Clients
    {
        return $this->client;
    }

    public function setClient(?Clients $client): self
    {
        $this->client = $client;

        return $this;
    }

    public function getPaymentType(): ?int
    {
        return $this->paymentType;
    }

    public function setPaymentType(int $paymentType): self
    {
        $this->paymentType = $paymentType;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }
}