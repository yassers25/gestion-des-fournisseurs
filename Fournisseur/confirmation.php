<?php
session_start();
include('../connexion.php');

// Vérifier si le formulaire a été soumis
$form_submitted = isset($_SESSION['form_submitted']) ? $_SESSION['form_submitted'] : false;

// Réinitialiser la variable de session
if ($form_submitted) {
    unset($_SESSION['form_submitted']);
}

// Récupération du terme de recherche s'il est défini
$searchTerm = isset($_GET['search']) ? mysqli_real_escape_string($link, $_GET['search']) : '';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Historique des Propositions</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f0f8ff;
            font-family: 'Arial', sans-serif;
        }
        .container {
            margin-top: 50px;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            padding: 20px;
        }
        .header h1 {
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
        .message {
            font-size: 18px;
            text-align: center;
            margin-bottom: 20px;
            color: green; /* Message en vert */
        }
        .table-responsive {
            margin-top: 20px;
        }
        .table-bordered th, .table-bordered td {
            border: 1px solid #dee2e6;
        }
        thead th {
            background-color: #0066b2;
            color: #fff;
        }
        tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        tbody tr:hover {
            background-color: #f1f1f1;
        }
        .btn-back {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            color: #fff;
            background-color: #007bff;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            font-size: 16px;
            text-align: center;
        }
        .btn-back:hover {
            background-color: #0056b3;
        }
        .status-approved {
            color: green;
            font-weight: bold;
        }
        .status-disapproved {
            color: red;
            font-weight: bold;
        }
        .status-pending {
            color: orange;
            font-weight: bold;
        }
        @media (max-width: 768px) {
            .header h1 {
                font-size: 32px;
            }
        }
        /* Style pour la croix dans le champ de recherche */
        .clearable {
            position: relative;
            display: inline-block;
        }
        .clearable input[type="text"] {
            padding-right: 24px; /* Espace pour la croix */
        }
        .clearable__clear {
            position: absolute;
            right: 8px;
            top: 50%;
            transform: translateY(-50%);
            width: 16px;
            height: 16px;
            background: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>') no-repeat center center;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <?php include 'navbar_f.php'; ?>
    <br><br>
    <div class="container">
        <div class="header">
            <h1>Historique des Propositions</h1>
        </div>
        <?php if ($form_submitted): ?>
            <div class="message">
                <p>Merci pour votre soumission ! Votre formulaire a été soumis avec succès.</p>
            </div>
        <?php endif; ?>
        <form method="GET" action="">
            <div class="form-group clearable">
                <input type="text" name="search" class="form-control" placeholder="produits/services" value="<?php echo htmlspecialchars($searchTerm); ?>">
            </div>
            <button type="submit" class="btn btn-primary">Rechercher</button>
        </form>
        <div class="history">
            <div class="table-responsive">
                <?php
                $idCompteFournisseur = $_SESSION['ID_COMPTE_FOURNISSEUR'];

                $sql_historique = "
                    SELECT f.*, 
                           cf.`NOM ENTREPRISE`, 
                           cf.`SECTEUR ACTIVITE`, 
                           GROUP_CONCAT(DISTINCT CONCAT('- ', dc.`DEPARTEMENT CIBLE`) ORDER BY dc.`DEPARTEMENT CIBLE` ASC SEPARATOR '<br>') AS DEPARTEMENTS_CIBLES
                    FROM fournisseur f
                    JOIN `compte_fournisseur` cf ON f.ID_COMPTE_FOURNISSEUR = cf.ID_COMPTE_FOURNISSEUR
                    LEFT JOIN `fournisseur_departement` fd ON f.ID_FOURNISSEUR = fd.ID_FOURNISSEUR
                    LEFT JOIN `departements cibles` dc ON fd.ID_DEPARTEMENT = dc.ID_DEPARTEMENT
                    WHERE f.ID_COMPTE_FOURNISSEUR = '$idCompteFournisseur'
                ";

                if ($searchTerm) {
                    $sql_historique .= " AND f.`PRODUITS ET SERVICES` LIKE '%$searchTerm%'";
                }

                $sql_historique .= "
                    GROUP BY f.ID_FOURNISSEUR
                    ORDER BY f.DATE_SUBMISSION DESC;
                ";

                $result_historique = mysqli_query($link, $sql_historique);

                if ($result_historique && mysqli_num_rows($result_historique) > 0) {
                    echo '<table class="table table-bordered">';
                    echo '<thead>
                            <tr>
                                <th class="text-center">Date de Soumission</th>
                                <th class="text-center">Produits/Services</th>
                                <th class="text-center">Description</th>
                                <th class="text-center">Document</th>
                                <th class="text-center">Départements ciblés</th>
                                <th class="text-center">SEWS MFZ ou SEWS MAROC</th>
                                <th class="text-center">Status</th>
                            </tr>
                          </thead>';
                    echo '<tbody>';

                    while ($row = mysqli_fetch_assoc($result_historique)) {
                        echo '<tr>';
                        echo '<td class="text-center">' . htmlspecialchars($row['DATE_SUBMISSION']) . '</td>';
                        echo '<td class="text-center">' . htmlspecialchars($row['PRODUITS ET SERVICES']) . '</td>';
                        echo '<td class="text-center">' . htmlspecialchars($row['DESCRIPTION']) . '</td>';
                        echo "<td class='text-center'><a href='../files/".$row['FICHIER']."' download>".$row['FICHIER']."</a></td>";
                        echo '<td class="text-center">' . $row['DEPARTEMENTS_CIBLES'] . '</td>';
                        echo '<td class="text-center">' . htmlspecialchars($row['SEWZ']) . '</td>';
                        echo '<td class="text-center">';
                        if ($row['APPROUVE'] == 'Approuvé') {
                            echo '<span class="status-approved">Approuvé</span>';
                        } elseif ($row['APPROUVE'] == 'Désapprouvé') {
                            echo '<span class="status-disapproved">Désapprouvé</span>';
                        } else {
                            echo '<span class="status-pending">Pas encore</span>';
                        }
                        echo '</td>';
                        echo '</tr>';
                    }

                    echo '</tbody>';
                    echo '</table>';
                } else {
                    echo '<p>Aucune proposition trouvée.</p>';
                }
                ?>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Ajouter dynamiquement le bouton de suppression
            var clearableInputs = document.querySelectorAll('.clearable input[type="text"]');
            clearableInputs.forEach(function(input) {
                var clearButton = document.createElement('span');
                clearButton.classList.add('clearable__clear');
                input.parentNode.appendChild(clearButton);
                clearButton.addEventListener('click', function() {
                    input.value = '';
                });
            });
        });
    </script>
</body>
</html>
