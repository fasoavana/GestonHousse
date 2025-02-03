<?php

include 'includes/header.php';
include 'includes/db.php';
include 'includes/fonctions.php';

// Ajouter une commande avec avance
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['marquer_arrivee'])) {
        $commande_id = $_POST['commande_id'];
        $sql = "UPDATE commandes SET statut = 'arrivée' WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['id' => $commande_id]);
    } elseif (isset($_POST['marquer_livree'])) {
        $commande_id = $_POST['commande_id'];
        $date_livraison = date('Y-m-d'); // Date actuelle
        $sql = "UPDATE commandes SET statut = 'livrée', date_livraison = :date_livraison WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['id' => $commande_id, 'date_livraison' => $date_livraison]);
    } elseif (isset($_POST['marquer_retourne'])) {
        $commande_id = $_POST['commande_id'];
        $sql = "UPDATE commandes SET statut = 'retourné' WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['id' => $commande_id]);
    } elseif (isset($_POST['ajouter'])) {
        // Ajouter une nouvelle commande

        $produit_id = $_POST['produit_id'];
        $nom_client = $_POST['nom_client'];
        $numero_client = $_POST['numero_client'];
        $lieu_livraison = $_POST['lieu_livraison'];
        $prix_total = $_POST['prix_total'];
        $avance = $_POST['avance']; // Avance initiale
        $date_commande = date('Y-m-d');
        $date_arrivee_prevue = date('Y-m-d', strtotime('+7 days'));

        $sql = "INSERT INTO commandes (produit_id, nom_client, numero_client, lieu_livraison, prix_total, avance, 
        date_commande, date_arrivee_prevue) 
        VALUES (:produit_id, :nom_client, :numero_client, :lieu_livraison, :prix_total, :avance, 
        :date_commande, :date_arrivee_prevue)";
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

        header("Location: ".$_SERVER['PHP_SELF']);
        exit();
    }
}


// Ajouter un paiement additionnel
// Ajouter un paiement additionnel et vérifier si le montant total est atteint
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ajouter_paiement'])) {
    $commande_id = $_POST['commande_id'];
    $montant_paiement = $_POST['montant_paiement'];

    // Récupérer l'avance actuelle et le prix total de la commande
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ajouter_paiement'])) {
        $commande_id = $_POST['commande_id'];
        $montant_paiement = $_POST['montant_paiement'];
        
        $sql = "UPDATE commandes 
                SET avance = avance + :montant_paiement 
                WHERE id = :commande_id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'montant_paiement' => $montant_paiement,
            'commande_id' => $commande_id
        ]);
    }
    header("Location: ".$_SERVER['PHP_SELF']);
    exit();
}


// Récupérer la liste des commandes
$commandes = getCommandes();
?>

<!DOCTYPE html>
<html lang="fr">

<body>
   

    <main>
        <form method="POST">

            <input type="text" name="nom_client" placeholder="Nom du client" required>
            <select name="produit_id" id="produit-select" class="select2" required>
            <option value="">Sélectionnez un modèle</option>
            <?php
                $produits = getProduits();
                    foreach ($produits as $produit) {
                        echo "<option value='{$produit['id']}'>{$produit['modele']}</option>";
                    }
            ?>
        </select>
            <input type="text" name="numero_client" placeholder="Numéro du client" required>
            <textarea name="lieu_livraison" placeholder="Lieu de livraison" ></textarea>
            <input type="number" name="prix_total" placeholder="Prix total (Ar)" step="0.01" required>
            <input type="number" name="avance" placeholder="Acompte (Ar)" step="0.01" > <!-- Nouveau champ -->
            <button type="submit" name="ajouter">Ajouter</button>
        </form>

        <h2>Liste des commandes</h2>
        <table>
            <tr>

                <th>Nom du client</th>
                <th>Modèle</th>
                <th>Numéro client</th>
                <th>Lieu de livraison</th>
                <th>Prix total (Ar)</th>
                <th>Acompte (Ar)</th>
                <th>Reste (Ar)</th> <!-- Nouvelle colonne -->
                <th>Date commande</th>
                <th>Date  prévue</th>
                <th>Statut</th>
                <th>Actions</th>
                <th>Paiement additionnel</th> <!-- Nouvelle colonne -->
            </tr>
            <?php foreach ($commandes as $commande): ?>
                <tr>
                    
                    <td><?= $commande['nom_client'] ?></td>
                    <td><?= $commande['modele'] ?></td>
                    <td><?= $commande['numero_client'] ?></td>
                    <td><?= $commande['lieu_livraison'] ?></td>
                    <td><?= number_format($commande['prix_total'], 0, '.', ',') ?></td>
                    <td>

                        <?php 
                            $total = $commande['prix_total'];
                            $avance = $commande['avance'];

                            // Si l'avance couvre totalement le prix total, afficher "payée"
                            echo ($avance >= $total) 
                                ? "<span style='color: green; font-weight: bold;'>payée</span>" 
                                : number_format($avance, 0, '.', ','); 
                        ?>
                        
                    </td> <!-- Avance -->


                    </td>
                    <td>                     
                        <?= number_format($commande['prix_total'] - $commande['avance'], 0, '.', ',') ?>

                    </td> <!-- Reste à payer -->
                    <td><?= $commande['date_commande'] ?></td>
                    <td><?= $commande['date_arrivee_prevue'] ?></td>
                    <td><?= $commande['statut'] ?></td>
                    <td>
                        <?php if ($commande['statut'] === 'en attente'): ?>
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="commande_id" value="<?= $commande['id'] ?>">
                                <button type="submit" name="marquer_arrivee">Marquer comme arrivée</button>
                            </form>
                        <?php elseif ($commande['statut'] === 'arrivée'): ?>
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="commande_id" value="<?= $commande['id'] ?>">
                                <button type="submit" name="marquer_livree">Marquer comme livrée</button>
                            </form>
                        <?php elseif ($commande['statut'] === 'livrée'): ?>
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="commande_id" value="<?= $commande['id'] ?>">
                                <button type="submit" name="marquer_retourne">Marquer comme retourné</button>
                            </form>
                        <?php endif; ?>
                    </td>
                    <td>
                        <!-- Formulaire pour ajouter un paiement additionnel -->
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="commande_id" value="<?= $commande['id'] ?>">
                            <input type="number" name="montant_paiement" placeholder="Montant (Ar)" step="0.01" required>
                            <button type="submit" name="ajouter_paiement">Ajouter paiement</button>
                        </form>
                        

                    </td>
                </tr>
            <?php endforeach; 

?>
        </table>
    </main>

    <footer>
        <p>&copy; 2023 Votre entreprise. Tous droits réservés.</p>
    </footer>
    <script>
    $(document).ready(function() {
        $('#produit-select').select2({
            placeholder: "Recherchez un modèle...",
            allowClear: true
        });
    });
</script>

</body>
</html>