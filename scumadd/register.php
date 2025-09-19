<?php
header('Content-Type: application/json');
session_start();

// Connexion à la base
$dsn = "mysql:host=localhost;dbname=shreksolution;charset=utf8";
$dbUser = "shrekuser";
$dbPass = "2004Athemane";

try {
    $pdo = new PDO($dsn, $dbUser, $dbPass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
} catch(PDOException $e) {
    echo json_encode(['status'=>'error','message'=>'Erreur serveur.']);
    exit;
}

// Récupération et nettoyage des données
$username = trim($_POST['username'] ?? '');
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

if (!$username || !$email || !$password) {
    echo json_encode(['status'=>'error','message'=>'Veuillez remplir tous les champs.']);
    exit;
}

// Limiter la longueur
if (strlen($username) > 255 || strlen($email) > 255 || strlen($password) > 255) {
    echo json_encode(['status'=>'error','message'=>'Données trop longues.']);
    exit;
}

// Vérifie si le nom d'utilisateur ou l'email existe déjà
$stmt = $pdo->prepare("SELECT id FROM users WHERE email=? OR username=?");
$stmt->execute([$email, $username]);
if($stmt->fetch()){
    echo json_encode(['status'=>'error','message'=>'Utilisateur déjà existant']);
    exit;
}

// Hash du mot de passe
$hash = password_hash($password, PASSWORD_DEFAULT);

// Insertion dans la base
$stmt = $pdo->prepare("INSERT INTO users(username,email,password,role) VALUES(?,?,?, 'user')");
if($stmt->execute([$username, $email, $hash])){
    echo json_encode(['status'=>'success','redirect'=>'pagewithoutadminacces.html']);
}else{
    echo json_encode(['status'=>'error','message'=>'Erreur inscription']);
}
?>