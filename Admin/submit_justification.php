<?php
include('../connexion.php');

if (isset($_POST['id']) && isset($_POST['justification'])) {
    $id_fournisseur = $_POST['id'];
    $justification = $_POST['justification'];

    $query = "UPDATE fournisseur SET JUSTIFICATION='$justification' WHERE ID_FOURNISSEUR=$id_fournisseur";

    if (mysqli_query($link, $query)) {
        echo "Justification mise à jour avec succès";
    } else {
        echo "Erreur lors de la mise à jour de la justification: " . mysqli_error($link);
    }
} else {
    echo "Données invalides";
}
?>
