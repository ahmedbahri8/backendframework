<?php
session_start();
require_once __DIR__ . '/fonctions.php';

protegerPage();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Profil</title>
</head>
<body>
    <h1>Profil</h1>
    <p>Utilisateur connecté : <?= htmlspecialchars($_SESSION['username'], ENT_QUOTES, 'UTF-8') ?></p>
    <p><a href="dashboard.php">Retour au tableau de bord</a></p>
</body>
</html>