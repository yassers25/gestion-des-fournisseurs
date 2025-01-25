<?php
session_start();
include("../connexion.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $login = $_POST['login'];
    $password =$_POST['password'];

    $query = "SELECT * FROM compte_fournisseur WHERE `ADRESSE MAIL` = '$login'";
    $resultat = mysqli_query($link, $query);

    if (mysqli_num_rows($resultat) > 0) {
        $compte_fournisseur = mysqli_fetch_assoc($resultat);
        
        if ($password == $compte_fournisseur['PASSWORD']) {  // Vérification du mot de passe 

        
            $_SESSION['loggedin_fournisseur'] = true;
            $_SESSION['ID_COMPTE_FOURNISSEUR']= $compte_fournisseur['ID_COMPTE_FOURNISSEUR'];
            $_SESSION['ADRESSE MAIL'] = $compte_fournisseur['ADRESSE MAIL'];
            $_SESSION['PASSWORD'] = $compte_fournisseur['PASSWORD'];
            $_SESSION['NOM'] = $compte_fournisseur['NOM'];
            $_SESSION['PRENOM'] = $compte_fournisseur['PRENOM'];
            $_SESSION['PHOTO'] = $compte_fournisseur['PHOTO'];
        
            header("Location: fournisseur.php");
            exit;
        } else {
            echo '<p style="color: red; font-weight: bold; text-align: center; font-size: 1.5em;">Le mot de passe est incorrect.</p>';
        }
    } else {
        echo '<p style="color: red; font-weight: bold; text-align: center; font-size: 1.5em;">L\'adresse mail n\'existe pas</p>';
    }

    mysqli_close($link);
}
?>
