-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : jeu. 01 août 2024 à 16:52
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `mfzdb`
--

-- --------------------------------------------------------

--
-- Structure de la table `achat`
--

CREATE TABLE `achat` (
  `ID_ACHAT` int(11) NOT NULL,
  `NOM DE PRODUIT` varchar(40) NOT NULL,
  `DESCRIPTION` varchar(100) NOT NULL,
  `FICHIER` varchar(25) NOT NULL,
  `QUANTITE` int(11) NOT NULL,
  `ID_ADMIN` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `achat`
--

INSERT INTO `achat` (`ID_ACHAT`, `NOM DE PRODUIT`, `DESCRIPTION`, `FICHIER`, `QUANTITE`, `ID_ADMIN`) VALUES
(9, 'Cable HDMI', 'Le câble HDMI est la solution idéale pour connecter vos appareils audiovisuels ', 'Cable HDMI.pdf', 20, 1),
(10, 'Câble USB pour voiture', 'Permet de recharger vos appareils mobiles (téléphone, tablette, GPS) pendant vos trajets. Assurez-vo', 'Câble USB pour voiture.pd', 30, 1);

-- --------------------------------------------------------

--
-- Structure de la table `admin`
--

CREATE TABLE `admin` (
  `ID_ADMIN` int(11) NOT NULL,
  `LOGIN` varchar(50) NOT NULL,
  `PASSWORD` varchar(50) NOT NULL,
  `SEWZ` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `admin`
--

INSERT INTO `admin` (`ID_ADMIN`, `LOGIN`, `PASSWORD`, `SEWZ`) VALUES
(1, 'SEWS MFZ', '123', 'SEWS MFZ'),
(2, 'SEWS MAROC', '321', 'SEWS MAROC');

-- --------------------------------------------------------

--
-- Structure de la table `compte_fournisseur`
--

CREATE TABLE `compte_fournisseur` (
  `ID_COMPTE_FOURNISSEUR` int(11) NOT NULL,
  `NOM` varchar(100) NOT NULL,
  `PRENOM` varchar(100) NOT NULL,
  `ADRESSE MAIL` varchar(100) NOT NULL,
  `PASSWORD` varchar(100) NOT NULL,
  `PHOTO` varchar(50) NOT NULL,
  `NOM ENTREPRISE` varchar(20) NOT NULL,
  `LOCALISATION` varchar(100) NOT NULL,
  `EMAIL ENTREPRISE` varchar(20) NOT NULL,
  `NUMERO DE TELEPHONE` int(11) NOT NULL,
  `SECTEUR ACTIVITE` varchar(60) NOT NULL,
  `ICE` int(11) NOT NULL,
  `REGISTRE DE COMMERCE` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `compte_fournisseur`
--

INSERT INTO `compte_fournisseur` (`ID_COMPTE_FOURNISSEUR`, `NOM`, `PRENOM`, `ADRESSE MAIL`, `PASSWORD`, `PHOTO`, `NOM ENTREPRISE`, `LOCALISATION`, `EMAIL ENTREPRISE`, `NUMERO DE TELEPHONE`, `SECTEUR ACTIVITE`, `ICE`, `REGISTRE DE COMMERCE`) VALUES
(22, 'Salhi', 'Yasser', 'yasser.salhi@uit.ac.ma', '123', 'Salhi.jpeg', 'Entreprise 1', 'Rabat', 'entreprise1@gmail.co', 690586860, 'Industrie Électrique et Électronique ', 2147483647, 2147483647),
(23, 'yasser', 'salhi', 'salhiyasser25@gmail.com', '123', 'yasser.jpg', 'Entreprise 2', 'Tanger', 'entreprise2@gmail.co', 690586860, 'Télécommunications ', 2147483647, 6262);

-- --------------------------------------------------------

--
-- Structure de la table `departements cibles`
--

CREATE TABLE `departements cibles` (
  `ID_DEPARTEMENT` int(11) NOT NULL,
  `DEPARTEMENT CIBLE` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `departements cibles`
--

INSERT INTO `departements cibles` (`ID_DEPARTEMENT`, `DEPARTEMENT CIBLE`) VALUES
(1, 'Le Département Ingénierie'),
(2, 'Le Département Des Ressources Humaines'),
(3, 'Le Département Qualité'),
(4, 'Le département Production'),
(5, 'Le Département IT'),
(6, 'Le Département Finance');

-- --------------------------------------------------------

--
-- Structure de la table `fournisseur`
--

CREATE TABLE `fournisseur` (
  `ID_FOURNISSEUR` int(11) NOT NULL,
  `PRODUITS ET SERVICES` varchar(50) NOT NULL,
  `FICHIER` varchar(50) NOT NULL,
  `DESCRIPTION` text NOT NULL,
  `SEWZ` varchar(60) NOT NULL,
  `JUSTIFICATION` varchar(50) NOT NULL,
  `APPROUVE` varchar(255) DEFAULT 'Pas encore',
  `ID_COMPTE_FOURNISSEUR` int(11) NOT NULL,
  `DATE_SUBMISSION` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `fournisseur`
--

INSERT INTO `fournisseur` (`ID_FOURNISSEUR`, `PRODUITS ET SERVICES`, `FICHIER`, `DESCRIPTION`, `SEWZ`, `JUSTIFICATION`, `APPROUVE`, `ID_COMPTE_FOURNISSEUR`, `DATE_SUBMISSION`) VALUES
(45, 'cable aislado', '22_cable aislado.pdf', 'Assurez la sécurité et la fiabilité de vos projets avec nos câbles isolés de haute qualité, disponibles dans une large gamme de modèles adaptés à vos besoins spécifiques. Contactez-nous pour une offre personnalisée.', 'SEWS MFZ', 'Nous disposons désormais de la quantité nécessaire', 'Désapprouvé', 22, '2024-07-28 16:14:42'),
(46, 'cable ethernet', '22_cable ethernet.pdf', 'Le câble Ethernet est le choix fiable pour une connexion Internet filaire rapide et stable, idéal pour les jeux, le streaming et le travail à domicile.', 'SEWS MFZ', 'aa', 'Approuvé', 22, '2024-07-28 21:32:53');

-- --------------------------------------------------------

--
-- Structure de la table `fournisseur_departement`
--

CREATE TABLE `fournisseur_departement` (
  `ID_FOURNISSEUR` int(11) NOT NULL,
  `ID_DEPARTEMENT` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `fournisseur_departement`
--

INSERT INTO `fournisseur_departement` (`ID_FOURNISSEUR`, `ID_DEPARTEMENT`) VALUES
(45, 3),
(45, 4),
(46, 4),
(46, 5);

-- --------------------------------------------------------

--
-- Structure de la table `interet_fournisseur`
--

CREATE TABLE `interet_fournisseur` (
  `id` int(11) NOT NULL,
  `date_interet` datetime DEFAULT current_timestamp(),
  `ID_ACHAT` int(11) NOT NULL,
  `ID_COMPTE_FOURNISSEUR` int(11) NOT NULL,
  `etat` enum('accepté','refusé','pas encore') DEFAULT 'pas encore',
  `justification` text DEFAULT NULL,
  `PRIX_UNITAIRE` int(11) NOT NULL,
  `PRIX_TOTAL` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `interet_fournisseur`
--

INSERT INTO `interet_fournisseur` (`id`, `date_interet`, `ID_ACHAT`, `ID_COMPTE_FOURNISSEUR`, `etat`, `justification`, `PRIX_UNITAIRE`, `PRIX_TOTAL`) VALUES
(22, '2024-07-28 21:43:09', 9, 22, 'accepté', NULL, 10, 200),
(24, '2024-07-28 21:50:27', 10, 22, 'accepté', NULL, 5, 150),
(25, '2024-07-28 22:33:15', 9, 23, 'refusé', 'Nous avons sélectionné une autre offre en raison d\'un prix unitaire plus bas.', 6, 120),
(26, '2024-07-28 22:33:19', 10, 23, 'accepté', NULL, 6, 180);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `achat`
--
ALTER TABLE `achat`
  ADD PRIMARY KEY (`ID_ACHAT`),
  ADD KEY `ID_ADMIN` (`ID_ADMIN`);

--
-- Index pour la table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`ID_ADMIN`);

--
-- Index pour la table `compte_fournisseur`
--
ALTER TABLE `compte_fournisseur`
  ADD PRIMARY KEY (`ID_COMPTE_FOURNISSEUR`);

--
-- Index pour la table `departements cibles`
--
ALTER TABLE `departements cibles`
  ADD PRIMARY KEY (`ID_DEPARTEMENT`);

--
-- Index pour la table `fournisseur`
--
ALTER TABLE `fournisseur`
  ADD PRIMARY KEY (`ID_FOURNISSEUR`),
  ADD KEY `ID_COMPTE_FOURNISSEUR` (`ID_COMPTE_FOURNISSEUR`);

--
-- Index pour la table `fournisseur_departement`
--
ALTER TABLE `fournisseur_departement`
  ADD PRIMARY KEY (`ID_FOURNISSEUR`,`ID_DEPARTEMENT`),
  ADD KEY `ID_DEPARTEMENT` (`ID_DEPARTEMENT`);

--
-- Index pour la table `interet_fournisseur`
--
ALTER TABLE `interet_fournisseur`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ID_ACHAT` (`ID_ACHAT`),
  ADD KEY `ID_COMPTE_FOURNISSEUR` (`ID_COMPTE_FOURNISSEUR`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `achat`
--
ALTER TABLE `achat`
  MODIFY `ID_ACHAT` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT pour la table `admin`
--
ALTER TABLE `admin`
  MODIFY `ID_ADMIN` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `compte_fournisseur`
--
ALTER TABLE `compte_fournisseur`
  MODIFY `ID_COMPTE_FOURNISSEUR` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT pour la table `departements cibles`
--
ALTER TABLE `departements cibles`
  MODIFY `ID_DEPARTEMENT` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `fournisseur`
--
ALTER TABLE `fournisseur`
  MODIFY `ID_FOURNISSEUR` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;

--
-- AUTO_INCREMENT pour la table `interet_fournisseur`
--
ALTER TABLE `interet_fournisseur`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `achat`
--
ALTER TABLE `achat`
  ADD CONSTRAINT `achat_ibfk_1` FOREIGN KEY (`ID_ADMIN`) REFERENCES `admin` (`ID_ADMIN`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `fournisseur`
--
ALTER TABLE `fournisseur`
  ADD CONSTRAINT `fournisseur_ibfk_1` FOREIGN KEY (`ID_COMPTE_FOURNISSEUR`) REFERENCES `compte_fournisseur` (`ID_COMPTE_FOURNISSEUR`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `fournisseur_departement`
--
ALTER TABLE `fournisseur_departement`
  ADD CONSTRAINT `fournisseur_departement_ibfk_1` FOREIGN KEY (`ID_FOURNISSEUR`) REFERENCES `fournisseur` (`ID_FOURNISSEUR`),
  ADD CONSTRAINT `fournisseur_departement_ibfk_2` FOREIGN KEY (`ID_DEPARTEMENT`) REFERENCES `departements cibles` (`ID_DEPARTEMENT`);

--
-- Contraintes pour la table `interet_fournisseur`
--
ALTER TABLE `interet_fournisseur`
  ADD CONSTRAINT `interet_fournisseur_ibfk_1` FOREIGN KEY (`ID_ACHAT`) REFERENCES `achat` (`ID_ACHAT`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `interet_fournisseur_ibfk_2` FOREIGN KEY (`ID_COMPTE_FOURNISSEUR`) REFERENCES `compte_fournisseur` (`ID_COMPTE_FOURNISSEUR`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
