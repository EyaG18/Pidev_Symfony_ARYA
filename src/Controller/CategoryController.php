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

class CategoryController extends AbstractController
{
    #[Route('/category', name: 'app_category')]
    public function index(): Response
    {
        return $this->render('category/index.html.twig', [
            'controller_name' => 'CategoryController',
        ]);
    }

#[Route('/AjoutCategoryForm')]
public function AddCategory(Request $request)
{
$category = new Catégorie();
$form= $this->createForm(CategoryType::class, $category);
$form->handleRequest($request);
if ($form->isSubmitted() && $form->isValid())
{
     //$em=$this->getDoctrine()->getManager();
$em=$this->getDoctrine()->getManager();
     $em->persist($category);
     $em->flush();
     
}
return 
$this->render('category/AddCategory.html.twig',['ff'=>$form->createView()]);
}


#[Route('/AfficherCat',name:'AffC')]
function affichCategory(CategoryRepository $repo){
    $cat=$repo->findAll();
    return $this->render('category/ListCategoryBack.html.twig',['cat'=>$cat]);
}





}
