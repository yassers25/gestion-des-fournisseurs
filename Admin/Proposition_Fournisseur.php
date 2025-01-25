<?php
// Inclure le fichier de connexion à la base de données
include('../connexion.php');
session_start();
if(!isset($_SESSION['loggedin_admin']) || $_SESSION['loggedin_admin'] !== true) {
    header('Location: login_admin.php');
    exit;
}

// Requête pour récupérer les départements cibles
$query_departements = "SELECT ID_DEPARTEMENT, `DEPARTEMENT CIBLE` FROM `departements cibles`";
$result_departements = mysqli_query($link, $query_departements);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Dashboard - Gestion des Fournisseurs</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
  <link rel="icon" href="../assets/img/icone.ico" type="image/x-icon">
  <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
  <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
  <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Roboto', sans-serif;
      line-height: 1.6;
      font-size: 16px;
      color: #333;
      background: #f0f8ff;
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

    table {
      width: 100%;
      table-layout: auto;
    }

    th, td {
      text-align: center;
      vertical-align: middle;
      padding: 10px;
    }

    th {
      background-color: #0066b2;
      color: white;
    }

    td img {
      max-width: 100%;
      height: auto;
    }

    .btn-primary {
      background-color: #3498db;
      border: none;
    }

    @media (max-width: 767px) {
      h1 {
        font-size: 2em;
      }

      .container {
        padding-left: 0;
        padding-right: 0;
      }

      .form-inline {
        display: flex;
        flex-direction: column;
        align-items: stretch;
      }

      .form-inline .form-control {
        margin-bottom: 0.5rem;
        width: 100%;
      }

      .form-inline .btn {
        width: 100%;
      }
    }

    /* Personnalisation de la liste déroulante Select2 */
    .select2-container--big-drop .select2-results__options {
      max-height: 200px; /* Hauteur maximale de la liste déroulante */
    }

    .status-approuve {
      color: green;
    }

    .status-desapprouve {
      color: red;
    }

    .status-pas-encore {
      color: orange;
    }

    /* Styles pour le bouton de fermeture */
    .close-btn {
      position: absolute;
      right: 10px;
      top: 50%;
      transform: translateY(-50%);
      background: none;
      border: none;
      font-size: 20px;
      color: #333;
      cursor: pointer;
    }
  </style>
</head>
<body>

<?php include 'navbar.php'; ?>
<br><br><br><br>
<div class="container">
  <h1>Gestion des propositions des Fournisseurs</h1>

  <div class="row mt-4">
    <div class="col-12">
      <!-- Formulaire de recherche et de filtre -->
      <form id="search-form" class="form position-relative">
        <div class="form-group position-relative">
          <input type="text" name="search" id="search" class="form-control" placeholder="Rechercher par 'Nom de l'entreprise' ou 'Produits et Services' ou 'Secteur d'activité'">
          <button type="button" class="close-btn">&times;</button>
        </div>
        <div class="form-group">
          <select name="departement[]" id="departement" class="form-control" multiple>
            <?php
            while ($departement = mysqli_fetch_assoc($result_departements)) {
              echo "<option value='" . $departement['ID_DEPARTEMENT'] . "'>" . $departement['DEPARTEMENT CIBLE'] . "</option>";
            }
            ?>
          </select>
        </div>
        <div class="form-group">
          <select name="approuve" id="approuve" class="form-control">
            <option value="">Tous les statuts</option>
            <option value="Pas encore" <?php if (isset($_GET['approuve']) && $_GET['approuve'] == 'Pas encore') echo 'selected'; ?>>Pas encore</option>
            <option value="Approuvé" <?php if (isset($_GET['approuve']) && $_GET['approuve'] == 'Approuvé') echo 'selected'; ?>>Approuvé</option>
            <option value="Désapprouvé" <?php if (isset($_GET['approuve']) && $_GET['approuve'] == 'Désapprouvé') echo 'selected'; ?>>Désapprouvé</option>
          </select>
        </div>
        <div class="form-group">
          <button type="submit" class="btn btn-primary">Rechercher</button>
        </div>
      </form>

      <!-- Tableau pour afficher les fournisseurs -->
      <div class="table-responsive">
        <table class="table table-striped" id="fournisseurs-table">
          <thead>
            <tr>
              <th>Date de Soumission</th>
              <th>Nom de l'entreprise</th>
              <th>Produits ou Services</th>
              <th>Secteur d'activité</th>
              <th>Département Ciblé</th>
              <th>Document</th>
              <th>Statut</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody id="fournisseurs-tbody">
            <!-- Les données des fournisseurs seront injectées ici par AJAX -->
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
<br>
<!-- Footer -->
<footer class="footer mt-auto text-center">
  <div class="container">
    <span>&copy; 2024 SEWS-E. Tous droits réservés.</span>
  </div>
</footer>

<!-- Scripts JavaScript -->

<script>
  $(document).ready(function() {
    // Initialiser Select2 pour le champ de sélection multiple
    $('#departement').select2({
      placeholder: ' Sélectionnez les départements ciblés',
      allowClear: true,
      dropdownAutoWidth: true, // Ajuster automatiquement la largeur de la liste déroulante
      dropdownParent: $('#departement').parent() // Fixer le parent pour la liste déroulante
    });

    // Fonction pour charger les fournisseurs via AJAX
    function loadFournisseurs(search = '', departement = '', approuve = '') {
      $.ajax({
        url: 'fetch_fournisseurs.php',
        method: 'GET',
        data: {search: search, departement: departement, approuve: approuve},
        success: function(response) {
          $('#fournisseurs-tbody').html(response);
        }
      });
    }

    // Charger tous les fournisseurs au chargement de la page
    loadFournisseurs();

    // Charger les fournisseurs en fonction des critères de recherche et de filtre
    $('#search-form').on('submit', function(e) {
      e.preventDefault();
      const search = $('#search').val();
      const departement = $('#departement').val();
      const approuve = $('#approuve').val();
      loadFournisseurs(search, departement, approuve);
    });

    // Vider le champ de recherche lorsque le bouton de fermeture est cliqué
    $('.close-btn').on('click', function() {
      $('#search').val('');
      loadFournisseurs();
    });
  });
</script>
</body>
</html>
