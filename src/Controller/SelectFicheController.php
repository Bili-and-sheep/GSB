<?php

// src/Controller/SelectFicheController.php

namespace App\Controller;

use App\Entity\FicheFrais;
use App\Entity\LigneFraisForfait;
use App\Form\SelectFicheType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class SelectFicheController extends AbstractController
{
    #[Route('/selectfiche', name: 'app_select_fiche')]
    public function index(Request $request,UserInterface $user, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(SelectFicheType::class, null, ['user' => $user]);
        $form->handleRequest($request);
        $montantLFF = 0;
        $montantLFHF = 0;
        $selectedFiche = null;
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var FicheFrais $selectedFiche */
            $selectedFiche = $form->get('id')->getData();
            if ($selectedFiche) {
                foreach ($selectedFiche->getLigneFraisForfait() as $ligne) {
                    $montantLFF += $ligne->getMontant();
                }
                foreach ($selectedFiche->getLigneFraisHorsForfait() as $ligne) {
                    $montantLFHF += $ligne->getMontant();
                }
            }
        }


        return $this->render('select_fiche/index.html.twig', [
            'form' => $form,
            'selectedFiche' => $selectedFiche,
            'controller_name' => 'SelectFicheController',
            'montantLFF' => $montantLFF,
            'montantLFHF' => $montantLFHF,
        ]);
    }
    #[Route('/selectfiche/update/{id}', name: 'app_select_fiche_update', methods: ['POST'])]
    public function updateToBeValided(FicheFrais $ficheFrais, EntityManagerInterface $entityManager): Response
    {
        $ficheFrais->setToBeValided(!$ficheFrais->getToBeValided()); // Toggle the state
        $entityManager->flush();

        return $this->redirectToRoute('app_select_fiche');
    }
}
