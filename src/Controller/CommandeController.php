<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Entity\User;
use App\Entity\Livraison;
use App\Form\CommandeType;
use App\Repository\PanierRepository;
use App\Repository\CommandeRepository;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use App\Repository\UserRepository;

use Stripe\Checkout\Session;
use Stripe\Stripe;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Knp\Component\Pager\PaginatorInterface;
use Dompdf\Dompdf;
use Dompdf\Options;
use App\Entity\Notification;
use App\Entity\Avis;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Entity\Catégorie;


class CommandeController extends AbstractController
{
    /*#[Route('/commande', name: 'app_commande')]
    public function index(): Response
    {
        return $this->render('commande/index.html.twig', [
            'controller_name' => 'CommandeController',
            'cate' => $listCategories,
        ]);
    }*/

    #[Route('/commandes', name: 'afficher_commande')]
    public function affichCommande(CommandeRepository $repo, PaginatorInterface $paginator, Request $request,ManagerRegistry $emm): Response
    {

        
        $criteria = ['type' => 'reclamation'];
        $notification = $emm->getRepository(Notification::class)->findBy($criteria);
        $criteria2 = ['type' => 'avis'];
        $notification2 = $emm->getRepository(Notification::class)->findBy($criteria2);
        $n = $emm->getRepository(Notification::class)->count([]);
        // Retrieve all commands
        $commandes = $repo->findAll();
        
        // Paginate the results
        $pagination = $paginator->paginate(
            $commandes, // Query results
            $request->query->getInt('page', 1), 
            6
        );
    
        return $this->render('commande/ListCommande.html.twig', [
            'pagination' => $pagination, 'com' => $commandes,  'notifR' => $notification,'n' => $n ,'notifA' => $notification2
        ]);
    }


    #[Route('/commande/update/{id}', name: 'Commande_update', methods: ['GET', 'POST'])]
    public function editCommande(Request $request, CommandeRepository $commandeRepository, $id, EntityManagerInterface $entityManager, ManagerRegistry $emm): Response
    {
        $criteria = ['type' => 'reclamation'];
        $notification = $emm->getRepository(Notification::class)->findBy($criteria);
        $criteria2 = ['type' => 'avis'];
        $notification2 = $emm->getRepository(Notification::class)->findBy($criteria2);
        $n = $emm->getRepository(Notification::class)->count([]);
        
        // Retrieve the Commande entity to edit based on the identifier
        $commande = $commandeRepository->find($id);
    
        // Check if the Commande entity exists
        if (!$commande) {
            throw $this->createNotFoundException('No Commande found for ID: ' . $id);
        }
    
        // Create the Commande form, excluding user-related fields
        $form = $this->createForm(CommandeType::class, $commande);
    
        // Handle form submission
        $form->handleRequest($request);
    
        // Check if the form is submitted and valid
        if ($form->isSubmitted() && $form->isValid()) {
            // Save the changes to the database
            $entityManager->flush();
    
            // Redirect to the page showing all Commandes after the update
            return $this->redirectToRoute('afficher_commande');
        }
    
        return $this->renderForm('commande/editcommande.html.twig', [
            'commande' => $commande,
            'form' => $form,
            'notifR' => $notification, 'n' => $n, 'notifA' => $notification2
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




//Touskié front  : hedheya baad ena fl panier nnzel commander ena naayet llfonction hedhi
#[Route('/Commander', name: 'show_commande')]
public function showcommande(Request $request, PanierRepository $panierRepository,CommandeRepository $commandeRepository , SessionInterface $session
): Response
{
    //$user = $this->getDoctrine()->getRepository(User::class)->find($userId);
    $user = $this->getUser();
    $em = $this->getDoctrine()->getManager();
    $listCategories = $em->getRepository(Catégorie::class)->findAll();


    if (!$user) {
        $this->addFlash('warning', 'Utilisateur non trouvé.');
    }

    $panier = $panierRepository->findBy(['id_user' => $user]);

    if (empty($panier)) {
        $this->addFlash('warning', 'Aucun article de panier trouvé pour l\'utilisateur fourni.');
       
    }

    $panierId = null;
    if (!empty($panier)) {
        $panierId = $panier[0]->getIdPanier(); 
    }
       $commande = null;
       if ($panierId) {
           $commande = $commandeRepository->findOneBy(['idPanier' => $panierId]);
       }

     

    return $this->render('commande/AjoutCommande.html.twig', [
        'user' => $user,
        'panier' => $panier,
        'panierId' => $panierId,
        'commande' => $commande,
        'cate' => $listCategories,
        
    ]);
}


#[Route('/commande', name: 'add_commande')]
public function addCommande(Request $request, PanierRepository $panierRepository, EntityManagerInterface $entityManager, SessionInterface $session): Response
{
    $user = $this->getUser(); // Get the current authenticated user

    // Retrieve any panier associated with the user
    $panier = $panierRepository->findOneBy(['id_user' => $user]);

    // If panier is not found, handle the scenario according to your application logic
    if (!$panier) {
        throw $this->createNotFoundException('No panier found for this user.');
    }

    $em = $this->getDoctrine()->getManager();
    $listCategories = $em->getRepository(Catégorie::class)->findAll();

    $commande = new Commande();

    $form = $this->createForm(CommandeType::class, $commande);
    
    // Remove the 'status' field from the form
    $form->remove('status');

    $form->handleRequest($request);
    
    if ($form->isSubmitted() && $form->isValid()) {
        $livrable = $commande->isLivrable();

        if ($livrable === true) {
            $commande->setLivrable(true);
        } else {
            $commande->setLivrable(false);
        }
        
        $commande->setPanier($panier);

        $commande->setDateCom(new \DateTime());

        $randomReference = random_int(100000, 999999);
        $commande->setReference($randomReference);

        $commande->setPrixTotal($panier);

        if (!$user) {
            throw new \Exception('Failed to retrieve User from Panier');
        }

        // Set the user associated with the panier
        $commande->setIdUser($panier->getIdUser());

        if ($commande->getPanier() === null || $commande->getIdUser() === null) {
            throw new \Exception('Panier and User cannot be null for Commande creation');
        }

        $entityManager->persist($commande);
        $entityManager->flush();

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

            $livraison->setIdUser($user);

            $livraison->setIdCommande($commande);
           

            $entityManager->persist($livraison);
            $entityManager->flush();

            return $this->redirectToRoute('confirmer_commande', [
                'panierId' => $panier->getIdPanier(),
                'cate' => $listCategories,
                'user' => $user
            ]);
        }
    } 

    $entityManager->flush();

    return $this->render('commande/frontform.html.twig', [
        'form' => $form->createView(),
        'commande' => $commande,
        'panier' => $panier,
        'user' => $user,
        'cate' => $listCategories
    ]);
}



#[Route('/confirmer/{panierId}', name: 'confirmer_commande')]
public function confirmerCommande(PanierRepository $panierRepository, CommandeRepository $commandeRepository, $panierId): Response
{

    $em = $this->getDoctrine()->getManager();

    $listCategories = $em->getRepository(Catégorie::class)->findAll();
    $panier = $panierRepository->find($panierId);
    
    if (!$panier) {
        throw $this->createNotFoundException('Le panier avec l\'identifiant '.$panierId.' n\'existe pas.');
    }

    $commande = $commandeRepository->findOneBy(['idPanier' => $panierId]);

    return $this->render('commande/confirmation.html.twig', [
        'panier' => $panier, 
        'commande' => $commande, 
        'cate' => $listCategories,
        
    ]);
}



#[Route('/commandefront', name: 'afficher_commandefront')]
public function displayUserCommands(CommandeRepository $commandeRepository, SessionInterface $session): Response
{
    // Get the currently authenticated user
    $user = $this->getUser();

    // Check if a user is logged in
    if (!$user) {
        // Handle the scenario where no user is logged in, e.g., redirect to login page
        // For example:
        return $this->redirectToRoute('app_login');
    }

    // Retrieve user commands based on the currently authenticated user
    $userCommands = $commandeRepository->findBy(['id_user' => $user]);

    // Fetch categories from the database
    $em = $this->getDoctrine()->getManager();
    $listCategories = $em->getRepository(Catégorie::class)->findAll();

    // Render the template with user commands and other necessary data
    return $this->render('commande/CommandListFront.html.twig', [
        'user' => $user,
        'com' => $userCommands,
        'cate' => $listCategories,
    ]);
}

#[Route('/commande/delete/{id}', name: 'commande_deletefront')]
public function deleteCommandefront($id, CommandeRepository $commandeRepository, EntityManagerInterface $entityManager, SessionInterface $session): JsonResponse
{
    $user = $this->getUser();

    $command = $commandeRepository->find($id);
    $em = $this->getDoctrine()->getManager();
    $listCategories = $em->getRepository(Catégorie::class)->findAll();

    if (!$command) {
        return new JsonResponse(['error' => 'Command not found', 'cate' => $listCategories], Response::HTTP_NOT_FOUND);
    }

    // Check if the status of the command is "Traitée" or "Annulée"
    $status = $command->getStatus();
    if ($status === 'Traitée' || $status === 'Annulée') {
        return new JsonResponse(['error' => 'Cannot delete command with status "Traitée" or "Annulée"', 'cate' => $listCategories], Response::HTTP_BAD_REQUEST);
    }

    $entityManager->remove($command);
    $entityManager->flush();

    return new JsonResponse(['message' => 'Command deleted successfully', 'cate' => $listCategories]);
}

/*
    #[Route('/facture/{iduser}', name: 'facture')]
    public function facture($iduser, PanierRepository $panierRepository): Response
    {
       
        $panier = $panierRepository->findBy(['idUser' => $iduser]);
        
        
        if (!$panier) {
            $this->addFlash('warning', 'Aucun panier trouvé pour cet utilisateur.');
            return $this->redirectToRoute('app_commande'); 
        }
    
        $totalPrice = 0;
    
        foreach ($panier as $panierItem) {
            $totalPrice += $panierItem->getQuantiteparproduit() * $panierItem->getIdProduit()->getPrixp();
        }
    
        return $this->render('commande/facture.html.twig', [
            'panier' => $panier,
            'totalPrice' => $totalPrice,
            'iduser' => $iduser, 
        ]);
    }
    
    #[Route('/facturepdf/{iduser}', name: 'facturepdf')]
public function indexpdf($iduser, PanierRepository $panierRepository)
{
    $panier = $panierRepository->findBy(['idUser' => $iduser]);
    
    $totalPrice = 0;

    foreach ($panier as $panierItem) {
        $totalPrice += $panierItem->getQuantiteparproduit() * $panierItem->getIdProduit()->getPrixp();
    }

    $pdfOptions = new Options();
    $pdfOptions->set('defaultFont', 'Arial');
    
    $dompdf = new Dompdf($pdfOptions);
    
    $html = $this->renderView('commande/facture.html.twig', [
        'title' => "Votre facture en pdf",
        'panier' => $panier, 
        'totalPrice' => $totalPrice, 
    ]);
    
    // Load HTML to Dompdf
    $dompdf->loadHtml($html);
    
    $dompdf->setPaper('A4', 'portrait');

    $dompdf->render();

    $dompdf->stream("mypdf.pdf", [
        "Attachment" => true,
    ]);
}



    
    public function checkout(Panier $panier, StripeService $stripeService)
    {
        $stripeParams = []; // Add any additional Stripe parameters here

        try {
            $paymentIntent = $stripeService->stripe($stripeParams, $panier);
            $clientSecret = $paymentIntent->client_secret;

            // Pass $clientSecret to your frontend
        } catch (\Stripe\Exception\ApiErrorException $e) {
            // Handle payment errors gracefully
        }
    }*/

 
    #[Route('/checkout/{userId}', name: 'checkout', methods: ['GET', 'POST'])]
    public function checkout($userId, Request $request, PanierRepository $panierRepository, ProduitRepository $produitRepository, SessionInterface $session ): Response
    {
        Stripe::setApiKey('sk_test_51Oq3VTC4DfcX81jOg4IgEi0DJ88o63817Nf6dAFOFyj6G249vlbaCGEipesnC5cEIaCzsT3PD2j0SGXrkmzGNWUn00AqxMnm2A');
    
        $user = $this->getUser();
    
        if (!$user) {
            $this->addFlash('warning', 'Utilisateur non trouvé.');
        }
    
        $panier = $panierRepository->findBy(['id_user' => $userId]);
    
        if (empty($panier)) {
            $this->addFlash('warning', 'Aucun article de panier trouvé pour l\'utilisateur fourni.');
        }
    
     
        $lineItems = [];
    
       
        foreach ($panier as $cartItem) {
            $product = $produitRepository->find($cartItem->getIdProduit());
            $productName = $product->getNomp();
            $productPrice = $product->getPrixp();
            $productQuantity = $cartItem->getQuantiteparproduit();
    
            $conversionRate = 3.15;
            // creation line_item l kol produit fl panier
            $lineItem = [
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => [
                        'name' => $productName,
                    ],
                    'unit_amount' => $productPrice , 
                ],
                'quantity' => $productQuantity,
            ];
    
            
            $lineItems[] = $lineItem;
        }
    
        
        $totalPrice = 0;
        foreach ($lineItems as $lineItem) {
            $productPriceTnd = $lineItem['price_data']['unit_amount'] / 100 * $conversionRate;
            $lineItem['price_data']['unit_amount'] = $productPriceTnd * 100; // Convert back to cents
        }
    
        // naaml f Stripe session
        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => $lineItems,
            'mode' => 'payment',
            'success_url' => $this->generateUrl('success_url', [], UrlGeneratorInterface::ABSOLUTE_URL),
            //'error_url' => $this->generateUrl('error_url', [], UrlGeneratorInterface::ABSOLUTE_URL),


        ]);
          // Call addCommande method to handle command creation

    
        return $this->redirect($session->url, 303);
    }
    


    #[Route('/success-url', name: 'success_url')]
    public function successUrl(SessionInterface $session, Request $request, PanierRepository $panierRepository, EntityManagerInterface $entityManager): Response
    {
        // Get the current authenticated user
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        $panier = $panierRepository->findOneBy(['id_user' => $user]);
        $listCategories = $em->getRepository(Catégorie::class)->findAll();
    
        // Retrieve any panier associated with the user
        $panier = $panierRepository->findOneBy(['id_user' => $user]);
    
        // If panier is not found, handle the scenario according to your application logic
        if (!$panier) {
            throw $this->createNotFoundException('No panier found for this user.');
        }
    
        $commande = new Commande();
    
        $form = $this->createForm(CommandeType::class, $commande);
    
        // Remove the 'status' field from the form
        $form->remove('status');
    
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $livrable = $commande->isLivrable();
    
            if ($livrable === true) {
                $commande->setLivrable(true);
            } else {
                $commande->setLivrable(false);
            }
    
            $commande->setPanier($panier);
            $commande->setDateCom(new \DateTime());
            $randomReference = random_int(100000, 999999);
            $commande->setReference($randomReference);
            $commande->setPrixTotal($panier);
    
            if (!$user) {
                throw new \Exception('Failed to retrieve User from Panier');
            }
    
            // Set the user associated with the panier
            $commande->setIdUser($panier->getIdUser());
    
            if ($commande->getPanier() === null || $commande->getIdUser() === null) {
                throw new \Exception('Panier and User cannot be null for Commande creation');
            }
    
            $entityManager->persist($commande);
            $entityManager->flush();
    
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
    
                $livraison->setIdUser($user);
                $livraison->setIdCommande($commande);
                $entityManager->persist($livraison);
                $entityManager->flush();
    
                return $this->redirectToRoute('confirmer_commande', [
                    'panierId' => $panier->getIdPanier(),
                    'userId' => $user,
                    'cate' => $listCategories,
                ]);
            }
        }
    
        return $this->redirectToRoute('confirmer_commande', [
            'panierId' => $panier->getIdPanier(),
            'cate' => $listCategories,
            'user' => $user
        ]);
    }
    

    #[Route('/statcommande', name: 'statCom')]
    public function StatCommandes(CommandeRepository $commandeRepository,ManagerRegistry $emm): Response
    {

        $criteria = ['type' => 'reclamation'];
        $notification = $emm->getRepository(Notification::class)->findBy($criteria);
        $criteria2 = ['type' => 'avis'];
        $notification2 = $emm->getRepository(Notification::class)->findBy($criteria2);
        $n = $emm->getRepository(Notification::class)->count([]);
        $commandes = $commandeRepository->findAll();
    
        $statuses = [];
        
        foreach ($commandes as $commande) {
            $status = $commande->getStatus();
            
        if (!isset($statuses[$status])) {
            $statuses[$status] = 0;
        }
        
        $statuses[$status]++;
    }
    
        
        return $this->render('commande/chartjs.html.twig', [
            'statuses' => $statuses, 
            'notifR' => $notification, 'n' => $n, 'notifA' => $notification2
        ]);
    }
    
}    