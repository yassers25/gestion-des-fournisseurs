<?php
include('../connexion.php'); // Inclure le fichier de connexion à la base de données

// Vérifier si l'ID est passé dans l'URL
if (isset($_GET['id'])) {
    // Récupérer et échapper l'ID du fournisseur pour éviter les injections SQL
    $id_fournisseur = intval($_GET['id']); // Utiliser intval pour s'assurer que c'est un entier

    // Préparer la requête SQL pour supprimer les enregistrements du département
    $query1 = "DELETE FROM fournisseur_departement WHERE ID_FOURNISSEUR = $id_fournisseur";
    // Exécuter la première requête
    if (mysqli_query($link, $query1)) {
        // Si la première requête réussit, préparer et exécuter la deuxième requête
        $query2 = "DELETE FROM fournisseur WHERE ID_FOURNISSEUR = $id_fournisseur";
        if (mysqli_query($link, $query2)) {
            // Redirection vers la page d'accueil après suppression
            header("Location: dashboard.php");
            exit();
        } else {
            echo "Erreur lors de la suppression du fournisseur : " . mysqli_error($link);
        }
    } else {
        echo "Erreur lors de la suppression du fournisseur_departement : " . mysqli_error($link);
    }

    // Fermer la connexion à la base de données
    $link->close();
} else {
    echo "ID du fournisseur non spécifié.";
}
?>
