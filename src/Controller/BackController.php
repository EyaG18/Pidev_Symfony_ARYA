<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class BackController extends AbstractController
{

    #[Route('/back', name: 'app_back')]
    public function index(UserRepository $userRepository,SessionInterface $session,EntityManagerInterface $entityManager): Response
    {
        return $this->render('back/index.html.twig');
    }
}
