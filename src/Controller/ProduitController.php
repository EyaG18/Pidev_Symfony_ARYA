<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Form\ProduitType;
use App\Repository\ProduitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;

use Symfony\Component\HttpFoundation\Request;


class ProduitController extends AbstractController
{
    #[Route('/produitE', name: 'app_produit')]
    public function index(): Response
    {
        return $this->render('produit/index.html.twig', [
            'controller_name' => 'ProduitController',
        ]);
    }

    #[Route('/Listproduit',name:'showProduit')]
    function affichAuthor(ProduitRepository $repo){
        $prod=$repo->findAll();
        return $this->render('produit/ListProduitBack.html.twig',['prod'=>$prod]);
    }

    #[Route('/AjoutProduitForm', name:'ajoutProd')]
public function AddProduit(Request $request, ManagerRegistry $mr) :Response
{
$produit = new Produit();
$form= $this->createForm(ProduitType::class, $produit);
$form->handleRequest($request);
if ($form->isSubmitted() && $form->isValid())
{
     //$em=$this->getDoctrine()->getManager();
     $produit=$form->getData();
$photo = $form['ImageP']->getData() ;    
     $em= $mr->getManager();
     $em->persist($produit);
     $em->flush();
     return $this->redirectToRoute('showProduit');
}
return 
$this->render('produit/AjouterProduit.html.twig',['ff'=>$form->createView()]);
}

#[Route('/produit/delete/{id}', name: 'product_delete')]
public function deleteProduit($id, ManagerRegistry $manager, ProduitRepository $authorepository ): Response
{
    $em = $manager->getManager();
    $author = $authorepository->find($id);
    //$book->deleteBookByIdAuthor($id) ;
        $em->remove($author);
        $em->flush();
        $this->addFlash('success', 'Le produit a été supprimé avec succès.');
    
    return $this->redirectToRoute('showProduit');
}















































}
