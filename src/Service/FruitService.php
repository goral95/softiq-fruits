<?php 
namespace App\Service;

use App\Entity\Fruit;
use App\Entity\Nutrients;
use Doctrine\ORM\EntityManagerInterface;

class FruitService
{
    private $entityManager;
    private $fruitRepository;

    public function __construct(EntityManagerInterface $entityManager){
        $this->entityManager = $entityManager;
        $this->fruitRepository = $entityManager->getRepository(Fruit::class);
    }

    public function getAllFruits(){
        $fruits = $this->fruitRepository->findAll();
        $data = [];

        foreach($fruits as $fruit){
            $data[] = [
                'id' => $fruit->getId(),
                'name' => $fruit->getName(),
                'nutrients' => array(
                    'carbohydrates' => $fruit->getNutrients()->getCarbohydrates(),
                    'protein' => $fruit->getNutrients()->getProtein(),
                    'fat' => $fruit->getNutrients()->getFat(),
                    'calories' => $fruit->getNutrients()->getCalories(),
                    'sugar' => $fruit->getNutrients()->getSugar(),
                )
            ];
        }

        return $data;
    }

    public function saveAllFruits($fruitsFromJson){
        foreach($fruitsFromJson as $fruitToLoad){
            $fruit = new Fruit();
            $nutrients = new Nutrients();
            $nutrients->setCarbohydrates($fruitToLoad['nutrients']['carbohydrates']);
            $nutrients->setProtein($fruitToLoad['nutrients']['protein']);
            $nutrients->setFat($fruitToLoad['nutrients']['fat']);
            $nutrients->setCalories($fruitToLoad['nutrients']['calories']);
            $nutrients->setSugar($fruitToLoad['nutrients']['sugar']);
            $fruit->setName($fruitToLoad['name']);
            $fruit->setNutrients($nutrients);
            $this->entityManager->persist($fruit);
        }

        $this->entityManager->flush();
    }
}