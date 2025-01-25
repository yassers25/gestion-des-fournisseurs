<?php
session_start();
include('../connexion.php');

if (!isset($_SESSION['loggedin_admin']) || $_SESSION['loggedin_admin'] !== true) {
    echo 'error';
    exit;
}

if (isset($_POST['ID_ACHAT'])) {
    $idAchat = $_POST['ID_ACHAT'];

    $query = "
        SELECT f.*, i.etat, i.PRIX_UNITAIRE , i.prix_total , i.date_interet 
        FROM interet_fournisseur i
        JOIN compte_fournisseur f ON i.ID_COMPTE_FOURNISSEUR = f.ID_COMPTE_FOURNISSEUR
        WHERE i.ID_ACHAT = $idAchat
        ORDER BY i.prix_total DESC
    ";
    $result = mysqli_query($link, $query);
    if (!$result) {
        echo "Erreur dans la requête: " . mysqli_error($link);
        exit;
    }
    ?>
    <!DOCTYPE html>
    <html lang="fr">
    <head>
        <meta charset="UTF-8">
        <title>Fournisseurs Intéressés</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
        <style>
            .container {
                overflow-x: auto; /* Défilement horizontal si nécessaire */
            }
        </style>
    </head>
    <body>
        <div class="container">
            <h1>Fournisseurs Intéressés</h1>
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>Nom du Fournisseur</th>
                        <th>Photo</th>
                        <th>Prix Unitaire</th>
                        <th>Prix Total</th> 
                        <th>date interet</th>
                        <th>Action</th>
                        <th>Etat</th>
                        <th>Voir le compte</th> 
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                        <tr id="row_<?php echo $row['ID_COMPTE_FOURNISSEUR']; ?>">
                            <td><?php echo htmlspecialchars($row['NOM']); ?></td>
                            <td><img src='../photos/<?php echo htmlspecialchars($row['PHOTO']); ?>' width='100'></td>
                            <td><?php echo htmlspecialchars($row['PRIX_UNITAIRE']); ?> DH</td> 
                            <td><?php echo htmlspecialchars($row['prix_total']); ?> DH</td> 
                            <td><?php echo htmlspecialchars($row['date_interet']); ?></td>
                            <td id="etat_<?php echo $row['ID_COMPTE_FOURNISSEUR']; ?>"><?php echo htmlspecialchars($row['etat']); ?></td>
                            
                            <td>
                                <?php if ($row['etat'] === 'pas encore') { ?>
                                    <button class="btn btn-success" id="refuseBtn_<?php echo $row['ID_COMPTE_FOURNISSEUR']; ?>" onclick="confirmAccept(<?php echo $row['ID_COMPTE_FOURNISSEUR']; ?>, <?php echo $idAchat; ?>)">Accepter</button>
                                    <button class="btn btn-danger" id="acceptBtn_<?php echo $row['ID_COMPTE_FOURNISSEUR']; ?>" onclick="confirmRefuse(<?php echo $row['ID_COMPTE_FOURNISSEUR']; ?>, <?php echo $idAchat; ?>)">Refuser</button>
                                    
                                    <!-- Modal -->
                                    <div class="modal fade" id="acceptModal_<?php echo $row['ID_COMPTE_FOURNISSEUR']; ?>" tabindex="-1" role="dialog" aria-labelledby="acceptModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="acceptModalLabel">Confirmation</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    Êtes-vous sûr de vouloir accepter ce fournisseur ?
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                                                    <button type="button" class="btn btn-success" onclick="acceptSupplier(<?php echo $row['ID_COMPTE_FOURNISSEUR']; ?>, <?php echo $idAchat; ?>)">Confirmer</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Modal pour le refus -->
                                    <div class="modal fade" id="refuseModal_<?php echo $row['ID_COMPTE_FOURNISSEUR']; ?>" tabindex="-1" role="dialog" aria-labelledby="refuseModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="refuseModalLabel">Refus du Fournisseur</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <form id="justificationForm_<?php echo $row['ID_COMPTE_FOURNISSEUR']; ?>">
                                                        <div class="form-group">
                                                            <label for="justification_<?php echo $row['ID_COMPTE_FOURNISSEUR']; ?>">Justification :</label>
                                                            <textarea class="form-control" id="justification_<?php echo $row['ID_COMPTE_FOURNISSEUR']; ?>" rows="3"></textarea>
                                                        </div>
                                                    </form>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                                                    <button type="button" class="btn btn-danger" onclick="refuseSupplier(<?php echo $row['ID_COMPTE_FOURNISSEUR']; ?>, <?php echo $idAchat; ?>)">Refuser</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php } else { ?>
                                    <span><?php echo htmlspecialchars($row['etat']); ?></span>
                                <?php } ?>
                            </td>
                            <td>
                                <!-- Bouton pour voir le compte fournisseur -->
                                <a href="voir_compte_fournisseur.php?id=<?php echo $row['ID_COMPTE_FOURNISSEUR']; ?>" class="btn btn-info">Voir le compte</a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>

        <!-- Modal de chargement -->
        <div class="modal fade" id="loadingModal" tabindex="-1" role="dialog" aria-labelledby="loadingModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-body text-center">
                        <div class="spinner-border text-primary" role="status">
                            <span class="sr-only">Chargement...</span>
                        </div>
                        <p>Envoi de l'email en cours...</p>
                    </div>
                </div>
            </div>
        </div>

        <script>
            function confirmAccept(idFournisseur, idAchat) {
                $('#acceptModal_' + idFournisseur).modal('show');
            }

            function acceptSupplier(idFournisseur, idAchat) {
                $('#loadingModal').modal('show'); // Afficher le modal de chargement
                $.ajax({
                    url: 'changer_etat.php',
                    type: 'POST',
                    data: { ID_COMPTE_FOURNISSEUR: idFournisseur, ID_ACHAT: idAchat, etat: 'accepté' },
                    success: function(response) {
                        $('#loadingModal').modal('hide'); // Cacher le modal de chargement
                        if (response.trim() === 'success') {
                            $('#etat_' + idFournisseur).text('accepté'); // Mettre à jour l'état dans la ligne concernée
                            $('#acceptBtn_' + idFournisseur).hide(); // Cacher le bouton Accepter
                            $('#refuseBtn_' + idFournisseur).hide(); // Cacher le bouton Refuser
                            $('#acceptModal_' + idFournisseur).modal('hide'); // Cacher le modal après la mise à jour
                        } else {
                            alert('Erreur: ' + response);
                        }
                    },
                    error: function() {
                        alert('Erreur lors de la requête.');
                    }
                });
            }
            function confirmRefuse(idFournisseur, idAchat) {
                $('#refuseModal_' + idFournisseur).modal('show');
            }

            function refuseSupplier(idFournisseur, idAchat) {
                var justification = $('#justification_' + idFournisseur).val();
                $('#loadingModal').modal('show'); // Afficher le modal de chargement
                $.ajax({
                    url: 'changer_etat.php',
                    type: 'POST',
                    data: { ID_COMPTE_FOURNISSEUR: idFournisseur, ID_ACHAT: idAchat, etat: 'refusé', justification: justification },
                    success: function(response) {
                        $('#loadingModal').modal('hide'); // Cacher le modal de chargement
                        if (response.trim() === 'success') {
                            $('#etat_' + idFournisseur).text('refusé'); // Mettre à jour l'état dans la ligne concernée
                            $('#acceptBtn_' + idFournisseur).hide(); // Cacher le bouton Accepter
                            $('#refuseBtn_' + idFournisseur).hide(); // Cacher le bouton Refuser
                            $('#refuseModal_' + idFournisseur).modal('hide'); // Cacher le modal après la mise à jour
                        } else {
                            alert('Erreur: ' + response);
                        }
                    },
                    error: function() {
                        alert('Erreur lors de la requête.');
                    }
                });
            }
        </script>
    </body>
    </html>
    <?php
} else {
    echo "ID d'achat non spécifié.";
}
?>
