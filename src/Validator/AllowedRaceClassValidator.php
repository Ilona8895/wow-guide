<?php

namespace App\Validator;

use App\Dto\CreateCharacterRequest;
use App\Repository\CharacterClassRepository;
use App\Repository\RaceRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

final class AllowedRaceClassValidator extends ConstraintValidator
{
    public function __construct(
        private readonly RaceRepository $races,
        private readonly CharacterClassRepository $classes,
    ) {
    }

    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof AllowedRaceClass) {
            throw new UnexpectedTypeException($constraint, AllowedRaceClass::class);
        }

        if (!$value instanceof CreateCharacterRequest) {
            return;
        }

        $race = $this->races->find($value->raceId);
        $class = $this->classes->find($value->classId);

        if ($race === null || $class === null) {
            $this->context->buildViolation($constraint->notFoundMessage)
                ->atPath($race === null ? 'raceId' : 'classId')
                ->addViolation();

            return;
        }

        if (!$race->getAllowedClasses()->contains($class)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ race }}', $race->getName())
                ->setParameter('{{ class }}', $class->getName())
                ->atPath('classId')
                ->addViolation();
        }
    }
}