<?php
session_start();
include('../connexion.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idAchat = $_POST['ID_ACHAT'];
    $prixUnitaire = $_POST['PRIX_UNITAIRE'];
    $idFournisseur = $_SESSION['ID_COMPTE_FOURNISSEUR'];

    // Validation du prix unitaire
    if (!is_numeric($prixUnitaire) || $prixUnitaire <= 0) {
        echo 'Le prix unitaire doit être un nombre positif.';
        exit;
    }

    // Récupération de la quantité du produit
    $query = "SELECT QUANTITE FROM achat WHERE ID_ACHAT = '$idAchat'";
    $result = mysqli_query($link, $query);
    if ($result && $row = mysqli_fetch_assoc($result)) {
        $quantite = $row['QUANTITE'];
        $prixTotal = $prixUnitaire * $quantite;
    } else {
        echo 'Erreur lors de la récupération de la quantité.';
        exit;
    }

    // Insertion des données dans la base de données
    $query = "INSERT INTO interet_fournisseur (ID_COMPTE_FOURNISSEUR, ID_ACHAT, PRIX_UNITAIRE, PRIX_TOTAL) 
              VALUES ('$idFournisseur', '$idAchat', '$prixUnitaire', '$prixTotal') 
              ON DUPLICATE KEY UPDATE PRIX_UNITAIRE='$prixUnitaire', PRIX_TOTAL='$prixTotal'";
    if (mysqli_query($link, $query)) {
        echo 'success';
    } else {
        echo 'Erreur lors de l\'enregistrement: ' . mysqli_error($link);
    }
}
?>
