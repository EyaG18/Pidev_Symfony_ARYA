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
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Dompdf\Dompdf;
use Dompdf\Options;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;

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
       
        // creation du form tt en recuperant les data du produit selectionne
        $form = $this->createForm(ProduitType::class, $produit, [
            'categories' => $categories,
        ]);

        // pre filling  les champs du formulaire avec les dataa du produit
        $form->handleRequest($request);
        $is_edit = $produit->getIdProduit() !== null;

        if ($form->isSubmitted() && $form->isValid()) {
           
            // recuperation les données du formulaire
            $produit = $form->getData();

            // recuperation de  l'idd de la catégorie sélectionnée dans le form
            $categoryId = $form->get('Id_Categorie')->getData();
            $categorie = $entityManager->getRepository(Catégorie::class)->find($categoryId);
            // updatingg  la catégorie du produit
            $produit->setIdCategorie($categorie);
            //l'upload de l'image du produit
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
                }
                $produit->setImagep($newFilename);
            }
            // Enregistrer les modifications dans la base de données
            $entityManager->flush();
           
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


   
#[Route('/product/{idp}', name: 'product_details')]
public function productDetails(int $idp): Response
{
    $entityManager = $this->getDoctrine()->getManager();
    $product = $entityManager->getRepository(Produit::class)->find($idp);

    if (!$product) {
        throw $this->createNotFoundException('Produit non trouvé pour l\'ID ' . $idp);
    }

    return $this->render('produit/details.html.twig', [
        'product' => $product,
    ]);
}

#[Route('/ListproduitFrontEYA',name:'showProduitFront')]
function afficherProduitsFront(ProduitRepository $repo){
    $prod=$repo->findAll();
    return $this->render('produit/FrontProduitGrid.html.twig',['prod'=>$prod]);
}


#[Route('/pdf',name:'PDF_Produits',methods:"GET")]
public function pdfProduits(ProduitRepository $produitRepository)
{
    // Configure Dompdf according to your needs
   $pdfOptions = new Options();
   $pdfOptions->set('IsFontSubsettingEnabled', true);
   $pdfOptions->set('IsHtml5ParserEnabled', true);
   $pdfOptions->set('isRemoteEnabled', true);
   $pdfOptions->set('defaultFont', 'Arial');
   $pdfOptions->setIsRemoteEnabled(true);
   $pdfOptions->setTempDir('temp');
//$pdfOptions->set('isRemoteEnabled', true);

// Instantiate Dompdf with our options
$dompdf = new Dompdf($pdfOptions);
//$this->$dompdf->setOptions($pdfOptions);
   
    // Retrieve the HTML generated in our twig file
    $html = $this->renderView('produit/pdfProduit.html.twig', [
        'prod' => $produitRepository->findAll(),
    ]);
    // Load HTML to Dompdf
    //$dompdf->set_option('isRemoteEnabled',true);
    $dompdf->loadHtml($html);
    // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
    $dompdf->setPaper('A3', 'portrait');

    // Render the HTML as PDF
    $dompdf->render();
    // Output the generated PDF to Browser (inline view)
    $dompdf->stream("StockActuelProduits.pdf", [
        "product" => true
    ]);
}
#[Route('/stat-produits',name:'stat_produits')]


public function StatProduits(ProduitRepository $produitRepository, CategoryRepository $categoryRepository): Response
{
    // Récupération de tous les produits
    $produits = $produitRepository->findAll();

    // Initialisation des tableaux pour les statistiques
    $categories = [];
    // Parcours des produits
    foreach ($produits as $produit) {
        // Récupération de l'ID de la catégorie associée au produit
        $categorieId = $produit->getIdCatégorie();
        // Récupération de la catégorie à partir de l'ID
        $categorie = $categoryRepository->find($categorieId);
        if ($categorie) {
            // Récupération du nom de la catégorie
            $categorieNom = $categorie->getNomCategorie();
            // Ajout de la catégorie au tableau (si elle n'existe pas déjà)
            if (!isset($categories[$categorieNom])) {
                $categories[$categorieNom] = 0;
            }
            // Vérification si le produit est en rupture de stock
            if ($produit->getQteP() - $produit->getQteSeuilP() <= 5) {
                // Incrémentation du compteur de rupture de stock pour la catégorie
                $categories[$categorieNom]++;
            }
        }
    }
    // Tri des catégories par ordre décroissant de rupture de stock
    //arsort($categories);
    return $this->render('produit/TryChartJS.html.twig', [
        'categories' => $categories,
    ]);
}









}








































































































