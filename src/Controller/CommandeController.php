<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Entity\User;
use App\Form\CommandeType;
use App\Repository\PanierRepository;
use App\Repository\CommandeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;


class CommandeController extends AbstractController
{
    #[Route('/commande', name: 'app_commande')]
    public function index(): Response
    {
        return $this->render('commande/index.html.twig', [
            'controller_name' => 'CommandeController',
        ]);
    }



    #[Route('/commandes', name: 'afficher_commande')]
    public function affichCommande(CommandeRepository $repo): Response
    {
        $commandes = $repo->findAll();
        return $this->render('commande/ListCommande.html.twig', ['com' => $commandes]);
    }

    #[Route('/update/{id}', name: 'Commande_update', methods: ['GET', 'POST'])]
public function edit(Request $request, CommandeRepository $commandeRepository, $id, EntityManagerInterface $entityManager): Response
{
    $commande = $commandeRepository->find($id);

    if (!$commande) {
        throw $this->createNotFoundException('Aucun commande trouvé pour cet ID: ' . $id);
    }

    // Exclude user-related fields from the form (adjust field names as needed)
    $form = $this->createForm(CommandeType::class, $commande);
    $form->remove('nomuser');
    $form->remove('prenomuser');
    $form->remove('emailusr');

  
    //$commande->setNomuser($commande->getIdUser()->getNomuser());
    //$commande->setPrenomuser($commande->getIdUser()->getPrenomuser());
    //$commande->setEmailusr($commande->getIdUser()->getEmailusr());

    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $entityManager->flush();

        return $this->redirectToRoute('afficher_commande', [], Response::HTTP_SEE_OTHER);
    }

    return $this->renderForm('commande/editcommande.html.twig', [
        'commande' => $commande,
        'form' => $form,
    ]);
}

    


    #[Route('/commande/delete/{id}', name: 'commande_delete')]
    public function deleteCommande($id, CommandeRepository $commandeRepository, EntityManagerInterface $entityManager): Response
    {
        $commande = $commandeRepository->find($id);

        if (!$commande) {
            throw $this->createNotFoundException('Aucune commande trouvée pour cet ID: ' . $id);
        }

        $entityManager->remove($commande);
        $entityManager->flush();

        $this->addFlash('success', 'La commande a été supprimée avec succès.');

        // Assuming a route named 'afficher_commande' exists for listing commandes
        return $this->redirectToRoute('afficher_commande');
    }


    #[Route('/commande/show/{userId}', name: 'show_commande')]
    public function showcommande($userId, Request $request, PanierRepository $panierRepository): Response
    {
        // Retrieve the user based on the provided user ID
        $user = $this->getDoctrine()->getRepository(User::class)->find($userId);
    
        // Check if the user exists
        if (!$user) {
            $this->addFlash('warning', 'Utilisateur non trouvé.');
            // Handle the case where the user is not found, e.g., redirect or return a response
        }
    
        // Retrieve the paniers for the user
        $paniers = $panierRepository->findBy(['idUser' => $userId]);
    
        // Check if any paniers are found for the user
        if (empty($paniers)) {
            $this->addFlash('warning', 'Aucun article de panier trouvé pour l\'utilisateur fourni.');
            // Handle the case where no paniers are found, e.g., redirect or return a response
        }
    
        // Render the template with the paniers and user information
        return $this->render('commande/AjoutCommande.html.twig', [
            'user' => $user,
            'paniers' => $paniers,
        ]);
    }

    #[Route('/commande/add/{userId}', name: 'add_commande')]
    public function addCommande($userId, Request $request, PanierRepository $panierRepository, EntityManagerInterface $entityManager): Response
    {
        // Retrieve the user based on the provided user ID
        $user = $this->getDoctrine()->getRepository(User::class)->find($userId);
    
        // Check if the user exists
        if (!$user) {
            $this->addFlash('warning', 'Utilisateur non trouvé.');
            // Handle the case where the user is not found, e.g., redirect or return a response
        }
    
        // Retrieve the panier for the user
        $panier = $panierRepository->findOneBy(['idUser' => $userId]);
    
        // Check if a panier is found for the user
        if (!$panier) {
            $this->addFlash('warning', 'Aucun article de panier trouvé pour l\'utilisateur fourni.');
            // Handle the case where no panier is found, e.g., redirect or return a response
        }
    
        // Create a new instance of Commande
        $commande = new Commande();
    
        // Set the user for the commande
        $commande->setIdUser($user);
    
        // Set the Id_Panier for the commande
        $commande->setIdPanier($panier);
    
        // Set the current date and time as the value for Date_com
        $commande->setDateCom(new \DateTime());
    
        // Generate a random reference number
        $reference = mt_rand(100000, 999999);
    
        // Set the random reference number for the commande
        $commande->setReference($reference);
    
        // Check if the user selected "livraison à domicile" and set livrable accordingly
        if ($request->request->get('livraison_mode') === 'livraison_domicile') {
            $commande->setLivrable(1);
        } else {
            // If "retrait en magasin" or other options are chosen, set livrable accordingly
            $commande->setLivrable(0);
        }
    
        // Set the default status to "attente"
        $commande->setStatus('attente');
    
        // Persist the commande entity to the database
        $entityManager->persist($commande);
        $entityManager->flush();
    
        // Render the confirmation page and pass the userId variable to the template
        return $this->redirectToRoute('afficher_commande');
    }
}    