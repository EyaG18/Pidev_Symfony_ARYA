<?php

namespace App\Service;

use Exception;
use Twilio\Rest\Client;

class TwilioService
{
    private $twilioClient;
    private $twilioPhoneNumber;

    public function __construct(string $accountSid, string $authToken, string $twilioPhoneNumber)
    {
        $this->twilioClient = new Client($accountSid, $authToken);
        $this->twilioPhoneNumber = $twilioPhoneNumber;
    }

    public function sendSMS(string $recipient, string $message): bool
    {
        try {
            $this->twilioClient->messages->create(
                $recipient,
                [
                    'from' => $this->twilioPhoneNumber,
                    'body' => $message,
                ]
            );

            return true; // Message sent successfully
        } catch (Exception $e) {
            return false; // Failed to send message
        }
    }
}
