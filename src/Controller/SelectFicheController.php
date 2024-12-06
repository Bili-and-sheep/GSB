<?php

// src/Controller/SelectFicheController.php

namespace App\Controller;

use App\Entity\FicheFrais;
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
    public function index(Request $request,UserInterface $user): Response
    {
        $form = $this->createForm(SelectFicheType::class, null, ['user' => $user]);
        $form->handleRequest($request);

        $selectedFiche = null;
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var FicheFrais $selectedFiche */
            $selectedFiche = $form->get('fiche')->getData();
        }

        return $this->render('select_fiche/index.html.twig', [
            'form' => $form,
            'selectedFiche' => $selectedFiche,
            'controller_name' => 'SelectFicheController',

        ]);
    }
}
