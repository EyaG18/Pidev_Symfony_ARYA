<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Reclamation;
use App\Entity\User;
use App\Repository\ReclamationRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use App\Form\ReclamationType;

class ReclamationController extends AbstractController
{  
    #[Route('/reclamation', name: 'app_reclamation')]
    public function index(): Response
    {
        return $this->render('reclamation/index.html.twig', [
            'controller_name' => 'ReclamationController',
        ]);
    }

    /********************************************FRONT****************************************************** */
    #[Route('/addReclamation/{id_c}', name: 'app_addReclamation')]
    public function addReclamation(Request $request, ManagerRegistry $emm, $id_c): Response
    {
        $user = $emm->getRepository(User::class)->find($id_c);
        $reclamation = new Reclamation();
        $reclamation->setIdClient($user);
        $form = $this->CreateForm(ReclamationType::class, $reclamation);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {


            $em = $emm->getManager();
            $em->persist($reclamation);
            $em->flush();
            return $this->render('baseFront.html.twig');
        }


        return $this->render('reclamation/addreclamation.html.twig', ['form' => $form->createView()]);
    }
    #[Route('/consulterReclamation/{id_c}', name: 'app_consulterReclamation')]
    function afficherReclamationFront(ManagerRegistry $emm, $id_c)
    {
        $criteriaNT = ['idClient' => $id_c, 'statuReclamation' => "en attente"];
        $reclamationNT = $emm->getRepository(Reclamation::class)->findBy($criteriaNT);

        $criteria = ['idClient' => $id_c, 'statuReclamation' => "traité"];
        $reclamation = $emm->getRepository(Reclamation::class)->findBy($criteria);

        return $this->render('reclamation/listReclamation.html.twig', ['reclamationT' => $reclamation, 'reclamationNT' => $reclamationNT]);
    }

    #[Route('/deleteReclamation/{id_r}', name: 'app_deleteReclamation')]
    public function delete(ManagerRegistry $emm, $id_r)
    {
        $em = $emm->getManager();
        $reclamation = $emm->getRepository(Reclamation::class)->find($id_r);
        $em->remove($reclamation);
        $em->flush();
        $this->addFlash('success', 'La reclamation a été supprimé avec succès.');
        return $this->redirectToRoute('app_test');
    }
    #[Route('/editReclamation/{ref}', name: 'app_editReclamation')]
    public function editReclamation(ManagerRegistry $doctrine, Request $request, $ref)
    {
        $em = $doctrine->getManager();
        $reclamation = $doctrine->getRepository(Reclamation::class)->find($ref);
        $form = $this->createForm(ReclamationType::class, $reclamation);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $em->persist($reclamation);
            $em->flush();
            return $this->redirectToRoute('app_test');
        } else {
            return $this->renderForm('reclamation/addReclamation.html.twig', ['form' => $form, 'reclamation' => $reclamation]);
        }


    }

    /********************************************Back****************************************************** */
    #[Route('/consulterReclamationBack', name: 'app_consulterReclamationBack')]
    function afficherReclamationFBack(ReclamationRepository $repo)
    {

        $reclamation = $repo->findAll();
        return $this->render('reclamation/listReclamationBack.html.twig', ['reclamation' => $reclamation]);
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

}