<?php

namespace App\Controller;

use App\Entity\Entreprise;
use App\Repository\EntrepriseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class EntrepriseController extends AbstractController
{
    // dans le navigateur, http://127.0.0.1:8000/entreprise pour afficher cette vue ... l'argument '/entreprise' de Route est donc importante
    //on peut changer le name mais il faut vraiment qu'il soit unique car il servira pour du routage plus tard
    #[Route('/entreprise', name: 'app_entreprise')]

    // methode index qui renvoie une réponse
    // le render est la méthode qui permet de faire le lien entre le controller et la vue
    // (ancienne écriture) -> public function index(EntityManagerInterface $entityManager): Response
    public function index(EntrepriseRepository $entrepriseRepository): Response
    {

        // j'attribue une valeur directement dans le controleur
        $name1 = 'test de variable';
        $tableau = ["value1", "value2", "value3", "value4"];


        // ici, on va récupérer les données dans notre base de données $bidule = (va chercher dans la BDD) -> doctrine fera la relation entre le projet et la BDD. (dans le return, on crée aussi 'entreprise' pour la passer à la vue)
        // en l'ocurrence, on a récup toutes les entreprises de notre BDD, qu'on va envoyer via la méthode render dans la vue entreprise/index.html.twig 
        // (ancienne écriture) -> $entreprises = $entityManager->getRepository(Entreprise::class)->findAll();
        // findAll -> $entreprises = $entrepriseRepository->findAll();
        $entreprises = $entrepriseRepository->findBy([], ["raisonSociale"=> "ASC"]);
        // équivaut à -> SELECT * FROM entreprise ORDER BY raisonSociale ASC
        // agit sur notre affichage dans la vue

        $entreprises2 = $entrepriseRepository->findBy(["ville" => "STRASBOURG"], ['raisonSociale'=> "ASC"]);
        // on ne veut que les entreprises qui sont sur Strasbourg
        // équivaut à -> SELECT * FROM entreprise WHERE ville = 'STRASBOURG' ORDER BY raisonSociale ASC

        $entreprises3 = $entrepriseRepository->findBy([], ['raisonSociale'=> "DESC"]);
        
        


 
        
        // 'entreprise/index.html.twig' -> chemin vers la vue (c'est en effet dans le dossier entreprise, c'est bien le fichier index.html.twig -> les infos vont là bas)
        return $this->render('entreprise/index.html.twig', [

            // argument par défaut(on peut avoir 0 argument) pour montrer comment faire passer un argument dans la vue -> dans entreprise/index.html.twig, on a donc {{ controller_name }}
            'controller_name' => 'EntrepriseController',

            'name' => 'Elan Formation',

            'name1' => $name1,

            'tableau' => $tableau,

            // je crée aussi une variable entreprises qu'on passera à la vue.
            'entreprises' => $entreprises,

            'entreprises2' => $entreprises2,

            'entreprises3' => $entreprises3,


        ]);
    }

    // {id} sera l'identifiant de l'entreprise choisie -> dans Entity/Entreprise, $id sert bien à identifier chaque objet $entreprise
    #[Route('/entreprise/{id}', name: 'show_entreprise')]
    public function show(Entreprise $entreprise): Response
    {
        
        // on ira vers la vue show dans le dossier entreprise
        return $this->render('entreprise/show.html.twig', [

            // on fait passer l'objet $entreprise choisi
            'entreprise' => $entreprise,

        ]);
    }
}

