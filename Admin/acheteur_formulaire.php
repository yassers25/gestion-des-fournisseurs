<?php
session_start();
include('../connexion.php');

if(!isset($_SESSION['loggedin_admin']) || $_SESSION['loggedin_admin'] !== true) {
    header('Location: login_admin.php');
    exit;
}
$idAdmin = $_SESSION['ID_ADMIN'];  // Récupérer l'ID du compte administrateur de la session

if(isset($_POST['sub'])) {
    $nomProduit = $_POST['nomProduit'];
    $quantite = $_POST['quantite'];
    $description = $_POST['description'];

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
        $nom_fichier = $nomProduit . "." . $extension_upload;
        if(!move_uploaded_file($temp_name, $dossier . $nom_fichier)) {
            exit("Problème dans le téléchargement du fichier, Réessayez");
        }
        $file_name = $nom_fichier;
    } else {
        $file_name = "inconnu.pdf";
    }
    
    $requette = "INSERT INTO achat (`NOM DE PRODUIT`, `QUANTITE`, `DESCRIPTION`, `FICHIER`, `ID_ADMIN`) 
                VALUES ('$nomProduit', '$quantite', '$description', '$file_name',$idAdmin)";
    
    $resultat = mysqli_query($link, $requette);
    
    if(!$resultat) {
        exit("Erreur d'insertion : " . mysqli_error($link));
    }
    
    header('location: acheteur.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Formulaire Acheteur</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/meyer-reset/2.0/reset.min.css">
    <link rel="stylesheet" href="./style.css">
    <link rel="icon" href="../assets/img/icone.ico" type="image/x-icon">
    <style>
        .container {
            margin-top: 15px; /* Déplace le container vers le haut */
            margin-bottom: 50px; /* Ajustement pour éviter le chevauchement du bas */
        }
    </style>
</head>
<body>
<?php include 'navbar.php'; ?>
<br>
<div class="container">
    <div class="text">
        Formulaire Acheteur
    </div>
    <form action="" method="post" id="monform" enctype="multipart/form-data">
        <div class="form-row">
            <div class="input-data">
                <input type="text" name="nomProduit" required="required"/>
                <div class="underline"></div>
                <label for="nomProduit">Nom de Produit</label>
            </div>
            <div class="input-data">
                <input type="number" name="quantite" required="required"/>
                <div class="underline"></div>
                <label for="quantite">Quantité</label>
            </div>
        </div>

        <div class="form-row">
            <div class="input-data textarea">
                <textarea name="description" rows="8" cols="80" required></textarea>
                <br/>
                <div class="underline"></div>
                <label for="description">Description sur le produit</label>
            </div>
        </div>
        
        <div class="form-row">
            <div class="input-data">
                <h4>Fichier</h4>
                <br>
                <input type="file" name="fichier" required="required"/>
            </div>
            <br/>
            <div class="form-row submit-btn">
                <div class="input-data">
                    <div class="inner"></div>
                    <input type="submit" value="Soumettre" name="sub">
                </div>
            </div>
        </div>
    </form>
    <div style="text-align:center">
        <span >&copy; 2024 SEWS-E. Tous droits réservés.</span>
    </div>
</div>
</body>
</html>
