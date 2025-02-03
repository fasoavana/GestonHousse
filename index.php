<?php

include 'includes/header.php';
include 'includes/db.php';
include 'includes/fonctions.php';

// Récupérer les statistiques des commandes
$commandes_en_attente = countCommandesByStatut('en attente');
$commandes_arrivees = countCommandesByStatut('arrivée');
$commandes_livrees = countCommandesByStatut('livrée');
$commandes_retournees = countCommandesByStatut('retourné');
?>

<h1>Tableau de bord</h1>

<div class="stats">
    <div class="stat-card">
        <h2>Commandes en attente</h2>
        <p><?= $commandes_en_attente ?></p>
    </div>
    <div class="stat-card">
        <h2>Commandes arrivées</h2>
        <p><?= $commandes_arrivees ?></p>
    </div>
    <div class="stat-card">
        <h2>Commandes livrées</h2>
        <p><?= $commandes_livrees ?></p>
    </div>
    <div class="stat-card">
        <h2>Commandes retournées</h2>
        <p><?= $commandes_retournees ?></p>
    </div>
</div>

<?php include 'includes/footer.php'; ?>