<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Fruit;
use App\Entity\Nutrients;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class FruitController extends AbstractController
{   

    /**
	* @Route("/fruits", methods="GET")
	*/
    public function showAllFruits(EntityManagerInterface $entityManager): JsonResponse
    {   
        
        $repository = $entityManager->getRepository(Fruit::class);
        $fruits = $repository->findAll();
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
        return new JsonResponse($data);
    }

    /**
	* @Route("/fruits/load", methods="POST")
	*/
    public function loadAllFruits(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {   
        $fruitsFromJson = json_decode($request->getContent(),true);

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
            $entityManager->persist($fruit);
        }
        
        $entityManager->flush();
        
        return new JsonResponse('Succesfully loaded all fruits from JSON.');
    }
}
