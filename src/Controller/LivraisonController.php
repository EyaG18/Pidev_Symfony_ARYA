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
use Knp\Component\Pager\PaginatorInterface; 
use App\Entity\Notification;
use App\Entity\Avis;
use Doctrine\Persistence\ManagerRegistry;



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
    public function affichlivraison(LivraisonRepository $repo,PaginatorInterface $paginator, Request $request,ManagerRegistry $emm ): Response
    {

        $criteria = ['type' => 'reclamation'];
        $notification = $emm->getRepository(Notification::class)->findBy($criteria);
        $criteria2 = ['type' => 'avis'];
        $notification2 = $emm->getRepository(Notification::class)->findBy($criteria2);
        $n = $emm->getRepository(Notification::class)->count([]);
        $livraisons = $repo->findAll();
           // Paginate the results
           $pagination = $paginator->paginate(
            $livraisons, // Query results
            $request->query->getInt('page', 1), // Current page number, default 1
            6// Number of items per page
        );
        return $this->render('livraison/ListLivraison.html.twig', ['pagination' => $pagination,'liv' => $livraisons ,  'notifR' => $notification, 'n' => $n, 'notifA' => $notification2]);
    }


    #[Route('/livraison/update/{id}', name: 'livraison_update', methods: ['GET', 'POST'])]
    public function editLivraison(Request $request, LivraisonRepository $livraisonRepository, $id, EntityManagerInterface $entityManager,ManagerRegistry $emm ): Response
    {

        $criteria = ['type' => 'reclamation'];
        $notification = $emm->getRepository(Notification::class)->findBy($criteria);
        $criteria2 = ['type' => 'avis'];
        $notification2 = $emm->getRepository(Notification::class)->findBy($criteria2);
        $n = $emm->getRepository(Notification::class)->count([]);
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
            'notifR' => $notification, 'n' => $n, 'notifA' => $notification2
        ]);
    }
    
    
    
        #[Route('/livraison/delete/{id}', name: 'livraison_delete')]
        public function deletelivraison($id, livraisonRepository $livraisonRepository, EntityManagerInterface $entityManager,ManagerRegistry $emm): Response
        {
            $criteria = ['type' => 'reclamation'];
            $notification = $emm->getRepository(Notification::class)->findBy($criteria);
            $criteria2 = ['type' => 'avis'];
            $notification2 = $emm->getRepository(Notification::class)->findBy($criteria2);
            $n = $emm->getRepository(Notification::class)->count([]);
            $livraison = $livraisonRepository->find($id);
    
            if (!$livraison) {
                throw $this->createNotFoundException('Aucune livraison trouvée pour cet ID: ' . $id);
            }
    
            $entityManager->remove($livraison);
            $entityManager->flush();
    
            $this->addFlash('success', 'La livraison a été supprimée avec succès.');
    
            return $this->redirectToRoute('afficher_livraison',[        
                 'notifR' => $notification, 'n' => $n, 'notifA' => $notification2
        ]);
        }
       
     /*  
        #[Route('/livraison/add/{commandeId}', name: 'livraison_add')]
        public function addLivraison(Request $request, $commandeId, EntityManagerInterface $entityManager)
        {
    $commande = $entityManager->getRepository(Commande::class)->find($commandeId);

    if ($commande->isLivrable()) {
        $livraison = new Livraison();

        
        $commandeReference = $commande->getReference();
        $livraison->setReference($commandeReference);

        $livraison->setStatusLivraison('en attente');

        $livraison->setPrixLivraison(8);

     
        $commandeDate = $commande->getDateCom();
        if ($commandeDate !== null) {
            $livraisonDate = (new \DateTime($commandeDate->format('Y-m-d')))->modify('+2 days');
            $livraison->setDateLivraison($livraisonDate);
        } else {
            $defaultLivraisonDate = new \DateTime();
            $defaultLivraisonDate->modify('+2 days');
            $livraison->setDateLivraison($defaultLivraisonDate);
        }

        $user = $commande->getIdUser();
        $livraison->setIdUser($user);

        $livraison->setIdCommande($commande);

        $entityManager->persist($livraison);
        $entityManager->flush();

      
        return $this->redirectToRoute('commande/confirmation.html.twig');
    } else {
        $this->addFlash('error', 'La commande associée n\'est pas livrable.');
        return $this->redirectToRoute('app_livraison');
    }
}*/

#[Route('/stat', name: 'statLiv')]

public function StatLivraisons(LivraisonRepository $livraisonRepository,ManagerRegistry $emm): Response
{
    $criteria = ['type' => 'reclamation'];
    $notification = $emm->getRepository(Notification::class)->findBy($criteria);
    $criteria2 = ['type' => 'avis'];
    $notification2 = $emm->getRepository(Notification::class)->findBy($criteria2);
    $n = $emm->getRepository(Notification::class)->count([]);
    $livraisons = $livraisonRepository->findAll();

   
    $statuses = [];
    
   
    foreach ($livraisons as $livraison) {
        $status = $livraison->getStatusLivraison();
        
        if (!isset($statuses[$status])) {
            $statuses[$status] = 0;
        }
        
        $statuses[$status]++;
    }
    
    return $this->render('livraison/chartJSLiv.html.twig', [
        'statuses' => $statuses,
        'notifR' => $notification, 'n' => $n, 'notifA' => $notification2
    ]);
}














}