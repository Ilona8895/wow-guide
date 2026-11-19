<?php

namespace App\Repository;

use App\Entity\Race;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Race>
 */
class RaceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Race::class);
    }

    /**
     * @return Race[]
     */
    public function findAllWithAllowedClasses(): array
    {
        return $this->createQueryBuilder('r')
            ->leftJoin('r.allowedClasses', 'c')->addSelect('c')
            ->orderBy('r.name', 'ASC')
            ->addOrderBy('r.faction', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
