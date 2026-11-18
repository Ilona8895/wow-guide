<?php

namespace App\Dto;

use App\Validator\AllowedRaceClass;
use Symfony\Component\Validator\Constraints as Assert;

#[AllowedRaceClass]
final readonly class CreateCharacterRequest
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Length(min: 2, max: 12)]
        #[Assert\Regex(pattern: '/^\p{L}+$/u', message: 'Imię może zawierać tylko litery.')]
        public string $name,

        #[Assert\NotBlank]
        #[Assert\Length(min: 2, max: 40)]
        #[Assert\Regex(pattern: '/^\p{L}+$/u', message: 'Nazwisko może zawierać tylko litery.')]
        public string $surname,

        #[Assert\Range(min: 1, max: 60)]
        public int $level,

        #[Assert\Positive]
        public int $raceId,

        #[Assert\Positive]
        public int $classId,

        #[Assert\NotBlank]
        #[Assert\Email]
        public string $playerEmail,
    ) {
    }
}