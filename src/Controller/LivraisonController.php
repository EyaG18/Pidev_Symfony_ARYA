<?php

namespace App\Controller;

use App\Repository\LivraisonRepository;
use App\Form\LivraisonType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Livraison;

class LivraisonController extends AbstractController
{
    #[Route('/livraison', name: 'app_livraison')]
    public function index(): Response
    {
        return $this->render('livraison/index.html.twig', [
            'controller_name' => 'LivraisonController',
        ]);
    }


    #[Route('/livraisons', name: 'afficher_livraison')]
    public function affichlivraison(LivraisonRepository $repo): Response
    {
        $livraisons = $repo->findAll();
        return $this->render('livraison/ListLivraison.html.twig', ['liv' => $livraisons]);
    }


    #[Route('/livraison/update/{id}', name: 'livraison_update', methods: ['GET', 'POST'])]
    public function editLivraison(Request $request, LivraisonRepository $livraisonRepository, $id, EntityManagerInterface $entityManager): Response
    {
        // Retrieve the Livraison entity to edit based on the identifier
        $livraison = $livraisonRepository->find($id);
    
        // Check if the Livraison entity exists
        if (!$livraison) {
            throw $this->createNotFoundException('No Livraison found for ID: ' . $id);
        }
    
        // Create the Livraison form, excluding user-related fields
        $form = $this->createForm(LivraisonType::class, $livraison);
    
    
        // Handle form submission
        $form->handleRequest($request);
    
        // Check if the form is submitted and valid
        if ($form->isSubmitted() && $form->isValid()) {
            // Save the changes to the database
            $entityManager->flush();
    
            // Redirect to the page showing all Livraisons after the update
            return $this->redirectToRoute('afficher_livraison');
        }
    
        // Render the form for editing the Livraison entity
        return $this->renderForm('livraison/editlivraison.html.twig', [
            'livraison' => $livraison,
            'form' => $form,
        ]);
    }
    
    
    
        #[Route('/livraison/delete/{id}', name: 'livraison_delete')]
        public function deletelivraison($id, livraisonRepository $livraisonRepository, EntityManagerInterface $entityManager): Response
        {
            $livraison = $livraisonRepository->find($id);
    
            if (!$livraison) {
                throw $this->createNotFoundException('Aucune livraison trouvée pour cet ID: ' . $id);
            }
    
            $entityManager->remove($livraison);
            $entityManager->flush();
    
            $this->addFlash('success', 'La livraison a été supprimée avec succès.');
    
            // Assuming a route named 'afficher_livraison' exists for listing livraisons
            return $this->redirectToRoute('afficher_livraison');
        }
    

}
