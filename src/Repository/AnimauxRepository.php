<?php

namespace App\Repository;

use App\Entity\Animaux;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Animaux>
 */
class AnimauxRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Animaux::class);
    }

    public function findAllAnimaux(): array
    {
        return $this->createQueryBuilder('a')
            ->orderBy('a.nom', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
