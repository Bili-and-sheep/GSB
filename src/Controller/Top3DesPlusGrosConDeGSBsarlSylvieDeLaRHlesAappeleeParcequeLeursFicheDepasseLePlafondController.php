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

            // Conversion du mois en un numéro numérique (par exemple "Juin" => 6)
            $months = [
                'Janvier' => 1,
                'Février' => 2,
                'Mars' => 3,
                'Avril' => 4,
                'Mai' => 5,
                'Juin' => 6,
                'Juillet' => 7,
                'Août' => 8,
                'Septembre' => 9,
                'Octobre' => 10,
                'Novembre' => 11,
                'Décembre' => 12,
            ];

            // Vérifier si le mois est valide et obtenir le numéro du mois
            if (isset($months[$month])) {
                $monthNumber = $months[$month];
            } else {
                throw new \InvalidArgumentException('Mois invalide');
            }

            $year = (new \DateTime())->format('Y'); // Utilise l'année actuelle pour la requête

            // Création d'un objet DateTime pour faire la comparaison dans la requête
            $monthDate = DateTime::createFromFormat('Y-m', $year . '-' . str_pad($monthNumber, 2, '0', STR_PAD_LEFT));

            // Vérifier si la conversion DateTime a réussi
            if (!$monthDate) {
                throw new \InvalidArgumentException('Le format de date fourni est invalide.');
            }

            $qb = $em->getRepository('App\Entity\FicheFrais')
                ->createQueryBuilder('f')
                ->join('f.User', 'u')
                ->select('u.nom, u.prenom, SUM(f.montantValid) as totalMontant')
                ->where('MONTH(f.mois) = :month')
                ->andWhere('YEAR(f.mois) = :year')
                ->groupBy('u.id')
                ->orderBy('totalMontant', 'DESC')
                ->setMaxResults(3)
                ->setParameter('month', $monthDate->format('m'))
                ->setParameter('year', $monthDate->format('Y'));

            $topVisitors = $qb->getQuery()->getResult();
        }

        return $this->render('LeTop/index.html.twig', [
            'controller_name' => 'OUAI LE TOP 3',
            'form' => $form->createView(),
            'topVisitors' => $topVisitors,
        ]);
    }
}