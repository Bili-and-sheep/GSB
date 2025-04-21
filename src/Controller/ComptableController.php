<?php

namespace App\Controller;

use App\Entity\FicheFrais;
use App\Form\FicheFraisComptableType;
use App\Form\SelectFicheComptableType;
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
        $form = $this->createForm(SelectFicheComptableType::class);
        $form->handleRequest($request);

        // Valeur par défaut : les fiches à valider
        $toBeValidedValue = $entityManager->getRepository(FicheFrais::class)->createQueryBuilder('f')
            ->where('f.ToBeValided = :toBeValided')
            ->setParameter('toBeValided', true)
            ->setMaxResults(5)
            ->getQuery()
            ->getResult();

        // Si le formulaire est soumis et valide, on filtre
        if ($form->isSubmitted() && $form->isValid()) {


            $data = $form->getData();
            $mois = $form->get('mois')->getData();
            $annee = $form->get('annee')->getData();
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


        return $this->render('comptable/index.html.twig', [
            'ficheFrais' => $toBeValidedValue,
            'form' => $form->createView(),
        ]);

    }

    #[Route('/fiche/{id}', name: 'app_comptable_fiche', methods: ['GET'])]
    public function show(FicheFrais $ficheFrais, Request $request): Response
    {
        $form = $this->createForm(FicheFraisComptableType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $ficheFrais->setToBeValided($data['toBeValided']);
            $ficheFrais->setEtat($data['etat']);
            $ficheFrais->setDateModif(new \DateTimeImmutable());
            $ficheFrais->setMontantValide($data['montantValide']);

            $ficheFrais->getLigneFraisForfait()->clear();
            foreach ($data['ligneFraisForfait'] as $ligneFrais) {
                $ficheFrais->addLigneFraisForfait($ligneFrais);
            }
            $ficheFrais->getLigneFraisHorsForfait()->clear();
            foreach ($data['ligneFraisHorsForfait'] as $ligneFrais) {
                $ficheFrais->addLigneFraisHorsForfait($ligneFrais);
            }


            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($ficheFrais);
            $entityManager->flush();

            return $this->redirectToRoute('app_comptable_manegeFF', [], Response::HTTP_SEE_OTHER);
        }
        return $this->render('comptable/show.html.twig', [
            'controller_name' => 'ComptableController',
            'form' => $form->createView(),
            'fiche_frais' => $ficheFrais,
        ]);
    }
}
