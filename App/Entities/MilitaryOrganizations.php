<?php

namespace App\Entities;

use Doctrine\ORM\Mapping as ORM;
use HTR\Interfaces\Entities\EntityInterface;

/**
 * MilitaryOrganizations
 *
 * @ORM\Table(name="military_organizations", uniqueConstraints={@ORM\UniqueConstraint(name="naval_indicative_UNIQUE", columns={"naval_indicative"})}, indexes={@ORM\Index(name="fk_military_organizations_users1_idx", columns={"munition_manager"}), @ORM\Index(name="fk_military_organizations_users2_idx", columns={"fiscal_agent"}), @ORM\Index(name="fk_military_organizations_users3_idx", columns={"munition_fiel"})})
 * @ORM\Entity
 */
class MilitaryOrganizations implements EntityInterface
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
     * @ORM\Column(name="naval_indicative", type="string", length=6, nullable=false)
     */
    private $navalIndicative;

    /**
     * @var integer
     *
     * @ORM\Column(name="uasg_number", type="integer", nullable=false)
     */
    private $uasgNumber;

    /**
     * @var \App\Entities\Users
     *
     * @ORM\ManyToOne(targetEntity="App\Entities\Users")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="munition_manager", referencedColumnName="id")
     * })
     */
    private $munitionManager;

    /**
     * @var \App\Entities\Users
     *
     * @ORM\ManyToOne(targetEntity="App\Entities\Users")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="fiscal_agent", referencedColumnName="id")
     * })
     */
    private $fiscalAgent;

    /**
     * @var \App\Entities\Users
     *
     * @ORM\ManyToOne(targetEntity="App\Entities\Users")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="munition_fiel", referencedColumnName="id")
     * })
     */
    private $munitionFiel;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="App\Entities\Biddings", mappedBy="militaryOrganizations")
     */
    private $biddings;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="App\Entities\Users", mappedBy="militaryOrganizations")
     */
    private $users;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->biddings = new \Doctrine\Common\Collections\ArrayCollection();
        $this->users = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getNavalIndicative()
    {
        return $this->navalIndicative;
    }

    public function getUasgNumber()
    {
        return $this->uasgNumber;
    }

    public function getMunitionManager()
    {
        return $this->munitionManager;
    }

    public function getFiscalAgent()
    {
        return $this->fiscalAgent;
    }

    public function getMunitionFiel()
    {
        return $this->munitionFiel;
    }

    public function getBiddings()
    {
        return $this->biddings;
    }

    public function getUsers()
    {
        return $this->users;
    }

    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    public function setNavalIndicative($navalIndicative)
    {
        $this->navalIndicative = $navalIndicative;
        return $this;
    }

    public function setUasgNumber($uasgNumber)
    {
        $this->uasgNumber = $uasgNumber;
        return $this;
    }

    public function setMunitionManager($munitionManager)
    {
        $this->munitionManager = $munitionManager;
        return $this;
    }

    public function setFiscalAgent($fiscalAgent)
    {
        $this->fiscalAgent = $fiscalAgent;
        return $this;
    }

    public function setMunitionFiel($munitionFiel)
    {
        $this->munitionFiel = $munitionFiel;
        return $this;
    }

    public function setBiddings($biddings)
    {
        $this->biddings = $biddings;
        return $this;
    }

    public function setUsers($users)
    {
        $this->users = $users;
        return $this;
    }
}
