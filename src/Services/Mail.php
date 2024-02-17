<?php

namespace Ipeweb\IpeSheets\Services;

use Exception;
use Ipeweb\IpeSheets\Bootstrap\Helper;
use PHPMailer\PHPMailer\PHPMailer;

class Mail
{
    private $enviadoPor;

    public function __construct(string $enviadoPor)
    {
        $this->enviadoPor = $enviadoPor;
    }

    public function sendEmail(string $enviarPara, string $tituloEmail, string $corpoEmail): bool
    {
        $mail = new PHPMailer();

        try {
            $googleEmail = Helper::env("GOOGLE_APP_EMAIL");

            $mail->SMTPDebug = false;
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;
            $mail->Mailer = 'smtp';
            $mail->CharSet = PHPMailer::CHARSET_UTF8;

            $mail->SMTPAuth = true;

            $mail->Username = $googleEmail;
            $mail->Password = Helper::env("GOOGLE_APP_PASSWORD");

            $mail->setFrom($googleEmail, $this->enviadoPor);
            $mail->addAddress($enviarPara);

            $mail->isHTML(true);
            $mail->Subject = $tituloEmail;
            $mail->Body = $corpoEmail;

            return $mail->send();
        } catch (Exception $e) {
            throw $e;
        }
    }
}
