<?php

namespace App\Entities;

use Doctrine\ORM\Mapping as ORM;
use HTR\Interfaces\Entities\EntityInterface;

/**
 * StockControlsItems
 *
 * @ORM\Table(name="stock_controls_items", indexes={@ORM\Index(name="fk_stoke_entries_items_stok_entries1_idx", columns={"stok_entries_id"})})
 * @ORM\Entity
 */
class StockControlsItems implements EntityInterface
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
     * @ORM\Column(name="quantity", type="float", precision=9, scale=2, nullable=false)
     */
    private $quantity;

    /**
     * @var string
     *
     * @ORM\Column(name="pi_identifier", type="string", length=20, nullable=true)
     */
    private $piIdentifier;

    /**
     * @var \App\Entities\StockControls
     *
     * @ORM\ManyToOne(targetEntity="App\Entities\StockControls")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="stok_entries_id", referencedColumnName="id")
     * })
     */
    private $stokEntries;

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

    public function getQuantity()
    {
        return $this->quantity;
    }

    public function getPiIdentifier()
    {
        return $this->piIdentifier;
    }

    public function getStokEntries()
    {
        return $this->stokEntries;
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

    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;
        return $this;
    }

    public function setPiIdentifier($piIdentifier)
    {
        $this->piIdentifier = $piIdentifier;
        return $this;
    }

    public function setStokEntries($stokEntries)
    {
        $this->stokEntries = $stokEntries;
        return $this;
    }
}
