<?php

namespace App\Entities;

use Doctrine\ORM\Mapping as ORM;
use HTR\Interfaces\Entities\EntityInterface;

/**
 * RecipesPatterns
 *
 * @ORM\Table(name="recipes_patterns", indexes={@ORM\Index(name="fk_recipes_military_organizations1_idx", columns={"military_organizations_id"})})
 * @ORM\Entity
 */
class RecipesPatterns implements EntityInterface
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

    public function getMilitaryOrganizations()
    {
        return $this->militaryOrganizations;
    }

    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    public function setMilitaryOrganizations($militaryOrganizations)
    {
        $this->militaryOrganizations = $militaryOrganizations;
        return $this;
    }
}
