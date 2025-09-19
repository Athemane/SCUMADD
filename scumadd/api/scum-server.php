<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

// Configuration SCUM Server
$scumConfig = [
    'server_ip' => '176.57.140.86',
    'server_port' => 27015, // Port query Steam
    'rcon_port' => 27020,   // Port RCON
    'rcon_password' => 'LesRefugies2024Secure!'
];

class ScumServerAPI {
    private $serverIP;
    private $queryPort;
    private $rconPort;
    private $rconPassword;
    private $socket;

    public function __construct($config) {
        $this->serverIP = $config['server_ip'];
        $this->queryPort = $config['server_port'];
        $this->rconPort = $config['rcon_port'];
        $this->rconPassword = $config['rcon_password'];
    }

    // Récupérer les infos générales du serveur via Steam Query
    public function getServerInfo() {
        try {
            $this->socket = fsockopen("udp://{$this->serverIP}", $this->queryPort, $errno, $errstr, 5);
            
            if (!$this->socket) {
                return ['error' => 'Serveur inaccessible', 'online' => false];
            }

            // Requête A2S_INFO
            $packet = "\xFF\xFF\xFF\xFF\x54Source Engine Query\x00";
            fwrite($this->socket, $packet);
            
            $response = fread($this->socket, 4096);
            fclose($this->socket);
            
            if (strlen($response) < 10) {
                return ['error' => 'Réponse invalide', 'online' => false];
            }

            // Parser basique de la réponse
            $data = substr($response, 6);
            $serverName = substr($data, 0, strpos($data, "\x00"));
            
            // Récupérer les joueurs via A2S_PLAYER
            $players = $this->getPlayersInfo();
            
            return [
                'name' => $serverName,
                'online' => true,
                'players' => $players['count'],
                'max_players' => 64, // À adapter selon votre serveur
                'player_list' => $players['list'],
                'admins_online' => $players['admins_count']
            ];
            
        } catch (Exception $e) {
            return ['error' => $e->getMessage(), 'online' => false];
        }
    }

    // Récupérer la liste des joueurs
    private function getPlayersInfo() {
        try {
            $this->socket = fsockopen("udp://{$this->serverIP}", $this->queryPort, $errno, $errstr, 5);
            
            if (!$this->socket) {
                return ['count' => 0, 'list' => [], 'admins_count' => 0];
            }

            // Requête A2S_PLAYER
            $packet = "\xFF\xFF\xFF\xFF\x55\xFF\xFF\xFF\xFF";
            fwrite($this->socket, $packet);
            
            $response = fread($this->socket, 4096);
            fclose($this->socket);
            
            // Parser simplifié - à adapter selon la réponse SCUM
            $playerList = [];
            $adminsCount = 0;
            
            // Simulation de données (remplacez par le parsing réel)
            // Cette partie nécessite d'analyser la réponse binaire spécifique à SCUM
            
            return [
                'count' => count($playerList),
                'list' => $playerList,
                'admins_count' => $adminsCount
            ];
            
        } catch (Exception $e) {
            return ['count' => 0, 'list' => [], 'admins_count' => 0];
        }
    }

    // Connexion RCON pour récupérer les véhicules
    public function getVehicles() {
        try {
            $rcon = new ScumRCON($this->serverIP, $this->rconPort, $this->rconPassword);
            
            if (!$rcon->connect()) {
                return ['error' => 'Connexion RCON échouée'];
            }

            // Commandes RCON pour récupérer les véhicules (à adapter selon SCUM)
            $vehicleData = $rcon->executeCommand('#ListVehicles'); // Commande exemple
            
            return $this->parseVehicleData($vehicleData);
            
        } catch (Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    private function parseVehicleData($data) {
        // Parser les données des véhicules retournées par RCON
        // Format exemple à adapter selon votre serveur SCUM
        $vehicles = [];
        
        // Simulation de données véhicules
        $vehicles = [
            [
                'id' => 1,
                'type' => 'SUV',
                'condition' => 85,
                'location' => 'C2-4567-8901',
                'status' => 'available'
            ],
            [
                'id' => 2,
                'type' => 'Motorcycle',
                'condition' => 45,
                'location' => 'D3-2345-6789',
                'status' => 'damaged'
            ]
        ];
        
        return $vehicles;
    }
}

// Classe RCON simplifiée
class ScumRCON {
    private $socket;
    private $host;
    private $port;
    private $password;

    public function __construct($host, $port, $password) {
        $this->host = $host;
        $this->port = $port;
        $this->password = $password;
    }

    public function connect() {
        $this->socket = fsockopen($this->host, $this->port, $errno, $errstr, 10);
        
        if (!$this->socket) {
            return false;
        }

        // Authentification RCON (protocole Source RCON)
        $authPacket = $this->buildPacket(3, $this->password);
        fwrite($this->socket, $authPacket);
        
        $response = $this->readResponse();
        return $response['id'] !== -1;
    }

    public function executeCommand($command) {
        if (!$this->socket) return null;
        
        $packet = $this->buildPacket(2, $command);
        fwrite($this->socket, $packet);
        
        return $this->readResponse();
    }

    private function buildPacket($type, $body) {
        $id = 1;
        $data = pack('VV', $id, $type) . $body . "\x00\x00";
        return pack('V', strlen($data)) . $data;
    }

    private function readResponse() {
        $sizeData = fread($this->socket, 4);
        if (strlen($sizeData) < 4) return ['id' => -1, 'data' => ''];
        
        $size = unpack('V', $sizeData)[1];
        $response = fread($this->socket, $size);
        
        $id = unpack('V', substr($response, 0, 4))[1];
        $data = substr($response, 8, -2);
        
        return ['id' => $id, 'data' => $data];
    }
}

// Point d'entrée API
$action = $_GET['action'] ?? '';
$scumAPI = new ScumServerAPI($scumConfig);

switch ($action) {
    case 'server-info':
        echo json_encode($scumAPI->getServerInfo());
        break;
        
    case 'vehicles':
        echo json_encode($scumAPI->getVehicles());
        break;
        
    case 'all':
        $serverInfo = $scumAPI->getServerInfo();
        $vehicles = $scumAPI->getVehicles();
        
        echo json_encode([
            'server' => $serverInfo,
            'vehicles' => $vehicles,
            'timestamp' => time()
        ]);
        break;
        
    default:
        echo json_encode(['error' => 'Action non valide']);
}
?>