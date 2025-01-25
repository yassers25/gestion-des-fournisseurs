<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Creative Navbar</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        .navbar {
            position: fixed;
            width: 100%;
            z-index: 1000;
            background-color: #1a5a9a;
            padding: 1rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .navbar .logo {
            font-size: 1.5rem;
            font-weight: bold;
            margin-left: 29px;
            color: #fff;
        }

        .navbar ul {
            list-style: none;
            display: flex;
            margin: 0;
            padding: 0;
            margin-left: 20px;
            transform: translateX(-100px);
            position: relative;
        }

        .navbar ul li {
            margin: 0 1rem;
            position: relative;
        }

        .navbar ul li a {
            color: #fff;
            text-decoration: none;
            font-size: 1.2rem;
            position: relative;
            padding-bottom: 5px;
            transition: all 0.3s ease-in-out;
        }

        .navbar ul li a::before {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            background-color: #fff;
            visibility: hidden;
            transition: all 0.3s ease-in-out;
        }

        .navbar ul li a.active::before,
        .navbar ul li a:hover::before {
            visibility: visible;
            width: 100%;
            transition: all 0.3s ease-in-out;
        }

        .navbar ul li ul {
            display: none;
            position: absolute;
            background-color: #1a5a9a;
            top: 100%;
            left: 0;
            padding: 0;
            margin: 0;
            list-style: none;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            border-radius: 4px;
            z-index: 1000;
            min-width: 200px;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.5s ease, visibility 0.5s ease;
        }

        .navbar ul li:hover ul {
            display: block;
            opacity: 1;
            visibility: visible;
        }

        .navbar ul li ul li {
            width: 100%;
            margin: 0;
        }

        .navbar ul li ul li a {
            padding: 10px;
            font-size: 1rem;
            color: #fff;
            display: block;
            transition: background-color 0.3s ease;
        }

        .navbar ul li ul li a:hover {
            background-color: #005a8d;
        }

        .navbar .toggle-button {
            display: none;
            flex-direction: column;
            cursor: pointer;
        }

        .navbar .toggle-button div {
            width: 30px;
            height: 3px;
            background-color: #fff;
            margin: 5px 0;
            transition: all 0.3s;
        }

        @media (max-width: 768px) {
            .navbar ul {
                display: none;
                flex-direction: column;
                width: 100%;
                background-color: #333;
                position: absolute;
                top: 60px;
                left: 0;
                transition: transform 0.3s ease-in-out;
                transform: translateX(-100%);
            }

            .navbar ul.show {
                display: flex;
                transform: translateX(0);
            }

            .navbar ul li {
                text-align: center;
                margin: 1rem 0;
            }

            .navbar ul li ul {
                position: static;
                opacity: 1;
                visibility: visible;
                display: none;
                box-shadow: none;
            }

            .navbar ul li:hover ul {
                display: block;
            }

            .navbar .toggle-button {
                display: flex;
            }
        }

        .navbar .logo .small-text {
            font-size: 18px; /* Taille plus petite pour "espace admin" */
        }

       /* Flèche uniquement pour les éléments "Formulaire" et "Demandes" */
.navbar ul li a.has-submenu::after {
    content: ' ▼';
    display: inline-block;
    margin-left: 4px; /* Réduit l'espace entre le texte et la flèche */
    vertical-align: middle;
    border-width: 3px; /* Réduit la taille de la flèche */
    border-style: solid;
    border-color: transparent transparent #fff transparent;
    transition: transform 0.5s ease;
    font-size: 0.9rem; /* Réduit la taille de la flèche */
}

.navbar ul li a.has-submenu:hover::after {
    transform: rotate(-180deg);
}


    </style>
</head>
<body>
    <?php 
    // Get the current page's filename 
    $currentPage = basename($_SERVER['PHP_SELF']);
    ?>

    <nav class="navbar">
        <div class="logo">SEWS-E <span class="small-text">espace administrateur</span></div>
        <ul id="nav-links">
            <li><a href="../home.php" <?php if ($currentPage == '../home.php') echo 'class="active"'; ?>>Home</a></li>
            <li><a href="dashboard.php" <?php if ($currentPage == 'dashboard.php') echo 'class="active"'; ?>>Dashboard</a></li>
            <li><a href="Proposition_Fournisseur.php" <?php if ($currentPage == 'Proposition_Fournisseur.php') echo 'class="active"'; ?>>Proposition des fournisseurs</a></li>
            <li>
                <a href="acheteur.php" class="has-submenu">Acheteur</a>
                <ul>
                    <li><a href="acheteur_formulaire.php" <?php if ($currentPage == 'acheteur_formulaire.php') echo 'class="active"'; ?>>formulaire acheteur</a></li>
                    <li><a href="admin_interets.php" <?php if ($currentPage == 'admin_interets.php') echo 'class="active"'; ?>>Intererts aux demandes</a></li>
                </ul>
            </li>
            
            <li><a href="deconnexion_a.php" <?php if ($currentPage == 'deconnexion_a.php') echo 'class="active"'; ?>>Déconnexion</a></li>
        </ul>
        <div class="toggle-button" id="toggle-button">
            <div></div>
            <div></div>
            <div></div>
        </div>
    </nav>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const toggleButton = document.getElementById('toggle-button');
            const navLinks = document.getElementById('nav-links');

            toggleButton.addEventListener('click', function () {
                navLinks.classList.toggle('show'); 
            });
        });
    </script>
</body>
</html>
