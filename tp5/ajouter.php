<?php
require_once __DIR__ . '/connexion.php';
require_once __DIR__ . '/fonctions.php';

$valeurs = [
    'nom' => '',
    'prenom' => '',
    'age' => '',
    'formation' => '',
    'moyenne' => ''
];
$erreurs = [];
$succes = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $resultat = validerEtudiant($_POST);
    $valeurs = $resultat['valeurs'];
    $erreurs = $resultat['erreurs'];

    if (empty($erreurs)) {
        try {
            $sql = "INSERT INTO etudiants (nom, prenom, age, formation, moyenne)
                    VALUES (:nom, :prenom, :age, :formation, :moyenne)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                'nom' => $valeurs['nom'],
                'prenom' => $valeurs['prenom'],
                'age' => $valeurs['age'],
                'formation' => $valeurs['formation'],
                'moyenne' => $valeurs['moyenne']
            ]);

            $succes = "L'étudiant a été ajouté avec succès.";
            $valeurs = [
                'nom' => '',
                'prenom' => '',
                'age' => '',
                'formation' => '',
                'moyenne' => ''
            ];
        } catch (PDOException $e) {
            $erreurs[] = "Une erreur est survenue lors de l'ajout de l'étudiant.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter un étudiant</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <h1>Ajouter un étudiant</h1>
        <nav class="menu">
            <a href="index.php" class="btn">Accueil</a>
            <a href="liste.php" class="btn">Liste des étudiants</a>
            <a href="rechercher.php" class="btn">Rechercher</a>
        </nav>

        <?php if ($succes !== ''): ?>
            <p class="message succes"><?= htmlspecialchars($succes, ENT_QUOTES, 'UTF-8') ?></p>
        <?php endif; ?>

        <?php if (!empty($erreurs)): ?>
            <ul class="message erreur">
                <?php foreach ($erreurs as $erreur): ?>
                    <li><?= htmlspecialchars($erreur, ENT_QUOTES, 'UTF-8') ?></li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>

        <form method="POST" class="form-card">
            <label for="nom">Nom :</label>
            <input id="nom" type="text" name="nom" value="<?= htmlspecialchars((string) $valeurs['nom'], ENT_QUOTES, 'UTF-8') ?>" required>

            <label for="prenom">Prénom :</label>
            <input id="prenom" type="text" name="prenom" value="<?= htmlspecialchars((string) $valeurs['prenom'], ENT_QUOTES, 'UTF-8') ?>" required>

            <label for="age">Âge :</label>
            <input id="age" type="number" name="age" min="16" max="100" value="<?= htmlspecialchars((string) $valeurs['age'], ENT_QUOTES, 'UTF-8') ?>" required>

            <label for="formation">Formation :</label>
            <input id="formation" type="text" name="formation" value="<?= htmlspecialchars((string) $valeurs['formation'], ENT_QUOTES, 'UTF-8') ?>" required>

            <label for="moyenne">Moyenne :</label>
            <input id="moyenne" type="number" name="moyenne" min="0" max="20" step="0.01" value="<?= htmlspecialchars((string) $valeurs['moyenne'], ENT_QUOTES, 'UTF-8') ?>" required>

            <button type="submit" class="btn">Ajouter</button>
        </form>
    </div>
</body>
</html>