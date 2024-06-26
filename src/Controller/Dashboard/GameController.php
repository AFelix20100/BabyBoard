<?php

namespace App\Controller\Dashboard;

use App\Entity\Game;
use App\Entity\Player;
use App\Entity\Team;
use App\Form\GameType;
use App\Form\GameTypeAddType;
use App\Repository\GameRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


#[Route('/dashboard/mes-matchs')]
#[IsGranted('ROLE_USER')]
class GameController extends AbstractController
{
    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
    }

    #[Route('/', name: 'app_dashboard_game_index', methods: ['GET'])]
    public function index(GameRepository $gameRepository): Response
    {
        return $this->render('dashboard/game/index.html.twig', [
            'games' => $gameRepository->getAllGameCreated($this->getUser()),
        ]);
    }

    #[Route('/ajouter', name: 'app_dashboard_game_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $game = new Game();
        $player = $this->getUser();
        /** @var Player $player */
        $teams = $this->entityManager->getRepository(Team::class)->getTeamsByHost($player);
        if (empty($teams)) {
            $this->addFlash(
                'error',
                'Vous devez avoir une équipe pour créer un match.'
            );
            return $this->redirectToRoute('app_dashboard_game_index');
        }
        $form = $this->createForm(GameTypeAddType::class, $game);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($game);
            $entityManager->flush();

            return $this->redirectToRoute('app_dashboard_game_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('dashboard/game/new.html.twig', [
            'game' => $game,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_dashboard_game_show', methods: ['GET'])]
    public function show(Game $game): Response
    {
        return $this->render('dashboard/game/show.html.twig', [
            'game' => $game,
        ]);
    }

    #[Route('/{id}/modifier', name: 'app_dashboard_game_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Game $game, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(GameType::class, $game);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_dashboard_game_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('dashboard/game/edit.html.twig', [
            'game' => $game,
            'form' => $form,
        ]);
    }
}
