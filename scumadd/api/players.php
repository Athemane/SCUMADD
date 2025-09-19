<?php
header('Content-Type: application/json');

// Inclure votre configuration de base de données
$dsn = "mysql:host=localhost;dbname=shreksolution;charset=utf8";
$dbUser = "shrekuser";
$dbPass = "2004Athemane";

try {
    // Connexion SCUM API
    $scumResponse = file_get_contents('http://localhost/api/scum-server.php?action=server-info');
    $scumData = json_decode($scumResponse, true);
    
    if ($scumData && !isset($scumData['error'])) {
        $response = [
            'players' => $scumData['player_list'] ?? [],
            'adminsOnlineCount' => $scumData['admins_online'] ?? 0,
            'totalPlayers' => $scumData['players'] ?? 0,
            'serverOnline' => $scumData['online'] ?? false
        ];
    } else {
        // Données par défaut si le serveur est offline
        $response = [
            'players' => [],
            'adminsOnlineCount' => 0,
            'totalPlayers' => 0,
            'serverOnline' => false
        ];
    }
    
    echo json_encode($response);
    
} catch (Exception $e) {
    echo json_encode([
        'error' => $e->getMessage(),
        'players' => [],
        'adminsOnlineCount' => 0,
        'totalPlayers' => 0,
        'serverOnline' => false
    ]);
}
?>
