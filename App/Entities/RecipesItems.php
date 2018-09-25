<?php

namespace App\Entities;

use Doctrine\ORM\Mapping as ORM;
use HTR\Interfaces\Entities\EntityInterface;

/**
 * RecipesItems
 *
 * @ORM\Table(name="recipes_items", indexes={@ORM\Index(name="fk_recipes_items_recipes1_idx", columns={"recipes_id"})})
 * @ORM\Entity
 */
class RecipesItems implements EntityInterface
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
     * @var \App\Entities\Recipes
     *
     * @ORM\ManyToOne(targetEntity="App\Entities\Recipes")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="recipes_id", referencedColumnName="id")
     * })
     */
    private $recipes;

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

    public function getRecipes()
    {
        return $this->recipes;
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

    public function setRecipes($recipes)
    {
        $this->recipes = $recipes;
        return $this;
    }
}
