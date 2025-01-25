<?php
session_start();
include("../connexion.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $login = $_POST['login'];
    $password =$_POST['password'];

    $query = "SELECT * FROM ADMIN WHERE `LOGIN` = '$login'";
    $resultat = mysqli_query($link, $query);

    if (mysqli_num_rows($resultat) > 0) {
        $administrateur = mysqli_fetch_assoc($resultat);
        
        if ($password == $administrateur['PASSWORD']) {  // Vérification du mot de passe 

        
            $_SESSION['loggedin_admin'] = true;
            $_SESSION['ID_ADMIN']= $administrateur['ID_ADMIN'];
            $_SESSION['LOGIN'] = $administrateur['LOGIN'];
            $_SESSION['PASSWORD'] = $administrateur['PASSWORD'];
            $_SESSION['SEWZ']=$administrateur['SEWZ'];
            header("Location: dashboard.php");
            exit;
        } else {
            echo '<p style="color: red; font-weight: bold; text-align: center; font-size: 1.5em;">Le mot de passe est incorrect.</p>';
        }
    } else {
        echo '<p style="color: red; font-weight: bold; text-align: center; font-size: 1.5em;">LOGIN n\'existe pas</p>';
    }

    mysqli_close($link);
}
?>
