<?php

namespace App\Controller;

use App\Dto\CharacterClassResponse;
use App\Repository\CharacterClassRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/classes', name: 'api_classes_')]
final class CharacterClassController extends AbstractController
{
    #[Route('', name: 'list', methods: ['GET'])]
    public function list(CharacterClassRepository $repository): JsonResponse
    {
        $classes = $repository->findBy([], ['name' => 'ASC']);

        return $this->json(
            array_map(CharacterClassResponse::fromEntity(...), $classes)
        );
    }
}
