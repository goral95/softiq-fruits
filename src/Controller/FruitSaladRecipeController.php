<?php

namespace App\Controller;

use App\Service\FruitSaladRecipeService;
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
    private $fruitSaladRecipeService;

    public function __construct(FruitSaladRecipeService $fruitSaladRecipeService){
        $this-> fruitSaladRecipeService = $fruitSaladRecipeService;
    }

    /**
	* @Route("/create", methods="POST")
	*/
    public function createFruitSaladRecipe(Request $request): JsonResponse
    {
        $saladRecipeJson = json_decode($request->getContent(),true);

        $data = $this->fruitSaladRecipeService->addSaladRecipe($saladRecipeJson);

        if(is_array($data)){
            return new JsonResponse($data, Response::HTTP_BAD_REQUEST);
        }

        return new JsonResponse("Utworzono nowy przepis o id: ".$data);
    }

    /**
	* @Route("/update/{id}", methods="PUT")
	*/
    public function updateFruitSaladRecipe(Request $request, int $id): JsonResponse
    {
        $saladRecipeJson = json_decode($request->getContent(),true);

        $data = $this->fruitSaladRecipeService->updateSaladRecipe($saladRecipeJson, $id);
        
        if(is_array($data)){
            if(array_key_exists('notFoundError', $data)){
                return new JsonResponse($data, Response::HTTP_NOT_FOUND);
            }else{
                return new JsonResponse($data, Response::HTTP_BAD_REQUEST); 
            }
        }

        return new JsonResponse("Zaktualizowano przepis o id: ".$data);
    }

    /**
	* @Route("/remove/{id}", methods="DELETE")
	*/
    public function removeFruitSaladRecipe(Request $request, int $id): JsonResponse
    {
        $data = $this->fruitSaladRecipeService->removeSaladRecipe($id);
        
        if(is_array($data)){
            return new JsonResponse($data, Response::HTTP_NOT_FOUND);
        }

        return new JsonResponse("Usunieto przepis o id: ".$data);
    }

    /**
	* @Route("/show", methods="GET")
	*/
    public function showAllSaladRecipes(): JsonResponse
    {   
        $data = $this->fruitSaladRecipeService->getAllSaladRecipes();
        return new JsonResponse($data);
    }

    /**
	* @Route("/show/{id}", methods="GET")
	*/
    public function showSaladRecipe(int $id): JsonResponse
    {   
        
        $data = $this->fruitSaladRecipeService->getSaladRecipe($id);
        
        if(array_key_exists('notFoundError', $data)){
            return new JsonResponse($data, Response::HTTP_NOT_FOUND);
        }

        return new JsonResponse($data);
        
    }
}
