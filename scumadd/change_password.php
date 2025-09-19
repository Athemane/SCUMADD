<?php
header('Content-Type: application/json');
session_start();

if(!isset($_SESSION['user_id'])){
    echo json_encode(['status'=>'error','message'=>'Connectez-vous']);
    exit;
}

$dsn = "mysql:host=localhost;dbname=shreksolution;charset=utf8";
$dbUser = "shrekuser";
$dbPass = "2004Athemane";

try {
    $pdo = new PDO($dsn, $dbUser, $dbPass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
} catch(PDOException $e){
    echo json_encode(['status'=>'error','message'=>'Erreur serveur']);
    exit;
}

$new = $_POST['newPassword'] ?? '';
if(!$new){
    echo json_encode(['status'=>'error','message'=>'Entrez un mot de passe']);
    exit;
}

$hash = password_hash($new, PASSWORD_DEFAULT);
$stmt = $pdo->prepare("UPDATE users SET password=? WHERE id=?");
if($stmt->execute([$hash, $_SESSION['user_id']])){
    echo json_encode(['status'=>'success','message'=>'Mot de passe modifié']);
}else{
    echo json_encode(['status'=>'error','message'=>'Erreur serveur']);
}
?>