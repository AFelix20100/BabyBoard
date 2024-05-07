<?php

namespace App\Controller\Dashboard;

use App\Entity\TeamComposition;
use App\Entity\Team;
use App\Form\TeamCompositionType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/dashboard/gestion-equipe')]
#[IsGranted('ROLE_USER')]
class TeamCompositionController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;    
    }

    #[Route('/{id}/ajouter-joueur', name: 'app_dashboard_team_composition_new', methods: ['GET', 'POST'])]
    public function new(Team $team, Request $request, EntityManagerInterface $entityManager): Response
    {
        $teamComposition = new TeamComposition();
        $teamComposition->setTeam($team);
        $teamComposition->setGuest(true);
        $teamComposition->setHost(false);
        $form = $this->createForm(TeamCompositionType::class, $teamComposition);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {    
            $entityManager->persist($teamComposition);
            $entityManager->flush();

            return $this->redirectToRoute('app_dashboard_team_composition_show', ['id' => $team->getId()], Response::HTTP_SEE_OTHER);

        }

        return $this->render('dashboard/team_composition/new.html.twig', [
            'team_composition' => $teamComposition,
            'team' => $team,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/participants', name: 'app_dashboard_team_composition_show', methods: ['GET'])]
    public function show(Team $team): Response
    {
        $teamComposition = $this->entityManager->getRepository(TeamComposition::class)->findBy(["team" => $team->getId()]);
        return $this->render('dashboard/team_composition/index.html.twig', [
            'team_composition' => $teamComposition,
            'team' => $team,
        ]);
    }

    #[Route('/{id}/modifier', name: 'app_dashboard_team_composition_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, TeamComposition $teamComposition, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TeamCompositionType::class, $teamComposition);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('dashboard/app_team_composition_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('dashboard/team_composition/edit.html.twig', [
            'team_composition' => $teamComposition,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_dashboard_team_composition_delete', methods: ['POST'])]
    public function delete(Request $request, TeamComposition $teamComposition, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$teamComposition->getId(), $request->getPayload()->get('_token'))) {
            $entityManager->remove($teamComposition);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_dashboard_team_composition_show', ['id' => $teamComposition->getTeam()->getId()], Response::HTTP_SEE_OTHER);
    }
}
