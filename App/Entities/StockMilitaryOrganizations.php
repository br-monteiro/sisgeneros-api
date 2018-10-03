<?php

namespace App\Entities;

use Doctrine\ORM\Mapping as ORM;
use HTR\Interfaces\Entities\EntityInterface;

/**
 * StockMilitaryOrganizations
 *
 * @ORM\Table(name="stock_military_organizations", indexes={@ORM\Index(name="fk_stok_items_military_organizations1_idx", columns={"military_organizations_id"})})
 * @ORM\Entity
 */
class StockMilitaryOrganizations implements EntityInterface
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
     * @var \App\Entities\MilitaryOrganizations
     *
     * @ORM\ManyToOne(targetEntity="App\Entities\MilitaryOrganizations")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="military_organizations_id", referencedColumnName="id")
     * })
     */
    private $militaryOrganizations;

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

    public function getMilitaryOrganizations()
    {
        return $this->militaryOrganizations;
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

    public function setMilitaryOrganizations($militaryOrganizations)
    {
        $this->militaryOrganizations = $militaryOrganizations;
        return $this;
    }
}
