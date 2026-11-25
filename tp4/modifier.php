<?php
session_start();
require_once __DIR__ . '/fonctions.php';

protegerPage();
initialiserDonnees();

$id = filter_var($_GET['id'] ?? $_POST['id'] ?? null, FILTER_VALIDATE_INT);
$etudiant = ($id !== false && isset($_SESSION['etudiants'][$id])) ? $_SESSION['etudiants'][$id] : null;
$erreurs = [];
$succes = '';

if ($etudiant === null) {
    $erreurs[] = "Étudiant introuvable.";
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $resultat = validerEtudiant($_POST);
    $valeurs = $resultat['valeurs'];
    $erreurs = $resultat['erreurs'];

    if (empty($erreurs)) {
        $_SESSION['etudiants'][$id] = [
            'id' => $id,
            'nom' => $valeurs['nom'],
            'prenom' => $valeurs['prenom'],
            'age' => $valeurs['age'],
            'formation' => $valeurs['formation'],
            'moyenne' => $valeurs['moyenne']
        ];
        $etudiant = $_SESSION['etudiants'][$id];
        $succes = "Étudiant modifié avec succès.";
    } else {
        $etudiant = array_merge($etudiant, $valeurs);
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier un étudiant</title>
</head>
<body>
    <h1>Modifier un étudiant</h1>
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

    <?php if ($etudiant !== null): ?>
        <form method="POST" action="modifier.php?id=<?= (int) $etudiant['id'] ?>">
            <input type="hidden" name="id" value="<?= (int) $etudiant['id'] ?>">

            <label for="nom">Nom :</label>
            <input id="nom" type="text" name="nom" value="<?= htmlspecialchars((string) $etudiant['nom'], ENT_QUOTES, 'UTF-8') ?>" required><br><br>

            <label for="prenom">Prénom :</label>
            <input id="prenom" type="text" name="prenom" value="<?= htmlspecialchars((string) $etudiant['prenom'], ENT_QUOTES, 'UTF-8') ?>" required><br><br>

            <label for="age">Âge :</label>
            <input id="age" type="number" name="age" min="16" max="100" value="<?= htmlspecialchars((string) $etudiant['age'], ENT_QUOTES, 'UTF-8') ?>" required><br><br>

            <label for="formation">Formation :</label>
            <input id="formation" type="text" name="formation" value="<?= htmlspecialchars((string) $etudiant['formation'], ENT_QUOTES, 'UTF-8') ?>" required><br><br>

            <label for="moyenne">Moyenne :</label>
            <input id="moyenne" type="number" name="moyenne" min="0" max="20" step="0.01" value="<?= htmlspecialchars((string) $etudiant['moyenne'], ENT_QUOTES, 'UTF-8') ?>" required><br><br>

            <button type="submit">Enregistrer</button>
        </form>
    <?php endif; ?>
</body>
</html>