<?php
include 'includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $produit_id = $_POST['produit_id'];
    $nom_client = $_POST['nom_client'];
    $numero_client = $_POST['numero_client'];
    $lieu_livraison = $_POST['lieu_livraison'];
    $prix_total = $_POST['prix_total'];
    $avance = $_POST['avance'];
    $date_commande = date('Y-m-d');
    $date_arrivee_prevue = date('Y-m-d', strtotime('+7 days'));

    $sql = "INSERT INTO commandes (produit_id, nom_client, numero_client, lieu_livraison, prix_total, avance, date_commande, date_arrivee_prevue) 
            VALUES (:produit_id, :nom_client, :numero_client, :lieu_livraison, :prix_total, :avance, :date_commande, :date_arrivee_prevue)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'produit_id' => $produit_id,
        'nom_client' => $nom_client,
        'numero_client' => $numero_client,
        'lieu_livraison' => $lieu_livraison,
        'prix_total' => $prix_total,
        'avance' => $avance,
        'date_commande' => $date_commande,
        'date_arrivee_prevue' => $date_arrivee_prevue
    ]);

    echo "Commande ajoutée avec succès";
}
