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

    public function getVictoriesAndDefeats(Player $player): array
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = '
        SELECT
            SUM(CASE WHEN G.winner_team_id = T.id THEN 1 ELSE 0 END) AS nombre_victoires,
            SUM(CASE WHEN G.winner_team_id != T.id THEN 1 ELSE 0 END) AS nombre_defaites
        FROM
            team T
                LEFT JOIN
            game G ON T.id = G.red_team_id OR T.id = G.blue_team_id
        WHERE
            T.id IN (
                SELECT DISTINCT T.id
                FROM team T
                         INNER JOIN team_composition TC ON TC.team_id = T.id
                WHERE (TC.is_host = true OR TC.is_guest = true)
                  AND TC.player_id = :playerId
            );
        ';

        $resultSet = $conn->executeQuery($sql, ['playerId' => $player->getId()]);

        // Fetch the first (and only) row from the result set
        $data = $resultSet->fetchAssociative();

        // If there's no data, return an empty array
        if (!$data) {
            return [];
        }

        // Extract the values from the associative array
        $victories = $data['nombre_victoires'];
        $defeats = $data['nombre_defaites'];

        // Return the extracted values as an associative array
        return [
            'nombre_victoires' => $victories,
            'nombre_defaites' => $defeats,
        ];
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
