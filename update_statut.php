<?php
include 'includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $commande_id = $_POST['commande_id'];
    $nouveau_statut = $_POST['nouveau_statut']; // Récupérer le statut

    $sql = "UPDATE commandes SET statut = :nouveau_statut WHERE id = :commande_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'nouveau_statut' => $nouveau_statut,
        'commande_id' => $commande_id
    ]);

    echo "Statut mis à jour";
}
