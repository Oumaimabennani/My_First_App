<?php

namespace App\Repository;

use App\Entity\Author;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Author>
 */
class AuthorRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Author::class);
    }

    public function findByLibraryName(string $libraryName)
    {
        return $this->createQueryBuilder('a')
            ->innerJoin('a.library', 'l')
            ->andWhere('l.name = :libraryName')
            ->setParameter('libraryName', $libraryName)
            ->getQuery()
            ->getResult();
    }
}
