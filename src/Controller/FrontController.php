<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FrontController extends AbstractController
{
    #[Route('/', name: 'app_front')]
    public function index(): Response
    {
        return $this->render('front/index.html.twig');
    }
    #[Route('/profile', name: 'app_front_profile')]
    public function indexProfile(): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_front');
        }

        return $this->render('front/profile.html.twig');
    }
    #[Route('/profile/edit', name: 'app_front_profile_edit')]
    public function indexProfileEdit(Request $request, ManagerRegistry $manager, UserRepository $userRepository, Filesystem $filesystem): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_front');
        }

        $user = $this->getUser();
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
            return $this->redirectToRoute('app_front_profile');
        }

        return $this->render('front/editProfile.html.twig',[
            'form' => $form->createView(),
        ]);
    }
    #[Route('/profile/changePassword', name: 'app_front_profile_edit_changePassword')]
    public function indexProfileEditChangePassword(Request $request, ManagerRegistry $manager, UserRepository $userRepository): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_front');
        }
        
        $user = $this->getUser();
        $form = $this->createFormBuilder()
        ->add('currentPassword', PasswordType::class, [
            'label' => 'Current Password',
            'mapped' => false,
        ])
        ->add('newPassword', RepeatedType::class, [
            'type' => PasswordType::class,
            'invalid_message' => 'The password fields must match.',
            'required' => true,
            'first_options' => ['label' => 'New Password'],
            'second_options' => ['label' => 'Repeat New Password'],
        ])
        ->getForm();        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em=$manager->getManager();
            $em->persist($user);
            $em->flush();
            return $this->redirectToRoute('app_front_profile');
        }
        return $this->render('front/editPassword.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
