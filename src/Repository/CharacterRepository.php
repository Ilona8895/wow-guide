<?php

namespace App\Repository;

use App\Entity\Character;
use App\Enum\Faction;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Character>
 */
class CharacterRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Character::class);
    }

    /**
     * @return Character[]
     */
    public function findWithFilters(?Faction $faction, ?string $class): array
    {
        $qb = $this->createQueryBuilder('c')
            ->join('c.race', 'r')->addSelect('r')
            ->join('c.characterClass', 'cc')->addSelect('cc')
            ->orderBy('c.level', 'DESC');

        if ($faction !== null) {
            $qb->andWhere('r.faction = :faction')
                ->setParameter('faction', $faction);
        }

        if ($class !== null) {
            $qb->andWhere('LOWER(cc.name) = LOWER(:class)')
                ->setParameter('class', $class);
        }

        return $qb->getQuery()->getResult();
    }
}