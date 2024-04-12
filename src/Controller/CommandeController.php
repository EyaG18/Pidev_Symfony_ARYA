<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Entity\Panier;
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

    #[Route('/commandes/show/{idCommande}', name: 'show_commande')]
    public function showCommande(int $idCommande, PanierRepository $panierRepository): Response
    {
        $paniers = $panierRepository->findBy(['idCommande' => $idCommande]);
        
        // Extract data for rendering in Twig template
        $extractedData = $panierRepository->extractDataForTwig($paniers);

        return $this->render('commande/AjoutCommande.html.twig', [
            'paniers' => $extractedData,
        ]);
    }
    #[Route('/commande/add', name: 'add_commande')]
    public function addCommande(Request $request, PanierRepository $panierRepository): Response
    {
        // Récupérer l'identifiant du panier depuis la requête
        $panierId = $request->request->get('panierId');
    
        // Vérifier si l'identifiant du panier est fourni
        if (!$panierId) {
            $this->addFlash('warning', 'Veuillez fournir un identifiant de panier.');
          
        }
    
        // Récupérer les paniers basés sur l'identifiant du panier fourni
        $paniers = $panierRepository->findBy(['idPanier' => $panierId]);
    
        // Vérifier si des articles de panier ont été trouvés pour l'identifiant de panier fourni
        if (empty($paniers)) {
            $this->addFlash('warning', 'Aucun article de panier trouvé pour l\'identifiant de panier fourni.');
           
        }
    
        // Rendre le modèle avec les articles du panier pour l'identifiant du panier fourni
        return $this->render('commande/AjoutCommande.html.twig', [
            'paniers' => $paniers,
        ]);
    }
    
}    