<?php

namespace App\Controller;

use Proxies\__CG__\App\Entity\Catégorie;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;

class CategoryController extends AbstractController
{
    #[Route('/category', name: 'app_category')]
    public function index(): Response
    {
        return $this->render('category/index.html.twig', [
            'controller_name' => 'CategoryController',
        ]);
    }

#[Route('/AjoutCategoryForm',name:'AddCategory')]
public function AddCategory(Request $request, ManagerRegistry $mr): Response
{
$category = new Catégorie();

$form= $this->createForm(CategoryType::class, $category);
$form->handleRequest($request);
if ($form->isSubmitted() && $form->isValid())
{
     //$em=$this->getDoctrine()->getManager();
     $em = $mr->getManager();
     $em->persist($category);
     $em->flush();
}
return 
$this->render('category/AddCategory.html.twig',['form'=>$form->createView()]);

}


#[Route('/AfficherCat',name:'AffC')]
function affichCategory(CategoryRepository $repo){
    $cat=$repo->findAll();
    return $this->render('category/ListCategoryBack.html.twig',['cat'=>$cat]);
}

#[Route('/category/delete/{id}', name: 'category_delete')]
public function deleteProduit($id, ManagerRegistry $manager, CategoryRepository $authorepository ): Response
{
    $em = $manager->getManager();
    $category = $authorepository->find($id);
    //$book->deleteBookByIdAuthor($id) ;
        $em->remove($category);
        $em->flush();
        $this->addFlash('success', 'La Catégorie a été supprimée avec succès.');
    
    return $this->redirectToRoute('AffC');
}
#[Route('/editCategory/{id}', name: 'edit_category')]
public function editCategory(int $id, Request $request, EntityManagerInterface $entityManager): Response
{
    // Récupérer la catégorie à modifier en fonction de l'identifiant
    $category = $entityManager->getRepository(Catégorie::class)->find($id);

    // Vérifier si la catégorie existe
    if (!$category) {
        throw $this->createNotFoundException('La catégorie n\'existe pas');
    }

    // Créer le formulaire en utilisant la catégorie récupérée
    $form = $this->createForm(CategoryType::class, $category);

    // Gérer la soumission du formulaire
    $form->handleRequest($request);

    // Vérifier si le formulaire est soumis et valide
    if ($form->isSubmitted() && $form->isValid()) {
        // Enregistrer les modifications dans la base de données
        $entityManager->flush();

        // Ajouter un message flash pour confirmer la modification
        $this->addFlash('success', 'Catégorie modifiée avec succès');

        // Rediriger vers la page d'affichage des catégories après la modification
        return $this->redirectToRoute('AffC');
    }

    // Afficher le formulaire de modification de la catégorie
    return $this->render('category/editCategory.html.twig', [
        'form' => $form->createView(),
    ]);
}



}
