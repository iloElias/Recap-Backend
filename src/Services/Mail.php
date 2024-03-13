<?php

namespace Ipeweb\RecapSheets\Services;

use Exception;
use Ipeweb\RecapSheets\Bootstrap\Helper;
use PHPMailer\PHPMailer\PHPMailer;

class Mail
{
    private string $enviadoPor;

    public function __construct(string $enviadoPor)
    {
        $this->enviadoPor = $enviadoPor;
    }

    public function sendEmail(string $enviarPara, string $tituloEmail, string $corpoEmail): bool
    {
        $phpMailer = new PHPMailer();
        $googleEmail = Helper::env("GOOGLE_APP_EMAIL");
        $phpMailer->SMTPDebug = false;
        $phpMailer->isSMTP();
        $phpMailer->Host = 'smtp.gmail.com';
        $phpMailer->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $phpMailer->Port = 587;
        $phpMailer->Mailer = 'smtp';
        $phpMailer->CharSet = PHPMailer::CHARSET_UTF8;
        $phpMailer->SMTPAuth = true;
        $phpMailer->Username = $googleEmail;
        //Helper::env("GOOGLE_APP_EMAIL");
        $phpMailer->Password = Helper::env("GOOGLE_APP_PASSWORD");
        $phpMailer->setFrom($googleEmail, $this->enviadoPor);
        $phpMailer->addAddress($enviarPara);
        $phpMailer->isHTML(true);

        $phpMailer->Subject = $tituloEmail;
        $phpMailer->Body = $corpoEmail;
        return $phpMailer->send();
    }
}
