<?php
// Inclure le fichier de connexion à la base de données
include('../connexion.php');
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['loggedin_admin']) || $_SESSION['loggedin_admin'] !== true) {
    // Rediriger vers la page de connexion si l'utilisateur n'est pas connecté
    header('Location: login_admin.php');
    exit;
}

// Récupérer le login de l'administrateur connecté
$admin_login = $_SESSION['LOGIN'];
$Id_Admin=$_SESSION['ID_ADMIN'];

// Préparer la requête pour obtenir les détails de l'admin connecté
$query_admin = "SELECT SEWZ FROM admin WHERE LOGIN = '$admin_login'";
$result_admin = mysqli_query($link, $query_admin);

// Vérifier si la requête a réussi
if ($result_admin) {
    $admin_data = mysqli_fetch_assoc($result_admin);
    $sewz_admin = $admin_data['SEWZ'];
} else {
    // Gérer les erreurs de requête ici
    die('Erreur lors de la récupération des informations de l\'administrateur');
}

// Requête pour obtenir les données des trois graphiques

// Graphique 1: Nombre de fournisseurs par statut
$query_suppliers_by_status = "
    SELECT APPROUVE, COUNT(*) AS count
    FROM fournisseur
    WHERE SEWZ = '$sewz_admin'
    GROUP BY APPROUVE";
$result_suppliers_by_status = mysqli_query($link, $query_suppliers_by_status);

// Préparer les données pour le graphique 1
$approved_count = 0;
$disapproved_count = 0;
$pending_count = 0;

while ($row = mysqli_fetch_assoc($result_suppliers_by_status)) {
    if ($row['APPROUVE'] == 'Approuvé') {
        $approved_count = $row['count'];
    } elseif ($row['APPROUVE'] == 'Désapprouvé') {
        $disapproved_count = $row['count'];
    } elseif ($row['APPROUVE'] == 'Pas encore') {
        $pending_count = $row['count'];
    }
}

$labels_json = json_encode(['Approuvé', 'Désapprouvé', 'Pas encore']);
$data_approved_json = json_encode([$approved_count, 0, 0]);
$data_disapproved_json = json_encode([0, $disapproved_count, 0]);
$data_pending_json = json_encode([0, 0, $pending_count]);

// Graphique 2: Nombre de fournisseurs par état d'intérêt
$query_interest_by_status = "
    SELECT 
    interet_fournisseur.etat, 
    COUNT(*) AS count
FROM 
    interet_fournisseur
JOIN 
    achat ON interet_fournisseur.ID_ACHAT = achat.ID_ACHAT
WHERE 
    achat.ID_ADMIN = $Id_Admin
GROUP BY 
    interet_fournisseur.etat";
$result_interest_by_status = mysqli_query($link, $query_interest_by_status);

// Préparer les données pour le graphique 2
$labels_interest = [];
$data_interest = [];
while ($row = mysqli_fetch_assoc($result_interest_by_status)) {
    $labels_interest[] = $row['etat'];
    $data_interest[] = $row['count'];
}
$labels_interest_json = json_encode($labels_interest);
$data_interest_json = json_encode($data_interest);

// Graphique 3: Nombre total de fournisseurs intéressés et acceptés par produit
$query_products_stats = "
    SELECT 
        a.`NOM DE PRODUIT`,
        COUNT(DISTINCT i.ID_COMPTE_FOURNISSEUR) AS total_fournisseurs,
        SUM(CASE WHEN i.etat = 'accepté' THEN 1 ELSE 0 END) AS fournisseurs_acceptes
    FROM 
        achat a
    LEFT JOIN 
        interet_fournisseur i ON a.ID_ACHAT = i.ID_ACHAT
    WHERE 
    a.ID_ADMIN = $Id_Admin
    GROUP BY 
        a.`NOM DE PRODUIT`
    ORDER BY 
        a.`NOM DE PRODUIT`";
$result_products_stats = mysqli_query($link, $query_products_stats);

// Préparer les données pour le graphique 3
$product_labels = [];
$total_fournisseurs = [];
$fournisseurs_acceptes = [];

while ($row = mysqli_fetch_assoc($result_products_stats)) {
    $product_labels[] = $row['NOM DE PRODUIT'];
    $total_fournisseurs[] = $row['total_fournisseurs'];
    $fournisseurs_acceptes[] = $row['fournisseurs_acceptes'];
}
$product_labels_json = json_encode($product_labels);
$total_fournisseurs_json = json_encode($total_fournisseurs);
$fournisseurs_acceptes_json = json_encode($fournisseurs_acceptes);

mysqli_close($link);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statistiques</title>
    <!-- Inclure Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            background-color: #f0f8ff;
            font-family: Arial, sans-serif;
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
        .main-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin: 20px;
        }
        .chart-wrapper {
            width: 80vw;
            margin-bottom: 40px;
            background-color: #ffffff;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }
        .chart-container {
            height: 60vh; /* Ajuster la hauteur du graphique */
            width: 100%;
        }
        h3 {
            text-align: center;
            font-weight: 600;
            font-family: 'Poppins', sans-serif;
            background: -webkit-linear-gradient(right, #6caad6, #016bb7, #6caad6, #016bb7);
            background: -webkit-linear-gradient(left, #003366, #004080, #0059b3, #0073e6);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 30px;
        }
        .legend {
            display: flex;
            justify-content: center;
            margin-bottom: 10px;
        }
        .legend-item {
            display: flex;
            align-items: center;
            margin-right: 20px;
        }
        .legend-color {
            width: 20px;
            height: 20px;
            margin-right: 5px;
            border-radius: 50%;
        }
        .legend-text {
            font-weight: 500;
            font-family: 'Poppins', sans-serif;
        }
        .legend-approve {
            background-color: rgba(1, 215, 88, 0.8);
        }
        .legend-disapprove {
            background-color: rgba(255, 0, 0, 0.8);
        }
        .legend-pending {
            background-color: rgba(237, 127, 16, 0.8);
        }
        /* Style pour le message de bienvenue */
.welcome-message {
    text-align: center;
    font-size: 24px;
    font-weight: bold;
    color: #333;

    border: 1px solid #b2ebf2; /* Bordure légère pour un effet de délimitation */
    border-radius: 8px; /* Coins arrondis pour un look moderne */
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); /* Ombre légère pour ajouter de la profondeur */
    font-family: 'Poppins', sans-serif; /* Assurer l'utilisation de la même police */
}

    </style>
</head>
<body>
<?php include 'navbar.php'; ?>
<!-- Ajouter une section pour le message de bienvenue -->
 <br><br><br>
<div class="welcome-message">
    <?php
    if (isset($_SESSION['loggedin_admin']) && $_SESSION['loggedin_admin'] === true) {
        $admin_sewz = $_SESSION['SEWZ'];
        if ($admin_sewz === 'SEWS MFZ') {
            echo "<h2 >Bonjour SEWS MFZ</h2>";
        } elseif ($admin_sewz === 'SEWS MAROC') {
            echo "<h2>Bonjour SEWS MAROC</h2>";
        }
    }
    ?>
  </div>
<h1>Dashboard - Gestion des Fournisseurs</h1>
    <div class="main-container">
        
        <!-- Graphique 1: Nombre de fournisseurs par statut -->
        <div class="chart-wrapper">
            <h3>Graphique 1 : Distribution des Fournisseurs par Statut d'Approbation</h3>
         
            <div class="chart-container">
                <canvas id="statusChart"></canvas>
            </div>
        </div>
        
        <!-- Graphique 2: Nombre de fournisseurs par état d'intérêt -->
        <div class="chart-wrapper">
            <h3>Graphique 2 : Actions de l'Administrateur sur les Intérêts des Fournisseurs</h3>
            <div class="chart-container">
                <canvas id="interestChart"></canvas>
            </div>
        </div>
        
        <!-- Graphique 3: Nombre total de fournisseurs intéressés et acceptés par produit -->
        <div class="chart-wrapper">
            <h3>Graphique 3 : Nombre de Fournisseurs Intéressés et Acceptés par Produit</h3>
            <div class="chart-container">
                <canvas id="productsChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Script pour initialiser les graphiques -->
    <script>
        // Graphique 1: Fournisseurs par statut
        var ctx1 = document.getElementById('statusChart').getContext('2d');
        new Chart(ctx1, {
            type: 'bar',
            data: {
                labels: <?php echo $labels_json; ?>,
                datasets: [{
                    label: 'Approuvé',
                    data: <?php echo $data_approved_json; ?>,
                    backgroundColor: 'rgba(1, 215, 88, 0.8)',
                    borderColor: 'rgba(1, 215, 88, 1)',
                    borderWidth: 1
                },
                {
                    label: 'Désapprouvé',
                    data: <?php echo $data_disapproved_json; ?>,
                    backgroundColor: 'rgba(255, 0, 0, 0.8)',
                    borderColor: 'rgba(255, 0, 0, 1)',
                    borderWidth: 1
                },
                {
                    label: 'Pas encore',
                    data: <?php echo $data_pending_json; ?>,
                    backgroundColor: 'rgba(237, 127, 16, 0.8)',
                    borderColor: 'rgba(237, 127, 16, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return Number.isInteger(value) ? value : '';
                            }
                        }
                    }
                }
            }
        });

        // Graphique 2: Intérêt des fournisseurs
        var ctx2 = document.getElementById('interestChart').getContext('2d');
        new Chart(ctx2, {
            type: 'pie',
            data: {
                labels: <?php echo $labels_interest_json; ?>,
                datasets: [{
                    label: 'État d\'Intérêt',
                    data: <?php echo $data_interest_json; ?>,
                    backgroundColor: [
                        'rgba(1, 215, 88, 0.8)',
                        'rgba(255, 0, 0, 0.8)',
                        'rgba(237, 127, 16, 0.8)',
                        'rgba(255, 206, 86, 0.8)',
                        'rgba(153, 102, 255, 0.8)',
                        'rgba(255, 159, 64, 0.8)'
                    ],
                    borderColor: [
                        'rgba(1, 215, 88, 1)',
                        'rgba(255, 0, 0, 1)',
                        'rgba(237, 127, 16, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true
            }
        });

        // Graphique 3: Fournisseurs par produit
        var ctx3 = document.getElementById('productsChart').getContext('2d');
        new Chart(ctx3, {
            type: 'bar',
            data: {
                labels: <?php echo $product_labels_json; ?>,
                datasets: [{
                    label: 'Total des Fournisseurs Intéressés',
                    data: <?php echo $total_fournisseurs_json; ?>,
                    backgroundColor: 'rgba( 1 , 215 , 88, 0.8)',
                    borderColor: 'rgba( 1 , 215 , 88, 1)',
                    borderWidth: 1
                },
                {
                    label: 'Fournisseurs Acceptés',
                    data: <?php echo $fournisseurs_acceptes_json; ?>,
                    backgroundColor: 'rgba( 1 , 88 , 215, 0.8)',
                    borderColor: 'rgba( 1 , 88 , 215, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return Number.isInteger(value) ? value : '';
                            }
                        }
                    }
                }
            }
        });
    </script>
</body>
</html>
