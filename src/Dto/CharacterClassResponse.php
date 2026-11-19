<?php

namespace App\Dto;

use App\Entity\CharacterClass;
use App\Enum\ArmorType;

final readonly class CharacterClassResponse
{
    /**
     * @param list<string> $armorTypes
     */
    public function __construct(
        public int $id,
        public string $name,
        public array $armorTypes,
    ) {
    }

    public static function fromEntity(CharacterClass $characterClass): self
    {
        return new self(
            id: $characterClass->getId(),
            name: $characterClass->getName(),
            armorTypes: array_map(
                static fn (ArmorType $armorType) => $armorType->value,
                $characterClass->getArmorType(),
            ),
        );
    }
}
