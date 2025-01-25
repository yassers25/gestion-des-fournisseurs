<?php
session_start();
include('../connexion.php');
require '../PHPMailer-master/lib/Exception.php';
require '../PHPMailer-master/lib/PHPMailer.php';
require '../PHPMailer-master/lib/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if (!isset($_SESSION['loggedin_admin']) || $_SESSION['loggedin_admin'] !== true) {
    echo 'error';
    exit;
}

if (isset($_POST['ID_COMPTE_FOURNISSEUR']) && isset($_POST['ID_ACHAT']) && isset($_POST['etat'])) {
    $idFournisseur = $_POST['ID_COMPTE_FOURNISSEUR'];
    $idAchat = $_POST['ID_ACHAT'];
    $etat = $_POST['etat'];
    $justification = isset($_POST['justification']) ? $_POST['justification'] : null;

    $stmt = $link->prepare("UPDATE interet_fournisseur SET etat = ? , justification = ? WHERE ID_COMPTE_FOURNISSEUR = ? AND ID_ACHAT = ?");
    $stmt->bind_param("ssii", $etat, $justification, $idFournisseur, $idAchat);
    
    if ($stmt->execute()) {
        echo 'success';

        // Récupérer l'email du fournisseur
        $queryEmail = "SELECT `ADRESSE MAIL` FROM compte_fournisseur WHERE ID_COMPTE_FOURNISSEUR = $idFournisseur";
        $resultEmail = mysqli_query($link, $queryEmail);
        if ($resultEmail && mysqli_num_rows($resultEmail) > 0) {
            $emailFournisseur = mysqli_fetch_assoc($resultEmail)['ADRESSE MAIL'];

            // Envoyer l'email approprié
            if ($etat === 'accepté') {
                $subject = "Confirmation de l'acceptation de votre offre";
                $message = "Votre offre a été acceptée. Nous vous remercions pour votre intérêt et nous sommes impatients de travailler avec vous.";
            } elseif ($etat === 'refusé') {
                $subject = "Refus de votre offre";
                $message = "Nous regrettons de vous informer que votre offre n'a pas été retenue. Nous vous remercions pour votre proposition et nous espérons pouvoir collaborer avec vous sur de futures opportunités. 
                Raison du refus: $justification";
            }

            if (envoyerEmail($emailFournisseur, $subject, $message)) {
            } else {
                echo "Erreur lors de l'envoi de l'email au fournisseur.";
            }
        } else {
            echo "Email du fournisseur non trouvé.";
        }
    } else {
        echo "Erreur lors de la mise à jour: " . $stmt->error;
    }
} else {
    echo 'error: missing parameters';
}

function envoyerEmail($to, $subject, $message) {
    try {
        $mail = new PHPMailer(true);

        // Configuration SMTP
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';  // Adresse du serveur SMTP
        $mail->SMTPAuth = true;

        $mail->Username = ''; // Remplacez par votre adresse email
        $mail->Password = 'votre_mot_de_passe'; // Remplacez par votre mot de passe 

        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;
        $mail->setFrom('votre_email@example.com', 'Sews'); // Remplacez par votre adresse email

        // Destinataire
        $mail->addAddress($to);

        // Contenu de l'email
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $message;

        $mail->send();
        return true;
    } catch (Exception $e) {
        return false;
    }
}
?>
