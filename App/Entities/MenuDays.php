<?php

namespace App\Entities;

use Doctrine\ORM\Mapping as ORM;
use HTR\Interfaces\Entities\EntityInterface;

/**
 * MenuDays
 *
 * @ORM\Table(name="menu_days", indexes={@ORM\Index(name="fk_menu_days_menus1_idx", columns={"menus_id"}), @ORM\Index(name="fk_menu_days_meals1_idx", columns={"meals_id"})})
 * @ORM\Entity
 */
class MenuDays implements EntityInterface
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
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="date", nullable=false)
     */
    private $date;

    /**
     * @var \App\Entities\Meals
     *
     * @ORM\ManyToOne(targetEntity="App\Entities\Meals")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="meals_id", referencedColumnName="id")
     * })
     */
    private $meals;

    /**
     * @var \App\Entities\Menus
     *
     * @ORM\ManyToOne(targetEntity="App\Entities\Menus")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="menus_id", referencedColumnName="id")
     * })
     */
    private $menus;

    public function getId()
    {
        return $this->id;
    }

    public function getDate()
    {
        return $this->date;
    }

    public function getMeals()
    {
        return $this->meals;
    }

    public function getMenus()
    {
        return $this->menus;
    }

    public function setDate($date)
    {
        $this->date = $date;
        return $this;
    }

    public function setMeals($meals)
    {
        $this->meals = $meals;
        return $this;
    }

    public function setMenus($menus)
    {
        $this->menus = $menus;
        return $this;
    }
}
