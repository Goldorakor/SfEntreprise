<?php

namespace App\Controller;

use App\Entity\Employe;
use App\Repository\EmployeRepository;
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
