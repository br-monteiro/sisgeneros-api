<?php

namespace App\Entities;

use Doctrine\ORM\Mapping as ORM;
use HTR\Interfaces\Entities\EntityInterface;

/**
 * BiddingsItems
 *
 * @ORM\Table(name="biddings_items", indexes={@ORM\Index(name="fk_biddings_items_suppliers1_idx", columns={"suppliers_id"}), @ORM\Index(name="fk_biddings_items_biddings1_idx", columns={"biddings_id"})})
 * @ORM\Entity
 */
class BiddingsItems implements EntityInterface
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
     * @var integer
     *
     * @ORM\Column(name="number", type="integer", nullable=false)
     */
    private $number;

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
     * @ORM\Column(name="initial_quantity", type="float", precision=9, scale=2, nullable=false)
     */
    private $initialQuantity;

    /**
     * @var float
     *
     * @ORM\Column(name="current_quantity", type="float", precision=9, scale=2, nullable=false)
     */
    private $currentQuantity;

    /**
     * @var float
     *
     * @ORM\Column(name="value", type="float", precision=9, scale=2, nullable=false)
     */
    private $value;

    /**
     * @var \App\Entities\Biddings
     *
     * @ORM\ManyToOne(targetEntity="App\Entities\Biddings")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="biddings_id", referencedColumnName="id")
     * })
     */
    private $biddings;

    /**
     * @var \App\Entities\Suppliers
     *
     * @ORM\ManyToOne(targetEntity="App\Entities\Suppliers")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="suppliers_id", referencedColumnName="id")
     * })
     */
    private $suppliers;

    public function getId()
    {
        return $this->id;
    }

    public function getNumber()
    {
        return $this->number;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getSupplyUnit()
    {
        return $this->supplyUnit;
    }

    public function getInitialQuantity()
    {
        return $this->initialQuantity;
    }

    public function getCurrentQuantity()
    {
        return $this->currentQuantity;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function getBiddings()
    {
        return $this->biddings;
    }

    public function getSuppliers()
    {
        return $this->suppliers;
    }

    public function setNumber($number)
    {
        $this->number = $number;
        return $this;
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

    public function setInitialQuantity($initialQuantity)
    {
        $this->initialQuantity = $initialQuantity;
        return $this;
    }

    public function setCurrentQuantity($currentQuantity)
    {
        $this->currentQuantity = $currentQuantity;
        return $this;
    }

    public function setValue($value)
    {
        $this->value = $value;
        return $this;
    }

    public function setBiddings($biddings)
    {
        $this->biddings = $biddings;
        return $this;
    }

    public function setSuppliers($suppliers)
    {
        $this->suppliers = $suppliers;
        return $this;
    }
}
