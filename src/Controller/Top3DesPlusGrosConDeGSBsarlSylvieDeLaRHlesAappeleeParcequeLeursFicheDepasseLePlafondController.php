<?php
namespace App\Controller;

use App\Form\SelectMonthType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use DateTime; // Importez la classe native PHP DateTime

class Top3DesPlusGrosConDeGSBsarlSylvieDeLaRHlesAappeleeParcequeLeursFicheDepasseLePlafondController extends AbstractController
{
    #[IsGranted('ROLE_COMPTABLE')]
    #[Route('/lepodium', name: 'app_lepodium')]

    public function topVisitors(Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(SelectMonthType::class);
        $form->handleRequest($request);

        $topVisitors = [];
        if ($form->isSubmitted() && $form->isValid()) {
            $month = $form->get('month')->getData();
//            dd($month);

            $qb = $em->getRepository('App\Entity\FicheFrais')
                ->createQueryBuilder('f')
                ->join('f.User', 'u')
                ->select('u.nom, u.prenom, SUM(f.montantValid) as totalMontant')
                ->where('f.mois = :month')
                ->groupBy('u.id')
                ->orderBy('totalMontant', 'DESC')
                ->setMaxResults(3)
                ->setParameter('month', $month);

            $topVisitors = $qb->getQuery()->getResult();

//            dd($topVisitors, $qb->getQuery()->getSQL());

        }

        return $this->render('LeTop/index.html.twig', [
            'controller_name' => 'OUAI LE TOP 3',
            'form' => $form->createView(),
            'topVisitors' => $topVisitors,
        ]);
    }
}