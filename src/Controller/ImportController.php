<?php

namespace App\Controller;

use App\Entity\Etat;
use App\Entity\FicheFrais;
use App\Entity\FraisForfait;
use App\Entity\LigneFraisForfait;
use App\Entity\LigneFraisHorsForfait;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ImportController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/import', name: 'app_import')]
    public function index(): Response
    {
        return $this->render('import/index.html.twig', [
            'controller_name' => 'ImportController',
        ]);
    }

    #[Route('/import/importUser', name: 'app_import_user', methods: ['GET', 'POST'])]
    public function importUser(Request $request): Response
    {
        $filePath = $this->getParameter('kernel.project_dir') . '/public/visiteur.json';
        if (file_exists($filePath)) {
            $jsonData = file_get_contents($filePath);
            $data = json_decode($jsonData, false);
        } else {
            $data = [];
        }

        foreach ($data as $user) {
            $newUser = new User();
            $newUser->setOldId($user->id);
            $newUser->setNom($user->nom);
            $newUser->setPrenom($user->prenom);
            $newUser->setEmail(strtolower($user->nom . '.' . $user->prenom . '@gsb.fr'));
            $newUser->setAdresse($user->adresse);
            $newUser->setPassword($user->mdp);
            $newUser->setCp($user->cp);
            $newUser->setVille($user->ville);
            $newUser->setDateEmbauche(new \DateTime($user->dateEmbauche));
            $newUser->setRoles(['ROLE_USER']);
            $newUser->setPassword(password_hash($user->mdp, PASSWORD_DEFAULT));

            $this->entityManager->persist($newUser);
            $this->entityManager->flush();
        }

        return $this->render('import/index.html.twig', [
            'controller_name' => 'importUser Done',
        ]);
    }

    #[Route('/import/etat', name: 'app_import_etat', methods: ['GET', 'POST'])]
    public function importetat(Request $request): Response
    {
        $etat = new Etat();
        $etat->setId(1);
        $etat->setLibelle('Fiche créée, saisie en cours');
        $this->entityManager->persist($etat);
        $this->entityManager->flush();

        $etat = new Etat();
        $etat->setId(2);
        $etat->setLibelle('Saisie clôturée');
        $this->entityManager->persist($etat);
        $this->entityManager->flush();

        $etat = new Etat();
        $etat->setId(3);
        $etat->setLibelle('Validée et mise en paiement');
        $this->entityManager->persist($etat);
        $this->entityManager->flush();

        $etat = new Etat();
        $etat->setId(4);
        $etat->setLibelle('Remboursée');
        $this->entityManager->persist($etat);
        $this->entityManager->flush();

        return $this->render('import/index.html.twig', [
            'controller_name' => 'importEtat Done',
        ]);
    }

    #[Route('/import/fraisforfait', name: 'app_import_fraisforfait', methods: ['GET', 'POST'])]
    public function fraisforfait(Request $request): Response
    {
        $frfo = new FraisForfait();
        $frfo->setId(1);
        $frfo->setLibelle('Forfait Etape');
        $frfo->setMontant(110.00);
        $this->entityManager->persist($frfo);
        $this->entityManager->flush();

        $frfo = new FraisForfait();
        $frfo->setId(2);
        $frfo->setLibelle('Frais Kilométrique');
        $frfo->setMontant(0.62);
        $this->entityManager->persist($frfo);
        $this->entityManager->flush();

        $frfo = new FraisForfait();
        $frfo->setId(3);
        $frfo->setLibelle('Nuitée Hôtel');
        $frfo->setMontant(80.00);
        $this->entityManager->persist($frfo);
        $this->entityManager->flush();

        $frfo = new FraisForfait();
        $frfo->setId(4);
        $frfo->setLibelle('Repas Restaurant');
        $frfo->setMontant(25.00);
        $this->entityManager->persist($frfo);
        $this->entityManager->flush();

        return $this->render('import/index.html.twig', [
            'controller_name' => 'importFraisForfait Done',
        ]);
    }




    #[Route('/import/importFicheFrais', name: 'app_import_ff', methods: ['GET', 'POST'])]
    public function importFF(Request $request): Response
    {
        $filePath = $this->getParameter('kernel.project_dir') . '/public/fichefrais.json';
        if (file_exists($filePath)) {
            $jsonData = file_get_contents($filePath);
            $data = json_decode($jsonData, false);
        } else {
            $data = [];
        }

        foreach ($data as $ff) {
            $newFF = new FicheFrais();
// Convert the string to a DateTime object
            $mois = \DateTime::createFromFormat('Ym', $ff->mois);
            $newFF->setMois($mois);
            $newFF->setNbJustificatifs($ff->nbJustificatifs);
            $newFF->setMontantValid($ff->montantValide);
            $newFF->setDateModif(new \DateTime($ff->dateModif));

            switch ($ff->idEtat) {
                case 'CR':
                    $newFF->setEtat($this->entityManager->getRepository(Etat::class)->findOneBy(['id' => 1]));
                    break;
                case 'CL':
                    $newFF->setEtat($this->entityManager->getRepository(Etat::class)->findOneBy(['id' => 2]));
                    break;
                case 'VA':
                    $newFF->setEtat($this->entityManager->getRepository(Etat::class)->findOneBy(['id' => 3]));
                    break;
                case 'RB':
                    $newFF->setEtat($this->entityManager->getRepository(Etat::class)->findOneBy(['id' => 4]));
                    break;
            }


            $newFF->setUser($this->entityManager->getRepository(User::class)->findOneBy(['oldId' => $ff->idVisiteur]));

            $this->entityManager->persist($newFF);
            $this->entityManager->flush();
        }

        return $this->render('import/index.html.twig', [
            'controller_name' => 'importFicheFrais Done',
        ]);
    }

    #[Route('/import/importLigneFicheFrais', name: 'app_import_lff', methods: ['GET', 'POST'])]
    public function importLFF(Request $request): Response
    {
        $filePath = $this->getParameter('kernel.project_dir') . '/public/lignefraisforfait.json';
        if (file_exists($filePath)) {
            $jsonData = file_get_contents($filePath);
            $data = json_decode($jsonData, false);
        } else {
            $data = [];
        }

        foreach ($data as $lff) {
            $newLFF = new LigneFraisForfait();
            $newLFF->setQuantite($lff->quantite);

            $mois = \DateTime::createFromFormat('Ym', $lff->mois);
            $user = $this->entityManager->getRepository(User::class)->findOneBy(['oldId' => $lff->idVisiteur]);
            $ficheFrais = $this->entityManager->getRepository(FicheFrais::class)->findOneBy(['mois' => $mois, 'User' => $user]);
            $newLFF->setFicheFrais($ficheFrais);

            switch ($lff->idFraisForfait) {
                case 'ETP':
                    $newLFF->setFraisForfait($this->entityManager->getRepository(FraisForfait::class)->findOneBy(['id' => 1]));
                    break;
                case 'KM':
                    $newLFF->setFraisForfait($this->entityManager->getRepository(FraisForfait::class)->findOneBy(['id' => 2]));
                    break;
                case 'NUI':
                    $newLFF->setFraisForfait($this->entityManager->getRepository(FraisForfait::class)->findOneBy(['id' => 3]));
                    break;
                case 'REP':
                    $newLFF->setFraisForfait($this->entityManager->getRepository(FraisForfait::class)->findOneBy(['id' => 4]));
                    break;
            }

            $this->entityManager->persist($newLFF);
            $this->entityManager->flush();
        }

        return $this->render('import/index.html.twig', [
            'controller_name' => 'importLigneFicheFrais Done',
        ]);
    }

    #[Route('/import/importLigneFicheHorsFrais', name: 'app_import_lfhf', methods: ['GET', 'POST'])]
    public function importLFHF(Request $request): Response
    {
        $filePath = $this->getParameter('kernel.project_dir') . '/public/lignefraishorsforfait.json';
        if (file_exists($filePath)) {
            $jsonData = file_get_contents($filePath);
            $data = json_decode($jsonData, false);
        } else {
            $data = [];
        }

        foreach ($data as $lfhf) {
            $newLFhF = new LigneFraisHorsForfait();
            $newLFhF->setId($lfhf->id);
            $newLFhF->setLibelle($lfhf->libelle);
            $newLFhF->setDate(new \DateTime($lfhf->date));
            $newLFhF->setMontant($lfhf->montant);

            // Convert the mois string to a DateTime object
            $mois = \DateTime::createFromFormat('Ym', $lfhf->mois);
            $user = $this->entityManager->getRepository(User::class)->findOneBy(['oldId' => $lfhf->idVisiteur]);
            $ficheFrais = $this->entityManager->getRepository(FicheFrais::class)->findOneBy(['mois' => $mois, 'User' => $user]);

            $newLFhF->setFicheFrais($ficheFrais);

            $this->entityManager->persist($newLFhF);
            $this->entityManager->flush();
        }

        return $this->render('import/index.html.twig', [
            'controller_name' => 'importLigneFicheHorsFrais Done',
        ]);
    }

    #[Route('/import/deleteAll', name: 'app_import_delete_all', methods: ['GET', 'POST'])]
    public function deleteAll(): Response
    {
        // Suppression des entités dans le bon ordre pour respecter les clés étrangères
        $repositories = [
            LigneFraisHorsForfait::class,
            LigneFraisForfait::class,
            FicheFrais::class,
            User::class,
            FraisForfait::class,
            Etat::class,
        ];

        foreach ($repositories as $repository) {
            $entities = $this->entityManager->getRepository($repository)->findAll();
            foreach ($entities as $entity) {
                $this->entityManager->remove($entity);
            }
        }

        // Effectuer la suppression
        $this->entityManager->flush();

        return $this->render('import/index.html.twig', [
            'controller_name' => 'All to data are gone !',
        ]);
    }


}