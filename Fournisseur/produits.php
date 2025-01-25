<?php
session_start();
include('../connexion.php');

// Vérifiez si le fournisseur est connecté
if (!isset($_SESSION['loggedin_fournisseur']) || $_SESSION['loggedin_fournisseur'] !== true) {
    header('Location: login_fournisseur.php');
    exit;
}
$idFournisseur = $_SESSION['ID_COMPTE_FOURNISSEUR'];

// Récupération des produits disponibles avec filtrage par nom de produit
$searchTerm = '';
if (isset($_POST['search'])) {
    $searchTerm = mysqli_real_escape_string($link, $_POST['search']);
}
$query = "SELECT * FROM achat WHERE `NOM DE PRODUIT` LIKE '%$searchTerm%'";
$result = mysqli_query($link, $query);

// Vérifiez s'il y a une erreur dans la requête SQL
if (!$result) {
    die("Erreur dans la requête: " . mysqli_error($link));
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Produits disponibles</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="icon" href="../assets/img/icone.ico" type="image/x-icon">
    <style>
        body {
            background-color: #f0f8ff;
            font-family: 'Arial', sans-serif;
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
        .container {
            margin-top: 50px;
        }
        .btn-primary {
            background-color: #0073e6;
            border-color: #0073e6;
            margin: 5px;
        }
        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }
        .table-bordered th, .table-bordered td {
            border: 1px solid #dee2e6;
        }
        thead th {
            background-color: #0066b2;
            color: #fff;
        }
        .text-success {
            color: green;
            font-weight: bold;
        }
        .search-wrapper {
            position: relative;
            width: 100%;
        }
        .search-wrapper input[type="text"] {
            width: 100%;
            padding-right: 30px; /* espace pour l'icône de fermeture */
        }
        .search-wrapper .clear-btn {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
        }
    </style>
</head>
<body>
<?php include 'navbar_f.php'; ?>
<br><br>
<div class="container">
    <h1>Produits disponibles</h1>
    <form method="post" action="">
        <div class="form-group search-wrapper">
            <input type="text" name="search" class="form-control" placeholder="Rechercher par nom du produit" value="<?php echo htmlspecialchars($searchTerm); ?>">
            <span class="clear-btn">&times;</span>
        </div>
        <button type="submit" class="btn btn-primary">Rechercher</button>
    </form>
    <div class="table-responsive">
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th class="text-center">Nom du Produit</th>
                    <th class="text-center">Quantité</th>
                    <th class="text-center">Description</th>
                    <th class="text-center">Document</th>
                    <th class="text-center">Intéressé</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)) { 
                    // Vérifier si le fournisseur a déjà montré de l'intérêt pour ce produit
                    $idProduit = $row['ID_ACHAT'];
                    $interestQuery = "SELECT * FROM interet_fournisseur WHERE ID_COMPTE_FOURNISSEUR = '$idFournisseur' AND ID_ACHAT = '$idProduit'";
                    $interestResult = mysqli_query($link, $interestQuery);
                    $isInterested = mysqli_num_rows($interestResult) > 0;
                ?>
                <tr>
                    <td class="text-center"><?php echo htmlspecialchars($row['NOM DE PRODUIT']); ?></td>
                    <td class="text-center"><?php echo htmlspecialchars($row['QUANTITE']); ?></td>
                    <td class="text-center"><?php echo htmlspecialchars($row['DESCRIPTION']); ?></td>
                    <td class="text-center">
                        <?php if ($row['FICHIER'] !== "inconnu.pdf") { ?>
                            <a href="../files/<?php echo htmlspecialchars($row['FICHIER']); ?>" download><?php echo htmlspecialchars($row['FICHIER']); ?></a>
                        <?php } else { ?>
                            Pas de fichier disponible
                        <?php } ?>
                    </td>
                    <td class="text-center">
                        <?php if ($isInterested) { ?>
                            <span class="text-success">Intéressé</span>
                        <?php } else { ?>
                            <button class="btn btn-primary" onclick="marquerInteret(<?php echo $row['ID_ACHAT']; ?>, this)">Je suis intéressé</button>
                        <?php } ?>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal de confirmation -->
<div class="modal fade" id="confirmModal" tabindex="-1" role="dialog" aria-labelledby="confirmModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmModalLabel">Confirmation</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Êtes-vous sûr de vouloir confirmer votre intérêt pour ce produit ?
                <div class="form-group">
                    <label for="prixUnitaire">Prix unitaire :</label>
                    <input type="text" class="form-control" id="prixUnitaire" placeholder="Entrez le prix unitaire">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-primary" id="confirmBtn">Confirmer</button>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
var produitActuelId;
var boutonActuel;

function marquerInteret(idProduit, bouton) {
    produitActuelId = idProduit;
    boutonActuel = bouton;
    $('#confirmModal').modal('show');
}

$('#confirmBtn').click(function() {
    var prixUnitaire = $('#prixUnitaire').val();
    if (isNaN(prixUnitaire) || prixUnitaire <= 0) {
        alert('Veuillez entrer un prix unitaire valide.');
        return;
    }
    
    $.ajax({
        url: 'interet.php',
        type: 'POST',
        data: { 
            ID_ACHAT: produitActuelId,
            PRIX_UNITAIRE: prixUnitaire
        },
        success: function(response) {
            if (response.trim() === 'success') {
                $(boutonActuel).replaceWith('<span class="text-success">Intéressé</span>');
                $('#confirmModal').modal('hide');
            } else {
                alert('Erreur: ' + response);
            }
        },
        error: function() {
            alert('Erreur lors de la requête.');
        }
    });
});

$(document).ready(function() {
    $('.clear-btn').click(function() {
        $('input[name="search"]').val('');
    });
});
</script>
</body>
<footer class="footer mt-auto text-center">
  <div class="container">
    <span>&copy; 2024 SEWS-E. Tous droits réservés.</span>
  </div>
</footer>
</html>
