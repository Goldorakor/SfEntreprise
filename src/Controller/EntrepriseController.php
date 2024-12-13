<?php

namespace App\Controller;

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
    public function index(): Response
    {

        $name1 = 'test de variable';
        $tableau = ["value1", "value2", "value3", "value4"];
        
        
        
        // 'entreprise/index.html.twig' -> chemin vers la vue (c'est en effet dans le dossier entreprise, c'est bien le fichier index.html.twig -> les infos vont là bas)
        return $this->render('entreprise/index.html.twig', [

            // argument par défaut(on peut avoir 0 argument) pour montrer comment faire passer un argument dans la vue -> dans entreprise/index.html.twig, on a donc {{ controller_name }}
            'controller_name' => 'EntrepriseController',

            'name' => 'Elan Formation',

            'name1' => $name1,

            'tableau' => $tableau


        ]);
    }
}

