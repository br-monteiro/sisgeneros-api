<?php

namespace App\Entities;

use Doctrine\ORM\Mapping as ORM;
use HTR\Interfaces\Entities\EntityInterface;

/**
 * Users
 *
 * @ORM\Table(name="users", uniqueConstraints={@ORM\UniqueConstraint(name="nip_UNIQUE", columns={"nip"})})
 * @ORM\Entity
 */
class Users implements EntityInterface
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
     * @ORM\Column(name="name", type="string", length=45, nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="full_name", type="string", length=100, nullable=false)
     */
    private $fullName;

    /**
     * @var string
     *
     * @ORM\Column(name="military_post", type="string", length=100, nullable=false)
     */
    private $militaryPost;

    /**
     * @var string
     *
     * @ORM\Column(name="nip", type="string", length=10, nullable=false)
     */
    private $nip;

    /**
     * @var string
     *
     * @ORM\Column(name="is_master", type="string", length=3, nullable=false)
     */
    private $isMaster;

    /**
     * @var string
     *
     * @ORM\Column(name="active", type="string", length=3, nullable=false)
     */
    private $active;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="App\Entities\MilitaryOrganizations", inversedBy="users")
     * @ORM\JoinTable(name="users_has_military_organizations",
     *   joinColumns={
     *     @ORM\JoinColumn(name="users_id", referencedColumnName="id")
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

    public function getName()
    {
        return $this->name;
    }

    public function getFullName()
    {
        return $this->fullName;
    }

    public function getMilitaryPost()
    {
        return $this->militaryPost;
    }

    public function getNip()
    {
        return $this->nip;
    }

    public function getIsMaster()
    {
        return $this->isMaster;
    }

    public function getActive()
    {
        return $this->active;
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

    public function setFullName($fullName)
    {
        $this->fullName = $fullName;
        return $this;
    }

    public function setMilitaryPost($militaryPost)
    {
        $this->militaryPost = $militaryPost;
        return $this;
    }

    public function setNip($nip)
    {
        $this->nip = $nip;
        return $this;
    }

    public function setIsMaster($isMaster)
    {
        $this->isMaster = $isMaster;
        return $this;
    }

    public function setActive($active)
    {
        $this->active = $active;
        return $this;
    }

    public function setMilitaryOrganizations($militaryOrganizations)
    {
        $this->militaryOrganizations = $militaryOrganizations;
        return $this;
    }
}
