<?php
header('Content-Type: application/json');
session_start();

// Configuration de la connexion à la base
$dsn = "mysql:host=VOTRE_HOTE;dbname=VOTRE_NOM_BDD;charset=utf8";
$dbUser = "VOTRE_UTILISATEUR";
$dbPass = "VOTRE_MOT_DE_PASSE";

try {
    $pdo = new PDO($dsn, $dbUser, $dbPass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
} catch(PDOException $e) {
    echo json_encode(['status'=>'error','message'=>'Erreur serveur.']);
    exit;
}

// Récupération et nettoyage des données POST
$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';

if (!$username || !$password) {
    echo json_encode(['status'=>'error','message'=>'Veuillez remplir tous les champs.']);
    exit;
}

if (strlen($username) > 255 || strlen($password) > 255) {
    echo json_encode(['status'=>'error','message'=>'Données trop longues.']);
    exit;
}

// Recherche de l'utilisateur dans la base
$stmt = $pdo->prepare("SELECT id, password, role FROM users WHERE username = ? OR email = ?");
$stmt->execute([$username, $username]);
$user = $stmt->fetch();

if($user && password_verify($password, $user['password'])){
    $_SESSION['user_id'] = $user['id'];
    $redirect = ($user['role'] === 'admin') ? 'admin_dashboard.html' : 'pagewithoutadminacces.html';
    echo json_encode([
        'status' => 'success',
        'message' => 'Connexion réussie.',
        'redirect' => $redirect
    ]);
} else {
    echo json_encode(['status'=>'error','message'=>'Identifiants incorrects.']);
}
?>
