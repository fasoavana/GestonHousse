<?php

include 'includes/header.php';
include 'includes/db.php';
include 'includes/fonctions.php';

// Ajouter un produit
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ajouter'])) {
    $modele = $_POST['modele'];
    $description = $_POST['description'];

    $sql = "INSERT INTO produits (modele, description) VALUES (:modele, :description)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['modele' => $modele, 'description' => $description]);
}

// Récupérer la liste des produits
$produits = getProduits();
?>

<h1>Gestion des produits</h1>
<form method="POST">
    <input type="text" name="modele" placeholder="Modèle" required>
    <textarea name="description" placeholder="Description"></textarea>
    <button type="submit" name="ajouter">Ajouter</button>
</form>

<h2>Liste des produits</h2>
<ul>
    <?php foreach ($produits as $produit): ?>
        <li>
            <strong><?= $produit['modele'] ?></strong>
            <p><?= $produit['description'] ?></p>
        </li>
    <?php endforeach; ?>
</ul>

<?php include 'includes/footer.php'; ?>
