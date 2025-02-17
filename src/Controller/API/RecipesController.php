<?php

namespace App\Controller\API;

use App\DTO\CreateRecipeDTO;
use App\DTO\PaginationDTO;
use App\Entity\Recipe;
use App\Repository\RecipeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Requirement\Requirement;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

#[Route("/api/recipes", name: "api.recipes.")]
class RecipesController extends AbstractController
{
    #[Route("/", name: "index", methods: ['GET'])]
    public function index(
        RecipeRepository $repository,
        Request $request,
        #[MapQueryString()]
        PaginationDTO $pagination
    ) {
        $recipes = $repository->paginateRecipe($request);
        return $this->json($recipes, 200, [], [
            'groups' => 'recipes.index'
        ]);
    }

    #[Route("/create", name: 'create', methods: ['POST'])]
    public function create(
        // #[MapRequestPayload(serializationContext: [
        //     'groups' => 'recipes.create'
        // ])]
        #[MapRequestPayload()]
        CreateRecipeDTO $recipeDTO,
        EntityManagerInterface $em
    ): Response {
        $recipe = new Recipe();
        $recipe->setTitle($recipeDTO->title)
            ->setSlug($recipeDTO->slug)
            ->setDuration($recipeDTO->duration)
            ->setCreatedAt(new \DateTimeImmutable())
            ->setUpdatedAt(new \DateTimeImmutable());
        $em->persist($recipe);
        $em->flush();

        return $this->json($recipeDTO, 201, [], [
            'groups' => ['recipes.index', 'recipes.show'],
        ]);
    }


    #[Route("/show/{id}", name: "show", requirements: ['id' => Requirement::DIGITS])]
    public function show(Recipe $recipe)
    {
        return $this->json($recipe, 200, [], [
            'groups' => ['recipes.index', 'recipes.show'],

        ]);
    }
}
