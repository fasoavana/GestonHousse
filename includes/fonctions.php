<?php
/**
 * Compte le nombre de commandes par statut.
 *
 * @param string $statut Le statut des commandes à compter.
 * @return int Le nombre de commandes.
 */
function countCommandesByStatut($statut) {
    global $pdo;
    $sql = "SELECT COUNT(*) as total FROM commandes WHERE statut = :statut";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['statut' => $statut]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result['total'];
}

/**
 * Récupère la liste des produits.
 *
 * @return array La liste des produits.
 */
function getProduits() {
    global $pdo;
    $sql = "SELECT * FROM produits";
    $stmt = $pdo->query($sql);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Récupère la liste des commandes avec les détails des produits.
 *
 * @return array La liste des commandes.
 */
function getCommandes() {
    global $pdo;
    $sql = "SELECT commandes.*, produits.modele 
            FROM commandes 
            JOIN produits ON commandes.produit_id = produits.id";
    $stmt = $pdo->query($sql);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>