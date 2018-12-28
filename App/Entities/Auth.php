<?php

namespace App\Entities;

use Doctrine\ORM\Mapping as ORM;
use HTR\Interfaces\Entities\EntityInterface;

/**
 * Auth
 *
 * @ORM\Table(name="auth", uniqueConstraints={@ORM\UniqueConstraint(name="users_id_UNIQUE", columns={"users_id"}), @ORM\UniqueConstraint(name="username_UNIQUE", columns={"username"})}, indexes={@ORM\Index(name="fk_auth_users_idx", columns={"users_id"})})
 * @ORM\Entity
 */
class Auth implements EntityInterface
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
     * @ORM\Column(name="username", type="string", length=32, nullable=false)
     */
    private $username;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=60, nullable=false)
     */
    private $password;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="validate", type="date", nullable=false)
     */
    private $validate;

    /**
     * @var string
     *
     * @ORM\Column(name="is_change", type="string", length=3, nullable=true)
     */
    private $isChange = 'no';

    /**
     * @var \App\Entities\Users
     *
     * @ORM\ManyToOne(targetEntity="App\Entities\Users")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="users_id", referencedColumnName="id")
     * })
     */
    private $users;

    public function getId()
    {
        return $this->id;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function getValidate()
    {
        return $this->validate;
    }

    public function getIsChange()
    {
        return $this->isChange;
    }

    public function getUsers()
    {
        return $this->users;
    }

    public function setUsername($username)
    {
        $this->username = $username;
        return $this;
    }

    public function setPassword($password)
    {
        $this->password = $password;
        return $this;
    }

    public function setValidate($validate)
    {
        $this->validate = $validate;
        return $this;
    }

    public function setIsChange($isChange)
    {
        $this->isChange = $isChange;
        return $this;
    }

    public function setUsers($users)
    {
        $this->users = $users;
        return $this;
    }
}
