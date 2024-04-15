<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ProduitRepository;

class TestController extends AbstractController
{
    #[Route('/baseFront', name: 'app_test')]
    public function index(ProduitRepository $repo): Response
    {

        $prod = $repo->findAll();
        return $this->render('baseFront.html.twig', ['prod' => $prod])

        ;
    }

    #[Route('/baseBack', name: 'app_test2')]
    public function index2(): Response
    {
        return $this->render('baseBack.html.twig')

        ;
    }

    #[Route('/testAvis', name: 'app_Avis')]
    public function index3(): Response
    {
        return $this->render('avis/addAvis.html.twig')

        ;
    }
}