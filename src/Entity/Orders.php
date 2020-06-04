<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Orders
 *
 * @ORM\Table(name="orders", indexes={@ORM\Index(name="FK_CLIENT_ID", columns={"client_id"})})
 * @ORM\Entity
 */
class Orders
{
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
     * @var \Clients
     *
     * @ORM\ManyToOne(targetEntity="Clients")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="client_id", referencedColumnName="id")
     * })
     */
    private $client;


}
