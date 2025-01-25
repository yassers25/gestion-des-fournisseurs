<?php
session_start();
include('../connexion.php');

// Vérifiez si le fournisseur est connecté
if (!isset($_SESSION['loggedin_fournisseur']) || $_SESSION['loggedin_fournisseur'] !== true) {
    header('Location: login_fournisseur.php');
    exit;
}
$idFournisseur = $_SESSION['ID_COMPTE_FOURNISSEUR'];

// Vérifiez si une recherche a été effectuée
$search = isset($_GET['search']) ? $_GET['search'] : '';

// Récupération des intérêts
$query = "
    SELECT a.`NOM DE PRODUIT`, a.QUANTITE, a.DESCRIPTION, a.FICHIER, i.*
    FROM achat a
    JOIN interet_fournisseur i ON a.ID_ACHAT = i.ID_ACHAT
    WHERE i.ID_COMPTE_FOURNISSEUR = ? AND a.`NOM DE PRODUIT` LIKE ?
    ORDER BY i.date_interet DESC
";
$stmt = $link->prepare($query);
$searchParam = "%" . $search . "%";
$stmt->bind_param("is", $idFournisseur, $searchParam);
$stmt->execute();
$result = $stmt->get_result();

// Vérifiez s'il y a une erreur dans la requête SQL
if (!$result) {
    die("Erreur dans la requête: " . $link->error);
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Historique des intérêts</title>
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
        .table-bordered th, .table-bordered td {
            border: 1px solid #dee2e6;
        }
        thead th {
            background-color: #0066b2;
            color: #fff;
        }
        .etat-accepte {
            color: green;
            font-weight: bold;
        }
        .etat-non-accepte {
            color: red;
            font-weight: bold;
        }
        .etat-attente {
            color: orange;
            font-weight: bold;
        }
        .input-group .clear-btn {
            cursor: pointer;
        }
    </style>
</head>
<body>
<?php include 'navbar_f.php'; ?>
<br><br>
<div class="container">
    <h1>Historique des intérêts</h1>

    <!-- Formulaire de recherche -->
    <form class="form-inline mb-4" method="GET" action="">
        <div class="input-group mx-sm-3 mb-2">
            <input type="text" class="form-control" id="search" name="search" placeholder="Nom du Produit" value="<?php echo htmlspecialchars($search); ?>">
            <div class="input-group-append">
                <button type="button" class="btn btn-outline-secondary clear-btn">&times;</button>
            </div>
        </div>
        <button type="submit" class="btn btn-primary mb-2">Rechercher</button>
    </form>

    <div class="table-responsive">
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th class="text-center">Date d'Intérêt</th>
                    <th class="text-center">Nom du Produit</th>
                    <th class="text-center">Description</th>
                    <th class="text-center">Document</th>
                    <th class="text-center">Quantité</th>
                    <th class="text-center">Prix Unitaire</th>
                    <th class="text-center">Prix Total</th>
                    <th class="text-center">Etat</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()) { 
                    $etatClass = '';
                    switch ($row['etat']) {
                        case 'accepté':
                            $etatClass = 'etat-accepte';
                            break;
                        case 'refusé':
                            $etatClass = 'etat-non-accepte';
                            break;
                        case 'pas encore':
                            $etatClass = 'etat-attente';
                            break;
                    }
                ?>
                <tr>
                    <td class="text-center"><?php echo htmlspecialchars($row['date_interet']); ?></td>
                    <td class="text-center"><?php echo htmlspecialchars($row['NOM DE PRODUIT']); ?></td>
                    <td class="text-center"><?php echo htmlspecialchars($row['DESCRIPTION']); ?></td>
                    <td class="text-center">
                        <?php if ($row['FICHIER'] !== "inconnu.pdf") { ?>
                            <a href="../files/<?php echo htmlspecialchars($row['FICHIER']); ?>" download><?php echo htmlspecialchars($row['FICHIER']); ?></a>
                        <?php } else { ?>
                            Pas de fichier disponible
                        <?php } ?>
                    </td>
                    <td class="text-center"><?php echo htmlspecialchars($row['QUANTITE']); ?></td>
                    <td class="text-center"><?php echo htmlspecialchars($row['PRIX_UNITAIRE']); ?></td>
                    <td class="text-center"><?php echo htmlspecialchars($row['PRIX_TOTAL']); ?></td>
                    <td class="text-center <?php echo $etatClass; ?>"><?php echo htmlspecialchars($row['etat']); ?></td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
    document.querySelector('.clear-btn').addEventListener('click', function() {
        document.getElementById('search').value = '';
    });
</script>
</body>
<footer class="footer mt-auto text-center">
  <div class="container">
    <span>&copy; 2024 SEWS-E. Tous droits réservés.</span>
  </div>
</footer>
</html>
