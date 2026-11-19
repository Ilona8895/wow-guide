<?php

namespace App\Dto;

use App\Entity\Race;

final readonly class RaceResponse
{
    /**
     * @param list<string> $allowedClasses
     */
    public function __construct(
        public int $id,
        public string $name,
        public string $faction,
        public array $allowedClasses,
    ) {
    }

    public static function fromEntity(Race $race): self
    {
        return new self(
            id: $race->getId(),
            name: $race->getName(),
            faction: $race->getFaction()->value,
            allowedClasses: $race->getAllowedClasses()
                ->map(static fn ($class) => $class->getName())
                ->getValues(),
        );
    }
}
