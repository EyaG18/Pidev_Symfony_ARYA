<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Entity\User;
use App\Entity\Livraison;
use App\Form\CommandeType;
use App\Repository\PanierRepository;
use App\Repository\CommandeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use App\Repository\UserRepository;

use Dompdf\Dompdf;
use Dompdf\Options;


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


    return $this->redirectToRoute('afficher_commande');
}




//Touskié front 
#[Route('/commande/show/{userId}', name: 'show_commande')]
public function showcommande($userId, Request $request, PanierRepository $panierRepository,CommandeRepository $commandeRepository): Response
{
    // Récupérer l'utilisateur en fonction de l'ID d'utilisateur fourni
    $user = $this->getDoctrine()->getRepository(User::class)->find($userId);

    // Vérifier si l'utilisateur existe
    if (!$user) {
        $this->addFlash('warning', 'Utilisateur non trouvé.');
        // Rediriger ou gérer le cas où l'utilisateur n'est pas trouvé
    }

    // Récupérer les paniers de l'utilisateur
    $panier = $panierRepository->findBy(['idUser' => $userId]);

    // Vérifier si des paniers sont trouvés pour l'utilisateur
    if (empty($panier)) {
        $this->addFlash('warning', 'Aucun article de panier trouvé pour l\'utilisateur fourni.');
        // Rediriger ou gérer le cas où aucun panier n'est trouvé
    }

    // Récupérer l'ID du premier panier trouvé
    $panierId = null;
    if (!empty($panier)) {
        $panierId = $panier[0]->getIdPanier(); // Supposant que le premier panier correspondant est utilisé
    }
       // Récupérer la commande associée au panier
       $commande = null;
       if ($panierId) {
           $commande = $commandeRepository->findOneBy(['idPanier' => $panierId]);
       }

    // Rendre le modèle Twig en passant les variables 'user', 'paniers' et 'panierId'
    return $this->render('commande/AjoutCommande.html.twig', [
        'user' => $user,
        'panier' => $panier,
        'panierId' => $panierId,
        'commande' => $commande,
    ]);
}#[Route('/commande/add/{panierId}', name: 'add_commande')]
public function addCommande($panierId, Request $request, PanierRepository $panierRepository, EntityManagerInterface $entityManager): Response
{
    // Retrieve the panier based on the provided panier ID
    $panier = $panierRepository->find($panierId);

    // Check if the panier exists
    if (!$panier) {
        $this->addFlash('warning', 'Panier non trouvé.');
        return $this->redirectToRoute('app_livraison');
    }

    // Create a new Commande entity
    $commande = new Commande();

    // Create the form
    $form = $this->createForm(CommandeType::class, $commande);
    $form->remove('status');

    // Handle form submission
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $livrable = $commande->isLivrable();

        if ($livrable === true) {
            $commande->setLivrable(true);
        } else {
            $commande->setLivrable(false);
        }

        // Set the Panier
        $commande->setPanier($panier);

        // Set the current date and time
        $commande->setDateCom(new \DateTime());

        // Generate a random reference number
        $randomReference = random_int(100000, 999999);
        $commande->setReference($randomReference);

        // Set the total price
        $commande->setPrixTotal($panier);

        // Retrieve the user from the panier (assuming Panier has a getUser method)
        $user = $panier->getUser();

        // Check if user is retrieved from panier
        if (!$user) {
            throw new \Exception('Failed to retrieve User from Panier');
        }

        // Set the user for the Commande
        $commande->setIdUser($user);

        // Ensure both panier and user are set before persisting
        if ($commande->getPanier() === null || $commande->getIdUser() === null) {
            throw new \Exception('Panier and User cannot be null for Commande creation');
        }

        // Persist the Commande entity
        $entityManager->persist($commande);
        $entityManager->flush();

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
            $livraison->setIdUser($user);

            // Set the Commande
            $livraison->setIdCommande($commande);

            // Persist the Livraison entity
            $entityManager->persist($livraison);
            $entityManager->flush();

            // Redirect to the confirmation page
            return $this->redirectToRoute('confirmer_commande', ['panierId' => $panierId]);
        } else {
            // Redirect to the confirmation page
            return $this->redirectToRoute('confirmer_commande', ['panierId' => $panierId]);
        }
    }

    // Render the form
    return $this->render('commande/frontform.html.twig', [
        'form' => $form->createView(),
        'commande' => $commande,
        'panier' => $panier
    ]);
}


#[Route('/confirmer/{panierId}', name: 'confirmer_commande')]
public function confirmerCommande(PanierRepository $panierRepository, CommandeRepository $commandeRepository, $panierId): Response
{
    // Retrieve the panier associated with the provided panier ID
    $panier = $panierRepository->find($panierId);
    
    // Check if a panier was found
    if (!$panier) {
        // Handle the case where no panier is found
        throw $this->createNotFoundException('Le panier avec l\'identifiant '.$panierId.' n\'existe pas.');
    }

    // Retrieve the commande associated with the panier
    $commande = $commandeRepository->findOneBy(['idPanier' => $panierId]);

    // Render the confirmation page with the associated panier and commande
    return $this->render('commande/confirmation.html.twig', [
        'panier' => $panier, 
        'commande' => $commande, // Pass the commande variable to the Twig template
    ]);
}




#[Route('/commandefront/{userId}', name: 'afficher_commandefront')]
public function displayUserCommands($userId, CommandeRepository $commandeRepository, UserRepository $userRepository): Response
{
    // Retrieve the commands associated with the user ID
    $userCommands = $commandeRepository->findBy(['idUser' => $userId]);

    // Retrieve the user object based on the user ID
    $user = $userRepository->find($userId);

    // Check if the user exists
    if (!$user) {
        // Handle the case where the user is not found
        throw $this->createNotFoundException('User not found');
    }

    // Render the Twig template with the user's commands and user information
    return $this->render('commande/CommandListFront.html.twig', [
        'user' => $user,
        'userId' => $userId,
        'com' => $userCommands,
    ]);
}

    #[Route('/commande/delete/{id}', name: 'commande_deletefront')]
    public function deleteCommandefront($id, CommandeRepository $commandeRepository, EntityManagerInterface $entityManager): JsonResponse
    {
        // Find the command entity by its ID
        $command = $commandeRepository->find($id);
    
        if (!$command) {
            return new JsonResponse(['error' => 'Command not found'], Response::HTTP_NOT_FOUND);
        }
    
        // Remove the command from the database
        $entityManager->remove($command);
        $entityManager->flush();
    
        // Return success response
        return new JsonResponse(['message' => 'Command deleted successfully']);
    }
    #[Route('/facture/{panierId}', name: 'facture')]
    public function facture($panierId, PanierRepository $panierRepository, CommandeRepository $commandeRepository): Response
    {
        // Retrieve the panier associated with the provided panier ID
        $panier = $panierRepository->find($panierId);
    
        if (!$panier) {
            throw $this->createNotFoundException('Panier not found');
        }
    
        // Retrieve the commande associated with the panier
        $commande = $commandeRepository->findOneBy(['panier' => $panier]);
    
        if (!$commande) {
            throw $this->createNotFoundException('Commande not found');
        }
    
        // Determine if the commande is livrable
        $livrable = $commande->isLivrable();
        $frais = $livrable ? 8.00 : 0;
    
        return $this->render('commande/facture.html.twig', [
            'commande' => $commande,
            'panier' => $panier,
            'frais' => $frais
        ]);
    }
    

    #[Route('/facturepdf', name: 'facturepdf')]

    public function indexpdf()
    {
        // Configure Dompdf according to your needs
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');
        
        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);
        
        // Retrieve the HTML generated in our twig file
        $html = $this->renderView('commande/facture.html.twig', [
            'title' => "Votre facture en pdf"
        ]);
        
        // Load HTML to Dompdf
        $dompdf->loadHtml($html);
        
        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser (force download)
        $dompdf->stream("mypdf.pdf", [
            "Attachment" => true
        ]);
    }
    
}    