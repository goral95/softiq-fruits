<?php

namespace App\Entity;

use App\Repository\FruitInSaladRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FruitInSaladRepository::class)]
class FruitInSalad
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Fruit $fruit = null;

    #[ORM\Column]
    private ?int $Weight = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?nutrients $nutrients = null;

    #[ORM\ManyToOne(inversedBy: 'FruitsInSalad')]
    #[ORM\JoinColumn(nullable: false)]
    private ?FruitSaladRecipe $fruitSaladRecipe = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFruit(): ?Fruit
    {
        return $this->fruit;
    }

    public function setFruit(?Fruit $fruit): self
    {
        $this->fruit = $fruit;

        return $this;
    }

    public function getWeight(): ?int
    {
        return $this->Weight;
    }

    public function setWeight(int $Weight): self
    {
        $this->Weight = $Weight;

        return $this;
    }

    public function getNutrients(): ?nutrients
    {
        return $this->nutrients;
    }

    public function setNutrients(nutrients $nutrients): self
    {
        $this->nutrients = $nutrients;

        return $this;
    }

    public function getFruitSaladRecipe(): ?FruitSaladRecipe
    {
        return $this->fruitSaladRecipe;
    }

    public function setFruitSaladRecipe(?FruitSaladRecipe $fruitSaladRecipe): self
    {
        $this->fruitSaladRecipe = $fruitSaladRecipe;

        return $this;
    }
}
