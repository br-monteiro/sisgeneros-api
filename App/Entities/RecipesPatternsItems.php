<?php

namespace App\Entities;

use Doctrine\ORM\Mapping as ORM;
use HTR\Interfaces\Entities\EntityInterface;

/**
 * RecipesPatternsItems
 *
 * @ORM\Table(name="recipes_patterns_items", indexes={@ORM\Index(name="fk_recipes_items_recipes1_idx", columns={"recipes_patterns_id"})})
 * @ORM\Entity
 */
class RecipesPatternsItems implements EntityInterface
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
     * @var \App\Entities\RecipesPatterns
     *
     * @ORM\ManyToOne(targetEntity="App\Entities\RecipesPatterns")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="recipes_patterns_id", referencedColumnName="id")
     * })
     */
    private $recipesPatterns;

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getRecipesPatterns()
    {
        return $this->recipesPatterns;
    }

    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    public function setRecipesPatterns($recipesPatterns)
    {
        $this->recipesPatterns = $recipesPatterns;
        return $this;
    }
}
