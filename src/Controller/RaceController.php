<?php

namespace App\Controller;

use App\Dto\RaceResponse;
use App\Repository\RaceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/races', name: 'api_races_')]
final class RaceController extends AbstractController
{
    #[Route('', name: 'list', methods: ['GET'])]
    public function list(RaceRepository $repository): JsonResponse
    {
        $races = $repository->findAllWithAllowedClasses();

        return $this->json(
            array_map(RaceResponse::fromEntity(...), $races)
        );
    }
}
