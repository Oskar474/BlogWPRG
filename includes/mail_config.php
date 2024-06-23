<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';

function send_reset_email($email, $token) {
    $mail = new PHPMailer(true);
    try {

        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = '';
        $mail->Password = '';
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;


        $mail->setFrom('', 'Blog');
        $mail->addAddress($email);

        $mail->isHTML(true);
        $mail->Subject = 'Reset hasla';
        $resetLink = '' . $token;
        $mail->Body = 'Kliknij w ten link, aby zresetować swoje hasło: <a href="' . $resetLink . '">Zresetuj hasło</a>';

        $mail->send();
        echo 'Email z linkiem resetującym został wysłany.';
    } catch (Exception $e) {
        echo 'Wystąpił błąd podczas wysyłania wiadomości: ', $mail->ErrorInfo;
    }
}
?>
