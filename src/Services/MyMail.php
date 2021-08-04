<?php


namespace App\Services;


use Swift_Mailer;
use Swift_Message;
use Swift_SmtpTransport;

class MyMail
{
    private $mailer;

    public function __construct(){
        $transport = new Swift_SmtpTransport(
            'smtp.gmail.com',
            '587',
            'tls'
        );
        $transport->setUsername('YOUR_EMAIL_HERE');
        $transport->setPassword('YOUR_PASSWORD_HERE');
        $this->mailer = new Swift_Mailer($transport);
    }

    public function send($asunto, $mailTo, $nameTo, $text)
    {
        $message = new Swift_Message($asunto);
        $message->setFrom([$mailTo=>$nameTo], 'Proyecto FINAL');
        $message->setTo('YOUR_EMAIL_HERE');
        $message->setBody($text);

        $result = $this->mailer->send($message);

        return ($result===1);
    }


}