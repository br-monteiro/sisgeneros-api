<?php

namespace App\Entities;

use Doctrine\ORM\Mapping as ORM;
use HTR\Interfaces\Entities\EntityInterface;

/**
 * Requests
 *
 * @ORM\Table(name="requests", indexes={@ORM\Index(name="fk_requests_suppliers1_idx", columns={"suppliers_id"}), @ORM\Index(name="fk_requests_military_organizations1_idx", columns={"military_organizations_id"}), @ORM\Index(name="fk_requests_users1_idx", columns={"authorizer_user"}), @ORM\Index(name="fk_requests_users2_idx", columns={"requester_user"}), @ORM\Index(name="fk_requests_users3_idx", columns={"receiver_user"})})
 * @ORM\Entity
 */
class Requests implements EntityInterface
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
     * @ORM\Column(name="munition_manager", type="string", length=100, nullable=false)
     */
    private $munitionManager;

    /**
     * @var string
     *
     * @ORM\Column(name="munition_manager_post", type="string", length=100, nullable=false)
     */
    private $munitionManagerPost;

    /**
     * @var string
     *
     * @ORM\Column(name="munition_fiel", type="string", length=100, nullable=false)
     */
    private $munitionFiel;

    /**
     * @var string
     *
     * @ORM\Column(name="munition_fiel_post", type="string", length=100, nullable=false)
     */
    private $munitionFielPost;

    /**
     * @var string
     *
     * @ORM\Column(name="fiscal_agent", type="string", length=100, nullable=false)
     */
    private $fiscalAgent;

    /**
     * @var string
     *
     * @ORM\Column(name="fiscal_agent_post", type="string", length=100, nullable=false)
     */
    private $fiscalAgentPost;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=15, nullable=false)
     */
    private $status;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dalivery_date", type="date", nullable=false)
     */
    private $daliveryDate;

    /**
     * @var string
     *
     * @ORM\Column(name="fiscal_document", type="string", length=15, nullable=false)
     */
    private $fiscalDocument;

    /**
     * @var string
     *
     * @ORM\Column(name="is_biddging", type="string", length=3, nullable=false)
     */
    private $isBiddging;

    /**
     * @var integer
     *
     * @ORM\Column(name="supplier_evaluation", type="integer", nullable=false)
     */
    private $supplierEvaluation;

    /**
     * @var integer
     *
     * @ORM\Column(name="delivery_evaluation", type="integer", nullable=false)
     */
    private $deliveryEvaluation;

    /**
     * @var string
     *
     * @ORM\Column(name="observations", type="text", length=65535, nullable=true)
     */
    private $observations;

    /**
     * @var integer
     *
     * @ORM\Column(name="bidding_number", type="integer", nullable=true)
     */
    private $biddingNumber;

    /**
     * @var integer
     *
     * @ORM\Column(name="bidding_year", type="integer", nullable=true)
     */
    private $biddingYear;

    /**
     * @var integer
     *
     * @ORM\Column(name="bidding_uasg_number", type="integer", nullable=true)
     */
    private $biddingUasgNumber;

    /**
     * @var string
     *
     * @ORM\Column(name="bidding_uasg_name", type="string", length=100, nullable=true)
     */
    private $biddingUasgName;

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
     * @var \App\Entities\Suppliers
     *
     * @ORM\ManyToOne(targetEntity="App\Entities\Suppliers")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="suppliers_id", referencedColumnName="id")
     * })
     */
    private $suppliers;

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

    /**
     * @var \App\Entities\Users
     *
     * @ORM\ManyToOne(targetEntity="App\Entities\Users")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="receiver_user", referencedColumnName="id")
     * })
     */
    private $receiverUser;

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

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    public function getMunitionManager()
    {
        return $this->munitionManager;
    }

    public function getMunitionManagerPost()
    {
        return $this->munitionManagerPost;
    }

    public function getMunitionFiel()
    {
        return $this->munitionFiel;
    }

    public function getMunitionFielPost()
    {
        return $this->munitionFielPost;
    }

    public function getFiscalAgent()
    {
        return $this->fiscalAgent;
    }

    public function getFiscalAgentPost()
    {
        return $this->fiscalAgentPost;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function getDaliveryDate()
    {
        return $this->daliveryDate;
    }

    public function getFiscalDocument()
    {
        return $this->fiscalDocument;
    }

    public function getIsBiddging()
    {
        return $this->isBiddging;
    }

    public function getSupplierEvaluation()
    {
        return $this->supplierEvaluation;
    }

    public function getDeliveryEvaluation()
    {
        return $this->deliveryEvaluation;
    }

    public function getObservations()
    {
        return $this->observations;
    }

    public function getBiddingNumber()
    {
        return $this->biddingNumber;
    }

    public function getBiddingYear()
    {
        return $this->biddingYear;
    }

    public function getBiddingUasgNumber()
    {
        return $this->biddingUasgNumber;
    }

    public function getBiddingUasgName()
    {
        return $this->biddingUasgName;
    }

    public function getMilitaryOrganizations()
    {
        return $this->militaryOrganizations;
    }

    public function getSuppliers()
    {
        return $this->suppliers;
    }

    public function getAuthorizerUser()
    {
        return $this->authorizerUser;
    }

    public function getRequesterUser()
    {
        return $this->requesterUser;
    }

    public function getReceiverUser()
    {
        return $this->receiverUser;
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

    public function setMunitionManager($munitionManager)
    {
        $this->munitionManager = $munitionManager;
        return $this;
    }

    public function setMunitionManagerPost($munitionManagerPost)
    {
        $this->munitionManagerPost = $munitionManagerPost;
        return $this;
    }

    public function setMunitionFiel($munitionFiel)
    {
        $this->munitionFiel = $munitionFiel;
        return $this;
    }

    public function setMunitionFielPost($munitionFielPost)
    {
        $this->munitionFielPost = $munitionFielPost;
        return $this;
    }

    public function setFiscalAgent($fiscalAgent)
    {
        $this->fiscalAgent = $fiscalAgent;
        return $this;
    }

    public function setFiscalAgentPost($fiscalAgentPost)
    {
        $this->fiscalAgentPost = $fiscalAgentPost;
        return $this;
    }

    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    public function setDaliveryDate($daliveryDate)
    {
        $this->daliveryDate = $daliveryDate;
        return $this;
    }

    public function setFiscalDocument($fiscalDocument)
    {
        $this->fiscalDocument = $fiscalDocument;
        return $this;
    }

    public function setIsBiddging($isBiddging)
    {
        $this->isBiddging = $isBiddging;
        return $this;
    }

    public function setSupplierEvaluation($supplierEvaluation)
    {
        $this->supplierEvaluation = $supplierEvaluation;
        return $this;
    }

    public function setDeliveryEvaluation($deliveryEvaluation)
    {
        $this->deliveryEvaluation = $deliveryEvaluation;
        return $this;
    }

    public function setObservations($observations)
    {
        $this->observations = $observations;
        return $this;
    }

    public function setBiddingNumber($biddingNumber)
    {
        $this->biddingNumber = $biddingNumber;
        return $this;
    }

    public function setBiddingYear($biddingYear)
    {
        $this->biddingYear = $biddingYear;
        return $this;
    }

    public function setBiddingUasgNumber($biddingUasgNumber)
    {
        $this->biddingUasgNumber = $biddingUasgNumber;
        return $this;
    }

    public function setBiddingUasgName($biddingUasgName)
    {
        $this->biddingUasgName = $biddingUasgName;
        return $this;
    }

    public function setMilitaryOrganizations($militaryOrganizations)
    {
        $this->militaryOrganizations = $militaryOrganizations;
        return $this;
    }

    public function setSuppliers($suppliers)
    {
        $this->suppliers = $suppliers;
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

    public function setReceiverUser($receiverUser)
    {
        $this->receiverUser = $receiverUser;
        return $this;
    }
}
