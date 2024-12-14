<?php

namespace App\Controller;

use App\Entity\Employe;
use App\Form\EmployeType;
use App\Repository\EmployeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class EmployeController extends AbstractController
{
    #[Route('/employe', name: 'app_employe')]
    public function index(EmployeRepository $employeRepository): Response
    {

    // $employes = $employeRepository->findAll();
    $employes = $employeRepository->findby([], ['nom'=>'ASC']);
    // équivaut à -> SELECT * FROM employe ORDER BY nom ASC
    // agit sur notre affichage dans la vue

        return $this->render('employe/index.html.twig', [
            'employes' => $employes,
        ]);
    }





    // #[Route('/employe/{id}', name: 'show_employe')] -> on copie colle et on adapte à la fonction new()
    #[Route('/employe/new', name: 'new_employe')] // 'new_employe' est un nom cohérent qui décrit bien la fonction

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
        // 1. on crée un nouvel employé (un objet employe est bien créé ici)
        $employe = new Employe();

        // 2. on crée le formulaire à partir de EmployeType (on veut ce modèle là bien entendu)
        $form = $this->createForm(EmployeType::class, $employe); // c'est bien la méthode createForm() qui permet de créer le formulaire

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
            
            $employe = $form->getData(); // on récupère les données du formulaire dans notre objet entreprise
            
            $entityManager->persist($employe); // équivaut à la méthode prepare() en PDO

            $entityManager->flush(); // équivaut à la méthode execute() en PDOStatement

            // redirection vers la liste d'entreprises (si formulaire soumis et formulaire valide)
            return $this->redirectToRoute('app_employe');
        }


        // 3. on affiche le formulaire créé dans la page dédiée à cet affichage -> {{ form(formAddEntreprise) }} --> affichage par défaut 
        return $this->render('employe/new.html.twig', [ // 'employe/new.html.twig' -> vue dédiée à l'affichage du formulaire : on crée un nouveau fichier dans le dossier employe
            // 'form' => $form,  on fait passer une variable form qui prend la valeur $form
            // on change le nom pour éviter toute ambiguité 'form' -> 'formAddEmploye' comme expliqué dans new.html.twig
            'formAddEmploye' => $form,
        ]);
    }





    // {id} sera l'identifiant de l'employé choisi -> dans Entity/Employe, $id sert bien à identifier chaque objet $employe
    #[Route('/employe/{id}', name: 'show_employe')]
    public function show(Employe $employe): Response
    {
        
        // on ira vers la vue show dans le dossier employe
        return $this->render('employe/show.html.twig', [

            // on fait passer l'objet $entreprise choisi
            'employe' => $employe,

        ]);
    }

}
