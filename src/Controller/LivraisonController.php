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
use App\Entity\User;
use App\Entity\Panier;
use App\Entity\Commande;


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
    
            return $this->redirectToRoute('afficher_livraison');
        }
       
       
        #[Route('/livraison/add/{commandeId}', name: 'livraison_add')]
        public function addLivraison(Request $request, $commandeId, EntityManagerInterface $entityManager)
        {
    // Retrieve the Commande based on the provided ID
    $commande = $entityManager->getRepository(Commande::class)->find($commandeId);

    if ($commande->isLivrable()) {
        // Create a new Livraison entity
        $livraison = new Livraison();

        // Set the reference
        $commandeReference = $commande->getReference();
        $livraison->setReference($commandeReference);

        // Set the status
        $livraison->setStatusLivraison('en attente');

        // Set the price
        $livraison->setPrixLivraison(8);

        // Set the date
        $commandeDate = $commande->getDateCom();
        if ($commandeDate !== null) {
            $livraisonDate = (new \DateTime($commandeDate->format('Y-m-d')))->modify('+2 days');
            $livraison->setDateLivraison($livraisonDate);
        } else {
            $defaultLivraisonDate = new \DateTime();
            $defaultLivraisonDate->modify('+2 days');
            $livraison->setDateLivraison($defaultLivraisonDate);
        }

        // Set the User
        $user = $commande->getIdUser();
        $livraison->setIdUser($user);

        // Set the Commande
        $livraison->setIdCommande($commande);

        // Persist the Livraison entity
        $entityManager->persist($livraison);
        $entityManager->flush();

        // Redirect to a route or return a response
        return $this->redirectToRoute('commande/confirmation.html.twig');
    } else {
        // Handle the case where the Commande is not livrable
        $this->addFlash('error', 'La commande associée n\'est pas livrable.');
        return $this->redirectToRoute('app_livraison');
    }
}

#[Route('/stat', name: 'statLiv')]

public function StatLivraisons(LivraisonRepository $livraisonRepository): Response
{
    // Récupération de toutes les livraisons
    $livraisons = $livraisonRepository->findAll();

    // Initialisation des tableaux pour les statistiques
    $statuses = [];
    
    // Parcours des livraisons
    foreach ($livraisons as $livraison) {
        // Récupération du statut de livraison
        $status = $livraison->getStatusLivraison();
        
        // Ajout du statut au tableau (si il n'existe pas déjà)
        if (!isset($statuses[$status])) {
            $statuses[$status] = 0;
        }
        
        // Incrémentation du compteur pour le statut de livraison
        $statuses[$status]++;
    }
    
    return $this->render('livraison/chartJSLiv.html.twig', [
        'statuses' => $statuses,
    ]);
}














}