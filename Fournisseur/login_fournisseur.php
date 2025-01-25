<?php 
include('../connexion.php');

if(isset($_POST['sub'])){
    $nom=$_POST['nom'];
	$prenom=$_POST['prenom'];
	$Email=$_POST['Email'];
	$password=$_POST['password'];

    $nomEntreprise = $_POST['nomEntreprise'];
    $localisation = $_POST['localisation'];
    $emailEntreprise = $_POST['AdresseMail'];
    $numeroTelephone = $_POST['numeroTelephone'];
    $secteurActivite = $_POST['secteurActivite'];
    $ice = $_POST['ice'];
    $registreCommerce = $_POST['registreCommerce'];
	
	if(isset($_FILES['fichier']) and $_FILES['fichier']['error']==0)
	{
		$dossier= '../photos/';
		$temp_name=$_FILES['fichier']['tmp_name'];
		if(!is_uploaded_file($temp_name))
		{
		exit("le fichier est untrouvable");
		}
		if ($_FILES['fichier']['size'] >= 1000000){
			exit("Erreur, le fichier est volumineux");
		}
		$infosfichier = pathinfo($_FILES['fichier']['name']);
		$extension_upload = $infosfichier['extension'];
		
		$extension_upload = strtolower($extension_upload);
		$extensions_autorisees = array('png','jpeg','jpg');
		if (!in_array($extension_upload, $extensions_autorisees))
		{
		exit("Erreur, Veuillez inserer une image svp (extensions autorisées: png)");
		}
		$nom_photo=$nom.".".$extension_upload;
		if(!move_uploaded_file($temp_name,$dossier.$nom_photo)){
		exit("Problem dans le telechargement de l'image, Ressayez");
		}
		$ph_name=$nom_photo;
	}
	else{
		$ph_name="inconnu.jpg";
	}
	$requette="INSERT INTO compte_fournisseur (NOM,PRENOM,`ADRESSE MAIL`,PASSWORD,PHOTO,
  `NOM ENTREPRISE`,LOCALISATION,`EMAIL ENTREPRISE`,`NUMERO DE TELEPHONE`,`SECTEUR ACTIVITE`,ICE,`REGISTRE DE COMMERCE`)
   VALUES('$nom','$prenom','$Email','$password','$ph_name','$nomEntreprise','$localisation','$emailEntreprise','$numeroTelephone','$secteurActivite','$ice','$registreCommerce')";
	$resultat=mysqli_query($link,$requette);
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Fournisseur</title>
    <link rel="icon" type="image/png" href="images/icons/favicon.ico"/>
    <style> @import url('https://fonts.googleapis.com/css?family=Poppins:400,500,600,700&display=swap');
    
*{
  margin: 0;
  padding: 0;
  box-sizing: border-box;
  font-family: 'Poppins', sans-serif;
}
html,body{
  display: grid;
  height: 100%;
  width: 100%;
  place-items: center;
  background: -webkit-linear-gradient(left, #003366,#004080,#0059b3, #0073e6);
  background: #f0f8ff;
}
::selection{
  background: #1a75ff;
  color: #fff;
}
.wrapper{
  overflow: hidden;
  max-width: 390px;
  background: #fff;
  padding: 30px;
  border-radius: 15px;
  box-shadow: 0px 15px 20px rgba(0,0,0,0.1);
}
.wrapper .title-text{
  display: flex;
  width: 200%;
}
.wrapper .title{
  width: 50%;
  font-size: 35px;
  font-weight: 600;
  text-align: center;
  transition: all 0.6s cubic-bezier(0.68,-0.55,0.265,1.55);
}
.wrapper .slide-controls{
  position: relative;
  display: flex;
  height: 50px;
  width: 100%;
  overflow: hidden;
  margin: 30px 0 10px 0;
  justify-content: space-between;
  border: 1px solid lightgrey;
  border-radius: 15px;
}
.slide-controls .slide{
  height: 100%;
  width: 100%;
  color: #fff;
  font-size: 18px;
  font-weight: 500;
  text-align: center;
  line-height: 48px;
  cursor: pointer;
  z-index: 1;
  transition: all 0.6s ease;
}
.slide-controls label.signup{
  color: #000;
}
.slide-controls .slider-tab{
  position: absolute;
  height: 100%;
  width: 50%;
  left: 0;
  z-index: 0;
  border-radius: 15px;
  background: -webkit-linear-gradient(left,#003366,#004080,#0059b3
, #0073e6);
  transition: all 0.6s cubic-bezier(0.68,-0.55,0.265,1.55);
}
input[type="radio"]{
  display: none;
}
#signup:checked ~ .slider-tab{
  left: 50%;
}
#signup:checked ~ label.signup{
  color: #fff;
  cursor: default;
  user-select: none;
}
#signup:checked ~ label.login{
  color: #000;
}
#login:checked ~ label.signup{
  color: #000;
}
#login:checked ~ label.login{
  cursor: default;
  user-select: none;
}
.wrapper .form-container{
  width: 100%;
  overflow: hidden;
}
.form-container .form-inner{
  display: flex;
  width: 200%;
}
.form-container .form-inner form{
  width: 50%;
  transition: all 0.6s cubic-bezier(0.68,-0.55,0.265,1.55);
}
.form-inner form .field{
  height: 50px;
  width: 100%;
  margin-top: 20px;
}
.form-inner form .field input{
  height: 100%;
  width: 100%;
  outline: none;
  padding-left: 15px;
  border-radius: 15px;
  border: 1px solid lightgrey;
  border-bottom-width: 2px;
  font-size: 17px;
  transition: all 0.3s ease;
}
.form-inner form .field input:focus{
  border-color: #1a75ff;
  /* box-shadow: inset 0 0 3px #fb6aae; */
}
.form-inner form .field input::placeholder{
  color: #999;
  transition: all 0.3s ease;
}
form .field input:focus::placeholder{
  color: #1a75ff;
}
.form-inner form .pass-link{
  margin-top: 5px;
}
.form-inner form .signup-link{
  text-align: center;
  margin-top: 30px;
}
.form-inner form .pass-link a,
.form-inner form .signup-link a{
  color: #1a75ff;
  text-decoration: none;
}
.form-inner form .pass-link a:hover,
.form-inner form .signup-link a:hover{
  text-decoration: underline;
}
form .btn{
  height: 50px;
  width: 100%;
  border-radius: 15px;
  position: relative;
  overflow: hidden;
}
form .btn .btn-layer{
  height: 100%;
  width: 300%;
  position: absolute;
  left: -100%;
  background: -webkit-linear-gradient(right,#003366,#004080,#0059b3
, #0073e6);
  border-radius: 15px;
  transition: all 0.4s ease;;
}
form .btn:hover .btn-layer{
  left: 0;
}
form .btn input[type="submit"]{
  height: 100%;
  width: 100%;
  z-index: 1;
  position: relative;
  background: none;
  border: none;
  color: #fff;
  padding-left: 0;
  border-radius: 15px;
  font-size: 20px;
  font-weight: 500;
  cursor: pointer;
}
.message {
            background-color:	#3A8EBA;
            color: #fff;
            padding: 10px;
            text-align: center;
            margin-bottom: 20px;
            border-radius: 8px;
        }
</style>
</head>
<body>
<div class="message">
  <h3>Veuillez vous connecter avant de remplir le formulaire <br> ou de consulter les demandes.</h3>

    </div>
<div class="wrapper">
      <div class="title-text">
        <div class="title login"><h5>Login Fournisseur</h5></div>
        <div class="title signup"><h5>Signup Form</h5></div>
      </div>
      <div class="form-container">
        <div class="slide-controls">
          <input type="radio" name="slide" id="login" checked>
          <input type="radio" name="slide" id="signup">
          <label for="login" class="slide login">Login</label>
          <label for="signup" class="slide signup">Signup</label>
          <div class="slider-tab"></div>
        </div>
        <div class="form-inner">
          <form action="login_f.php" method="post" class="login">
            <div class="field">
              <input type="text" placeholder="Email Address" name="login" required>
            </div>
            <div class="field">
              <input type="password" placeholder="Password" name="password" required>
            </div>
            <div class="pass-link"><a href="#">Forgot password?</a></div>
            <div class="field btn">
              <div class="btn-layer"></div>
              <input type="submit" value="Login">
            </div>
      
            <div class="signup-link">Not a member? <a href="">Signup now</a></div>
            <div class="signup-link"><a href="../home.php"><h4>Retour à la page d'accueil</h4></a></div>
          </form>
          <form action="" method="post" id="monform" enctype="multipart/form-data">
            <div class="field">
              <input type="text" placeholder="Adresse mail" name="Email" required>
            </div>
            <div class="field">
              <input type="text" placeholder="Nom" name="nom" required>
            </div>
            <div class="field">
              <input type="text" placeholder="Prénom" name="prenom" required>
            </div>
            <div class="field">
              <input type="password" placeholder="Mot de passe" name="password" required>
            </div>
            <div class="field">
              <label for="fichier">Photo:</label>
              <input type="file" placeholder="Photo" name="fichier" accept="image/*" required>
            </div>
            <br>
            <div class="field">
              <input type="text" placeholder="Nom de l'entreprise" name="nomEntreprise" required>
            </div>
            <div class="field">
              <input type="text" placeholder="Secteur d'activité" name="secteurActivite" required>
            </div>
            <div class="field">
              <input type="text" placeholder="localisation" name="localisation" required>
            </div>
            <div class="field">
              <input type="text" placeholder="Adresse mail de l'entreprise" name="AdresseMail" required>
            </div>
            <div class="field">
              <input type="tel" placeholder="Numéro de téléphone" name="numeroTelephone" required>
            </div>
            <div class="field">
              <input type="number" placeholder="ICE" name="ice" required>
            </div>
            <div class="field">
              <input type="number" placeholder="Registre de Commerce" name="registreCommerce" required>
            </div>
            <br>
            <div class="field btn">
              <div class="btn-layer"></div>
              <input type="submit" value="Signup" name="sub">
            </div>
            <div class="signup-link"><a href="../home.php"><h4>Retour à la page d'accueil</h4></a></div>
          </form>
        </div>
      </div>
    </div>
    <script>
         const loginText = document.querySelector(".title-text .login");
      const loginForm = document.querySelector("form.login");
      const loginBtn = document.querySelector("label.login");
      const signupBtn = document.querySelector("label.signup");
      const signupLink = document.querySelector("form .signup-link a");
      signupBtn.onclick = (()=>{
        loginForm.style.marginLeft = "-50%";
        loginText.style.marginLeft = "-50%";
      });
      loginBtn.onclick = (()=>{
        loginForm.style.marginLeft = "0%";
        loginText.style.marginLeft = "0%";
      });
      signupLink.onclick = (()=>{
        signupBtn.click();
        return false;
      });

    </script>
</body>
</html>