<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Reclamation;
use App\Entity\Reponse;
use App\Entity\Notification;
use App\Form\ReponseType;
use App\Repository\ReponseRepository;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mime\Email;
use App\Service\MailSender;
use App\Controller\EntityManagerInterface;
use App\Repository\NotificationRepository;



class ReponseController extends AbstractController
{
    #[Route('/reponse', name: 'app_reponse')]
    public function index(ManagerRegistry $emm): Response
    {
        $criteria = ['type' => 'reclamation'];
        $notification = $emm->getRepository(Notification::class)->findBy($criteria);
        $criteria2 = ['type' => 'avis'];
        $notification2 = $emm->getRepository(Notification::class)->findBy($criteria2);
        $n = $emm->getRepository(Notification::class)->count([]);
        return $this->render('reclamation/statReclamation.html.twig', [
            'notifR' => $notification,
            'n' => $n,
            'notifA' => $notification2,
        ]);
    }

    #[Route('/addReponse/{id_r}', name: 'app_addReponse')]
    public function Repondre(Request $request, ManagerRegistry $emm, $id_r, MailSender $mailSender): Response
    {
        $criteria = ['type' => 'reclamation'];
        $notification = $emm->getRepository(Notification::class)->findBy($criteria);
        $criteria2 = ['type' => 'avis'];
        $notification2 = $emm->getRepository(Notification::class)->findBy($criteria2);
        $n = $emm->getRepository(Notification::class)->count([]);


        $reclamation = $emm->getRepository(Reclamation::class)->find($id_r);
        $reponse = new Reponse();
        $reponse->setIdReclamation($reclamation);
        $form = $this->CreateForm(ReponseType::class, $reponse);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $rep = $reponse->getIdReclamation();
            if ($rep instanceof Reclamation) {
                $rep->setStatuReclamation("traité");
            }

            $em = $emm->getManager();
            $em->persist($reponse);
            $em->flush();

            $user = $reclamation->getIdClient();
            $userEmail = $user->getEmailusr();
            $mailSender->sendEmail($userEmail, 'Arya : Reponse sur Reclamation ', $form->get('reponse')->getData());



            return $this->redirectToRoute('app_consulterReclamationBack');
        }
        return $this->render('reponse/addReponse.html.twig', ['form' => $form->createView(), 'notifR' => $notification, 'n' => $n, 'notifA' => $notification2]);
    }
    #[Route('/consulterReponse', name: 'app_consulterReponse')]
    function afficherReponse(ReponseRepository $repo, ManagerRegistry $emm)
    {
        $criteria = ['type' => 'reclamation'];
        $notification = $emm->getRepository(Notification::class)->findBy($criteria);
        $criteria2 = ['type' => 'avis'];
        $notification2 = $emm->getRepository(Notification::class)->findBy($criteria2);
        $n = $emm->getRepository(Notification::class)->count([]);
        $reponse = $repo->findAll();
        return $this->render('reponse/listReponse.html.twig', ['reponse' => $reponse, 'notifR' => $notification, 'n' => $n, 'notifA' => $notification2]);
    }
    #[Route('/deleteReponse/{id_r}', name: 'app_deleteReponse')]
    public function deleteReponse(ManagerRegistry $emm, $id_r)
    {
        $em = $emm->getManager();
        $reclamation = $emm->getRepository(Reponse::class)->find($id_r);
        $em->remove($reclamation);
        $em->flush();
        return $this->redirectToRoute('app_consulterReponse');
    }

    #[Route('/editReponse/{ref}', name: 'app_editReponse')]
    public function editReponse(ManagerRegistry $doctrine, Request $request, $ref)
    {
        $criteria = ['type' => 'reclamation'];
        $notification = $doctrine->getRepository(Notification::class)->findBy($criteria);
        $criteria2 = ['type' => 'avis'];
        $notification2 = $doctrine->getRepository(Notification::class)->findBy($criteria2);
        $n = $doctrine->getRepository(Notification::class)->count([]);
        $em = $doctrine->getManager();
        $reponse = $doctrine->getRepository(Reponse::class)->find($ref);
        $form = $this->createForm(ReponseType::class, $reponse);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $em->persist($reponse);
            $em->flush();
            return $this->redirectToRoute('app_consulterReponse');
        } else {
            return $this->renderForm('reponse/addReponse.html.twig', ['form' => $form, 'avis' => $reponse, 'notifR' => $notification, 'n' => $n, 'notifA' => $notification2]);
        }


    }


}