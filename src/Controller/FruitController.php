<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\FruitService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class FruitController extends AbstractController
{   
    private $fruitService;

    public function __construct(FruitService $fruitService){
        $this->fruitService = $fruitService;
    }
    /**
	* @Route("/fruits", methods="GET")
	*/
    public function showAllFruits(): JsonResponse
    {   
        $fruits = $this->fruitService->getAllFruits();
        return new JsonResponse($fruits);
    }

    /**
	* @Route("/fruits/load", methods="POST")
	*/
    public function loadAllFruits(Request $request): JsonResponse
    {   
        $fruitsFromJson = json_decode($request->getContent(),true);
        $this->fruitService->saveAllFruits($fruitsFromJson);
        return new JsonResponse('Succesfully loaded all fruits from JSON.');
    }
}
