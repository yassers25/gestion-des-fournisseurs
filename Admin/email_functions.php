<?php
require '../PHPMailer-master/lib/Exception.php';
require '../PHPMailer-master/lib/PHPMailer.php';
require '../PHPMailer-master/lib/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Fonction pour envoyer l'e-mail d'approbation
function sendApprovalEmail($id_fournisseur, $link) {
    $query = "
    SELECT f.*, c.*
    FROM fournisseur f
    JOIN compte_fournisseur c ON f.ID_COMPTE_FOURNISSEUR = c.ID_COMPTE_FOURNISSEUR
    WHERE f.ID_FOURNISSEUR = $id_fournisseur
    ";

    $result = mysqli_query($link, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $to_email = $row['ADRESSE MAIL'];
        $nom_entreprise = $row['NOM ENTREPRISE'];
        $nom_produit = $row['PRODUITS ET SERVICES'];

        // Instantiation de PHPMailer
        $mail = new PHPMailer(true);

        try {
            // Paramètres du serveur SMTP
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;

            $mail->Username = ''; // Remplacez par votre adresse email
            $mail->Password = 'votre_mot_de_passe'; // Remplacez par votre mot de passe 

            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;
            $mail->CharSet = 'UTF-8'; 
            // Destinataire
            $mail->setFrom('votre_email@example.com', 'Sews'); // Remplacez par votre adresse email
            $mail->addAddress($to_email);

            // Ajouter l'image intégrée
            $mail->addEmbeddedImage('logo.jpg', 'logo_img', 'logo.jpg');

            // Contenu de l'e-mail d'approbation
            $mail->isHTML(true);
            $mail->Subject = "Votre produit $nom_produit est validé !";
            $mail->Body = "
            <!DOCTYPE html>
            <html>
            <head>
                <style>
                    body {
                        font-family: Arial, sans-serif;
                        line-height: 1.6;
                    }
                    .header {
                        font-size: 15px;
                        font-weight: bold;
                    }
                    .content {
                        font-size: 14px;
                    }
                    .center {
                        text-align: center;
                    }
                </style>
            </head>
            <body>
                <p class='header'>Cher $nom_entreprise,</p>
                <p class='content'>
                    C'est avec grand plaisir que nous vous annonçons l'acceptation de votre produit, <strong>$nom_produit</strong>, par SEWS !<br><br>
                    Nous vous contacterons prochainement pour discuter des prochaines étapes et de la manière dont nous pouvons collaborer efficacement.
                    <br><br>Cordialement,<br>
                    L'équipe SEWS
                </p>
                <div class='center'>
                    <img src='cid:logo_img' alt='Logo' width='500' height='300'>
                </div>
            </body>
            </html>";

            // Attacher une image
            $mail->addAttachment('logo.jpg', 'logo.jpg');

            // Envoyer l'e-mail
            $mail->send();
            echo 'Email d\'approbation envoyé avec succès à ' . $to_email;
        } catch (Exception $e) {
            echo "Erreur lors de l'envoi de l'e-mail d'approbation: {$mail->ErrorInfo}";
        }
    } else {
        echo "Aucune adresse e-mail trouvée pour ce fournisseur.";
    }
}

// Fonction pour envoyer l'e-mail de désapprobation
function sendDisapprovalEmail($id_fournisseur, $justification, $link) {
    $query = "
    SELECT f.*, c.*
    FROM fournisseur f
    JOIN compte_fournisseur c ON f.ID_COMPTE_FOURNISSEUR = c.ID_COMPTE_FOURNISSEUR
    WHERE f.ID_FOURNISSEUR = $id_fournisseur
    ";

    $result = mysqli_query($link, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $to_email = $row['ADRESSE MAIL'];
        $nom_entreprise = $row['NOM ENTREPRISE'];
        $nom_produit = $row['PRODUITS ET SERVICES'];

        // Instantiation de PHPMailer
        $mail = new PHPMailer(true);

        try {
            // Paramètres du serveur SMTP
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;

            $mail->Username = ''; // Remplacez par votre adresse email
            $mail->Password = 'votre_mot_de_passe'; // Remplacez par votre mot de passe 

            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;
            $mail->CharSet = 'UTF-8'; 

            // Destinataire
            $mail->setFrom('votre_email@example.com', 'Sews'); // Remplacez par votre adresse email
            $mail->addAddress($to_email);

            // Ajouter l'image intégrée
            $mail->addEmbeddedImage('logo.jpg', 'logo_img', 'logo.jpg');

            // Contenu de l'e-mail de désapprobation
            $mail->isHTML(true);
            $mail->Subject = "Votre produit $nom_produit est non validé";
            $mail->Body = "
            <!DOCTYPE html>
            <html>
            <head>
                <style>
                    body {
                        font-family: Arial, sans-serif;
                        line-height: 1.6;
                    }
                    .header {
                        font-size: 15px;
                        font-weight: bold;
                    }
                    .content {
                        font-size: 14px;
                    }
                    .center {
                        text-align: center;
                    }
                </style>
            </head>
            <body>
                <p class='header'>Cher $nom_entreprise,</p>
                <p class='content'>
                    Nous vous informons que votre produit, <strong>$nom_produit</strong>, n'a pas été approuvé par SEWS  .
                    <br>Justification: $justification .
                    <br><br>
                    Nous vous remercions pour votre soumission et nous vous encourageons à nous soumettre de nouveaux produits à l'avenir.
                    <br><br>Cordialement,<br>
                    L'équipe SEWS
                </p>
                <div class='center'>
                    <img src='cid:logo_img' alt='Logo' width='500' height='300'>
                </div>
            </body>
            </html>";
                
            // Attacher une image
            $mail->addAttachment('logo.jpg', 'logo.jpg');

            // Envoyer l'e-mail
            $mail->send();
            echo 'Email de désapprobation envoyé avec succès à ' . $to_email;
        } catch (Exception $e) {
            echo "Erreur lors de l'envoi de l'e-mail de désapprobation: {$mail->ErrorInfo}";
        }
    } else {
        echo "Aucune adresse e-mail trouvée pour ce fournisseur.";
    }
}
?>
