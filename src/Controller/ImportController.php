<?php


namespace App\Controller;

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

    #[Route('/import', name: 'app_import', methods: ['GET', 'POST'])]
    public function import(Request $request): Response
    {
        //read visitor.json file
        $filePath = $this->getParameter('kernel.project_dir') . '/public/visiteur.json';
        if (file_exists($filePath)) {
            $jsonData = file_get_contents($filePath);
            $data = json_decode($jsonData, false);
        } else {
            $data = [];
        }

        //parcourir le tableau
        foreach ($data as $user) {
            // Process each user data here
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

            //hast the password
            $newUser->setPassword(password_hash($user->mdp, PASSWORD_DEFAULT));

            //persist the user
            $this->entityManager->persist($newUser);
            $this->entityManager->flush();


            // Add your logic to handle the user data

        }

        return $this->render('import/index.html.twig', [
            'controller_name' => 'app_start_import',
        ]);

    }


}