<?php

namespace App\Controller;

use App\Entity\FicheFrais;
use App\Entity\LigneFraisHorsForfait;
use App\Form\FicheFraisComptableType;
use App\Form\PlafondLFHLmaxType;
use App\Form\SelectFicheComptableType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;


#[Route('/manager')]
#[IsGranted('ROLE_MANAGER')]
final class ManagerController extends AbstractController
{
    #[Route('/manegeFF', name: 'app_manager_manegeFF')]
    public function index(EntityManagerInterface $entityManager, Request $request): Response
    {
        $formSelectFicheType = $this->createForm(SelectFicheComptableType::class);
        $formSelectFicheType->handleRequest($request);
        $fiches = [];

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

        return $this->render('manager/index.html.twig', [
            'controller_name' => 'ManagerController',
            'ficheFrais' => $toBeValidedValue ?? [],
            'formByDate' => $formSelectFicheType->createView(),
            'fichesState' => $fiches,
        ]);

    }

    #[Route('/fiche/{id}', name: 'app_manager_fiche', methods: ['GET', 'POST'])]
    public function show(LigneFraisHorsForfait $lfgf, FicheFrais $ficheFrais, Request $request, EntityManagerInterface $entityManager): Response
    {
        $form_plafond = $this->createForm(PlafondLFHLmaxType::class);
//        $form_plafond = $this->createForm(PlafondLFHLmaxType::class, $ficheFrais, [
//            'plafond_actuel' => $ficheFrais->getPlafondLFHF() // ou autre propriété
//        ]);
        $form_plafond->handleRequest($request);

        if ($form_plafond->isSubmitted() && $form_plafond->isValid()) {
            $ficheFrais->setPlafondLFHF($form_plafond->getData()->getPlafondLFHF());
            $this->addFlash('success', 'Plafond mis à jour avec succès.');

            $entityManager->flush();
        }

        return $this->render('manager/show.html.twig', [
            'controller_name' => 'ComptableController',
            'form' => $form_plafond->createView(),
            'fiche_frais' => $ficheFrais,

        ]);
    }
}
