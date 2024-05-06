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
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class PanierController extends AbstractController
{

    private $entityManager;

    public function __construct(ManagerRegistry $registry)
    {
        $this->entityManager = $registry->getManager();
    }

    #[Route('/panier', name: 'app_panier')]
    public function index(): Response
    {
        return $this->render('panier/index.html.twig', [
            'controller_name' => 'PanierController',
        ]);
    }
    

    #[Route('/MonPanier', name: 'showPanier')]
function affichAuthor(
    PanierRepository $repo,
    EntityManagerInterface $entityManager,
    Request $request,
    PaginatorInterface $paginator,SessionInterface $session
): Response {
    $listCategories = $entityManager->getRepository(Catégorie::class)->findAll();
    $idUser = $this->getUser();
    $prod = $repo->findBy(['id_user' => $idUser ]);
    $restaurants = $paginator->paginate(
        $prod, 
        $request->query->getInt('page', 1), 
        4 
    );
    return $this->render('panier/MonPanierList.html.twig', [
        'paniers' => $restaurants, 
        'cate' => $listCategories,
    ]);
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
    
    #[Route('/ajouterProduitPanier', name: 'addToPanier')]
    public function addToPanier(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $idProduit = $data['productId'];
        $quantite = $data['quantity'];
        $price = $data['price'];
        $idUser = $this->getUser();
  
        $produit = $this->entityManager->getRepository(Produit::class)->find($idProduit);
        if (!$produit) {
            return new JsonResponse(['error' => 'Produit non trouvé'], Response::HTTP_NOT_FOUND);
        }
        if ($produit->getQtep() < $quantite) {
           
            $this->addFlash('fail', 'La quantité que vous avez saisie dépasse le stock actuel du Produit ! ');
        } else {     $prixTotalProduit = $price * $quantite;
            $panier = new Panier();
            $panier->setIdUser($idUser);
            //$panier->setIdProduit($idProduit);
            $panier->setIdProduit($produit);
            $panier->setQuantiteParProduit($quantite);
            $panier->setPrixPanierUnitaire($prixTotalProduit);
        
            $this->entityManager->persist($panier);
            $this->entityManager->flush();
            $this->addFlash('success', 'Le Produit a été ajouté avec success à votre Panier ! ');
            //return $this->redirectToRoute('showPanier');
            return new JsonResponse(['message' => 'Produit ajouté au panier avec succès'], Response::HTTP_CREATED);       }
       
        
    }
    
















    }


   
    





