// Fonction pour rafraîchir les données du serveur
async function refreshData() {
  try {
    // Récupérer les données des joueurs
    const playerRes = await fetch('/api/players.php');
    const playerData = await playerRes.json();

    // Mettre à jour l'affichage des joueurs
    const playerCountEl = document.getElementById('playerCount');
    if (playerCountEl) {
      playerCountEl.textContent = playerData.totalPlayers || playerData.players.length;
    }

    const adminCountEl = document.getElementById('adminCount');
    if (adminCountEl) {
      adminCountEl.textContent = playerData.adminsOnlineCount || 0;
    }

    // Récupérer les données des véhicules
    const vehicleRes = await fetch('/api/scum-server.php?action=vehicles');
    const vehicleData = await vehicleRes.json();

    // Mettre à jour le tableau des véhicules
    updateVehicleTable(vehicleData);

  } catch (error) {
    console.error("Erreur lors du chargement des données :", error);

    // Affichage d'erreur dans l'interface
    const playerCountEl = document.getElementById('playerCount');
    const adminCountEl = document.getElementById('adminCount');

    if (playerCountEl) playerCountEl.textContent = 'Erreur';
    if (adminCountEl) adminCountEl.textContent = 'Erreur';
  }
}

// Fonction pour mettre à jour le tableau des véhicules
function updateVehicleTable(vehicles) {
  const tableBody = document.querySelector('#vehTable tbody');

  if (!tableBody) return;

  // Vider le tableau
  tableBody.innerHTML = '';

  if (vehicles.error) {
    const row = tableBody.insertRow();
    const cell = row.insertCell();
    cell.colSpan = 4;
    cell.textContent = 'Erreur: ' + vehicles.error;
    cell.style.color = '#ff4444';
    cell.style.textAlign = 'center';
    return;
  }

  if (!Array.isArray(vehicles) || vehicles.length === 0) {
    const row = tableBody.insertRow();
    const cell = row.insertCell();
    cell.colSpan = 4;
    cell.textContent = 'Aucun véhicule trouvé';
    cell.style.color = '#888';
    cell.style.textAlign = 'center';
    return;
  }

  // Ajouter chaque véhicule au tableau
  vehicles.forEach(vehicle => {
    const row = tableBody.insertRow();

    // ID
    const idCell = row.insertCell();
    idCell.textContent = vehicle.id;

    // Type
    const typeCell = row.insertCell();
    typeCell.textContent = vehicle.type;

    // Position (seulement pour admin)
    if (document.getElementById('adminCount')) {
      const posCell = row.insertCell();
      posCell.textContent = vehicle.location || 'N/A';

      // État
      const stateCell = row.insertCell();
      const condition = vehicle.condition || 0;
      const status = vehicle.status || 'unknown';

      const stateSpan = document.createElement('span');
      stateSpan.className = `veh-state ${condition > 60 ? 'available' : 'destroyed'}`;
      stateSpan.textContent = `${condition}%`;

      stateCell.appendChild(stateSpan);
    }
  });
}

// Démarrage automatique
setInterval(refreshData, 5000); // Toutes les 5 secondes
refreshData(); // Chargement initial
