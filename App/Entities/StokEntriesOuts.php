<?php

namespace App\Entities;

use Doctrine\ORM\Mapping as ORM;
use HTR\Interfaces\Entities\EntityInterface;

/**
 * StokEntriesOuts
 *
 * @ORM\Table(name="stok_entries_outs", uniqueConstraints={@ORM\UniqueConstraint(name="fiscal_document_UNIQUE", columns={"fiscal_document"})}, indexes={@ORM\Index(name="fk_stok_entries_military_organizations1_idx", columns={"military_organizations_id"}), @ORM\Index(name="fk_stok_entries_users1_idx", columns={"authorizer_user"}), @ORM\Index(name="fk_stok_entries_users2_idx", columns={"requester_user"}), @ORM\Index(name="fk_stok_entries_outs_military_organizations1_idx", columns={"military_organizations_origin"}), @ORM\Index(name="fk_stok_entries_outs_military_organizations2_idx", columns={"military_organizations_destiny"}), @ORM\Index(name="fk_stok_entries_outs_users1_idx", columns={"receiver_user"})})
 * @ORM\Entity
 */
class StokEntriesOuts implements EntityInterface
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
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=15, nullable=false)
     */
    private $status;

    /**
     * @var string
     *
     * @ORM\Column(name="stok_type", type="string", length=6, nullable=false)
     */
    private $stokType;

    /**
     * @var string
     *
     * @ORM\Column(name="fiscal_document", type="string", length=15, nullable=false)
     */
    private $fiscalDocument;

    /**
     * @var string
     *
     * @ORM\Column(name="transaction_type", type="string", length=6, nullable=false)
     */
    private $transactionType;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="date", nullable=false)
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="date", nullable=false)
     */
    private $updatedAt;

    /**
     * @var string
     *
     * @ORM\Column(name="observations", type="string", length=256, nullable=true)
     */
    private $observations;

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
     * @var \App\Entities\MilitaryOrganizations
     *
     * @ORM\ManyToOne(targetEntity="App\Entities\MilitaryOrganizations")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="military_organizations_origin", referencedColumnName="id")
     * })
     */
    private $militaryOrganizationsOrigin;

    /**
     * @var \App\Entities\MilitaryOrganizations
     *
     * @ORM\ManyToOne(targetEntity="App\Entities\MilitaryOrganizations")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="military_organizations_destiny", referencedColumnName="id")
     * })
     */
    private $militaryOrganizationsDestiny;

    /**
     * @var \App\Entities\Users
     *
     * @ORM\ManyToOne(targetEntity="App\Entities\Users")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="receiver_user", referencedColumnName="id")
     * })
     */
    private $receiverUser;

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

    public function getNumber()
    {
        return $this->number;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function getStokType()
    {
        return $this->stokType;
    }

    public function getFiscalDocument()
    {
        return $this->fiscalDocument;
    }

    public function getTransactionType()
    {
        return $this->transactionType;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    public function getObservations()
    {
        return $this->observations;
    }

    public function getMilitaryOrganizations()
    {
        return $this->militaryOrganizations;
    }

    public function getMilitaryOrganizationsOrigin()
    {
        return $this->militaryOrganizationsOrigin;
    }

    public function getMilitaryOrganizationsDestiny()
    {
        return $this->militaryOrganizationsDestiny;
    }

    public function getReceiverUser()
    {
        return $this->receiverUser;
    }

    public function getAuthorizerUser()
    {
        return $this->authorizerUser;
    }

    public function getRequesterUser()
    {
        return $this->requesterUser;
    }

    public function setNumber($number)
    {
        $this->number = $number;
        return $this;
    }

    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    public function setStokType($stokType)
    {
        $this->stokType = $stokType;
        return $this;
    }

    public function setFiscalDocument($fiscalDocument)
    {
        $this->fiscalDocument = $fiscalDocument;
        return $this;
    }

    public function setTransactionType($transactionType)
    {
        $this->transactionType = $transactionType;
        return $this;
    }

    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    public function setObservations($observations)
    {
        $this->observations = $observations;
        return $this;
    }

    public function setMilitaryOrganizations($militaryOrganizations)
    {
        $this->militaryOrganizations = $militaryOrganizations;
        return $this;
    }

    public function setMilitaryOrganizationsOrigin($militaryOrganizationsOrigin)
    {
        $this->militaryOrganizationsOrigin = $militaryOrganizationsOrigin;
        return $this;
    }

    public function setMilitaryOrganizationsDestiny($militaryOrganizationsDestiny)
    {
        $this->militaryOrganizationsDestiny = $militaryOrganizationsDestiny;
        return $this;
    }

    public function setReceiverUser($receiverUser)
    {
        $this->receiverUser = $receiverUser;
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
