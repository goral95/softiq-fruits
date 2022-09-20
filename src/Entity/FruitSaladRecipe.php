<?php

namespace App\Entity;

use App\Repository\FruitSaladRecipeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FruitSaladRecipeRepository::class)]
class FruitSaladRecipe
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToMany(mappedBy: 'fruitSaladRecipe', targetEntity: FruitInSalad::class, orphanRemoval: true)]
    private Collection $FruitsInSalad;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?nutrients $nutrients = null;

    #[ORM\Column]
    private ?int $weight = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    public function __construct()
    {
        $this->FruitsInSalad = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, FruitInSalad>
     */
    public function getFruitsInSalad(): Collection
    {
        return $this->FruitsInSalad;
    }

    public function addFruitsInSalad(FruitInSalad $fruitsInSalad): self
    {
        if (!$this->FruitsInSalad->contains($fruitsInSalad)) {
            $this->FruitsInSalad->add($fruitsInSalad);
            $fruitsInSalad->setFruitSaladRecipe($this);
        }

        return $this;
    }

    public function removeFruitsInSalad(FruitInSalad $fruitsInSalad): self
    {
        if ($this->FruitsInSalad->removeElement($fruitsInSalad)) {
            // set the owning side to null (unless already changed)
            if ($fruitsInSalad->getFruitSaladRecipe() === $this) {
                $fruitsInSalad->setFruitSaladRecipe(null);
            }
        }

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

    public function getWeight(): ?int
    {
        return $this->weight;
    }

    public function setWeight(int $weight): self
    {
        $this->weight = $weight;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }
}
