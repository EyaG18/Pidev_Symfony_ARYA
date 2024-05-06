<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Reclamation;
use App\Entity\Notification;
use App\Entity\User;
use App\Repository\ReclamationRepository;
use App\Repository\NotificationRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use App\Form\ReclamationType;
use App\Form\NotificationType;
use App\Entity\Catégorie;
use Symfony\Component\HttpFoundation\Session\SessionInterface;





class ReclamationController extends AbstractController
{
    #[Route('/reclamation', name: 'app_reclamation')]
    public function index(): Response
    {
        return $this->render('reclamation/chatbot.html.twig', [
            'controller_name' => 'ReclamationController',
        ]);
    }

    /********************************************FRONT****************************************************** */
    #[Route('/addReclamation', name: 'app_addReclamation')]
    public function addReclamation(Request $request, ManagerRegistry $emm, SessionInterface $session, EntityManagerInterface $entityManager): Response
    {
        $idUser = $this->getUser();
        //$user = $emm->getRepository(User::class)->find($id_c);

        $reclamation = new Reclamation();
        $reclamation->setIdClient($idUser);
        //var_dump($idUser);
        $reclamation->setStatuReclamation("en attente");
        $form = $this->CreateForm(ReclamationType::class, $reclamation);
        $listCategories = $entityManager->getRepository(Catégorie::class)->findAll();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {


            $em = $emm->getManager();
            $em->persist($reclamation);
            $em->flush();
            return $this->redirectToRoute('app_consulterReclamation');
        }


        return $this->render('reclamation/addreclamation.html.twig', ['form' => $form->createView(), 'cate' => $listCategories]);
    }
    #[Route('/consulterReclamation', name: 'app_consulterReclamation')]
    function afficherReclamationFront(ManagerRegistry $emm, EntityManagerInterface $entityManager, SessionInterface $session)
    {

        $idUser = $this->getUser();
        $criteriaNT = ['idClient' => $idUser, 'statuReclamation' => "en attente"];
        $reclamationNT = $emm->getRepository(Reclamation::class)->findBy($criteriaNT);
        $listCategories = $entityManager->getRepository(Catégorie::class)->findAll();
        $criteria = ['idClient' => $idUser, 'statuReclamation' => "traité"];
        $reclamation = $emm->getRepository(Reclamation::class)->findBy($criteria);

        return $this->render('reclamation/listReclamation.html.twig', ['reclamationT' => $reclamation, 'reclamationNT' => $reclamationNT, 'cate' => $listCategories,]);
    }

    #[Route('/deleteReclamation/{id_r}', name: 'app_deleteReclamation')]
    public function delete(ManagerRegistry $emm, $id_r)
    {
        $em = $emm->getManager();
        $reclamation = $emm->getRepository(Reclamation::class)->find($id_r);
        $em->remove($reclamation);
        $em->flush();
        $this->addFlash('success', 'La reclamation a été supprimé avec succès.');
        return $this->redirectToRoute('app_consulterReclamation');
    }
    #[Route('/editReclamation/{ref}', name: 'app_editReclamation')]
    public function editReclamation(ManagerRegistry $doctrine, Request $request, $ref, EntityManagerInterface $entityManager)
    {
        $em = $doctrine->getManager();
        $reclamation = $doctrine->getRepository(Reclamation::class)->find($ref);
        $form = $this->createForm(ReclamationType::class, $reclamation);
        $form->handleRequest($request);
        $listCategories = $entityManager->getRepository(Catégorie::class)->findAll();
        if ($form->isSubmitted()) {
            $em->persist($reclamation);
            $em->flush();
            return $this->redirectToRoute('app_consulterReclamation');
        } else {
            return $this->renderForm('reclamation/editReclamation.html.twig', ['form' => $form, 'reclamation' => $reclamation, 'cate' => $listCategories]);
        }


    }

    /********************************************Back****************************************************** */
    #[Route('/consulterReclamationBack', name: 'app_consulterReclamationBack')]
    function afficherReclamationFBack(ReclamationRepository $repo, ManagerRegistry $emm)
    {
        $criteria = ['type' => 'reclamation'];
        $notification = $emm->getRepository(Notification::class)->findBy($criteria);
        $criteria2 = ['type' => 'avis'];
        $notification2 = $emm->getRepository(Notification::class)->findBy($criteria2);
        $n = $emm->getRepository(Notification::class)->count([]);

        $reclamation = $repo->findAll();
        return $this->render('reclamation/listReclamationBack.html.twig', ['reclamation' => $reclamation, 'notifR' => $notification, 'n' => $n, 'notifA' => $notification2]);
    }
    #[Route('/deleteReclamationBack/{id_r}', name: 'app_deleteReclamationBack')]
    public function deleteBack(ManagerRegistry $emm, $id_r)
    {
        $em = $emm->getManager();
        $reclamation = $emm->getRepository(Reclamation::class)->find($id_r);
        $em->remove($reclamation);
        $em->flush();
        return $this->redirectToRoute('app_consulterReclamationBack');
    }

    #[Route('/statReclamation', name: 'app_statReclamation')]
    public function StatReclamations(ManagerRegistry $emm, EntityManagerInterface $entityManager): Response
    {
        $criteria = ['type' => 'reclamation'];
        $notification = $emm->getRepository(Notification::class)->findBy($criteria);
        $criteria2 = ['type' => 'avis'];
        $notification2 = $emm->getRepository(Notification::class)->findBy($criteria2);
        $n = $emm->getRepository(Notification::class)->count([]);
        // Récupération des réclamations
        $reclamations = $emm->getRepository(Reclamation::class)->findAll();

        // Initialisation du tableau des statistiques
        $stats = [];

        // Comptage du nombre de réclamations par type
        $totalReclamations = count($reclamations);
        foreach ($reclamations as $reclamation) {
            $typeReclamation = $reclamation->getTypeReclamation();
            if (!isset($stats[$typeReclamation])) {
                $stats[$typeReclamation] = 0;
            }
            $stats[$typeReclamation]++;
        }

        // Calcul des pourcentages
        foreach ($stats as $typeReclamation => $count) {
            $stats[$typeReclamation] = ($count / $totalReclamations) * 100;
        }


        return $this->render('reclamation/statReclamation.html.twig', [
            'stats' => $stats,
            'notifR' => $notification,
            'n' => $n,
            'notifA' => $notification2,

        ]);
    }



    /*************************************************************NOTIFICATION********************************************* */
    #[Route('/addNotification/{Type}', name: 'app_addNotification')]
    public function addNotification(Request $request, ManagerRegistry $emm, SessionInterface $session, EntityManagerInterface $entityManager, $Type): Response
    {
        $notification = new Notification();
        $notification->setType($Type);
        $form = $this->CreateForm(NotificationType::class, $notification);
        $form->handleRequest($request);
        $em = $emm->getManager();
        $em->persist($notification);
        $em->flush();
        if ($Type == "reclamation") {
            return $this->redirectToRoute('app_consulterReclamation');
        }

        return $this->redirectToRoute('app_consulterAvisFront');
    }

    #[Route('/notification', name: 'app_Notification')]
    function Notification(ManagerRegistry $emm)
    {

        $criteria = ['type' => 'reclamation'];
        $notification = $emm->getRepository(Notification::class)->findBy($criteria);
        $criteria2 = ['type' => 'avis'];
        $notification2 = $emm->getRepository(Notification::class)->findBy($criteria2);
        $n = $emm->getRepository(Notification::class)->count([]);
        return $this->renderView('baseBack.html.twig', ['notifR' => $notification, 'notifA' => $notification2, 'n' => $n]);
    }
    #[Route('/deleteNotification/{id_n}', name: 'app_deleteNotification')]
    public function deleteNotification(ManagerRegistry $emm, $id_n)
    {
        $em = $emm->getManager();
        $notification = $emm->getRepository(Notification::class)->find($id_n);
        $type = $notification->getType();
        $em->remove($notification);
        $em->flush();
        if ($type == "reclamation") {
            return $this->redirectToRoute('app_consulterReclamationBack');
        }
        return $this->redirectToRoute('app_consulterAvisBack');

    }

}