<?php

namespace App\Entities;

use Doctrine\ORM\Mapping as ORM;
use HTR\Interfaces\Entities\EntityInterface;

/**
 * Menus
 *
 * @ORM\Table(name="menus", indexes={@ORM\Index(name="fk_menus_military_organizations1_idx", columns={"military_organizations_id"}), @ORM\Index(name="fk_menus_users1_idx", columns={"authorizer_user"}), @ORM\Index(name="fk_menus_users2_idx", columns={"requester_user"})})
 * @ORM\Entity
 */
class Menus implements EntityInterface
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
     * @ORM\Column(name="beginning", type="date", nullable=false)
     */
    private $beginning;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="ending", type="date", nullable=false)
     */
    private $ending;

    /**
     * @var \App\Entities\MilitaryOrganizations
     *
     * @ORM\ManyToOne(targetEntity="App\Entities\MilitaryOrganizations")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="military_organizations_id", referencedColumnName="id")
     * })
     */
    private $militaryOrganizations;

    /**
     * @var \App\Entities\Users
     *
     * @ORM\ManyToOne(targetEntity="App\Entities\Users")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="authorizer_user", referencedColumnName="id")
     * })
     */
    private $authorizerUser;

    /**
     * @var \App\Entities\Users
     *
     * @ORM\ManyToOne(targetEntity="App\Entities\Users")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="requester_user", referencedColumnName="id")
     * })
     */
    private $requesterUser;

    public function getId()
    {
        return $this->id;
    }

    public function getBeginning()
    {
        return $this->beginning;
    }

    public function getEnding()
    {
        return $this->ending;
    }

    public function getMilitaryOrganizations()
    {
        return $this->militaryOrganizations;
    }

    public function getAuthorizerUser()
    {
        return $this->authorizerUser;
    }

    public function getRequesterUser()
    {
        return $this->requesterUser;
    }

    public function setBeginning($beginning)
    {
        $this->beginning = $beginning;
        return $this;
    }

    public function setEnding($ending)
    {
        $this->ending = $ending;
        return $this;
    }

    public function setMilitaryOrganizations($militaryOrganizations)
    {
        $this->militaryOrganizations = $militaryOrganizations;
        return $this;
    }

    public function setAuthorizerUser($authorizerUser)
    {
        $this->authorizerUser = $authorizerUser;
        return $this;
    }

    public function setRequesterUser($requesterUser)
    {
        $this->requesterUser = $requesterUser;
        return $this;
    }
}
