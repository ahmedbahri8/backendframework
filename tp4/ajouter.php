<?php
session_start();
require_once __DIR__ . '/fonctions.php';

protegerPage();
initialiserDonnees();

$valeurs = ['nom' => '', 'prenom' => '', 'age' => '', 'formation' => '', 'moyenne' => ''];
$erreurs = [];
$succes = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $resultat = validerEtudiant($_POST);
    $valeurs = $resultat['valeurs'];
    $erreurs = $resultat['erreurs'];

    if (empty($erreurs)) {
        $id = $_SESSION['next_id']++;
        $_SESSION['etudiants'][$id] = [
            'id' => $id,
            'nom' => $valeurs['nom'],
            'prenom' => $valeurs['prenom'],
            'age' => $valeurs['age'],
            'formation' => $valeurs['formation'],
            'moyenne' => $valeurs['moyenne']
        ];
        $succes = "Étudiant ajouté avec succès.";
        $valeurs = ['nom' => '', 'prenom' => '', 'age' => '', 'formation' => '', 'moyenne' => ''];
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter un étudiant</title>
</head>
<body>
    <h1>Ajouter un étudiant</h1>
    <p><a href="dashboard.php">Tableau de bord</a> | <a href="liste.php">Liste</a></p>

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
        <label for="nom">Nom :</label>
        <input id="nom" type="text" name="nom" value="<?= htmlspecialchars((string) $valeurs['nom'], ENT_QUOTES, 'UTF-8') ?>" required><br><br>

        <label for="prenom">Prénom :</label>
        <input id="prenom" type="text" name="prenom" value="<?= htmlspecialchars((string) $valeurs['prenom'], ENT_QUOTES, 'UTF-8') ?>" required><br><br>

        <label for="age">Âge :</label>
        <input id="age" type="number" name="age" min="16" max="100" value="<?= htmlspecialchars((string) $valeurs['age'], ENT_QUOTES, 'UTF-8') ?>" required><br><br>

        <label for="formation">Formation :</label>
        <input id="formation" type="text" name="formation" value="<?= htmlspecialchars((string) $valeurs['formation'], ENT_QUOTES, 'UTF-8') ?>" required><br><br>

        <label for="moyenne">Moyenne :</label>
        <input id="moyenne" type="number" name="moyenne" min="0" max="20" step="0.01" value="<?= htmlspecialchars((string) $valeurs['moyenne'], ENT_QUOTES, 'UTF-8') ?>" required><br><br>

        <button type="submit">Ajouter</button>
    </form>
</body>
</html>