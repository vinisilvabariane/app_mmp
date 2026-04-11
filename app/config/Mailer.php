<?php

namespace App\config;

use PHPMailer\PHPMailer\Exception as MailException;
use PHPMailer\PHPMailer\PHPMailer;
use RuntimeException;

class Mailer
{
    public function send(string $toEmail, string $toName, string $subject, string $htmlBody, string $textBody = ''): void
    {
        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host = Env::get('MAIL_HOST', 'smtp.gmail.com');
            $mail->Port = (int) Env::get('MAIL_PORT', '587');
            $mail->SMTPAuth = true;
            $mail->SMTPSecure = Env::get('MAIL_ENCRYPTION', PHPMailer::ENCRYPTION_STARTTLS);
            $mail->Username = trim(Env::get('MAIL_USERNAME'));
            $mail->Password = str_replace(' ', '', trim(Env::get('MAIL_PASSWORD')));
            $mail->CharSet = 'UTF-8';

            $fromAddress = Env::get('MAIL_FROM_ADDRESS', $mail->Username);
            $fromName = Env::get('MAIL_FROM_NAME', 'Map My Path');

            if ($mail->Username === '' || $mail->Password === '') {
                throw new RuntimeException('As credenciais de email nao foram configuradas.');
            }

            $mail->setFrom($fromAddress, $fromName);
            $mail->addAddress($toEmail, $toName);
            $mail->Subject = $subject;
            $mail->isHTML(true);
            $mail->Body = $htmlBody;
            $mail->AltBody = $textBody !== '' ? $textBody : trim(strip_tags($htmlBody));

            $mail->send();
        } catch (MailException $exception) {
            throw new RuntimeException('Falha ao enviar email: ' . $exception->getMessage(), 0, $exception);
        }
    }
}
