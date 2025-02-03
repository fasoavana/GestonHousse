<?php
include 'includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $commande_id = $_POST['commande_id'];
    $montant_paiement = $_POST['montant_paiement'];

    $sql = "UPDATE commandes SET avance = avance + :montant_paiement WHERE id = :commande_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'montant_paiement' => $montant_paiement,
        'commande_id' => $commande_id
    ]);

    echo "Paiement mis à jour";
}
