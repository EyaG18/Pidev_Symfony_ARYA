<?php

namespace App\Controller;

use App\Entity\Fournisseur;
use App\Form\FournisseurType;
use App\Repository\FournisseurRepository;
use Doctrine\Persistence\ManagerRegistry;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Builder\BuilderInterface;
use Exception;
use InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Notification;


class FournisseurController extends AbstractController
{
    #[Route('/back/Fournisseur', name: 'app_back_Fournisseur')]
    public function index(Request $request, FournisseurRepository $FournisseurRepository, ManagerRegistry $emm): Response
    {
        $criteria = ['type' => 'reclamation'];
        $notification = $emm->getRepository(Notification::class)->findBy($criteria);
        $criteria2 = ['type' => 'avis'];
        $notification2 = $emm->getRepository(Notification::class)->findBy($criteria2);
        $n = $emm->getRepository(Notification::class)->count([]);


        $qrBuilder = new Builder();
        $searchQuery = $request->query->get('search');
        $searchBy = $request->query->get('search_by', 'idFournisseur');

        $sortBy = $request->query->get('sort_by', 'idFournisseur');
        $sortOrder = $request->query->get('sort_order', 'asc');

        $items = $FournisseurRepository->findBySearchAndSort($searchBy, $searchQuery, $sortBy, $sortOrder);

        $qrCodes = [];

        foreach ($items as $item) {
            $qrData = json_encode([
                'ID' => $item->getIdFournisseur(),
                'Nom' => $item->getNomFournisseur(),
                'Numero' => $item->getNumFournisseur(),
                'Adresse' => $item->getAdresseFournisseur(),
            ]);


            $qrResult = $qrBuilder
                ->size(200)
                ->margin(20)
                ->data($qrData)
                ->build();

            $qrCode = $qrResult->getDataUri();

            $qrCodes[] = $qrCode;

        }

        return $this->render('back/Fournisseur/index.html.twig', [
            "items" => $items,
            "qrCodes" => $qrCodes,
            'notifR' => $notification,
            'n' => $n,
            'notifA' => $notification2,

        ]);
    }

    #[Route('/back/Fournisseur/update/{id}', name: 'app_back_Fournisseur_update')]
    public function update(Request $request, ManagerRegistry $mr, $id, FournisseurRepository $FournisseurRepository): Response
    {
        $criteria = ['type' => 'reclamation'];
        $notification = $mr->getRepository(Notification::class)->findBy($criteria);
        $criteria2 = ['type' => 'avis'];
        $notification2 = $mr->getRepository(Notification::class)->findBy($criteria2);
        $n = $mr->getRepository(Notification::class)->count([]);
        $Fournisseur = $FournisseurRepository->find($id);
        $form = $this->createForm(FournisseurType::class, $Fournisseur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $mr->getManager();
            $em->persist($Fournisseur);
            $em->flush();
            return $this->redirectToRoute('app_back_Fournisseur');
        }

        return $this->render('back/Fournisseur/form.html.twig', [
            'form' => $form->createView(),
            'notifR' => $notification,
            'n' => $n,
            'notifA' => $notification2,
        ]);
    }

    #[Route('/back/Fournisseur/delete/{id}', name: 'app_back_Fournisseur_delete')]
    public function delete(FournisseurRepository $FournisseurRepository, int $id, ManagerRegistry $mr): Response
    {
        $Fournisseur = $FournisseurRepository->find($id);
        $entityManager = $mr->getManager();
        $entityManager->remove($Fournisseur);
        $entityManager->flush();

        return $this->redirectToRoute('app_back_Fournisseur');
    }

    #[Route('/fournisseur/add', name: 'app_back_Fournisseur_add')]
    public function add(Request $request, ManagerRegistry $mr): Response
    {
        $Fournisseur = new Fournisseur();
        $form = $this->createForm(FournisseurType::class, $Fournisseur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $mr->getManager();
            $em->persist($Fournisseur);
            $em->flush();
            return $this->redirectToRoute('app_front');
        }

        return $this->render('front/Fournisseur.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    public function readHTMLFile($filename)
    {
        if (empty($filename)) {
            throw new InvalidArgumentException("Filename cannot be empty.");
        }

        $filePath = realpath(__DIR__ . '/../../public/' . $filename);


        if (!file_exists($filePath)) {
            throw new Exception("File not found: $filename");
        }

        $fileContent = file_get_contents($filePath);

        if ($fileContent === false) {
            throw new Exception("Failed to read HTML file: $filename");
        }

        return $fileContent;
    }

    public function sendMail(string $recipient, string $subject, string $body)
    {
        $transport = Transport::fromDsn('smtp://bouzouitayassine@gmail.com:yumuaxedpbgicmdu@smtp.gmail.com:587');
        $mailer = new Mailer($transport);
        $email = (new Email());

        $email->from('bouzouitayassine@gmail.com');
        $email->to($recipient);
        $email->subject($subject);
        $email->html($body);
        $mailer->send($email);
    }

    #[Route('/back/Fournisseur/accepter/{id}', name: 'app_back_Fournisseur_accepter')]
    public function accepter(Request $request, ManagerRegistry $mr, int $id, FournisseurRepository $FournisseurRepository)
    {
        $Fournisseur = $FournisseurRepository->find($id);
        $recipient = 'aziz.zgolli@esprit.tn';
        $subject = 'Fournisseur Accepter';
        $htmlContent = $this->readHTMLFile('mail/notification/SUCCESS.html');
        $htmlContent = str_replace("{Title}", "Fournisseur Accepter", $htmlContent);
        $htmlContent = str_replace(
            "{Description}",
            "Nom: " . $Fournisseur->getNomFournisseur() . "<br>" .
            "Numero: " . $Fournisseur->getNumFournisseur() . "<br>" .
            "Adresse: " . $Fournisseur->getAdresseFournisseur() . "<br>"

            ,
            $htmlContent
        );

        $this->sendMail($recipient, $subject, $htmlContent);

        return $this->redirectToRoute('app_back_Fournisseur');

    }
}