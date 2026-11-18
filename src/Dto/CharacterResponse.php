<?php

namespace App\Dto;

use App\Entity\Character;

final readonly class CharacterResponse
{
    public function __construct(
        public int $id,
        public string $fullName,
        public int $level,
        public string $race,
        public string $faction,
        public string $class,
        public string $createdAt,
    ) {
    }

    public static function fromEntity(Character $character): self
    {
        return new self(
            id: $character->getId(),
            fullName: $character->getFullName(),
            level: $character->getLevel(),
            race: $character->getRace()->getName(),
            faction: $character->getRace()->getFaction()->value,
            class: $character->getCharacterClass()->getName(),
            createdAt: $character->getCreatedAt()->format(DATE_ATOM),
        );
    }
}