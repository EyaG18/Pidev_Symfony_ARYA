<?php
// src/Service/MessageGenerator.php
namespace App\Service;

use Twilio\Rest\Client;



class SmsGenerator
{
    
    public function SendSms(string $number, string $name, string $text)
    {
        
       
        $accountSid = $_ENV['TWILIO_ACCOUNT_SID_P'];
        $authToken = $_ENV['TWILIO_AUTH_TOKEN_P'];
        $fromNumber = $_ENV['TWILIO_FROM_NUMBER_P'];
        
        $toNumber = $number; // Le numéro de la personne qui reçoit le message
        $message = ''.$name.' Eya Gadhoumi'.' '.$text.''; //Contruction du sms

    
        //Client Twilio pour la création et l'envoie du sms
        $client = new Client($accountSid, $authToken);

        $client->messages->create(
            $toNumber,
            [
               'from' => $fromNumber,
                'body' => $message,
            ]
        );


    }
}