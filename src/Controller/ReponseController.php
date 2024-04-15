<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Reclamation;
use App\Entity\Reponse;
use App\Form\ReponseType;
use App\Repository\ReponseRepository;

class ReponseController extends AbstractController
{
    #[Route('/reponse', name: 'app_reponse')]
    public function index(): Response
    {
        return $this->render('reponse/index.html.twig', [
            'controller_name' => 'ReponseController',
        ]);
    }

    #[Route('/addReponse/{id_r}', name: 'app_addReponse')]
    public function Repondre(Request $request, ManagerRegistry $emm, $id_r): Response
    {
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
            return $this->redirectToRoute('app_consulterReclamationBack');
        }
        return $this->render('reponse/addReponse.html.twig', ['form' => $form->createView()]);
    }
    #[Route('/consulterReponse', name: 'app_consulterReponse')]
    function afficherReponse(ReponseRepository $repo)
    {
        $reponse = $repo->findAll();
        return $this->render('reponse/listReponse.html.twig', ['reponse' => $reponse]);
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
        $em = $doctrine->getManager();
        $reponse = $doctrine->getRepository(Reponse::class)->find($ref);
        $form = $this->createForm(ReponseType::class, $reponse);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $em->persist($reponse);
            $em->flush();
            return $this->redirectToRoute('app_consulterReponse');
        } else {
            return $this->renderForm('reponse/addReponse.html.twig', ['form' => $form, 'avis' => $reponse]);
        }


    }

}