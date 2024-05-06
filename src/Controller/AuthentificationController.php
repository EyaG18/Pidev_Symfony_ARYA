<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Form\UserType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AuthentificationController extends AbstractController
{
    #[Route('/register', name: 'app_authentification_register')]
    public function register(
        Request $request,
        ManagerRegistry $manager,
        SessionInterface $session,
        Filesystem $filesystem,
        UserPasswordEncoderInterface $passwordEncoder // Use password encoder interface
    ): Response {
        $user = new User();

        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $hashedPassword = $passwordEncoder->encodePassword($user, $user->getPassword());
            $user->setPassword($hashedPassword);

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
            return $this->redirectToRoute('app_front');
        }

        return $this->render('authentification/register.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
