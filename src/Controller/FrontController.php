<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FrontController extends AbstractController
{
    #[Route('/', name: 'app_front')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        
        $twig = $this->container->get('twig');
        $twig->addGlobal('doctrine', $entityManager);

        return $this->render('front/index.html.twig');
    }
}
