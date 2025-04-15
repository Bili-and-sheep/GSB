<?php

namespace App\Controller;

use App\Entity\FicheFrais;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;


#[Route('/comptable')]
#[IsGranted('ROLE_COMPTABLE')]
final class ComptableController extends AbstractController
{
    #
    #[Route('/manegeFF', name: 'app_comptable_manegeFF')]
    public function index(EntityManagerInterface $entityManager): Response
    {

        $toBeValidedValue = $entityManager->getRepository(FicheFrais::class)->createQueryBuilder('f')
            ->where('f.ToBeValided = :toBeValided')
            ->setParameter('toBeValided', true)
            ->setMaxResults(5)
            ->getQuery()
            ->getResult();
//        dd($toBeValidedValue);
        return $this->render('comptable/index.html.twig', [
            'controller_name' => 'ComptableController',
            'ficheFrais' => $toBeValidedValue,
        ]);
    }
}
