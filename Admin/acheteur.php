
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Espace Fournisseur</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        body {
    font-family: 'Roboto', sans-serif;
    line-height: 1.2;
    background-color: #f0f8ff;
    margin: 0;
    padding: 0;
}

.container {
    max-width: 800px;
    margin: 20px auto;
    padding: 20px;
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

.options {
    display: flex;
    justify-content: space-around;
    flex-wrap: wrap;
}

.option {
    width: 45%;
    padding: 20px;
    background-color: #fff;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    margin-bottom: 20px;
    transition: transform 0.3s ease;
}

.option:hover {
    transform: translateY(-5px);
}

h2 {
    color: white;
    font-size: 24px;
    margin-bottom: 10px;
    
}

p {
    color: black;
    line-height: 1.6;
    margin-bottom: 20px;
}

button {
    padding: 12px 24px;
    font-size: 16px;
    background-color: #007bff;
    color: #fff;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    transition: background-color 0.3s ease;
    
}

button:hover {
    background-color: #0056b3;
}
    /* Styles pour le bouton de retour */
    .btn-back {
        display: inline-block;
        padding: 10px 20px;
        background: -webkit-linear-gradient(left, #003366,#004080,#0059b3, #0073e6);
        color: #fff;
        text-decoration: none;
        border-radius: 4px;
        font-size: 16px;
        border-radius: 25px; 
        
    }
    #a1{
        background-color: rgba(54, 162, 235, 0.9);
    }
    #a2{
        background-color: rgba( 255 , 165 , 0, 0.9);
    }
</style>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <link rel="icon" href="../assets/img/icone.ico" type="image/x-icon">
</head>
<body>
<?php include 'navbar.php'; ?>
<br><br><br>
    <div class="container">
        <h1 class="animate__animated animate__fadeInDown">Bienvenue dans votre Espace Acheteur</h1>
        <div class="options">
            <div class="option animate__animated animate__fadeInLeft" id="a1">
                <h2>Remplir le formulaire</h2>
                <br>
                <p>Demander un produit aux fournisseurs..</p>
                <br>
                <button id="btnFormulaire">Accéder au formulaire</button>
            </div>
    
            <div class="option animate__animated animate__fadeInRight" id="a2">
                <h2>Voir les Fournisseurs Intéressés</h2>
                <p>
                Consultez les Fournisseurs Intéressés a nos demandes et le prix unitaire fixé
                </p>
                
                <button id="btnDemandes">Voir les demandes</button>
            </div>
        </div>
    </div>
    <div style="text-align: center; margin-top: 20px;">
    <a href="../home.php" class="btn-back">Retour à la page d'accueil</a>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('btnFormulaire').addEventListener('click', function() {
        window.location.href = 'acheteur_formulaire.php';
    });

    document.getElementById('btnDemandes').addEventListener('click', function() {
        window.location.href = 'admin_interets.php';
    });
});

</script>
</body>
<footer class="footer  text-center">
  <div class="container">
    <span>&copy; 2024 SEWS-E. Tous droits réservés.</span>
  </div>
</footer>
</html>
