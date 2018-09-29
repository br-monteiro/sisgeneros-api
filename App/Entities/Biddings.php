<?php

namespace App\Entities;

use Doctrine\ORM\Mapping as ORM;
use HTR\Interfaces\Entities\EntityInterface;

/**
 * Biddings
 *
 * @ORM\Table(name="biddings")
 * @ORM\Entity
 */
class Biddings implements EntityInterface
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
     * @var integer
     *
     * @ORM\Column(name="year", type="integer", nullable=false)
     */
    private $year;

    /**
     * @var integer
     *
     * @ORM\Column(name="uasg_number", type="integer", nullable=false)
     */
    private $uasgNumber;

    /**
     * @var string
     *
     * @ORM\Column(name="uasg_name", type="string", length=100, nullable=false)
     */
    private $uasgName;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="validate", type="date", nullable=false)
     */
    private $validate;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="App\Entities\MilitaryOrganizations", inversedBy="biddings")
     * @ORM\JoinTable(name="availability",
     *   joinColumns={
     *     @ORM\JoinColumn(name="biddings_id", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="military_organizations_id", referencedColumnName="id")
     *   }
     * )
     */
    private $militaryOrganizations;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->militaryOrganizations = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getNumber()
    {
        return $this->number;
    }

    public function getYear()
    {
        return $this->year;
    }

    public function getUasgNumber()
    {
        return $this->uasgNumber;
    }

    public function getUasgName()
    {
        return $this->uasgName;
    }

    public function getValidate()
    {
        return $this->validate;
    }

    public function getMilitaryOrganizations()
    {
        return $this->militaryOrganizations;
    }

    public function setNumber($number)
    {
        $this->number = $number;
        return $this;
    }

    public function setYear($year)
    {
        $this->year = $year;
        return $this;
    }

    public function setUasgNumber($uasgNumber)
    {
        $this->uasgNumber = $uasgNumber;
        return $this;
    }

    public function setUasgName($uasgName)
    {
        $this->uasgName = $uasgName;
        return $this;
    }

    public function setValidate($validate)
    {
        $this->validate = $validate;
        return $this;
    }

    public function setMilitaryOrganizations($militaryOrganizations)
    {
        $this->militaryOrganizations = $militaryOrganizations;
        return $this;
    }
}
