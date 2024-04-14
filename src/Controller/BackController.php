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
        if(!$session->get("user_id"))
            return $this->redirectToRoute('app_front');
       
        $user = $userRepository->find($session->get("user_id"));

        if($user->getRole() != "administrateur")
            return $this->redirectToRoute('app_front');

        $twig = $this->container->get('twig');
        $twig->addGlobal('doctrine', $entityManager);

        return $this->render('back/index.html.twig', [
            'currentUser' =>$user
        ]);
    }
}
