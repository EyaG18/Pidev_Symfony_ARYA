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
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AuthentificationController extends AbstractController
{
    #[Route('/logout', name: 'app_authentification_logout')]
    public function logout(SessionInterface $session): Response
    {
        $session->invalidate();
        return $this->redirectToRoute('app_front');
    }
    #[Route('/register', name: 'app_authentification_register')]
    public function register(Request $request,ManagerRegistry $manager, SessionInterface $session): Response
    {
        if($session->get("user_id"))
        {
            return $this->redirectToRoute('app_front');
        }
        $user = new User();

        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $em=$manager->getManager();
            $em->persist($user);
            $em->flush();
            $session->set('user_id', $user->getIdUser());
            if($user->getRole() == "administrateur")
                return $this->redirectToRoute('app_back');
            else
                return $this->redirectToRoute('app_front');
        }

        return $this->render('authentification/register.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    #[Route('/login', name: 'app_authentification_login')]
    public function login(Request $request,UserRepository $userRepository, SessionInterface $session): Response
    {
        if($session->get("user_id"))
        {
            return $this->redirectToRoute('app_front');
        }
        $user = new User();
    
        $form = $this->createFormBuilder($user)
            ->add('emailusr', EmailType::class, [
                'attr' => ['class' => 'form-control form-control-user'],
                'label' => 'Email Address',
            ])
            ->add('password', PasswordType::class, [
                'attr' => ['class' => 'form-control form-control-user'],
                'label' => 'Password',
            ])
            ->add('login', SubmitType::class, [
                'label' => 'Login',
                'attr' => ['class' => 'btn btn-primary btn-user btn-block mt-4'],
            ])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $email = $form->get('emailusr')->getData();
            $password = $form->get('password')->getData();
            
            $user = $userRepository->findOneBy(['emailusr' => $email]);
            
            if ($user) {
                $session->set('user_id', $user->getIdUser());
                if($user->getRole() == "administrateur")
                    return $this->redirectToRoute('app_back');
                else
                    return $this->redirectToRoute('app_front');
            } else {
                $this->addFlash('error', 'Invalid email or password.');
            }
        }

        return $this->render('authentification/Login.html.twig',[
            "form"=> $form->createView(),
        ]);
    }
}
