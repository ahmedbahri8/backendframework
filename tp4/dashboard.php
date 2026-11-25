<?php
session_start();
require_once __DIR__ . '/fonctions.php';

protegerPage();
initialiserDonnees();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Tableau de bord</title>
</head>
<body>
    <h1>Tableau de bord - Gestion des étudiants</h1>
    <p>Bienvenue <?= htmlspecialchars($_SESSION['username'], ENT_QUOTES, 'UTF-8') ?></p>
    <p>Utilisateur connecté : <?= htmlspecialchars($_SESSION['username'], ENT_QUOTES, 'UTF-8') ?></p>

    <ul>
        <li><a href="liste.php">Liste des étudiants</a></li>
        <li><a href="ajouter.php">Ajouter un étudiant</a></li>
        <li><a href="rechercher.php">Rechercher un étudiant</a></li>
        <li><a href="profil.php">Mon profil</a></li>
        <li><a href="modifier-mot-de-passe.php">Modifier mon mot de passe</a></li>
        <li><a href="logout.php">Se déconnecter</a></li>
    </ul>
</body>
</html>