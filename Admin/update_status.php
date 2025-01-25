<?php
include('../connexion.php');
require 'email_functions.php'; // Inclure le fichier des fonctions email

if (isset($_POST['id']) && isset($_POST['status'])) {
    $id_fournisseur = $_POST['id'];
    $status = $_POST['status'];

    $query = "UPDATE fournisseur SET APPROUVE='$status' WHERE ID_FOURNISSEUR=$id_fournisseur";

    if ($status == 'Désapprouvé' && isset($_POST['justification'])) {
        $justification = $_POST['justification'];
        $query = "UPDATE fournisseur SET APPROUVE='$status', JUSTIFICATION='$justification' WHERE ID_FOURNISSEUR=$id_fournisseur";
    }

    if (mysqli_query($link, $query)) {
        echo "Statut mis à jour avec succès";

        // Envoi de l'e-mail après mise à jour
        if ($status == 'Approuvé') {
            sendApprovalEmail($id_fournisseur, $link);
        } else if ($status == 'Désapprouvé') {
            sendDisapprovalEmail($id_fournisseur, $justification, $link);
        }
    } else {
        echo "Erreur lors de la mise à jour du statut: " . mysqli_error($link);
    }
} else {
    echo "Données invalides";
}
?>