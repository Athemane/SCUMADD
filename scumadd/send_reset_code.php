<?php
require 'vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Adresse email du destinataire à définir dynamiquement
$email = 'user@example.com'; // À remplacer par l'adresse email cible

$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host       = 'smtp.example.com';      // À remplacer par le serveur SMTP
    $mail->SMTPAuth   = true;
    $mail->Username   = 'your_username';         // À remplacer par ton identifiant SMTP
    $mail->Password   = 'your_password';         // À remplacer par ton mot de passe SMTP
    $mail->SMTPSecure = 'tls';
    $mail->Port       = 587;                     // Port SMTP (modifie si besoin)

    $mail->setFrom('no-reply@example.com', 'YourAppName'); // Expéditeur générique
    $mail->addAddress($email);

    $mail->isHTML(true);
    $mail->Subject = 'Test SMTP';
    $mail->Body    = "Ceci est un test SMTP depuis le serveur.";

    // Optionnel : activer le mode debug pour développement
    // $mail->SMTPDebug = 3;
    // $mail->Debugoutput = 'echo';
    // $mail->AuthType = 'LOGIN';

    $mail->send();
    echo "Mail envoyé avec succès à $email\n";
} catch (Exception $e) {
    echo "Erreur : {$mail->ErrorInfo}\n";
}
