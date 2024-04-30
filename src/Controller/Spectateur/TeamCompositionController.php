<?php

namespace App\Controller\Spectateur;

use App\Entity\TeamComposition;
use App\Form\TeamCompositionType;
use App\Repository\TeamCompositionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/team/composition')]
class TeamCompositionController extends AbstractController
{
    // #[Route('/', name: 'app_team_composition_index', methods: ['GET'])]
    // public function index(TeamCompositionRepository $teamCompositionRepository): Response
    // {
    //     return $this->render('team_composition/index.html.twig', [
    //         'team_compositions' => $teamCompositionRepository->findAll(),
    //     ]);
    // }

    // #[Route('/new', name: 'app_team_composition_new', methods: ['GET', 'POST'])]
    // public function new(Request $request, EntityManagerInterface $entityManager): Response
    // {
    //     $teamComposition = new TeamComposition();
    //     $form = $this->createForm(TeamCompositionType::class, $teamComposition);
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $entityManager->persist($teamComposition);
    //         $entityManager->flush();

    //         return $this->redirectToRoute('app_team_composition_index', [], Response::HTTP_SEE_OTHER);
    //     }

    //     return $this->render('team_composition/new.html.twig', [
    //         'team_composition' => $teamComposition,
    //         'form' => $form,
    //     ]);
    // }

    // #[Route('/{id}', name: 'app_team_composition_show', methods: ['GET'])]
    // public function show(TeamComposition $teamComposition): Response
    // {
    //     return $this->render('team_composition/show.html.twig', [
    //         'team_composition' => $teamComposition,
    //     ]);
    // }

    // #[Route('/{id}/edit', name: 'app_team_composition_edit', methods: ['GET', 'POST'])]
    // public function edit(Request $request, TeamComposition $teamComposition, EntityManagerInterface $entityManager): Response
    // {
    //     $form = $this->createForm(TeamCompositionType::class, $teamComposition);
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $entityManager->flush();

    //         return $this->redirectToRoute('app_team_composition_index', [], Response::HTTP_SEE_OTHER);
    //     }

    //     return $this->render('team_composition/edit.html.twig', [
    //         'team_composition' => $teamComposition,
    //         'form' => $form,
    //     ]);
    // }

    // #[Route('/{id}', name: 'app_team_composition_delete', methods: ['POST'])]
    // public function delete(Request $request, TeamComposition $teamComposition, EntityManagerInterface $entityManager): Response
    // {
    //     if ($this->isCsrfTokenValid('delete'.$teamComposition->getId(), $request->getPayload()->get('_token'))) {
    //         $entityManager->remove($teamComposition);
    //         $entityManager->flush();
    //     }

    //     return $this->redirectToRoute('app_team_composition_index', [], Response::HTTP_SEE_OTHER);
    // }
}
