<?php
header('Content-Type: application/json');
session_start();

// Connexion à la base (utiliser des variables d'environnement ou un fichier de config)
$host = getenv('DB_HOST') ?: 'localhost';
$dbname = getenv('DB_NAME') ?: 'database_name';
$charset = 'utf8';
$dbUser = getenv('DB_USER') ?: 'db_user';
$dbPass = getenv('DB_PASS') ?: 'db_password';

$dsn = "mysql:host=$host;dbname=$dbname;charset=$charset";

try {
    $pdo = new PDO($dsn, $dbUser, $dbPass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
} catch(PDOException $e) {
    echo json_encode(['status'=>'error','message'=>'Server error.']);
    exit;
}

// Récupération et nettoyage des données
$username = trim($_POST['username'] ?? '');
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

if (!$username || !$email || !$password) {
    echo json_encode(['status'=>'error','message'=>'Please fill in all fields.']);
    exit;
}

// Limiter la longueur
if (strlen($username) > 255 || strlen($email) > 255 || strlen($password) > 255) {
    echo json_encode(['status'=>'error','message'=>'Data too long.']);
    exit;
}

// Vérifie si le nom d'utilisateur ou l'email existe déjà
$stmt = $pdo->prepare("SELECT id FROM users WHERE email=? OR username=?");
$stmt->execute([$email, $username]);
if($stmt->fetch()){
    echo json_encode(['status'=>'error','message'=>'User already exists.']);
    exit;
}

// Hash du mot de passe
$hash = password_hash($password, PASSWORD_DEFAULT);

// Insertion dans la base
$stmt = $pdo->prepare("INSERT INTO users(username,email,password,role) VALUES(?,?,?, 'user')");
if($stmt->execute([$username, $email, $hash])){
    echo json_encode(['status'=>'success','redirect'=>'redirect_page.html']);
}else{
    echo json_encode(['status'=>'error','message'=>'Registration error.']);
}
?>
