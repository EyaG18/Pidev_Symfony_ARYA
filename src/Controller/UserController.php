<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{

    #[Route('/back/users', name: 'app_back_userList')]
    public function UserList(UserRepository $userRepository,SessionInterface $session): Response
    {
        if(!$session->get("user_id"))
            return $this->redirectToRoute('app_front');
        $user = $userRepository->find($session->get("user_id"));

        if($user->getRole() != "administrateur")
            return $this->redirectToRoute('app_front');

        $users = $userRepository->findAll();

        return $this->render('back/User/userAll.html.twig', [
            'users' => $users,
            'currentUser' =>$user
        ]);
    }
    #[Route('/back/user/edit/{id}', name: 'app_back_editUser')]
    public function editUser(Request $request, ManagerRegistry $manager,int $id, UserRepository $userRepository,SessionInterface $session): Response
    {
        if(!$session->get("user_id"))
            return $this->redirectToRoute('app_front');
        $user = $userRepository->find($session->get("user_id"));

        if($user->getRole() != "administrateur")
            return $this->redirectToRoute('app_front');

        $user = $userRepository->find($id);
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em=$manager->getManager();
            $em->persist($user);
            $em->flush();
            return $this->redirectToRoute('app_back_userList');
        }

        return $this->render('back/User/add.html.twig', [
            'form' => $form->createView(),
            'currentUser' =>$user
        ]);
    }

    #[Route('/back/user/delete/{id}', name: 'app_back_deleteUser')]
    public function deleteUser(int $id,UserRepository $userRepository,ManagerRegistry $manager,SessionInterface $session): Response
    {
        if(!$session->get("user_id"))
            return $this->redirectToRoute('app_front');
        $user = $userRepository->find($session->get("user_id"));

        if($user->getRole() != "administrateur")
            return $this->redirectToRoute('app_front');

        $user = $userRepository->find($id);
        $em=$manager->getManager();
        $em->remove($user);
        $em->flush();
        return $this->redirectToRoute('app_back_userList');
    }

    #[Route('/back/user/add', name: 'app_back_addUser')]
    public function addUser(Request $request, ManagerRegistry $manager,SessionInterface $session,UserRepository $userRepository): Response
    {
        if(!$session->get("user_id"))
            return $this->redirectToRoute('app_front');
        $user = $userRepository->find($session->get("user_id"));

        if($user->getRole() != "administrateur")
            return $this->redirectToRoute('app_front');

        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em=$manager->getManager();
            $em->persist($user);
            $em->flush();
            return $this->redirectToRoute('app_back_userList');
        }

        return $this->render('back/User/add.html.twig', [
            'form' => $form->createView(),
            'currentUser' =>$user
        ]);
    }
}
