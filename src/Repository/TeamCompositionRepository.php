<?php

namespace App\Repository;

use App\Entity\TeamComposition;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TeamComposition>
 *
 * @method TeamComposition|null find($id, $lockMode = null, $lockVersion = null)
 * @method TeamComposition|null findOneBy(array $criteria, array $orderBy = null)
 * @method TeamComposition[]    findAll()
 * @method TeamComposition[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TeamCompositionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TeamComposition::class);
    }

    //    /**
    //     * @return TeamComposition[] Returns an array of TeamComposition objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('t')
    //            ->andWhere('t.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('t.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?TeamComposition
    //    {
    //        return $this->createQueryBuilder('t')
    //            ->andWhere('t.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
