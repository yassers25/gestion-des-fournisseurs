<?php
include('../connexion.php');
session_start();
if(!isset($_SESSION['loggedin_fournisseur']) || $_SESSION['loggedin_fournisseur'] !== true) {
    header('Location:login_fournisseur.php');
    exit;
}
$idCompteFournisseur = $_SESSION['ID_COMPTE_FOURNISSEUR'];  // Récupérer l'ID du compte fournisseur de la session

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../PHPMailer-master/lib/Exception.php';
require '../PHPMailer-master/lib/PHPMailer.php';
require '../PHPMailer-master/lib/SMTP.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST')  {
    $produitsServices = $_POST['produitsServices'];
    $description = $_POST['description'];
    $departementcible = $_POST['departementcible'];
    $SEWZ = $_POST['SEWZ'];
    
    if(isset($_FILES['fichier']) && $_FILES['fichier']['error'] == 0) {
        $dossier = '../files/';
        $temp_name = $_FILES['fichier']['tmp_name'];
        if(!is_uploaded_file($temp_name)) {
            exit("Le fichier est introuvable");
        }
        if ($_FILES['fichier']['size'] >= 1000000) {
            exit("Erreur, le fichier est volumineux");
        }
        $infosfichier = pathinfo($_FILES['fichier']['name']);
        $extension_upload = $infosfichier['extension'];
        
        $extension_upload = strtolower($extension_upload);
        $extensions_autorisees = array('pdf','doc','docx');
        if (!in_array($extension_upload, $extensions_autorisees)) {
            exit("Erreur, Veuillez insérer un fichier valide (extensions autorisées: pdf, doc, docx)");
        }
        $nom_fichier = $idCompteFournisseur. '_' .$produitsServices . "." . $extension_upload;
        if(!move_uploaded_file($temp_name, $dossier . $nom_fichier)) {
            exit("Problème dans le téléchargement du fichier, Réessayez");
        }
        $file_name = $nom_fichier;
    } else {
        $file_name = "inconnu";
    }
    
    $requette = "INSERT INTO fournisseur (`PRODUITS ET SERVICES`, `DESCRIPTION`, `FICHIER`, `ID_COMPTE_FOURNISSEUR`,`SEWZ`) 
                VALUES ('$produitsServices','$description', '$file_name','$idCompteFournisseur','$SEWZ')";
    
    $resultat = mysqli_query($link, $requette);
    
    if(!$resultat) {
        exit("Erreur d'insertion : " . mysqli_error($link));
    }
    // Récupérer l'ID du fournisseur inséré
    $idFournisseur = mysqli_insert_id($link);
    // Récupérer les départements sélectionnés
$departementsSelectionnes = $_POST['departementcible'];

// Insérer chaque département sélectionné pour ce fournisseur
foreach ($departementsSelectionnes as $idDepartement) {
    $requetteInsertion = "INSERT INTO fournisseur_departement (ID_FOURNISSEUR, ID_DEPARTEMENT) 
                          VALUES ('$idFournisseur', '$idDepartement')";
    $resultatInsertion = mysqli_query($link, $requetteInsertion);
    
    if (!$resultatInsertion) {
        exit("Erreur d'insertion dans la table de liaison : " . mysqli_error($link));
    }
}

// Récupérer les informations du compte fournisseur
    $sql_info = "SELECT `NOM ENTREPRISE`, `LOCALISATION`,`EMAIL ENTREPRISE`, `ADRESSE MAIL`, `NUMERO DE TELEPHONE`, `SECTEUR ACTIVITE`, `ICE`, `REGISTRE DE COMMERCE` 
                 FROM `compte_fournisseur` 
                 WHERE `ID_COMPTE_FOURNISSEUR` = '$idCompteFournisseur'";
    $result_info = mysqli_query($link, $sql_info);

        
        if($result_info) {
            $row = mysqli_fetch_assoc($result_info);
            $nomEntreprise = $row['NOM ENTREPRISE'];
            $localisation = $row['LOCALISATION'];
            $email = $row['EMAIL ENTREPRISE'];
            $numeroTelephone = $row['NUMERO DE TELEPHONE'];
            $secteurActivite = $row['SECTEUR ACTIVITE'];
            $ice = $row['ICE'];
            $registreCommerce = $row['REGISTRE DE COMMERCE'];

            // Récupérer l'adresse email du compte fournisseur
            $emailTo = $row['ADRESSE MAIL'];
    
            // Envoyer un email
            $mail = new PHPMailer(true);
            try {
                // Paramètres du serveur
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com'; // Remplacez par le serveur SMTP de votre fournisseur
                $mail->SMTPAuth = true;

                $mail->Username = ''; // Remplacez par votre adresse email
                $mail->Password = 'votre_mot_de_passe'; // Remplacez par votre mot de passe 
                
               
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;
    
                // Destinataires
                $mail->setFrom('votre_email@example.com', 'SEWS'); // Remplacez par votre adresse email
                $mail->addAddress($emailTo);

                // Ajouter l'image intégrée
            $mail->addEmbeddedImage('logo.jpg', 'logo_img', 'logo.jpg');
    
                // Contenu de l'email
                $mail->isHTML(true);
                
                $mail->Subject = 'Confirmation de soumission de formulaire';
                $mail->Body    = '<!DOCTYPE html>
                <html lang="fr">
                <head>
                    <meta charset="UTF-8">
                    <meta name="viewport" content="width=device-width, initial-scale=1.0">
                    <style>
                        body {
                            font-family: Arial, sans-serif;
                            color: #333;
                            line-height: 1.6;
                            font-size: 15px;
                        }
                        ul {
                            list-style-type: none;
                            padding: 0;
                        }
                        li {
                            margin-bottom: 10px;
                        }
                        strong {
                            color: #000;
                        }
                        .footer {
                            margin-top: 20px;
                            font-size: 0.9em;
                            color: #555;
        }
                    </style>
                </head>
                <body>
                    <p>Merci d\'avoir soumis votre formulaire. Voici un récapitulatif de vos informations :</p>
                    <ul>
                        <li><strong>Nom de l\'entreprise:</strong> '.$nomEntreprise.'</li>
                        <li><strong>Localisation:</strong> '.$localisation.'</li>
                        <li><strong>Email de l\'entreprise:</strong> '.$email.'</li>
                        <li><strong>Numéro de téléphone:</strong> '.$numeroTelephone.'</li>
                        <li><strong>Secteur d\'activité:</strong> '.$secteurActivite.'</li>
                        <li><strong>ICE:</strong> '.$ice.'</li>
                        <li><strong>Registre de Commerce:</strong> '.$registreCommerce.'</li>
                        <li><strong>Produits et services:</strong> '.$produitsServices.'</li>
                        <li><strong>Description:</strong> '.$description.'</li>
                    </ul>
                    <p> Nous vous répondrons au plus tôt possible.
                    <br><br>Cordialement,<br>
                    L\'équipe SEWS
                    </p>
                    <div class="center">
                        <img src="cid:logo_img" alt="Logo" width="500" height="300">
                    </div>

                </body>
                </html>';
                
                $mail->AltBody = 'Merci d\'avoir soumis votre formulaire. Voici un récapitulatif de vos informations : Nom de l\'entreprise: '.$nomEntreprise.', Localisation: '.$localisation.', Email: '.$email.', Numéro de téléphone: '.$numeroTelephone.', Secteur d\'activité: '.$secteurActivite.', Produits et services: '.$produitsServices.', ICE: '.$ice.', Registre de Commerce: '.$registreCommerce.', Description: '.$description;
                            
    
                $mail->send();
                echo 'Email de confirmation envoyé.';
            } catch (Exception $e) {
                echo "L'email n'a pas pu être envoyé. Erreur: {$mail->ErrorInfo}";
            }
        } else {
            exit("Erreur de requête pour l'email : " . mysqli_error($link));
        }
    $_SESSION['form_submitted'] = true;
    header('location: confirmation.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Formulaire Fournisseur</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/meyer-reset/2.0/reset.min.css">
    <link rel="stylesheet" href="./style.css">
    <link rel="icon" href="assets/img/icone.ico" type="image/x-icon">

    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
  <style>
        .select2-container .select2-dropdown {
            max-height: 400px;
        }
        .select2-container--default .select2-selection--multiple {
            height: auto;
        }
        .select2-container {
            width: 100% !important;
        }

  </style>
</head>
<body>
<?php include 'navbar_f.php'; ?>
<br><br><br>

<div class="container">
    <div class="text">
        Formulaire Fournisseur
    </div>
    
    <form action="" method="post" id="monform" enctype="multipart/form-data">
        
        <div class="form-row">
        <div class="input-data">
                <input type="text" name="produitsServices" required="required"/>
                <div class="underline"></div>
                <label for="produitsServices">Produits et services</label>
            </div>
        </div>
        
        
        <div class="form-row">
    
            <div class="input-data">
            SEWS MFZ ou SEWS MAROC
            <br><br>
                <select name="SEWZ" required="required">
                    <option value="SEWS MFZ">SEWS MFZ</option>
                    <option value="SEWS MAROC">SEWS MAROC</option>
                </select>
                <br>
                <br>
                <hr>   
            </div>
        </div>
        <br>

        <div class="form-row">
            <div class="input-data">
            Sélectionnez les départements ciblés
            <br><br>
            <select id="mySelect" name="departementcible[]" multiple required="required">
            <?php
            $sql_departements = "SELECT `ID_DEPARTEMENT`, `DEPARTEMENT CIBLE` FROM `departements cibles`";
            $result_departements = mysqli_query($link, $sql_departements);
            
            if (!$result_departements) {
                exit("Erreur de requête : " . mysqli_error($link));
            }
            
            while ($departement = mysqli_fetch_assoc($result_departements)) {
                echo "<option value='" . $departement['ID_DEPARTEMENT'] . "'>" . $departement['DEPARTEMENT CIBLE'] . "</option>";
            }
            ?>
        </select>

                <br>
                <br>
                <hr>
            </div>
        </div>

        <br>
        <div class="form-row">
            <div class="input-data">
                <h4>Document</h4>
                <br>
                <input type="file" name="fichier" required="required" />
            </div>
            
           
        </div>
        <br>
        <div class="form-row">
            <div class="input-data textarea">
                <textarea name="description" rows="8" cols="80" required></textarea>
                <br/>
                <div class="underline"></div>
                <label for="description">Description sur le produit</label>
                <br/>
                </div>
                <div class="form-row submit-btn">
                    <div class="input-data">
                        <div class="inner"></div>
                        <input type="submit" value="Soumettre" name="sub" >
                    </div>
                </div>
        </div>
    </form>
 
    <div style="text-align:center">
        <span >&copy; 2024 SEWS-E. Tous droits réservés.</span>
    </div>

<script>
  document.getElementById('monform').addEventListener('submit', function(event) {
    var iceInput = document.getElementById('ice');
    var errorMessage = iceInput.nextElementSibling; // Récupère le message d'erreur immédiatement après l'input

    if (iceInput.value.length >= 20) {
      errorMessage.style.display = 'block';
      event.preventDefault(); // Empêche l'envoi du formulaire si la condition n'est pas remplie
    } else {
      errorMessage.style.display = 'none';
    }
  });
</script>

<script>
    $(document).ready(function() {
      $('#mySelect').select2({
        placeholder: 'Select options',
        allowClear: true
      });
    });
  </script>

</body>
</html>
