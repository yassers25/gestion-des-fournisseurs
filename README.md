# Application Web de Gestion des Fournisseurs

## Description
Le projet consiste à développer une application web pour la gestion des fournisseurs. L'objectif est de centraliser les données des fournisseurs, automatiser les notifications et faciliter la gestion des propositions via une interface web intuitive.

---

## Comptes Administrateurs
Pour vous connecter à la page d'administration, utilisez les informations suivantes :

### Compte 1
- **Login :** sewz mfz
- **Mot de passe :** 123

### Compte 2
- **Login :** sewz maroc
- **Mot de passe :** 321

---

## Guide d'Installation et de Configuration

### 1. Création de la Base de Données
1. Créez une base de données nommée `mfzdb`.
2. Importez le fichier SQL fourni dans cette base de données pour créer les tables et les données nécessaires.

### 2. Configuration des Informations Sensibles
Pour configurer les informations sensibles (comme l'email), modifiez les fichiers suivants dans le projet :

#### Fichiers à Modifier
- **Fournisseur/form.php** (lignes 101, 102, 109)
- **Admin/ChangerEtat.php** (lignes 67, 68, 72)
- **Admin/emailfunctions.php** (lignes 35, 36, 42, 126, 127, 134)

#### Instructions de Modification
Remplacez les valeurs par vos informations comme suit :
```php
$mail->Username = ''; // Remplacez par votre adresse email
$mail->Password = 'votre_mot_de_passe'; // Remplacez par votre mot de passe
$mail->setFrom('votre_email@example.com', 'Sews'); // Remplacez par votre adresse email
