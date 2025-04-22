<?php

namespace App\Controller;

use App\Entity\Etat;
use App\Entity\FicheFrais;
use App\Entity\LigneFraisHorsForfait;
use App\Form\FicheFraisComptableType;
use App\Form\SelectFicheComptableType;
use App\Form\SelectFicheByStateComptableType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;


#[Route('/comptable')]
#[IsGranted('ROLE_COMPTABLE')]
final class ComptableController extends AbstractController
{
    #[Route('/manegeFF', name: 'app_comptable_manegeFF')]
    public function index(EntityManagerInterface $entityManager, Request $request): Response
    {
        $formSelectFicheType = $this->createForm(SelectFicheComptableType::class);
        $formSelectFicheType->handleRequest($request);

        $formSelectFicheByStateType = $this->createForm(SelectFicheByStateComptableType::class);
        $formSelectFicheByStateType->handleRequest($request);
        $fiches = [];




        // Valeur par défaut : les fiches à valider
        $toBeValidedValue = $entityManager->getRepository(FicheFrais::class)->createQueryBuilder('f')
            ->where('f.toBeValided = :toBeValided')
            ->setParameter('toBeValided', true)
            ->setMaxResults(5)
            ->getQuery()
            ->getResult();





        // Si le formulaire est soumis et valide, on filtre
        if ($formSelectFicheType->isSubmitted() && $formSelectFicheType->isValid()) {


            $data = $formSelectFicheType->getData();
            $mois = $formSelectFicheType->get('mois')->getData();
            $annee = $formSelectFicheType->get('annee')->getData();
            $user = $data['user'];

            $date = new \DateTimeImmutable("{$annee}-{$mois}-01");

            $toBeValidedValue = $entityManager->getRepository(FicheFrais::class)->createQueryBuilder('f')
                ->where('f.User = :user')
                ->andWhere('f.mois = :date')
                ->setParameter('user', $user)
                ->setParameter('date', $date)
                ->getQuery()
                ->getResult();
        }









        if ($formSelectFicheByStateType->isSubmitted() && $formSelectFicheByStateType->isValid()) {
            $etat = $formSelectFicheByStateType->get('etat')->getData();
            $fiches = $entityManager->getRepository(FicheFrais::class)->createQueryBuilder('f')
                ->leftJoin('f.Etat', 'e')
                ->where('f.Etat = :etat')
                ->setParameter('etat', $etat)
                ->getQuery()
                ->getResult();
        }


        return $this->render('comptable/index.html.twig', [
            'ficheFrais' => $toBeValidedValue ?? [],
            'formByDate' => $formSelectFicheType->createView(),
            'formByState' => $formSelectFicheByStateType->createView(),
            'fichesState' => $fiches,
        ]);

    }

    #[Route('/fiche/{id}', name: 'app_comptable_fiche', methods: ['GET', 'POST'])]
    public function show(FicheFrais $ficheFrais, Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(FicheFraisComptableType::class, $ficheFrais);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $ficheFrais->setDateModif(new \DateTimeImmutable());

            $entityManager->persist($ficheFrais);
            $entityManager->flush();

            $this->addFlash('success', 'Données sauvegardées avec succès.');
            return $this->redirectToRoute('app_comptable_fiche', [
                'id' => $ficheFrais->getId(),
            ]);
        }

        return $this->render('comptable/show.html.twig', [
            'controller_name' => 'ComptableController',
            'form' => $form->createView(),
            'fiche_frais' => $ficheFrais,

        ]);
    }


    #[Route('/fiche/update/{id}', name: 'app_comptable_fiche_update', methods: ['POST'])]
    public function updateToBeValided(LigneFraisHorsForfait $lfgf, EntityManagerInterface $entityManager): Response
    {
        $lfgf->setIsValidate(!$lfgf->getIsValidate()); // toggle the boolean isValidate

        $libelle = $lfgf->getLibelle();

        if (str_starts_with($libelle, 'REFUSÉ : ')) {
            // Si déjà refusé, on enlève le préfixe pour le réaccepter
            $libelle = substr($libelle, strlen('REFUSÉ : '));
        } else {
            // Sinon on ajoute le préfixe pour le refuser
            $libelle = 'REFUSÉ : ' . $libelle;
        }

        $lfgf->setLibelle($libelle);

        $entityManager->flush();

        return $this->redirectToRoute('app_comptable_fiche', [
            'id' => $lfgf->getFicheFrais()->getId()
        ]);
    }

}
