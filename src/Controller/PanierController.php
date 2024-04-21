<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ProduitRepository;
use App\Entity\Produit;
use App\Entity\User;
use App\Entity\Panier;
use App\Repository\PanierRepository;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Catégorie;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;
// Import de la classe JsonResponse
use Symfony\Component\HttpFoundation\JsonResponse;



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


        $em = $this->getDoctrine()->getManager();
        $listCategories = $em->getRepository(Catégorie::class)->findAll();
        $prod = $repo->findBy(['id_user' => 3]); // Filtrer les produits par id_user = 3
        return $this->render('panier/MonPanierList.html.twig', ['paniers' => $prod
        , 'cate' => $listCategories,
        
        ] 
    );
    }
    #[Route('/panier/delete/{id}', name: 'product_delete_panier')]

    public function deleteProduit($id, ManagerRegistry $manager, PanierRepository $authorepository ): Response
    {
        $em = $manager->getManager();
        $author = $authorepository->find($id);
      
            $em->remove($author);
            $em->flush();
            $this->addFlash('success', 'Le produit a été supprimé avec succès de votre panier.');
        
        return $this->redirectToRoute('showPanier');
    }
    


   /* #[Route('/ajouterProduitPanier', name: 'addToPanier')]
    function addToPanier(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Récupérer les données du formulaire
        $idProduit = $request->request->get('productId');
        $quantite = $request->request->get('quantite');
        //$price = $request->request->get('price');
        // Vous pouvez également récupérer l'identifiant de l'utilisateur si nécessaire
        //$idUser = $this->getUser()->getId(); // Supposons que l'identifiant de l'utilisateur est dans une variable de session
        $produit = $entityManager->getRepository(Produit::class)->find($idProduit);
        $prixProduit = $produit->getPrixP();
    
        // Calculer le prix total du produit
        $prixTotalProduit = $prixProduit * $quantite;
        //$user = $this->getDoctrine()->getRepository(User::class)->find(3);
        $user = $entityManager->getRepository(User::class)->find(3);
    
        // Créer une nouvelle instance de l'entité Panier et lui attribuer les valeurs
        $panier = new Panier();
        $panier->setIdUser($user->getIdUser());
        $panier->setIdProduit($idProduit);
        $panier->setQuantiteParProduit($quantite);
        $panier->setPrixPanierUnitaire($prixTotalProduit);
    
        $entityManager->persist($panier);
        $entityManager->flush();
    
        return $this->redirectToRoute('showPanier');
    }*/
    #[Route('/ajouterProduitPanier', name: 'addToPanier')]
    public function addToPanier(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        // Récupérer les données JSON envoyées via la requête AJAX
        $data = json_decode($request->getContent(), true);
    
        // Récupérer les données du produit et de la quantité
        $idProduit = $data['productId'];
        $quantite = $data['quantity'];
        $price = $data['price'];
    
        // Vous pouvez également récupérer l'identifiant de l'utilisateur si nécessaire
        //$idUser = $this->getUser()->getId(); // Supposons que l'identifiant de l'utilisateur est dans une variable de session
        $user = $entityManager->getRepository(User::class)->find(3);
    
        // Récupérer le produit à partir de son identifiant
        $produit = $entityManager->getRepository(Produit::class)->find($idProduit);
        if (!$produit) {
            // Gérer le cas où le produit n'existe pas
            return new JsonResponse(['error' => 'Produit non trouvé'], Response::HTTP_NOT_FOUND);
        }
    
        // Calculer le prix total du produit
        $prixTotalProduit = $price * $quantite;
    
        // Créer une nouvelle instance de l'entité Panier et lui attribuer les valeurs
        $panier = new Panier();
        $panier->setIdUser($user);
        $panier->setIdProduit($idProduit);
        $panier->setQuantiteParProduit($quantite);
        $panier->setPrixPanierUnitaire($prixTotalProduit);
    
        $entityManager->persist($panier);
        $entityManager->flush();
    
        return new JsonResponse(['message' => 'Produit ajouté au panier avec succès'], Response::HTTP_CREATED);
    }
    
















    }


   
    





