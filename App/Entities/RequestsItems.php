<?php

namespace App\Entities;

use Doctrine\ORM\Mapping as ORM;
use HTR\Interfaces\Entities\EntityInterface;

/**
 * RequestsItems
 *
 * @ORM\Table(name="requests_items", indexes={@ORM\Index(name="fk_requests_items_requests1_idx", columns={"requests_id"})})
 * @ORM\Entity
 */
class RequestsItems implements EntityInterface
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=100, nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="supply_unit", type="string", length=5, nullable=false)
     */
    private $supplyUnit;

    /**
     * @var float
     *
     * @ORM\Column(name="request_quantity", type="float", precision=5, scale=2, nullable=false)
     */
    private $requestQuantity;

    /**
     * @var float
     *
     * @ORM\Column(name="delivery_quantity", type="float", precision=5, scale=2, nullable=false)
     */
    private $deliveryQuantity;

    /**
     * @var float
     *
     * @ORM\Column(name="value", type="float", precision=9, scale=2, nullable=false)
     */
    private $value;

    /**
     * @var \App\Entities\Requests
     *
     * @ORM\ManyToOne(targetEntity="App\Entities\Requests")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="requests_id", referencedColumnName="id")
     * })
     */
    private $requests;

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getSupplyUnit()
    {
        return $this->supplyUnit;
    }

    public function getRequestQuantity()
    {
        return $this->requestQuantity;
    }

    public function getDeliveryQuantity()
    {
        return $this->deliveryQuantity;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function getRequests()
    {
        return $this->requests;
    }

    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    public function setSupplyUnit($supplyUnit)
    {
        $this->supplyUnit = $supplyUnit;
        return $this;
    }

    public function setRequestQuantity($requestQuantity)
    {
        $this->requestQuantity = $requestQuantity;
        return $this;
    }

    public function setDeliveryQuantity($deliveryQuantity)
    {
        $this->deliveryQuantity = $deliveryQuantity;
        return $this;
    }

    public function setValue($value)
    {
        $this->value = $value;
        return $this;
    }

    public function setRequests($requests)
    {
        $this->requests = $requests;
        return $this;
    }
}
