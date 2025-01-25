<?php
// Inclure le fichier de connexion à la base de données
include('../connexion.php');

// Vérifier si l'ID du fournisseur est passé en paramètre
if (isset($_GET['id'])) {
    $fournisseur_id = $_GET['id'];

    // Préparer et exécuter la requête pour récupérer les détails du fournisseur par son ID
    $query = "SELECT * FROM `compte_fournisseur` WHERE ID_COMPTE_FOURNISSEUR = $fournisseur_id";
    $result = mysqli_query($link, $query);

    // Vérifier s'il y a des résultats
    if ($result && mysqli_num_rows($result) > 0) {
        $compte_fournisseur = mysqli_fetch_assoc($result);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Compte du Fournisseur</title>
    <link rel="icon" href="../assets/img/icone.ico" type="image/x-icon">
    <style>
        body {
        background: #f0f8ff;
        font-family: "lato", sans-serif;
        }
        .container {
            background-color: #ffffff;
            padding: 2rem;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 800px;
            margin: 2rem auto;
        }
        ul {
            list-style-type: none;
            padding: 0;
        }
        ul li {
            margin-bottom: 1rem;
        }
        ul li strong {
            font-weight: bold;
            color: #014373;
            display: block;
            margin-bottom: 0.5rem;
        }
        img {
            max-width: 100%;
            height: auto;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-height: 300px; /* Ajustez cette valeur selon votre besoin */
            object-fit: cover; /* Assure que l'image s'adapte correctement */
        
        }

        h1 {
    text-align: center;
  font-size: 41px;
  font-weight: 600;
  font-family: 'Poppins', sans-serif;
  background: -webkit-linear-gradient(right, #6caad6, #016bb7, #6caad6, #016bb7);
  background: -webkit-linear-gradient(left, #003366,#004080,#0059b3, #0073e6);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
    margin-bottom: 30px;
}
    .btn-back {
            background-color: #0073e6;
            color: #ffffff;
            border: none;
            padding: 0.75rem 1.5rem;
            text-transform: uppercase;
            font-weight: bold;
            border-radius: 5px;
            display: inline-block;
            text-decoration: none;
            transition: background-color 0.3s;
        }

        .btn-back:hover {
            background-color: #012d53;
        }
    </style>
</head>
<body>
<?php include 'navbar.php'; ?>
<br><br><br><br>
<h1>Compte du Fournisseur</h1>
    <div class="container">
        
        <ul>
            <li><strong>Photo de fournisseur:</strong> <img src="../photos/<?php echo $compte_fournisseur['PHOTO']; ?>" alt="Photo du compte fournisseur"></li>
            <li><strong>Nom de fournisseur:</strong> <?php echo $compte_fournisseur['NOM']; ?></li>
            <li><strong>Prénom de fournissueur:</strong> <?php echo $compte_fournisseur['PRENOM']; ?></li>
            <li><strong>Email de fournisseur:</strong> <?php echo $compte_fournisseur['ADRESSE MAIL']; ?></li>
            <li><strong>Nom de l'entreprise:</strong> <?php echo $compte_fournisseur['NOM ENTREPRISE']; ?></li>
            <li><strong>Localisation:</strong> <?php echo $compte_fournisseur['LOCALISATION']; ?></li>
            <li><strong>Email de l'entreprise:</strong> <?php echo $compte_fournisseur['EMAIL ENTREPRISE']; ?></li>
            <li><strong>Numéro de téléphone:</strong> <?php echo $compte_fournisseur['NUMERO DE TELEPHONE']; ?></li>
            <li><strong>Secteur d'activité:</strong> <?php echo $compte_fournisseur['SECTEUR ACTIVITE']; ?></li>
            <li><strong>ICE:</strong> <?php echo $compte_fournisseur['ICE']; ?></li>
            <li><strong>Registre de commerce:</strong> <?php echo $compte_fournisseur['REGISTRE DE COMMERCE']; ?></li>

            <!-- Ajoutez d'autres détails du compte fournisseur ici -->
            <a href="admin_interets.php" class="btn btn-back">Retour</a>
        </ul>
    </div>
</body>
</html>
<?php
    } else {
        echo "Aucun compte fournisseur trouvé pour cet ID.";
    }
} else {
    echo "ID du fournisseur non spécifié.";
}
