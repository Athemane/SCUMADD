<?php
require 'vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Email à tester
$email = 'athemane.dbh@gmail.com';

$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host       = 'mail.shreksolution.fr';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'contact@shreksolution.fr';
    $mail->Password   = 'Fucksamsung@35';
    $mail->SMTPSecure = 'tls';
    $mail->Port       = 587;

    $mail->setFrom('contact@shreksolution.fr', 'ShrekSolution');
    $mail->addAddress($email);

    $mail->isHTML(true);
    $mail->Subject = 'Test SMTP';
    $mail->Body    = "Ceci est un test SMTP depuis le VPS.";

    $mail->SMTPDebug = 3;
$mail->Debugoutput = 'echo';
$mail->AuthType = 'LOGIN';

    $mail->send();
    echo "Mail envoyé avec succès à $email\n";
} catch (Exception $e) {
    echo "Erreur : {$mail->ErrorInfo}\n";
}