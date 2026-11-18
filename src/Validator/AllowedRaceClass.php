<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

#[\Attribute(\Attribute::TARGET_CLASS)]
final class AllowedRaceClass extends Constraint
{
    public string $message = 'Rasa {{ race }} nie może być klasą {{ class }}.';
    public string $notFoundMessage = 'Nie znaleziono takiej rasy albo klasy.';

    public function getTargets(): string
    {
        return self::CLASS_CONSTRAINT;
    }
}