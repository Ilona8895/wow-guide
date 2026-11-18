<?php

namespace App\Controller;

use App\Dto\CharacterResponse;
use App\Enum\Faction;
use App\Repository\CharacterRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/characters', name: 'api_characters_')]
final class CharacterController extends AbstractController
{
    #[Route('', name: 'list', methods: ['GET'])]
    public function list(
        CharacterRepository $repository,
        #[MapQueryParameter] ?Faction $faction = null,
        #[MapQueryParameter] ?string $class = null,
    ): JsonResponse {
        $characters = $repository->findWithFilters($faction, $class);

        return $this->json(
            array_map(CharacterResponse::fromEntity(...), $characters)
        );
    }
}