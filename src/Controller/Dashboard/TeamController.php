<?php

namespace App\Controller\Dashboard;

use App\Entity\Team;
use App\Entity\Player;
use App\Form\TeamType;
use App\Entity\TeamComposition;
use App\Form\TeamCompositionAddType;
use App\Repository\TeamRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use App\Repository\TeamCompositionRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


#[Route('/dashboard/mes-equipes')]
#[IsGranted('ROLE_USER')]
class TeamController extends AbstractController
{
    public function __construct(private Security $security){
    }

    #[Route('/', name: 'app_dashboard_team_index', methods: ['GET'])]
    public function index(TeamRepository $teamRepository): Response
    {
        return $this->render('dashboard/team/index.html.twig', [
            'teams' => $teamRepository->getTeamsByHost($this->security->getUser()),
        ]);
    }

    #[Route('/ajouter', name: 'app_dashboard_team_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $team = new Team();
        $form = $this->createForm(TeamType::class, $team);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $teamComposition = new TeamComposition();
            $teamComposition->setTeam($team);
            $teamComposition->setPlayer($this->security->getUser());
            $teamComposition->setHost(1);
            $entityManager->persist($team);
            $entityManager->persist($teamComposition);
            $entityManager->flush();

            return $this->redirectToRoute('app_dashboard_team_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('dashboard/team/new.html.twig', [
            'team' => $team,
            'form' => $form,
        ]);
    }
    #[IsGranted('edit', 'team')]
    #[Route('/{id}', name: 'app_dashboard_team_show', methods: ['GET'])]
    public function show(Team $team): Response
    {
        return $this->render('dashboard/team/show.html.twig', [
            'team' => $team,
        ]);
    }
    #[IsGranted('edit', 'team')]
    #[Route('/{id}/modifier', name: 'app_dashboard_team_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Team $team, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TeamType::class, $team);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_dashboard_team_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('dashboard/team/edit.html.twig', [
            'team' => $team,
            'form' => $form,
        ]);
    }

    #[IsGranted('delete', 'team')]
    #[Route('/{id}', name: 'app_dashboard_team_delete', methods: ['POST'])]
    public function delete(Request $request, Team $team, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$team->getId(), $request->getPayload()->get('_token'))) {
            $team->setDeleted(true);
            $entityManager->persist($team);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_dashboard_team_index', [], Response::HTTP_SEE_OTHER);
    }
}
