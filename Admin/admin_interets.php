<?php
session_start();
include('../connexion.php');

if (!isset($_SESSION['loggedin_admin']) || $_SESSION['loggedin_admin'] !== true) {
    header('Location: login_admin.php');
    exit;
}
$idAdmin = $_SESSION['ID_ADMIN'];

$searchTerm = isset($_GET['search']) ? mysqli_real_escape_string($link, $_GET['search']) : '';

$query = "
    SELECT a.*, COUNT(i.ID_COMPTE_FOURNISSEUR) AS nb_interesses
    FROM achat a
    LEFT JOIN interet_fournisseur i ON a.ID_ACHAT = i.ID_ACHAT
    WHERE a.ID_ADMIN = $idAdmin
";

if ($searchTerm) {
    $query .= " AND a.`NOM DE PRODUIT` LIKE '%$searchTerm%'";
}

$query .= " GROUP BY a.ID_ACHAT";

$result = mysqli_query($link, $query);
if (!$result) {
    die("Erreur dans la requête: " . mysqli_error($link));
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Interets des Fournisseurs</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="icon" href="../assets/img/icone.ico" type="image/x-icon">
    <style>
        .table th, .table td {
            vertical-align: middle;
        }
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
            background: -webkit-linear-gradient(left, #003366, #004080, #0059b3, #0073e6);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 30px;
        }
        .container {
            margin-top: 50px;
        }
        .btn-primary, .btn-success {
            margin: 5px;
            width: 100px;
        }
        .btn-danger {
            margin: 10px;
        }
        thead th {
            background-color: #0066b2;
            color: #fff;
        }
        .table-bordered th, .table-bordered td {
            border: 1px solid #dee2e6;
        }
        .clear-button {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            background: transparent;
            border: none;
            cursor: pointer;
            display: none;
        }
        .clear-button::before {
            content: '✖';
        }
        .form-group {
            position: relative;
        }
        .form-group input:not(:placeholder-shown) + .clear-button {
            display: inline;
        }
    </style>
</head>
<body>
<?php include 'navbar.php'; ?>
<br><br>
<div class="container">
    <h1>Intérêts des Fournisseurs</h1>
    <form method="GET" action="">
        <div class="form-group">
            <input type="text" name="search" class="form-control" placeholder="Rechercher par nom de produit" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
            <button type="button" class="clear-button" onclick="clearSearch()"></button>
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
                    <th class="text-center">Fichier</th>
                    <th class="text-center">Nombre d'Intérêts</th>
                    <th class="text-center">Voir les Fournisseurs Intéressés</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                <tr>
                    <td class="text-center"><?php echo htmlspecialchars($row['NOM DE PRODUIT']); ?></td>
                    <td class="text-center"><?php echo htmlspecialchars($row['QUANTITE']); ?></td>
                    <td class="text-center"><?php echo htmlspecialchars($row['DESCRIPTION']); ?></td>
                    <td class="text-center"><?php if ($row['FICHIER'] !== "inconnu.pdf") { ?>
                            <a href="../files/<?php echo htmlspecialchars($row['FICHIER']); ?>" download><?php echo htmlspecialchars($row['FICHIER']); ?></a>
                        <?php } else { ?>
                            Pas de fichier disponible
                        <?php } ?>
                    </td>
                    <td class="text-center"><?php echo $row['nb_interesses']; ?></td>
                    <td class="text-center">
                        <div style="text-align: center;">
                            <button class="btn btn-primary" onclick="voirInteresses(<?php echo $row['ID_ACHAT']; ?>)">Voir</button>
                        </div>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
function clearSearch() {
    document.querySelector('input[name="search"]').value = '';
    document.querySelector('form').submit();
}

function voirInteresses(idAchat) {
    $.ajax({
        url: 'voir_interesses.php',
        type: 'POST',
        data: { ID_ACHAT: idAchat },
        success: function(response) {
            $('#interessesModal .modal-body').html(response);
            $('#interessesModal').modal('show');
        },
        error: function() {
            alert('Erreur lors de la requête.');
        }
    });
}
</script>

<!-- Modal pour afficher les fournisseurs intéressés -->
<div class="modal fade" id="interessesModal" tabindex="-1" role="dialog" aria-labelledby="interessesModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="interessesModalLabel">Fournisseurs Intéressés</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Les détails des fournisseurs seront chargés ici par AJAX -->
            </div>
        </div>
    </div>
</div>
</body>
<footer class="footer mt-auto text-center">
    <span>&copy; 2024 SEWS-E. Tous droits réservés.</span>
</footer>
</html>
