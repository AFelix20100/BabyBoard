<?php

namespace App\Repository;

use App\Entity\Team;
use App\Entity\Player;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Team>
 *
 * @method Team|null find($id, $lockMode = null, $lockVersion = null)
 * @method Team|null findOneBy(array $criteria, array $orderBy = null)
 * @method Team[]    findAll()
 * @method Team[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TeamRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Team::class);
    }

    /**
     * Fonction permettant de retourner toutes les équipes actives de l'utilisateur.
     * @param Player $player
     * @return mixed
     */
    public function getTeamsByHost(Player $player): mixed
    {
        return $this->createQueryBuilder('t')
            ->select('DISTINCT t') // Sélectionne uniquement des objets Team
            ->join('t.teamCompositions', 'tc') // Jointure avec la relation teamCompositions
            ->where('tc.player = :player')
            ->andWhere('tc.isHost = true') // Le joueur est l'hôte
            ->andWhere('t.isDeleted = false') // Le joueur est l'hôte
            ->setParameter('player', $player)
            ->getQuery()
            ->getResult(); // Par défaut, Doctrine hydrate en objets Team
    }



    /**
     * Fonction permettant de récupérer toutes les équipes actives ou non créée par un joueur.
     * @param Player $player
     * @return mixed
     */
    public function getAllTeamsByHost(Player $player): mixed
    {
        return $this->createQueryBuilder('t')
            ->select('DISTINCT t') // Sélectionne uniquement des objets Team
            ->join('t.teamCompositions', 'tc') // Jointure avec la relation teamCompositions
            ->where('tc.player = :player')
            ->andWhere('tc.isHost = true') // Le joueur est l'hôte
            ->setParameter('player', $player)
            ->getQuery()
            ->getResult(); // Par défaut, Doctrine hydrate en objets Team
    }

    /**
     * Fonction permettant de retourner la composition d'une équipe.
     * @param Player $player
     * @param Team $team
     * @return mixed
     */
    public function getTeamByHost(Player $player, Team $team)
    {
        return $this->createQueryBuilder('t')
            ->select('DISTINCT t') // Sélectionne uniquement des objets Team
            ->join('t.teamCompositions', 'tc') // Jointure avec la relation teamCompositions
            ->where('tc.player = :player')
            ->andWhere('tc.isHost = true') // Le joueur est l'hôte
            ->andWhere('t.isDeleted = false') // Le joueur est l'hôte
            ->andWhere('t.id = :team')
            ->setParameter('player', $player)
            ->setParameter('team', $team)
            ->getQuery()
            ->getResult();
    }

    public function getAllTeamReachedByPlayer(Player $player): mixed
    {
        return $this->createQueryBuilder('t')
            ->select('DISTINCT t') // Sélectionne uniquement des objets Team
            ->join('t.teamCompositions', 'tc') // Jointure avec la relation teamCompositions
            ->where('tc.player = :player')
            ->andWhere('tc.isHost = false') // Le joueur est l'hôte
            ->andWhere('tc.isGuest = true') // Le joueur est l'hôte
            ->setParameter('player', $player)
            ->getQuery()
            ->getResult(); // Par défaut, Doctrine hydrate en objets Team
    }

    public function getAllTeamsWithPlayer(Player $player){
        return $this->createQueryBuilder('t_sub')
            ->select('t_sub.id')
            ->distinct()
            ->join('t_sub.teamCompositions', 'tc_sub')
            ->where('tc_sub.isHost = true OR tc_sub.isGuest = true')
            ->andWhere('tc_sub.player = :playerId')
            ->setParameter('playerId', $player->getId()) // Remplacez 1 par l'ID du joueur
            ->getQuery()
            ->getResult();
    }

    //    /**
    //     * @return Team[] Returns an array of Team objects
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

    //    public function findOneBySomeField($value): ?Team
    //    {
    //        return $this->createQueryBuilder('t')
    //            ->andWhere('t.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
