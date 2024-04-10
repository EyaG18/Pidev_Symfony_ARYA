<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Form\ProduitType;
use App\Repository\ProduitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Catégorie;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


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
public function AddProduit(Request $request, ManagerRegistry $mr,EntityManagerInterface $entityManager): Response
{
    $categories = $entityManager->getRepository(Catégorie::class)->findAll();
    $produit = new Produit();
   
    $form = $this->createForm(ProduitType::class, $produit, [
          'categories' => $categories,
      ]);

    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
       
        $produit = $form->getData();
    
        $categoryId = $form->get('Id_Categorie')->getData();
        $categorie = $entityManager->getRepository(Catégorie::class)->find($categoryId);
        $produit->setIdCategorie($categorie);
      
$imageFile = $form->get('ImageP')->getData();
 if($imageFile){
    $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
     
     $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_EXTENSION);
     
     if (!in_array(strtolower($originalFilename), $allowedExtensions)) {
        $this->addFlash('error', 'Seules les images avec les extensions suivantes sont autorisées : jpg, jpeg, png, gif');
        return $this->redirectToRoute('ajoutProd');
    }
  
   
$newFilename=uniqid().'.png';

     try {
         $imageFile->move(

             $this->getParameter('images_directory'),
             $newFilename
         );
     } catch (FileException $e) {
        
     } }
     $produit->setImagep($newFilename);
        $em = $mr->getManager();
        $em->persist($produit);
        $em->flush();

        return $this->redirectToRoute('showProduit');
    } 

    return $this->render('produit/AjouterProduit.html.twig', [ 'form' => $form->createView(),]);
}


#[Route('/produit/delete/{id}', name: 'product_delete')]

public function deleteProduit($id, ManagerRegistry $manager, ProduitRepository $authorepository ): Response
{
    $em = $manager->getManager();
    $author = $authorepository->find($id);
  
        $em->remove($author);
        $em->flush();
        $this->addFlash('success', 'Le produit a été supprimé avec succès.');
    
    return $this->redirectToRoute('showProduit');
}

#[Route('/ModifierProduit/{id}', name:'modifierProd')]
    public function modifierProduit($id, Request $request, EntityManagerInterface $entityManager): Response
    {

        $produit = $entityManager->getRepository(Produit::class)->find($id);
        
        if (!$produit) {
            throw $this->createNotFoundException('Produit non trouvé');
        }
        $categories = $entityManager->getRepository(Catégorie::class)->findAll();
       
        // Créer le formulaire en passant les données du produit
        $form = $this->createForm(ProduitType::class, $produit, [
            'categories' => $categories,
        ]);

        // Pré-remplir les champs du formulaire avec les données du produit
        $form->handleRequest($request);
        $is_edit = $produit->getIdProduit() !== null;

        if ($form->isSubmitted() && $form->isValid()) {
            // Si le formulaire est soumis et valide, continuer

            // Récupérer les données du formulaire
            $produit = $form->getData();

            // Récupérer l'ID de la catégorie sélectionnée dans le formulaire
            $categoryId = $form->get('Id_Categorie')->getData();
            $categorie = $entityManager->getRepository(Catégorie::class)->find($categoryId);
            
            // Mettre à jour la catégorie du produit
            $produit->setIdCategorie($categorie);
          
            // Gérer l'upload de l'image du produit
            $imageFile = $form->get('ImageP')->getData();
            if ($imageFile) {
                $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_EXTENSION);
                if (!in_array(strtolower($originalFilename), $allowedExtensions)) {
                    $this->addFlash('error', 'Seules les images avec les extensions suivantes sont autorisées : jpg, jpeg, png, gif');
                    return $this->redirectToRoute('modifierProd', ['id' => $produit->getId()]);
                }
                $newFilename = uniqid().'.'.$originalFilename;
                try {
                    $imageFile->move(
                        $this->getParameter('images_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // Gérer l'exception en fonction de vos besoins
                }
                $produit->setImagep($newFilename);
            }

            // Enregistrer les modifications dans la base de données
            $entityManager->flush();

            // Rediriger vers la page de visualisation des produits après la modification
            return $this->redirectToRoute('showProduit');
        } 

        // Si le formulaire n'est pas soumis ou n'est pas valide, afficher le formulaire de modification avec les données actuelles
        return $this->render('produit/EditProduit.html.twig', [
            'form' => $form->createView(),
            'is_edit' => $is_edit, // Passer la variable is_edit au template Twig
        ]);
    }

#[Route('/phpinfo', name: 'phpinfo')]
public function phpinfo(): Response
{
    ob_start();
    phpinfo();
    $info = ob_get_clean();

    return new Response($info);
}

#[Route('/OpenINterfaceAjoutProd', name: 'route_name_to_ajouter_produit_html_twig')]
public function showAddProductInterface(): Response
    {
        return $this->render('produit/AjouterProduit.html.twig');
    }




}















































