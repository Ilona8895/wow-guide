<?php

namespace App\Controller;

use App\Dto\CreateCharacterRequest;
use App\Entity\Character;
use App\Repository\CharacterClassRepository;
use App\Repository\RaceRepository;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
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

    #[Route('', name: 'create', methods: ['POST'])]
    public function create(
        #[MapRequestPayload] CreateCharacterRequest $request,
        RaceRepository $races,
        CharacterClassRepository $classes,
        EntityManagerInterface $em,
    ): JsonResponse {
        $character = (new Character())
            ->setName($request->name)
            ->setSurname($request->surname)
            ->setLevel($request->level)
            ->setRace($races->find($request->raceId))
            ->setCharacterClass($classes->find($request->classId))
            ->setPlayerEmail($request->playerEmail);

        $em->persist($character);

        try {
            $em->flush();
        } catch (UniqueConstraintViolationException) {
            return $this->json(
                ['error' => 'Postać o takiej nazwie już istnieje.'],
                409
            );
        }

        return $this->json(CharacterResponse::fromEntity($character), 201);
    }
}