<?php

namespace App\Repository;

use App\Entity\Game;
use App\Entity\Player;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Game>
 *
 * @method Game|null find($id, $lockMode = null, $lockVersion = null)
 * @method Game|null findOneBy(array $criteria, array $orderBy = null)
 * @method Game[]    findAll()
 * @method Game[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GameRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Game::class);
    }

    public function findLastFourMatches(): array
    {
        return $this->createQueryBuilder('m')
            ->orderBy('m.createdAt', 'DESC')
            ->where('m.PointsBlue IS NOT NULL')
            ->andWhere('m.PointsRed IS NOT NULL')
            ->andWhere('m.winnerTeam IS NOT NULL')
            ->setMaxResults(4)
            ->getQuery()
            ->getResult();
    }

    public function findLastMatch(): ?Game
    {
        return $this->createQueryBuilder('m')
            ->orderBy('m.createdAt', 'DESC')
            ->where('m.PointsBlue IS NOT NULL')
            ->andWhere('m.PointsRed IS NOT NULL')
            ->andWhere('m.winnerTeam IS NOT NULL')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function getUnfinishedMatch(): ?Game
    {
        $query = $this->createQueryBuilder('g')
            ->orderBy('g.createdAt', 'DESC')
            ->where('g.PointsBlue IS NULL')
            ->andWhere('g.PointsRed IS NULL')
            ->andWhere('g.winnerTeam IS NULL')
            ->setMaxResults(1)
            ->getQuery();
        return $query->getOneOrNullResult();
    }

    /**
     * Fonction permettant d'avoir tous les matchs joués.
     * @param Player $player
     * @return array
     */
    public function getAllGamePlayed(Player $player): array
    {
        return $this->createQueryBuilder('g')
            ->join('g.RedTeam', 'rt')
            ->join('g.BlueTeam', 'bt')
            ->join('rt.teamCompositions', 'rtc')
            ->join('bt.teamCompositions', 'btc')
            ->where('rtc.player = :player OR btc.player = :player')
            ->setParameter('player', $player)
            ->getQuery()
            ->getResult();
    }

    public function getAllGameCreated(Player $player): array
    {
        return $this->createQueryBuilder('G')
            ->select('G')
            ->innerJoin('G.RedTeam', 'T')
            ->innerJoin('T.teamCompositions', 'TC')
            ->innerJoin('TC.player', 'P')
            ->andWhere('P.id = :player')
            ->andWhere('G.RedTeam IN (
        SELECT DISTINCT t.id
        FROM App\Entity\Team t
        JOIN t.teamCompositions tc
        WHERE tc.player = :player
        AND tc.isHost = true
        AND t.isDeleted = false
    )')
            ->setParameter('player', $player->getId())
            ->getQuery()
            ->getResult();

    }


//    /**
//     * @return Game[] Returns an array of Game objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('g')
//            ->andWhere('g.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('g.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Game
//    {
//        return $this->createQueryBuilder('g')
//            ->andWhere('g.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
