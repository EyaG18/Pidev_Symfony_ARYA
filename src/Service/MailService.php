<?php
namespace App\Service;

use Exception;
use InvalidArgumentException;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mime\Email;

class MailService
{
    public function readHTMLFile($filename) {
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

    public function sendMail(string $recipient, string $subject, string $body){
        $transport = Transport::fromDsn('smtp://azizowski10@gmail.com:vdqfssbdwqszdyca@smtp.gmail.com:587');
        $mailer = new Mailer($transport);
        $email = (new Email());

        $email->from('azizowski10@gmail.com');
        $email->to($recipient);
        $email->subject($subject);
        $email->html($body);
        $mailer->send($email);
    }
}
