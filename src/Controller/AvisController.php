<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\AvisRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Avis;
use App\Entity\User;
use App\Entity\Produit;
use App\Form\AvisType;


class AvisController extends AbstractController
{
    #[Route('/avis', name: 'app_avis')]
    public function index(): Response
    {
        return $this->render('avis/index.html.twig', [
            'controller_name' => 'AvisController',
        ]);
    }

    #[Route('/consulterAvisBack', name: 'app_consulterAvisBack')]
    function afficherReclamationFBack(AvisRepository $repo)
    {

        $avis = $repo->findAll();
        return $this->render('avis/listAvisBack.html.twig', ['avis' => $avis]);
    }
    #[Route('/deleteAvisnBack/{id_a}', name: 'app_deleteAvisBack')]
    public function deleteBack(ManagerRegistry $emm, $id_a)
    {
        $em = $emm->getManager();
        $avis = $emm->getRepository(Avis::class)->find($id_a);
        $em->remove($avis);
        $em->flush();
        return $this->redirectToRoute('app_consulterAvisBack');
    }
    /*************************************FRONT******************************** */
    #[Route('/consulterAvisFront', name: 'app_consulterAvisFront')]
    function afficherReclamationFront(AvisRepository $repo)
    {

        $avis = $repo->findAll();
        return $this->render('avis/listAvisFront.html.twig', ['avis' => $avis]);

    }

    #[Route('/addAvis/{idp}', name: 'app_addAvis')]
    public function addReclamation(Request $request, ManagerRegistry $emm, $idp): Response
    {
        $id_c = 10;
        $criteria = ['idUser' => $id_c];
        $produit = $emm->getRepository(Produit::class)->find($idp);
        $user = $emm->getRepository(User::class)->find($id_c);
        $avis = new Avis();


        $avis->setIdProduit($produit);
        $avis->setIdClient($user);
        $form = $this->CreateForm(AvisType::class, $avis);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {


            $em = $emm->getManager();
            $em->persist($avis);
            $em->flush();
            return $this->render('baseFront.html.twig');
        }
        return $this->render('avis/addAvis.html.twig', ['form' => $form->createView()]);
    }

    #[Route('/deleteAvisnFront/{id_a}', name: 'app_deleteAvisFront')]
    public function deleteFront(ManagerRegistry $emm, $id_a)
    {
        $em = $emm->getManager();
        $avis = $emm->getRepository(Avis::class)->find($id_a);
        $em->remove($avis);
        $em->flush();
        return $this->redirectToRoute('app_consulterAvisFront');
    }
    #[Route('/editAvis/{id_a}', name: 'app_editAvis')]
    public function editAvis(ManagerRegistry $doctrine, Request $request, $id_a)
    {
        $em = $doctrine->getManager();
        $avis = $doctrine->getRepository(Avis::class)->find($id_a);
        $form = $this->createForm(AvisType::class, $avis);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $em->persist($avis);
            $em->flush();
            return $this->redirectToRoute('app_consulterAvisFront');
        } else {
            return $this->renderForm('avis/addAvis.html.twig', ['form' => $form, 'avis' => $avis]);
        }


    }
}