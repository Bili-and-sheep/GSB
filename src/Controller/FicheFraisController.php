<?php

namespace App\Controller;

use App\Entity\Etat;
use App\Entity\FicheFrais;
use App\Form\SaisiFraisForfaitType;
use App\Form\FicheFraisType;
use App\Repository\FicheFraisRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/fiche/frais')]
final class FicheFraisController extends AbstractController
{
    #[Route(name: 'app_fiche_frais_index', methods: ['GET'])]
    public function index(FicheFraisRepository $ficheFraisRepository): Response
    {
        return $this->render('fiche_frais/index.html.twig', [
            'fiche_frais' => $ficheFraisRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_fiche_frais_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $ficheFrai = new FicheFrais();
        $form = $this->createForm(FicheFraisType::class, $ficheFrai);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($ficheFrai);
            $entityManager->flush();

            return $this->redirectToRoute('app_fiche_frais_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('fiche_frais/new.html.twig', [
            'fiche_frai' => $ficheFrai,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_fiche_frais_show', methods: ['GET'])]
    public function show(FicheFrais $ficheFrai): Response
    {
        return $this->render('fiche_frais/show.html.twig', [
            'fiche_frai' => $ficheFrai,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_fiche_frais_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, FicheFrais $ficheFrai, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(FicheFraisType::class, $ficheFrai);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_fiche_frais_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('fiche_frais/edit.html.twig', [
            'fiche_frai' => $ficheFrai,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_fiche_frais_delete', methods: ['POST'])]
    public function delete(Request $request, FicheFrais $ficheFrai, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$ficheFrai->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($ficheFrai);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_fiche_frais_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/new/limited', name: 'app_fiche_frais_limited_new', methods: ['GET', 'POST'])]
    public function newLimited(Request $request, EntityManagerInterface $entityManager): Response
    {
        $ficheFrai = new FicheFrais();
        $ficheFrai->setUser($this->getUser()); // Fixe l'utilisateur connecté

        $form = $this->createForm(SaisiFraisForfaitType::class, $ficheFrai, [
            'current_user' => $this->getUser(), // Option supplémentaire si besoin
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($ficheFrai);
            $entityManager->flush();

            return $this->redirectToRoute('app_fiche_frais_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('fiche_frais/new_limited.html.twig', [
            'fiche_frai' => $ficheFrai,
            'form' => $form,
        ]);
    }

    #[Route('/limited', name: 'app_fiche_frais_limited', methods: ['GET', 'POST'])]
    public function editCurrent(Request $request, EntityManagerInterface $entityManager, FicheFraisRepository $ficheFraisRepository
    ): Response {
        $user = $this->getUser();
        $currentMonth = new \DateTime('first day of this month');

        // Fetch the fiche frais for the current user and month
        $ficheFrai = $ficheFraisRepository->findOneBy([
            'User' => $user,
            'mois' => $currentMonth,
        ]);

        // If no fiche frais exists, create a new one
        if (!$ficheFrai) {
            $ficheFrai = new FicheFrais();
            $ficheFrai->setUser($user)
                ->setMois($currentMonth)
                ->setEtat($entityManager->getRepository(Etat::class)->findOneBy(['libelle' => 'En cours']))
                ->setDateModif(new \DateTime());
            $entityManager->persist($ficheFrai);
            $entityManager->flush();
        }

        // Restrict form to specific fields based on roles
        $formType = $this->isGranted('ROLE_USER') ? SaisiFraisForfaitType::class : FicheFraisType::class;

        $form = $this->createForm($formType, $ficheFrai);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ficheFrai->setDateModif(new \DateTime()); // Update modification date
            $entityManager->flush();

            $this->addFlash('success', 'Fiche frais mise à jour avec succès.');
            return $this->redirectToRoute('app_fiche_frais_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('fiche_frais/edit.html.twig', [
            'fiche_frai' => $ficheFrai,
            'form' => $form,
        ]);
    }

}
