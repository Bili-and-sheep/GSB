<?php

namespace App\Controller;

use App\Entity\Etat;
use App\Entity\FicheFrais;
use App\Entity\FraisForfait;
use App\Entity\LigneFraisForfait;
use App\Entity\LigneFraisHorsForfait;
use App\Form\SaisiFraisForfaitType;
use App\Form\FicheFraisType;
use App\Repository\FicheFraisRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/fiche/frais')]
#[IsGranted('ROLE_USER')]
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

    #[Route('/Add/LFF', name: 'app_fiche_frais_add_lff', methods: ['GET', 'POST'])]
    public function editCurrent(Request $request, EntityManagerInterface $entityManager, FicheFraisRepository $ficheFraisRepository): Response {

        $user = $this->getUser();

        //Recupere le mois actuel
        $currentMonth = new \DateTime('first day of this month');

        //si la fiche de frais de ce mois ci n'existe pas, on la crée , sinon on récupère la fiche de frais la recherche du user doit ce faire par rapport à son id
        $ficheFrais = $ficheFraisRepository->findOneBy(['mois' => $currentMonth, 'User' => $user]);


        if ($ficheFrais == null) {
            $ficheFrais = new FicheFrais();
            $ficheFrais->setMois($currentMonth);
            $ficheFrais->setUser($user);

            //récupération de l'état "crée" (id = 1) pour la fiche de frais
            $etat = $entityManager->getRepository(Etat::class)->find(1);
            $ficheFrais->setEtat($etat);
            $ficheFrais->setMontantValid(0);
            $ficheFrais->setNbJustificatifs(0);
            $ficheFrais->setDateModif(new \DateTime());

            $ligneFraisForfaitKM= new LigneFraisForfait();
            $ligneFraisForfaitKM->setFicheFrais($ficheFrais);
            $fraisForfaitKM = $entityManager->getRepository(FraisForfait::class)->find(2);
            $ligneFraisForfaitKM->setFraisForfait($fraisForfaitKM);
            $ligneFraisForfaitKM->setQuantite(0);
            $entityManager->persist($ligneFraisForfaitKM);

            $ligneFraisForfaitNUI= new LigneFraisForfait();
            $ligneFraisForfaitNUI->setFicheFrais($ficheFrais);
            $fraisForfaitNUI = $entityManager->getRepository(FraisForfait::class)->find(3);
            $ligneFraisForfaitNUI->setFraisForfait($fraisForfaitNUI);
            $ligneFraisForfaitNUI->setQuantite(0);
            $entityManager->persist($ligneFraisForfaitNUI);

            $ligneFraisForfaitREP= new LigneFraisForfait();
            $ligneFraisForfaitREP->setFicheFrais($ficheFrais);
            $fraisForfaitREP = $entityManager->getRepository(FraisForfait::class)->find(4);
            $ligneFraisForfaitREP->setFraisForfait($fraisForfaitREP);
            $ligneFraisForfaitREP->setQuantite(0);
            $entityManager->persist($ligneFraisForfaitREP);

            $ligneFraisForfaitETP= new LigneFraisForfait();
            $ligneFraisForfaitETP->setFicheFrais($ficheFrais);
            $fraisForfaitETP = $entityManager->getRepository(FraisForfait::class)->find(1);
            $ligneFraisForfaitETP->setFraisForfait($fraisForfaitETP);
            $ligneFraisForfaitETP->setQuantite(0);
            $entityManager->persist($ligneFraisForfaitETP);

            $entityManager->persist($ficheFrais);
            $entityManager->flush();
        }
        else{
            $ficheFrais = $ficheFraisRepository->findOneBy(['mois' => $currentMonth, 'User' => $user]);

        }

//        dd($ficheFrais);
        $form = $this->createForm(SaisiFraisForfaitType::class, [
            'km' => $ficheFrais->getLigneFraisForfait()[1]->getQuantite(),
            'nuites' => $ficheFrais->getLigneFraisForfait()[2]->getQuantite(),
            'repas' => $ficheFrais->getLigneFraisForfait()[3]->getQuantite(),
            'etp' => $ficheFrais->getLigneFraisForfait()[0]->getQuantite(),
        ]);


//        $ligneHorsFraisForfait= new LigneFraisHorsForfait();
//        $entityManager->persist($ligneHorsFraisForfait);
        $entityManager->flush();











        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $ficheFrais->getLigneFraisForfait()[1]->setQuantite($form->get('km')->getData());
            $ficheFrais->getLigneFraisForfait()[2]->setQuantite($form->get('nuites')->getData());
            $ficheFrais->getLigneFraisForfait()[3]->setQuantite($form->get('repas')->getData());
            $ficheFrais->getLigneFraisForfait()[0]->setQuantite($form->get('etp')->getData());

            $entityManager->persist($ficheFrais);
            $entityManager->flush();

            return $this->redirectToRoute('app_fiche_frais_add_lff', [], Response::HTTP_SEE_OTHER);

        }
        return $this->render('fiche_frais/new_limited.html.twig', [
            'controller_name' => 'LFF',
            'form' => $form->createView(),

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
}
