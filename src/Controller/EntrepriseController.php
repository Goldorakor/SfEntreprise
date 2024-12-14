<?php

namespace App\Controller;

use App\Entity\Entreprise;
use App\Repository\EntrepriseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Form\EntrepriseType; // importée car besoin dans public function new()

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






    // #[Route('/entreprise/{id}', name: 'show_entreprise')] -> on copie colle et on adapte à la fonction new()
    #[Route('/entreprise/new', name: 'new_entreprise')] // 'new_entreprise' est un nom cohérent qui décrit bien la fonction

    // https://symfony.com/doc/current/forms.html#rendering-forms
    /*
    public function new(Request $request): Response
    {
        $task = new Task();
        // ...

        $form = $this->createForm(TaskType::class, $task);

        return $this->render('task/new.html.twig', [
            'form' => $form,
        ]);
    }
    */
    public function new(Request $request, EntityManagerInterface $entityManager): Response // pour ajouter une entreprise à notre BDD
    {
        // 1. on crée une nouvelle entreprise (un objet entreprise est bien créé ici)
        $entreprise = new Entreprise();

        // 2. on crée le formulaire à partir de EntrepriseType (on veut ce modèle là bien entendu)
        $form = $this->createForm(EntrepriseType::class, $entreprise); // c'est bien la méthode createForm() qui permet de créer le formulaire

        // 4. le traitement s'effectue ici ! si le formulaire soumis est correct, on fera l'insertion en BDD
        /*
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // $form->getData() holds the submitted values
            // but, the original `$task` variable has also been updated
            $task = $form->getData();

            // ... perform some action, such as saving the task to the database

            return $this->redirectToRoute('task_success');
        }
        */
        $form->handleRequest($request);


        // bloc qui concerne la soumission
        if ($form->isSubmitted() && $form->isValid()) {
            
            $entreprise = $form->getData(); // on récupère les données du formulaire dans notre objet entreprise
            
            $entityManager->persist($entreprise); // équivaut à la méthode prepare() en PDO

            $entityManager->flush(); // équivaut à la méthode execute() en PDOStatement

            // redirection vers la liste d'entreprises (si formulaire soumis et formulaire valide)
            return $this->redirectToRoute('app_entreprise');
        }


        // 3. on affiche le formulaire créé dans la page dédiée à cet affichage -> {{ form(formAddEntreprise) }} --> affichage par défaut 
        return $this->render('entreprise/new.html.twig', [ // 'entreprise/new.html.twig' -> vue dédiée à l'affichage du formulaire : on crée un nouveau fichier dans le dossier entreprise
            // 'form' => $form,  on fait passer une variable form qui prend la valeur $form
            // on change le nom pour éviter toute ambiguité 'form' -> 'formAddEntreprise' comme expliqué dans new.html.twig
            'formAddEntreprise' => $form,
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

