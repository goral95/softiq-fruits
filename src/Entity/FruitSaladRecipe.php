<?php

namespace App\Entity;

use App\Repository\FruitSaladRecipeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: FruitSaladRecipeRepository::class)]
#[UniqueEntity( fields: 'name', message: 'Taka nazwa salatki juz istnieje')]
class FruitSaladRecipe
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToMany(mappedBy: 'fruitSaladRecipe', targetEntity: FruitInSalad::class, orphanRemoval: true)]
    #[Assert\Count(min: 2, minMessage: 'Przepis powinien miec conajmniej 2 owoce')]
    private Collection $FruitsInSalad;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Nutrients $nutrients = null;

    #[ORM\Column]
    private ?int $weight = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Nazwa przepisu nie moze byc pusta')]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank(message: 'Opis przepisu nie moze byc pusty')]
    #[Assert\Length(min: 20, minMessage : 'Opis musi miec conajmniej 20 znakow')]
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

    public function getNutrients(): ?Nutrients
    {
        return $this->nutrients;
    }

    public function setNutrients(Nutrients $nutrients): self
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
