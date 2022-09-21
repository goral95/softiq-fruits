<?php

namespace App\Controller;

use App\Entity\Fruit;
use App\Entity\FruitInSalad;
use App\Entity\FruitSaladRecipe;
use App\Entity\Nutrients;
use App\Repository\FruitSaladRecipeRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;
use PhpParser\Node\Expr\Cast\String_;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
	* @Route("/salad")
	*/
class FruitSaladRecipeController extends AbstractController
{   
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager){
        $this->entityManager = $entityManager;
    }

    /**
	* @Route("/create", methods="POST")
	*/
    public function createFruitSaladRecipe(Request $request): JsonResponse
    {
        $saladRecipeJson = json_decode($request->getContent(),true);

        $errors = $this->validateInputForSaladRecipe($saladRecipeJson);

        if(!(empty($errors))){
            return new JsonResponse($errors, Response::HTTP_BAD_REQUEST);
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
        
        return new JsonResponse("Utworzono nowy przepis o id: ".$fruitSaladRecipeToCreate->getId());
    }

    /**
	* @Route("/update/{id}", methods="PUT")
	*/
    public function updateFruitSaladRecipe(Request $request, int $id): JsonResponse
    {
        $saladRecipeJson = json_decode($request->getContent(),true);

        $fruitSaladRecipeToUpdate = $this->entityManager->getRepository(FruitSaladRecipe::class)->find($id);
        
        if(empty($fruitSaladRecipeToUpdate)){
            return new JsonResponse('Nie ma takiej salatki o id: '.$id, Response::HTTP_NOT_FOUND);
        }

        $errors = $this->validateInputForSaladRecipe($saladRecipeJson);

        if(!(empty($errors))){
            return new JsonResponse($errors, Response::HTTP_BAD_REQUEST);
        }

        $fruitSaladRecipeToUpdate->setName($saladRecipeJson['name']);
        $fruitSaladRecipeToUpdate->setDescription($saladRecipeJson['description']);
    
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
        
        $this->entityManager->persist($fruitSaladRecipeToUpdate);
        $this->entityManager->flush();
        
        return new JsonResponse("Zaktualizowano przepis o id: ".$id);
    }

    /**
	* @Route("/remove/{id}", methods="DELETE")
	*/
    public function removeFruitSaladRecipe(Request $request, int $id): JsonResponse
    {
    
        $fruitSaladRecipeToRemove = $this->entityManager->getRepository(FruitSaladRecipe::class)->find($id);
        
        if(empty($fruitSaladRecipeToRemove)){
            return new JsonResponse('Nie ma takiej salatki o id: '.$id, Response::HTTP_NOT_FOUND);
        }

        $this->entityManager->remove($fruitSaladRecipeToRemove);
        $this->entityManager->flush();
        
        return new JsonResponse("Usunieto przepis o id: ".$id);
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
        for($i = 0; !(is_null($fruitsInSalad->get($i))); $i++){
            $carbohydrates += $fruitsInSalad->get($i)->getNutrients()->getCarbohydrates();
            $protein += $fruitsInSalad->get($i)->getNutrients()->getProtein();
            $fat += $fruitsInSalad->get($i)->getNutrients()->getFat();
            $calories += $fruitsInSalad->get($i)->getNutrients()->getCalories();
            $sugar += $fruitsInSalad->get($i)->getNutrients()->getSugar();
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

        for($i = 0; !(is_null($fruitsInSalad->get($i))); $i++){
            $weight += $fruitsInSalad->get($i)->getWeight();
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
