<?php

namespace App\Entity;

use App\Repository\FruitInSaladRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: FruitInSaladRepository::class)]
class FruitInSalad
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotBlank(message: 'Nazwa owocu nie moze byc pusta lub zostala podana zla nazwa owocu')]
    private ?Fruit $fruit = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: 'Waga owocu nie moze byc pusta')]
    #[Assert\Positive(message: 'Waga owocu musi byc dodatnia')]
    #[Assert\DivisibleBy(value: 100, message: 'Waga owocu musi byc podzielna przez 100')]
    private ?int $Weight = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Nutrients $nutrients = null;

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

    public function getNutrients(): ?Nutrients
    {
        return $this->nutrients;
    }

    public function setNutrients(Nutrients $nutrients): self
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
