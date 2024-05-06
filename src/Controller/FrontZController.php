<?php

namespace App\Controller;

use App\Repository\OffreRepository;
use App\Repository\ProduitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mailer\Transport\Smtp\Auth\LoginPassword;
use Symfony\Component\Mailer\Transport\Smtp\EsmtpTransport;
use Symfony\Component\Routing\Annotation\Route;

class FrontZController extends AbstractController
{
    #[Route('/', name: 'app_front')]
    public function index(): Response
    {
        return $this->render('front/index.html.twig');
    }
    #[Route('/produit', name: 'app_front_produit')]
    public function indexProduit(ProduitRepository $produitRepository): Response
    {

        return $this->render('front/produit.html.twig', [
            'items' => $produitRepository->findAll()
        ]);
    }
    #[Route('/offres', name: 'app_front_offres')]
    public function indexOffre(OffreRepository $OffreRepository): Response
    {

        return $this->render('front/Offre.html.twig', [
            'items' => $OffreRepository->findAll()
        ]);
    }


}