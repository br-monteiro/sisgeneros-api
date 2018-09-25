<?php

namespace App\Entities;

use Doctrine\ORM\Mapping as ORM;
use HTR\Interfaces\Entities\EntityInterface;

/**
 * Suppliers
 *
 * @ORM\Table(name="suppliers", uniqueConstraints={@ORM\UniqueConstraint(name="cnpj_UNIQUE", columns={"cnpj"}), @ORM\UniqueConstraint(name="name_UNIQUE", columns={"name"})})
 * @ORM\Entity
 */
class Suppliers implements EntityInterface
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
     * @ORM\Column(name="cnpj", type="string", length=18, nullable=false)
     */
    private $cnpj;

    /**
     * @var string
     *
     * @ORM\Column(name="contacts", type="text", length=65535, nullable=true)
     */
    private $contacts;

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getCnpj()
    {
        return $this->cnpj;
    }

    public function getContacts()
    {
        return $this->contacts;
    }

    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    public function setCnpj($cnpj)
    {
        $this->cnpj = $cnpj;
        return $this;
    }

    public function setContacts($contacts)
    {
        $this->contacts = $contacts;
        return $this;
    }
}
