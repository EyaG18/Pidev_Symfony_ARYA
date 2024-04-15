<?php

namespace App\Controller;

use App\Entity\Offre;
use App\Form\OffreType;
use App\Repository\OffreRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/listoffrefront', name: 'app_offres_index')]
class OffrefrontController extends AbstractController
{
    
    public function index(OffreRepository $offreRepository): Response
    {
        return $this->render('offres/index.html.twig', [
            'offres' => $offreRepository->findAll(),
        ]);
    }
}
