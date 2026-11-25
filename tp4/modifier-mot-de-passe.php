<?php
session_start();
require_once __DIR__ . '/fonctions.php';

protegerPage();
initialiserDonnees();

$erreurs = [];
$succes = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ancien = (string) ($_POST['ancien_password'] ?? '');
    $nouveau = (string) ($_POST['nouveau_password'] ?? '');
    $confirmation = (string) ($_POST['confirm_password'] ?? '');
    $username = $_SESSION['username'];

    if (!password_verify($ancien, $_SESSION['utilisateurs'][$username])) {
        $erreurs[] = "L'ancien mot de passe est incorrect.";
    }
    if (strlen($nouveau) < 6) {
        $erreurs[] = "Le nouveau mot de passe doit contenir au moins 6 caractères.";
    }
    if ($nouveau !== $confirmation) {
        $erreurs[] = "La confirmation ne correspond pas au nouveau mot de passe.";
    }

    if (empty($erreurs)) {
        $_SESSION['utilisateurs'][$username] = password_hash($nouveau, PASSWORD_DEFAULT);
        $succes = "Mot de passe modifié avec succès.";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier le mot de passe</title>
</head>
<body>
    <h1>Modifier mon mot de passe</h1>
    <p>Utilisateur connecté : <?= htmlspecialchars($_SESSION['username'], ENT_QUOTES, 'UTF-8') ?></p>

    <?php if ($succes !== ''): ?>
        <p><?= htmlspecialchars($succes, ENT_QUOTES, 'UTF-8') ?></p>
    <?php endif; ?>
    <?php if (!empty($erreurs)): ?>
        <ul>
            <?php foreach ($erreurs as $erreur): ?>
                <li><?= htmlspecialchars($erreur, ENT_QUOTES, 'UTF-8') ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <form method="POST">
        <label for="ancien_password">Ancien mot de passe :</label>
        <input id="ancien_password" type="password" name="ancien_password" required><br><br>

        <label for="nouveau_password">Nouveau mot de passe :</label>
        <input id="nouveau_password" type="password" name="nouveau_password" minlength="6" required><br><br>

        <label for="confirm_password">Confirmer le nouveau mot de passe :</label>
        <input id="confirm_password" type="password" name="confirm_password" minlength="6" required><br><br>

        <button type="submit">Mettre à jour</button>
    </form>
    <p><a href="dashboard.php">Retour au tableau de bord</a></p>
</body>
</html>