<?php

namespace App\Entities;

use Doctrine\ORM\Mapping as ORM;
use HTR\Interfaces\Entities\EntityInterface;

/**
 * Recipes
 *
 * @ORM\Table(name="recipes", indexes={@ORM\Index(name="fk_recipes_menu_days1_idx", columns={"menu_days_id"})})
 * @ORM\Entity
 */
class Recipes implements EntityInterface
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
     * @var \App\Entities\MenuDays
     *
     * @ORM\ManyToOne(targetEntity="App\Entities\MenuDays")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="menu_days_id", referencedColumnName="id")
     * })
     */
    private $menuDays;

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getMenuDays()
    {
        return $this->menuDays;
    }

    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    public function setMenuDays($menuDays)
    {
        $this->menuDays = $menuDays;
        return $this;
    }
}
