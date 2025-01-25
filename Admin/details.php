<?php
include('../connexion.php');
session_start();
if(!isset($_SESSION['loggedin_admin']) || $_SESSION['loggedin_admin'] !== true) {
    header('Location: login_admin.php');
    exit;
}

// Vérifier si l'ID est passé dans l'URL
if (isset($_GET['id'])) {
    $id_fournisseur = $_GET['id'];

    // Requête pour récupérer les détails du fournisseur spécifique
    $query = "
    SELECT f.*, c.*
    FROM fournisseur f
    JOIN compte_fournisseur c ON f.ID_COMPTE_FOURNISSEUR = c.ID_COMPTE_FOURNISSEUR
    WHERE f.ID_FOURNISSEUR = $id_fournisseur
    ";
    $result = mysqli_query($link, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../assets/img/icone.ico" type="image/x-icon">
    <title>Détails Fournisseur</title>
    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body {
            background: #f0f8ff;
            font-family: "lato", sans-serif;
        }

        .container {
            margin-top: 2rem;
            background-color: #ffffff;
            padding: 2rem;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 800px;
            margin: 2rem auto;
        }

        .img-fluid {
            max-width: 350px;
           
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
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
        .details-list {
            list-style-type: none;
            padding: 0;
        }

        .details-list li {
            margin-bottom: 1rem;
        }

        .details-list strong {
            font-weight: bold;
            color: #014373;
            display: block;
            margin-bottom: 0.5rem;
        }

    
    /* Styles for the buttons */
    .btn-action {
        cursor: pointer;
        padding: 0.75rem 1.5rem;
        text-transform: uppercase;
        font-weight: bold;
        border-radius: 5px;
        display: inline-block;
        margin-right: 1rem;
        text-decoration: none;
        transition: background-color 0.3s;
        font-size: 14px; /* Ajouter cette ligne pour définir la taille de police */
    }

    /* Styles spécifiques pour chaque bouton */
    .btn-approve {
        background-color: #28a745;
        color: #ffffff;
        border: none;
    }

    .btn-approve:hover {
        background-color: #218838;
    }

    .btn-disapprove {
        background-color: #dc3545;
        color: #ffffff;
        border: none;
    }

    .btn-disapprove:hover {
        background-color: #c82333;
    }

    .btn-delete {
        background-color: #dc3545;
        color: #ffffff;
        border: none;
    }

    .btn-delete:hover {
        background-color: #c82333;
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


        .form-popup {
            display: none;
            position: fixed;
            bottom: 0;
            right: 15px;
            border: 3px solid #f1f1f1;
            z-index: 9;
            padding: 10px;
            background-color: #fff;
        }

        .form-container {
            max-width: 600px;
            padding: 20px;
            background-color: white;
        }

  
        .form-container textarea {
    width: 100%;
    height: 300px; /* Hauteur fixe du textarea */
    padding: 10px;
    margin: 10px 0 20px 0;
    display: inline-block;
    border: 1px solid #ccc;
    border-radius: 4px;
    box-sizing: border-box;
    resize: none; /* Empêche la redimension manuelle */
    font-family: Arial, sans-serif; /* Exemple de famille de police */
    font-size: 14px; /* Taille de la police */
    line-height: 1.5; /* Espacement des lignes */
}

        .form-container textarea:focus {
            background-color: #ddd;
            outline: none;
        }

        .form-container .btn {
            background-color: #4CAF50;
            color: white;
            padding: 16px 20px;
            border: none;
            cursor: pointer;
            width: 100%;
            margin-bottom: 10px;
            opacity: 0.8;
        }

        .form-container .cancel {
            background-color: red;
        }

        .form-container .btn:hover,
        .open-button:hover {
            opacity: 1;
        }



@-webkit-keyframes spin {
    0% { -webkit-transform: rotate(0deg); }
    100% { -webkit-transform: rotate(360deg); }
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

.loading-spinner, .loading-message {
    display: none;
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
}

.loading-spinner {
    border: 4px solid #f3f3f3;
    border-radius: 50%;
    border-top: 4px solid #3498db;
    width: 40px;
    height: 40px;
    margin: 20px auto;
    -webkit-animation: spin 2s linear infinite;
    animation: spin 2s linear infinite;
}

.loading-message {
    background-color: rgba(255, 255, 255, 0.8); /* Fond semi-transparent */
    padding: 25px;
    border-radius: 10px;
    text-align: center;
    font-size: 20px;
    color: #333;
}
        .download-link {
            background-color: #0073e6;
            color: #ffffff;
            padding: 0.4rem 0.4rem;
            border-radius: 5px;
            text-decoration: none;
            transition: background-color 0.3s;
            margin-left: 1rem;
        }

        .download-link:hover {
            background-color: #012d53;
        }
        img {
            max-width: 100%;
            height: auto;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-height: 300px; /* Ajustez cette valeur selon votre besoin */
            object-fit: cover; /* Assure que l'image s'adapte correctement */
        
        }
        .text-black {
    color: #000000; /* Noir */
    font-weight: 500;
}

    </style>
</head>

<body>
<?php include 'navbar.php'; ?>
<br><br><br>
<h1>Détails de <?php echo $row['NOM ENTREPRISE']; ?></h1>

    <div class="container">
      
    <ul class="details-list">
        <li><strong>- Photo de fournisseur:</strong> <img src="../photos/<?php echo $row['PHOTO']; ?>" alt="Photo du compte fournisseur"></li>
        <li><strong>- Nom de fournisseur: <span class="text-black"> <?php echo $row['NOM']; ?></span></strong></li>
        <li><strong>- Prénom de fournissueur: <span class="text-black"> <?php echo $row['PRENOM']; ?></span></strong></li>
        <li><strong>- Email de fournisseur: <span class="text-black"> <?php echo $row['ADRESSE MAIL']; ?></span></strong></li>
        <li><strong>- Nom de l'entreprise: <span class="text-black"> <?php echo $row['NOM ENTREPRISE']; ?></span></strong></li>
        <li><strong>- Localisation: <span class="text-black"> <?php echo $row['LOCALISATION']; ?></span></strong></li>
        <li><strong>- Email de l'entreprise: <span class="text-black"> <?php echo $row['EMAIL ENTREPRISE']; ?></span></strong></li>
        <li><strong>- Numéro de téléphone: <span class="text-black"> <?php echo $row['NUMERO DE TELEPHONE']; ?></span></strong></li>
        <li><strong>- Secteur d'activité: <span class="text-black"> <?php echo $row['SECTEUR ACTIVITE']; ?></span></strong></li>
        <li><strong>- ICE: <span class="text-black"> <?php echo $row['ICE']; ?></span></strong></li>
        <li><strong>- Registre de commerce: <span class="text-black"> <?php echo $row['REGISTRE DE COMMERCE']; ?></span></strong></li>
        <li><strong>- Produits ou Services: <span class="text-black"> <?php echo $row['PRODUITS ET SERVICES']; ?></span></strong></li>
        <li><strong>- Document:
            <?php if ($row['FICHIER'] !== "inconnu.pdf") { ?>
            <a href="../files/<?php echo htmlspecialchars($row['FICHIER']); ?>" class="download-link" download>Télécharger</a>
            <?php } else { ?>
            Pas de fichier disponible
            <?php } ?>
             
        </strong></li>
        <li><strong>- Description: <span class="text-black"><?php echo $row['DESCRIPTION']; ?></span></strong></li>
                <br>
        <a href="Proposition_Fournisseur.php" class="btn btn-back">Retour</a>
        <br>
        <br>
        <li><strong>- Statut: <span class="text-black" id="statut"><?php echo $row['APPROUVE']; ?></span></strong></li>
        <li><strong>- Actions:</strong></li>
    </ul>
    
   
    <?php if ($row['APPROUVE'] == 'Pas encore') { ?>
        <button class="btn-action btn-approve" id="approveBtn">Approuver</button>
        <button class="btn-action btn-disapprove" id="disapproveBtn">Désapprouver</button>
    <?php } else if ($row['APPROUVE'] == 'Approuvé') { ?>
        <button class="btn-action btn-approve" id="approveBtn" style="display:none;">Approuver</button>
        <button class="btn-action btn-disapprove" id="disapproveBtn" style="display:none;">Désapprouver</button>
    <?php } else if ($row['APPROUVE'] == 'Désapprouvé') { ?>
        <button class="btn-action btn-approve" id="approveBtn" style="display:none;">Approuver</button>
        <button class="btn-action btn-disapprove" id="disapproveBtn" style="display:none;">Désapprouver</button>
    <?php } ?>

    <a href="delete.php?id=<?php echo $row['ID_FOURNISSEUR']; ?>" class="btn-action btn btn-delete" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet enregistrement ?')">Supprimer</a>

    <div class="form-popup" id="approveForm">
        <form class="form-container">
            <h3>Confirmation d'approbation</h3>
            <p>Êtes-vous sûr d'approuver ce fournisseur ?</p>
            <button type="button" class="btn cancel" onclick="closeForm()">Annuler</button>
            <button type="button" class="btn" id="confirmApprove">Confirmer</button>
        </form>
    </div>

    <div class="form-popup" id="disapproveForm">
        <form action="/submit_justification.php" class="form-container">
            <h3>Justification</h3>
            <textarea placeholder="Expliquez la raison de la désapprobation..." name="justification" required></textarea>
            <button type="button" class="btn cancel" onclick="closeForm()">Fermer</button>
            <button type="submit" class="btn">Envoyer</button>
        </form>
    </div>

    <div class="loading-message">Veuillez patienter, envoi de l'email en cours...</div>
    <div class="loading-spinner"></div>


</div>

<script>
    $(document).ready(function () {
    $('#approveBtn').click(function () {
        $('#approveForm').show();
    });

    $('#confirmApprove').click(function () {
        var fournisseurId = <?php echo $id_fournisseur; ?>;
        $('.loading-message').show();
        $('.loading-spinner').show();
        $.ajax({
            type: "POST",
            url: "update_status.php",
            data: { id: fournisseurId, status: "Approuvé" },
            success: function (response) {
                $('#statut').text("Approuvé");
                $('#approveBtn').hide();
                $('#disapproveBtn').hide();
                $('#approveForm').hide();
                $('.loading-message').hide();
                $('.loading-spinner').hide();
            }
        });
    });

    $('#disapproveBtn').click(function () {
        $('#disapproveForm').show();
    });

    $('.form-container').submit(function (event) {
        event.preventDefault();
        var fournisseurId = <?php echo $id_fournisseur; ?>;
        var justification = $('textarea[name="justification"]').val();
        $('.loading-message').show();
        $('.loading-spinner').show();
        $.ajax({
            type: "POST",
            url: "update_status.php",
            data: {
                id: fournisseurId,
                status: "Désapprouvé",
                justification: justification
            },
            success: function (response) {
                $('#statut').text("Désapprouvé");
                $('#approveBtn').hide();
                $('#disapproveBtn').hide();
                $('#disapproveForm').hide();
                $('.loading-message').hide();
                $('.loading-spinner').hide();
            }
        });
    });
});

function closeForm() {
    $('#approveForm').hide();
    $('#disapproveForm').hide();
}

</script>

</body>

</html>
<?php
    } else {
        echo "Aucun fournisseur trouvé avec cet ID.";
    }
} else {
    echo "ID de fournisseur non spécifié.";
}
?>
