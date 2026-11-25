<?php
session_start();
require_once __DIR__ . '/fonctions.php';

initialiserDonnees();

if (estConnecte()) {
    header("Location: dashboard.php");
    exit;
}

$erreur = '';
$username = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim((string) ($_POST['username'] ?? ''));
    $password = (string) ($_POST['password'] ?? '');

    if (
        isset($_SESSION['utilisateurs'][$username]) &&
        password_verify($password, $_SESSION['utilisateurs'][$username])
    ) {
        $_SESSION['username'] = $username;
        $_SESSION['connecte'] = true;
        header("Location: dashboard.php");
        exit;
    } else {
        $erreur = "Identifiants incorrects.";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion</title>
</head>
<body>
    <h1>Connexion</h1>
    <?php if ($erreur !== ''): ?>
        <p><?= htmlspecialchars($erreur, ENT_QUOTES, 'UTF-8') ?></p>
    <?php endif; ?>
    <form method="POST">
        <label for="username">Nom d'utilisateur :</label>
        <input id="username" type="text" name="username" value="<?= htmlspecialchars($username, ENT_QUOTES, 'UTF-8') ?>" required><br><br>

        <label for="password">Mot de passe :</label>
        <input id="password" type="password" name="password" required><br><br>

        <button type="submit">Se connecter</button>
    </form>
    <p><a href="inscription.php">Créer un compte</a></p>
</body>
</html>