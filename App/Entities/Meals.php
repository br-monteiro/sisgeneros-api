<?php

namespace App\Entities;

use Doctrine\ORM\Mapping as ORM;
use HTR\Interfaces\Entities\EntityInterface;

/**
 * Meals
 *
 * @ORM\Table(name="meals", uniqueConstraints={@ORM\UniqueConstraint(name="name_UNIQUE", columns={"name"})})
 * @ORM\Entity
 */
class Meals implements EntityInterface
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
     * @var integer
     *
     * @ORM\Column(name="sort", type="integer", nullable=false)
     */
    private $sort;

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getSort()
    {
        return $this->sort;
    }

    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    public function setSort($sort)
    {
        $this->sort = $sort;
        return $this;
    }
}
