<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/vendor/autoload.php';

$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host       = 'mail.shreksolution.fr';  // SMTP LWS
    $mail->SMTPAuth   = true;
    $mail->Username   = 'contact@shreksolution.fr'; // ton email complet
    $mail->Password   = 'Fucksamsung@35';        // mot de passe email
    $mail->SMTPSecure = 'tls';
    $mail->Port       = 587;

    $mail->setFrom('contact@shreksolution.fr', 'Test VPS');
    $mail->addAddress('athemane.sg@icloud.com'); // ton email perso pour le test

    $mail->isHTML(true);
    $mail->Subject = 'Test SMTP depuis VPS';
    $mail->Body    = 'Ceci est un test pour vérifier la connexion SMTP depuis mon VPS.';

    $mail->SMTPDebug = 3;       // Affiche l’échange SMTP complet
    $mail->Debugoutput = 'echo'; // Affiche dans le terminal ou navigateur

    $mail->AuthType = 'LOGIN';

    $mail->send();
    echo "Mail envoyé avec succès !";
} catch (Exception $e) {
    echo "Erreur SMTP : {$mail->ErrorInfo}";
}