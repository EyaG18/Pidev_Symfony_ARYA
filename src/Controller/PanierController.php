<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ProduitRepository;
use App\Entity\Produit;
use App\Entity\User;
use App\Repository\PanierRepository;
class PanierController extends AbstractController
{
    #[Route('/panier', name: 'app_panier')]
    public function index(): Response
    {
        return $this->render('panier/index.html.twig', [
            'controller_name' => 'PanierController',
        ]);
    }
    #[Route('/MonPanier',name:'showPanier')]
    function affichAuthor(PanierRepository $repo){
        $prod = $repo->findBy(['id_user' => 3]); // Filtrer les produits par id_user = 3
        return $this->render('panier/MonPanierList.html.twig', ['paniers' => $prod]);
    }



    #[Route('/AddProduitDansPanier',name:'showPanier')]
    function addProductInPanier(PanierRepository $repo){
       
    }




}
