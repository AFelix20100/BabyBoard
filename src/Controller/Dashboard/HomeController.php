<?php

namespace App\Controller\Dashboard;

use App\Entity\Game;
use App\Entity\Team;
use App\Repository\TeamRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\SecurityBundle\Security;
use App\Entity\Player;

#[Route('/dashboard')]
#[IsGranted('ROLE_USER')]
class HomeController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private Security $security;
    public function __construct(EntityManagerInterface $entityManager, Security $security)
    {
        $this->entityManager = $entityManager;
        $this->security = $security;
    }
    #[Route('/', name: 'app_dashboard_index')]
    public function index(): Response
    {
        /** @var Player $player */
        $player = $this->security->getUser();

        $numberOfTeamsReached = count($this->entityManager->getRepository(Team::class)->getAllTeamReachedByPlayer($player));
//        dd($this->entityManager->getRepository(Team::class)->getAllTeamReachedByPlayer($player));
        $numberOfTeamsCreated = count($this->entityManager->getRepository(Team::class)->getAllTeamsByHost($player));
        $gamePlayed = $this->entityManager->getRepository(Game::class)->getAllGamePlayed($player);

        return $this->render('dashboard/home/index.html.twig', [
            'numberOfTeamsReached' => $numberOfTeamsReached,
            'numberOfTeamsCreated' => $numberOfTeamsCreated,
            'gamePlayed' => $gamePlayed,
            'controller_name' => 'DashboardController',
        ]);
    }

    #[Route('/mes-matchs', name: 'app_dashboard_game')]
    public function matchs(): Response
    {
        return $this->render('dashboard/game.html.twig', [
            'controller_name' => 'DashboardController',
        ]);
    }
}
