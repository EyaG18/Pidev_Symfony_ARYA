<?php

namespace App\Controller;

use App\Entity\Offre;
use App\Form\OffreType;
use App\Repository\OffreRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Notification;


class OffreController extends AbstractController
{
    #[Route('/back/Offre', name: 'app_back_Offre')]
    public function index(Request $request, OffreRepository $OffreRepository, ManagerRegistry $emm): Response
    {
        $criteria = ['type' => 'reclamation'];
        $notification = $emm->getRepository(Notification::class)->findBy($criteria);
        $criteria2 = ['type' => 'avis'];
        $notification2 = $emm->getRepository(Notification::class)->findBy($criteria2);
        $n = $emm->getRepository(Notification::class)->count([]);

        $searchQuery = $request->query->get('search');
        $searchBy = $request->query->get('search_by', 'idoffre');

        $sortBy = $request->query->get('sort_by', 'idoffre');
        $sortOrder = $request->query->get('sort_order', 'asc');

        $items = $OffreRepository->findBySearchAndSort($searchBy, $searchQuery, $sortBy, $sortOrder);

        return $this->render('back/Offre/index.html.twig', [
            "items" => $items,
            'notifR' => $notification,
            'n' => $n,
            'notifA' => $notification2
        ]);
    }
    #[Route('/back/Offre/add', name: 'app_back_Offre_add')]
    public function add(Request $request, ManagerRegistry $mr): Response
    {

        $criteria = ['type' => 'reclamation'];
        $notification = $mr->getRepository(Notification::class)->findBy($criteria);
        $criteria2 = ['type' => 'avis'];
        $notification2 = $mr->getRepository(Notification::class)->findBy($criteria2);
        $n = $mr->getRepository(Notification::class)->count([]);

        $Offre = new Offre();
        $form = $this->createForm(OffreType::class, $Offre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $mr->getManager();
            $em->persist($Offre);
            $em->flush();
            return $this->redirectToRoute('app_back_Offre');
        }

        return $this->render('back/Offre/form.html.twig', [
            'form' => $form->createView(),
            'notifR' => $notification,
            'n' => $n,
            'notifA' => $notification2
        ]);
    }
    #[Route('/back/Offre/update/{id}', name: 'app_back_Offre_update')]
    public function update(Request $request, ManagerRegistry $mr, $id, OffreRepository $OffreRepository): Response
    {

        $criteria = ['type' => 'reclamation'];
        $notification = $mr->getRepository(Notification::class)->findBy($criteria);
        $criteria2 = ['type' => 'avis'];
        $notification2 = $mr->getRepository(Notification::class)->findBy($criteria2);
        $n = $mr->getRepository(Notification::class)->count([]);

        $Offre = $OffreRepository->find($id);
        $form = $this->createForm(OffreType::class, $Offre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $mr->getManager();
            $em->persist($Offre);
            $em->flush();
            return $this->redirectToRoute('app_back_Offre');
        }

        return $this->render('back/Offre/form.html.twig', [
            'form' => $form->createView(),
            'notifR' => $notification,
            'n' => $n,
            'notifA' => $notification2
        ]);
    }

    #[Route('/back/Offre/delete/{id}', name: 'app_back_Offre_delete')]
    public function delete(OffreRepository $OffreRepository, int $id, ManagerRegistry $mr): Response
    {
        $Offre = $OffreRepository->find($id);
        $entityManager = $mr->getManager();
        $entityManager->remove($Offre);
        $entityManager->flush();

        return $this->redirectToRoute('app_back_Offre');
    }
}