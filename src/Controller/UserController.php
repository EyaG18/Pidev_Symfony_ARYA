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
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class UserController extends AbstractController
{

    #[Route('/back/users', name: 'app_back_userList')]
    public function UserList(Request $request,UserRepository $userRepository,SessionInterface $session): Response
    {
        $searchQuery = $request->query->get('search');
        $searchBy = $request->query->get('search_by', 'idUser');

        $sortBy = $request->query->get('sort_by', 'idUser');
        $sortOrder = $request->query->get('sort_order', 'asc');

        $items = $userRepository->findBySearchAndSort($searchBy,$searchQuery, $sortBy, $sortOrder);

        return $this->render('back/User/userAll.html.twig', [
            'users' => $items,
            
        ]);
    }
    #[Route('/back/user/edit/{id}', name: 'app_back_editUser')]
    public function editUser(Request $request, ManagerRegistry $manager,int $id, UserRepository $userRepository,SessionInterface $session, Filesystem $filesystem): Response
    {
        

        $user = $userRepository->find($id);
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $image = $form->get('image')->getData();
            if ($image) {
                $originalFilename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
                $newFilename = $originalFilename.'-'.uniqid().'.'.$image->guessExtension();
                
                // Move the file to the public directory
                try {
                    $filesystem->copy(
                        $image->getPathname(),
                        $this->getParameter('uploads_directory')."/uploaded/".$newFilename
                    );
                } catch (FileException $e) {
                    // Handle file upload error
                    // e.g. return some error response
                }
    
                // Update the user entity with the filename
                $user->setImage($newFilename);
            }
            $em=$manager->getManager();
            $em->persist($user);
            $em->flush();
            return $this->redirectToRoute('app_back_userList');
        }

        return $this->render('back/User/add.html.twig', [
            'form' => $form->createView(),
            
        ]);
    }

    #[Route('/back/user/delete/{id}', name: 'app_back_deleteUser')]
    public function deleteUser(int $id,UserRepository $userRepository,ManagerRegistry $manager,SessionInterface $session): Response
    {
        $user = $userRepository->find($id);
        $em=$manager->getManager();
        $em->remove($user);
        $em->flush();
        return $this->redirectToRoute('app_back_userList');
    }

    #[Route('/back/user/add', name: 'app_back_addUser')]
    public function addUser(Request $request, ManagerRegistry $manager,SessionInterface $session,UserRepository $userRepository , Filesystem $filesystem): Response
    {
        

        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $image = $form->get('image')->getData();
            if ($image) {
                $originalFilename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
                $newFilename = $originalFilename.'-'.uniqid().'.'.$image->guessExtension();
                
                // Move the file to the public directory
                try {
                    $filesystem->copy(
                        $image->getPathname(),
                        $this->getParameter('uploads_directory')."/uploaded/".$newFilename
                    );
                } catch (FileException $e) {
                    // Handle file upload error
                    // e.g. return some error response
                }
    
                // Update the user entity with the filename
                $user->setImage($newFilename);
            }
            $em=$manager->getManager();
            $em->persist($user);
            $em->flush();
            return $this->redirectToRoute('app_back_userList');
        }

        return $this->render('back/User/add.html.twig', [
            'form' => $form->createView(),
            
        ]);
    }
}
