<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Catégorie;
use App\Entity\Produit;
use App\Repository\ProduitRepository;

class FrontController extends AbstractController
{
    #[Route('/front', name: 'app_front')]
    public function index(): Response
    {
        return $this->render('front/FrontARYA.html.twig', [
            'controller_name' => 'FrontController',
        ]);
    }
    #[Route('/ListproduitFront',name:'showProduitFront')]
    function affichAuthor(ProduitRepository $repo){
        $prod=$repo->findAll();
        return $this->render('front/frontARYA.html.twig',['prod'=>$prod]);
    }


}
