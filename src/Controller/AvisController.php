<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\AvisRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Catégorie;
use App\Entity\Avis;
use App\Entity\User;
use App\Entity\Produit;
use App\Entity\Notification;
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
    function afficherReclamationFBack(AvisRepository $repo, ManagerRegistry $emm)
    {
        $criteria = ['type' => 'reclamation'];
        $notification = $emm->getRepository(Notification::class)->findBy($criteria);
        $criteria2 = ['type' => 'avis'];
        $notification2 = $emm->getRepository(Notification::class)->findBy($criteria2);
        $n = $emm->getRepository(Notification::class)->count([]);


        $avis = $repo->findAll();
        return $this->render('avis/listAvisBack.html.twig', ['avis' => $avis, 'notifR' => $notification, 'n' => $n, 'notifA' => $notification2]);
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
    #[Route('/consulterAvisFront/{id_p}', name: 'app_consulterAvisFront')]
    function afficherReclamationFront(AvisRepository $repo, EntityManagerInterface $entityManager, $id_p)
    {
        $listCategories = $entityManager->getRepository(Catégorie::class)->findAll();
        $avis = $repo->findBy(['idProduit' => $id_p]);
        return $this->render('avis/listAvisFront.html.twig', ['avis' => $avis, 'cate' => $listCategories, 'id_p' => $id_p]);

    }

    #[Route('/addAvis/{idp}', name: 'app_addAvis')]
    public function addReclamation(Request $request, ManagerRegistry $emm, $idp, EntityManagerInterface $entityManager): Response
    {
        $idUser = $this->getUser();
        $listCategories = $entityManager->getRepository(Catégorie::class)->findAll();
        $produit = $emm->getRepository(Produit::class)->find($idp);
        $user = $emm->getRepository(User::class)->find($idUser);
        $avis = new Avis();


        $avis->setIdProduit($produit);
        $avis->setIdClient($user);

        $form = $this->CreateForm(AvisType::class, $avis);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {


            $em = $emm->getManager();
            $em->persist($avis);
            $em->flush();
            return $this->render('base.html.twig', ['cate' => $listCategories]);
        }
        return $this->render('avis/addAvis.html.twig', ['form' => $form->createView(), 'cate' => $listCategories, 'idp' => $idp,]);
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
    public function editAvis(ManagerRegistry $doctrine, Request $request, $id_a, EntityManagerInterface $entityManager)
    {
        $listCategories = $entityManager->getRepository(Catégorie::class)->findAll();
        $em = $doctrine->getManager();
        $avis = $doctrine->getRepository(Avis::class)->find($id_a);
        $form = $this->createForm(AvisType::class, $avis);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $em->persist($avis);
            $em->flush();
            return $this->redirectToRoute('app_consulterAvisFront');
        } else {
            return $this->renderForm('avis/addAvis.html.twig', ['form' => $form, 'avis' => $avis, 'cate' => $listCategories]);
        }


    }
}