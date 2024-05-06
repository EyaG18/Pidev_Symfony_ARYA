<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use App\Entity\Produit;
use App\Service\SmsGenerator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

#[AsCommand(
    name: 'SuiviStock',
    description: 'Add a short description for your command',
)]
class SuiviStockCommand extends Command
{
    private $smsGenerator;
    private $entityManager;

    public function __construct(SmsGenerator $smsGenerator, EntityManagerInterface $entityManager)
    {
        parent::__construct();

        $this->smsGenerator = $smsGenerator;
        $this->entityManager = $entityManager;
    }

    protected function configure(): void
    {
        $this
            ->setName('SuiviStock')
            ->setDescription('Surveille le stock des produits et envoie une notification si nécessaire');
    }
   /* protected function execute(InputInterface $input, OutputInterface $output): int
    {
        // Récupérez les produits depuis la base de données (vous pouvez adapter cette partie)
        $produits = $this->entityManager->getRepository(Produit::class)->findAll();
    
        $productsOutOfStock = [];
    
        foreach ($produits as $produit) {
            $quantiteStock = $produit->getQtep();
            $quantiteSeuil = $produit->getQteseuilp();
    
            if ($quantiteStock <= $quantiteSeuil) {
                $productsOutOfStock[] = $produit;
            }
        }
    
        if (!empty($productsOutOfStock)) {
            $message = 'Attention ! Les produits suivants sont en rupture de stock :';
            foreach ($productsOutOfStock as $product) {
                $message .= sprintf("\n- %s : %d unités restantes", $product->getNomp(), $product->getQtep());
            }
    
            // Remplacez le numéro de téléphone par celui du gestionnaire
            $numeroGestionnaire = '+21623067230';
    
            // Envoi du SMS via Twilio
            $this->smsGenerator->SendSms($numeroGestionnaire, 'Gestionnaire', $message);
        }
    
        $output->writeln('Suivi du stock terminé.');
    
        return Command::SUCCESS;
    }*/































    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        // Récupérez les produits depuis la base de données (vous pouvez adapter cette partie)
        $produits = $this->entityManager->getRepository(Produit::class)->findAll();
    
        $productsOutOfStock = [];
    
        foreach ($produits as $produit) {
            $quantiteStock = $produit->getQtep();
            $quantiteSeuil = $produit->getQteseuilp();
    
            if ($quantiteStock <= $quantiteSeuil) {
                $productsOutOfStock[] = $produit;
            }
        }
    
        if (!empty($productsOutOfStock)) {
            $message = 'Attention ! Les produits suivants sont en rupture de stock :';
            foreach ($productsOutOfStock as $product) {
                $message .= sprintf("\n- %s : %d unités restantes", $product->getNomp(), $product->getQtep());
            }
    
            // Remplacez le numéro de téléphone par celui du gestionnaire
            $numeroGestionnaire = '+21623067230';
    
            // Envoi du SMS via Twilio
            $this->smsGenerator->SendSms($numeroGestionnaire, 'Gestionnaire', $message);
        }
        $output->writeln('Suivi du stock terminé.');
        return Command::SUCCESS;
    }
    
    /*protected function execute(InputInterface $input, OutputInterface $output): int
    {
        // Récupérez les produits depuis la base de données (vous pouvez adapter cette partie)
        $produits = $this->entityManager->getRepository(Produit::class)->findAll();

        foreach ($produits as $produit) {
            $quantiteStock = $produit->getQtep();
            $quantiteSeuil = $produit-> getQteseuilp();

            var_dump('eyaaaaa');
            if ($quantiteStock  <=$quantiteSeuil) {
                // Stock insuffisant, envoyez une notification au gestionnaire
                $message = sprintf(
                    'Attention ! Le stock du produit "%s" est faible. Actuellement, il ne reste que %d unités en stock.',
                    $produit->getNomp(),
                    $quantiteStock
                );
           
                echo("ena f west envoi des tests");
                // Remplacez le numéro de téléphone par celui du gestionnaire
                $numeroGestionnaire = '+21623067230';

                // Envoi du SMS via Twilio
                $this->smsGenerator->SendSms($numeroGestionnaire, 'Gestionnaire', $message);
            }
        }

        $output->writeln('Suivi du stock terminé.');

        return Command::SUCCESS;
    }*/




}
