<?php
session_start();
require_once __DIR__ . '/fonctions.php';

initialiserDonnees();

$username = '';
$erreurs = [];
$succes = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim((string) ($_POST['username'] ?? ''));
    $password = (string) ($_POST['password'] ?? '');
    $confirmPassword = (string) ($_POST['confirm_password'] ?? '');

    if ($username === '') {
        $erreurs[] = "Le nom d'utilisateur est obligatoire.";
    } elseif (isset($_SESSION['utilisateurs'][$username])) {
        $erreurs[] = "Ce nom d'utilisateur existe déjà.";
    }

    if (strlen($password) < 6) {
        $erreurs[] = "Le mot de passe doit contenir au moins 6 caractères.";
    }

    if ($password !== $confirmPassword) {
        $erreurs[] = "Les deux mots de passe ne sont pas identiques.";
    }

    if (empty($erreurs)) {
        $_SESSION['utilisateurs'][$username] = password_hash($password, PASSWORD_DEFAULT);
        $succes = "Compte créé avec succès. Vous pouvez maintenant vous connecter.";
        $username = '';
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Inscription</title>
</head>
<body>
    <h1>Inscription</h1>
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
        <label for="username">Nom d'utilisateur :</label>
        <input id="username" type="text" name="username" value="<?= htmlspecialchars($username, ENT_QUOTES, 'UTF-8') ?>" required><br><br>

        <label for="password">Mot de passe :</label>
        <input id="password" type="password" name="password" minlength="6" required><br><br>

        <label for="confirm_password">Confirmation du mot de passe :</label>
        <input id="confirm_password" type="password" name="confirm_password" minlength="6" required><br><br>

        <button type="submit">S'inscrire</button>
    </form>
    <p><a href="login.php">Retour à la connexion</a></p>
</body>
</html>