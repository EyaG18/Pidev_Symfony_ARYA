<?php

namespace App\Controller;

use App\Entity\Commande;
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

    #[Route('/commande/add', name: 'add_commande')]
    public function AddCommande(Request $request, EntityManagerInterface $entityManager): Response
    {
        $commande = new Commande();
        $form = $this->createForm(CommandeType::class, $commande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($commande);
            $entityManager->flush();

            $this->addFlash('success', 'La commande a été créée avec succès.');

            return $this->redirectToRoute('app_commande');
        }

        return $this->render('commande/AjoutCommande.html.twig', ['form' => $form->createView()]);
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

    #[Route('/commandes/show/{idPanier}', name: 'show_commande')]
    public function showCommande(int $idPanier, CommandeRepository $commandeRepository): Response
    {
        $commande = $commandeRepository->find($idPanier);

        if (!$commande) {
            throw $this->createNotFoundException('Aucune commande trouvée pour cet ID: ' . $idPanier);
        }

        return $this->render('commande/show.html.twig', ['commande' => $commande]);
    }
}