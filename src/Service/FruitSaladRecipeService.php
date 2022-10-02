<?php 
namespace App\Service;

use App\Entity\Fruit;
use App\Entity\FruitInSalad;
use App\Entity\FruitSaladRecipe;
use App\Entity\Nutrients;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;

class FruitSaladRecipeService
{
    private $entityManager;
    private $fruitSaladRecipeRepository;

    public function __construct(EntityManagerInterface $entityManager){
        $this->entityManager = $entityManager;
        $this->fruitSaladRecipeRepository = $entityManager->getRepository(FruitSaladRecipe::class);
    }

    public function getAllSaladRecipes(){
        $saladRecipes = $this->fruitSaladRecipeRepository->findAll();
        $data = [];
        $dataFruits = [];
        
        foreach($saladRecipes as $saladRecipe){
            $fruitsInSalad = $saladRecipe->getFruitsInSalad()->toArray();
            foreach($fruitsInSalad as $fruitInSalad){
                array_push($dataFruits, $fruitInSalad->getFruit()->getName());
            }
            $data[] = [
                'id' => $saladRecipe->getId(),
                'name' => $saladRecipe->getName(),
                'totalWeight' => $saladRecipe->getWeight(),
                'totalCalories' => $saladRecipe->getNutrients()->getCalories(),
                'fruits' => $dataFruits
            ];
            unset($dataFruits);
            $dataFruits = array(); 
        }

        return $data;
    }

    public function getSaladRecipe($id){
        $fruitSaladRecipeToShow = $this->fruitSaladRecipeRepository->find($id);
        
        if(empty($fruitSaladRecipeToShow)){
            return array('notFoundError' => 'Nie ma takiej salatki o id: '.$id);
        }
    
        $data = [];
        $dataFruits = [];
        $i = 1;
        
            $fruitsInSalad = $fruitSaladRecipeToShow->getFruitsInSalad()->toArray();
            foreach($fruitsInSalad as $fruitInSalad){
                $dataFruits[] = [
                    $i => array(
                        'name' => $fruitInSalad->getFruit()->getName(),
                        'weight' => $fruitInSalad->getWeight(),
                        'nutrients' => array(
                            'carbohydrates' => $fruitInSalad->getFruit()->getNutrients()->getCarbohydrates(),
                            'protein' => $fruitInSalad->getFruit()->getNutrients()->getProtein(),
                            'fat' => $fruitInSalad->getFruit()->getNutrients()->getFat(),
                            'calories' => $fruitInSalad->getFruit()->getNutrients()->getCalories(),
                            'sugar' => $fruitInSalad->getFruit()->getNutrients()->getSugar(),
                        ),
                    )
                ];
                $i++;
            }
            
            $data[] = [
                'id' => $fruitSaladRecipeToShow->getId(),
                'name' => $fruitSaladRecipeToShow->getName(),
                'description' => $fruitSaladRecipeToShow->getDescription(),
                'totalWeight' => $fruitSaladRecipeToShow->getWeight(),
                'nutrients' =>  array(
                    'carbohydrates' => $fruitSaladRecipeToShow->getNutrients()->getCarbohydrates(),
                    'protein' => $fruitSaladRecipeToShow->getNutrients()->getProtein(),
                    'fat' => $fruitSaladRecipeToShow->getNutrients()->getFat(),
                    'calories' => $fruitSaladRecipeToShow->getNutrients()->getCalories(),
                    'sugar' => $fruitSaladRecipeToShow->getNutrients()->getSugar(),
                ),
                'fruits' => array($dataFruits)
            ];
        
        return $data;
    }

    public function addSaladRecipe($saladRecipeJson){
        $errors = $this->validateInputForSaladRecipe($saladRecipeJson);

        if(!(empty($errors))){
            return $errors;
        }

        $fruitSaladRecipeToCreate = new FruitSaladRecipe;
        $fruitSaladRecipeToCreate->setName($saladRecipeJson['name']);
        $fruitSaladRecipeToCreate->setDescription($saladRecipeJson['description']);

        foreach($saladRecipeJson['fruitsInSalad'] as $fruitToAdd){
            $fruitInSalad = new FruitInSalad();
            $fruitInSalad->setWeight($fruitToAdd['weight']);
            $fruitInSalad->setFruit($this->entityManager->getRepository(Fruit::class)->findOneBy(['name' => $fruitToAdd['name']]));
            $fruitInSalad->setNutrients($this->calculateFruitNutrients($fruitInSalad));
            $fruitSaladRecipeToCreate->addFruitsInSalad($fruitInSalad);
            $this->entityManager->persist($fruitInSalad);
        }
        
        $fruitSaladRecipeToCreate->setNutrients($this->calculateSaladNutrients($fruitSaladRecipeToCreate->getFruitsInSalad()));
        $fruitSaladRecipeToCreate->setWeight($this->calculateSaladWeight($fruitSaladRecipeToCreate->getFruitsInSalad()));
        
        $this->entityManager->persist($fruitSaladRecipeToCreate);
        $this->entityManager->flush();

        return $fruitSaladRecipeToCreate->getId();
    }

    public function updateSaladRecipe($saladRecipeJson, $id){

        $fruitSaladRecipeToUpdate = $this->fruitSaladRecipeRepository->find($id);
        
        if(empty($fruitSaladRecipeToUpdate)){
            return array('notFoundError'=> 'Nie ma takiej salatki o id: '.$id);
        }

        $errors = $this->validateInputForSaladRecipe($saladRecipeJson);

        if(!(empty($errors))){
            return $errors;
        }

        $fruitSaladRecipeToUpdate->setName($saladRecipeJson['name']);
        $fruitSaladRecipeToUpdate->setDescription($saladRecipeJson['description']);

        $fruitsInSalad = $fruitSaladRecipeToUpdate->getFruitsInSalad()->toArray();
        foreach($fruitsInSalad as $fruitInSalad){
            $fruitSaladRecipeToUpdate->removeFruitsInSalad($fruitInSalad);
        }
    
        foreach($saladRecipeJson['fruitsInSalad'] as $fruitToAdd){
            $fruitInSalad = new FruitInSalad();
            $fruitInSalad->setWeight($fruitToAdd['weight']);
            $fruitInSalad->setFruit($this->entityManager->getRepository(Fruit::class)->findOneBy(['name' => $fruitToAdd['name']]));
            $fruitInSalad->setNutrients($this->calculateFruitNutrients($fruitInSalad));
            $fruitSaladRecipeToUpdate->addFruitsInSalad($fruitInSalad);
            $this->entityManager->persist($fruitInSalad);
        }
        
        $fruitSaladRecipeToUpdate->setNutrients($this->calculateSaladNutrients($fruitSaladRecipeToUpdate->getFruitsInSalad()));
        $fruitSaladRecipeToUpdate->setWeight($this->calculateSaladWeight($fruitSaladRecipeToUpdate->getFruitsInSalad()));

        $this->entityManager->flush();

        return $id;
    }

    public function removeSaladRecipe($id){
        $fruitSaladRecipeToRemove = $this->fruitSaladRecipeRepository->find($id);
        
        if(empty($fruitSaladRecipeToRemove)){
            return array('notFoundError'=> 'Nie ma takiej salatki o id: '.$id);
        }

        $this->entityManager->remove($fruitSaladRecipeToRemove);
        $this->entityManager->flush();

        return $id;
    }

    private function calculateFruitNutrients(FruitInSalad $fruitInSalad): Nutrients
    {
        $nutrients = new Nutrients();
        
        $multipler = $fruitInSalad->getWeight() / 100;

        $nutrients->setCarbohydrates($fruitInSalad->getFruit()->getNutrients()->getCarbohydrates() * $multipler);
        $nutrients->setProtein($fruitInSalad->getFruit()->getNutrients()->getProtein() * $multipler);
        $nutrients->setFat($fruitInSalad->getFruit()->getNutrients()->getFat() * $multipler);
        $nutrients->setCalories($fruitInSalad->getFruit()->getNutrients()->getCalories() * $multipler);
        $nutrients->setSugar($fruitInSalad->getFruit()->getNutrients()->getSugar() * $multipler);

        return $nutrients;
    }

    private function calculateSaladNutrients(Collection $fruitsInSalad): Nutrients
    {   
        
        $nutrients = new Nutrients();
        $carbohydrates = 0;
        $protein = 0;
        $fat = 0;
        $calories = 0;
        $sugar = 0;
        
        foreach($fruitsInSalad->toArray() as $fruitInSalad){
            $carbohydrates += $fruitInSalad->getNutrients()->getCarbohydrates();
            $protein += $fruitInSalad->getNutrients()->getProtein();
            $fat += $fruitInSalad->getNutrients()->getFat();
            $calories += $fruitInSalad->getNutrients()->getCalories();
            $sugar += $fruitInSalad->getNutrients()->getSugar();
        }

        $nutrients->setCarbohydrates($carbohydrates);
        $nutrients->setProtein($protein);
        $nutrients->setFat($fat);
        $nutrients->setCalories($calories);
        $nutrients->setSugar($sugar);
        
        return $nutrients;
    }

    private function calculateSaladWeight(Collection $fruitsInSalad)
    {   
        $weight = 0;

        foreach($fruitsInSalad->toArray() as $fruitInSalad){
            $weight += $fruitInSalad->getWeight();
        }

        return $weight;
    }

    private function validateInputForSaladRecipe($inputData) {
        $errors = [];

        if(!(empty($this->entityManager->getRepository(FruitSaladRecipe::class)->findBy(['name' => $inputData['name']])))){
            $errors['existSaladNameError'] = 'Salatka o takiej nazwie juz istnieje, podaj inna';
        }
        if(count($inputData['fruitsInSalad']) < 2){
            $errors['notEnoughFruitsError'] = 'Salatka musi miec co najmniej 2 owoce';
        } 
        if($this->checkEveryFruitWeightProvided($inputData['fruitsInSalad'])){
            $errors['noFruitWeightProvidedError'] =  'Kazdy owoc musi miec podana wage'; 
        }
        if(strlen($inputData['description']) < 20){
            $errors['incorrectDescriptionError'] = 'Opis musi miec conajmniej 20 znakow';
        }

        return $errors;
    }

    private function checkEveryFruitWeightProvided($fruitsInSaladProvided){
        foreach($fruitsInSaladProvided as $fruitInSaladProvided){
            if(empty($fruitInSaladProvided['weight'])){
                return true;
            }
        }
        return false;
    }
}