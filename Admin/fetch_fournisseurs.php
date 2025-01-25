
<?php
// Inclure le fichier de connexion à la base de données
include('../connexion.php');
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['loggedin_admin']) || $_SESSION['loggedin_admin'] !== true) {
    exit;
}

// Récupérer le login de l'administrateur connecté
$admin_login = $_SESSION['LOGIN'];

// Récupérer les paramètres de recherche
$search = $_GET['search'] ?? '';
$departements = $_GET['departement'] ?? array();
$approuve = $_GET['approuve'] ?? '';

// Requête pour obtenir les détails de l'admin connecté
$query_admin = "SELECT SEWZ FROM admin WHERE LOGIN = '$admin_login'";
$result_admin = mysqli_query($link, $query_admin);
$admin_data = mysqli_fetch_assoc($result_admin);
$sewz_admin = $admin_data['SEWZ'];

// Construire la requête principale pour récupérer les fournisseurs filtrés
$query = "SELECT f.*, cf.`NOM ENTREPRISE`, cf.`SECTEUR ACTIVITE`, GROUP_CONCAT(CONCAT('- ', dc.`DEPARTEMENT CIBLE`) SEPARATOR '<br>') AS DEPARTEMENTS_CIBLES
          FROM fournisseur f 
          JOIN `fournisseur_departement` fd ON f.ID_FOURNISSEUR = fd.ID_FOURNISSEUR
          JOIN `departements cibles` dc ON fd.ID_DEPARTEMENT = dc.ID_DEPARTEMENT
          JOIN `compte_fournisseur` cf ON f.ID_COMPTE_FOURNISSEUR = cf.ID_COMPTE_FOURNISSEUR
          WHERE f.SEWZ = '$sewz_admin'";

// Ajouter des conditions de recherche si des paramètres sont définis
$conditions = array();

if (!empty($search)) {
    $conditions[] = "(cf.`NOM ENTREPRISE` LIKE '%$search%' OR cf.`SECTEUR ACTIVITE` LIKE '%$search%' OR f.`PRODUITS ET SERVICES` LIKE '%$search%' )";
}

if (!empty($departements)) {
    // Si tous les départements sont sélectionnés
    if (in_array('', $departements)) {
        $query_departements = "SELECT ID_DEPARTEMENT FROM `departements cibles`";
        $result_departements = mysqli_query($link, $query_departements);
        $departements = array();
        while ($dept = mysqli_fetch_assoc($result_departements)) {
            $departements[] = $dept['ID_DEPARTEMENT'];
        }
    }

    $departements_list = implode(',', array_map('intval', $departements));
    $conditions[] = "fd.ID_DEPARTEMENT IN ($departements_list)";
}

if (!empty($approuve)) {
    $conditions[] = "f.APPROUVE = '$approuve'";
}

if (count($conditions) > 0) {
    $query .= " AND " . implode(' AND ', $conditions);
}

$query .= " GROUP BY f.ID_FOURNISSEUR";
$query .= " ORDER BY f.DATE_SUBMISSION DESC";
// Exécution de la requête
$result = mysqli_query($link, $query);

// Affichage des données des fournisseurs
while ($row = mysqli_fetch_assoc($result)) {
    echo "<tr>";
    echo "<td>".$row['DATE_SUBMISSION']."</td>";
    echo "<td>".$row['NOM ENTREPRISE']."</td>";
    echo "<td>".$row['PRODUITS ET SERVICES']."</td>";
    echo "<td>".$row['SECTEUR ACTIVITE']."</td>";
    echo "<td>".nl2br($row['DEPARTEMENTS_CIBLES'])."</td>";
    echo "<td><a href='../files/".$row['FICHIER']."' download>".$row['FICHIER']."</a></td>";
    echo "<td class='status-";
    if ($row['APPROUVE'] == 'Approuvé') {
        echo "approuve'>Approuvé";
    } elseif ($row['APPROUVE'] == 'Désapprouvé') {
        echo "desapprouve'>Désapprouvé";
    } else {
        echo "pas-encore'>Pas encore";
    }
    echo "</td>";
    echo "<td><a href='details.php?id=".$row['ID_FOURNISSEUR']."' class='btn btn-primary'>Détails</a></td>";
    echo "</tr>";
}
?>
