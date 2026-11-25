<?php
require_once __DIR__ . '/connexion.php';
require_once __DIR__ . '/fonctions.php';

$idBrut = $_GET['id'] ?? $_POST['id'] ?? null;
$id = filter_var($idBrut, FILTER_VALIDATE_INT);

$etudiant = null;
$erreurs = [];
$succes = '';

if ($id === false || $id <= 0) {
    $erreurs[] = "Identifiant d'étudiant invalide.";
} else {
    $etudiant = getEtudiantById($pdo, $id);
    if ($etudiant === null) {
        $erreurs[] = "Aucun étudiant trouvé pour cet identifiant.";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $etudiant !== null) {
    $resultat = validerEtudiant($_POST);
    $valeurs = $resultat['valeurs'];
    $erreurs = $resultat['erreurs'];

    if (empty($erreurs)) {
        try {
            $sql = "UPDATE etudiants
                    SET nom = :nom,
                        prenom = :prenom,
                        age = :age,
                        formation = :formation,
                        moyenne = :moyenne
                    WHERE id = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                'nom' => $valeurs['nom'],
                'prenom' => $valeurs['prenom'],
                'age' => $valeurs['age'],
                'formation' => $valeurs['formation'],
                'moyenne' => $valeurs['moyenne'],
                'id' => $id
            ]);

            $succes = "Les informations de l'étudiant ont été mises à jour.";
            $etudiant = getEtudiantById($pdo, $id);
        } catch (PDOException $e) {
            $erreurs[] = "Une erreur est survenue lors de la modification.";
        }
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
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <h1>Modifier un étudiant</h1>
        <nav class="menu">
            <a href="index.php" class="btn">Accueil</a>
            <a href="liste.php" class="btn">Liste des étudiants</a>
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

        <?php if ($etudiant !== null): ?>
            <form method="POST" action="modifier.php?id=<?= (int) $etudiant['id'] ?>" class="form-card">
                <input type="hidden" name="id" value="<?= (int) $etudiant['id'] ?>">

                <label for="nom">Nom :</label>
                <input id="nom" type="text" name="nom" value="<?= htmlspecialchars((string) $etudiant['nom'], ENT_QUOTES, 'UTF-8') ?>" required>

                <label for="prenom">Prénom :</label>
                <input id="prenom" type="text" name="prenom" value="<?= htmlspecialchars((string) $etudiant['prenom'], ENT_QUOTES, 'UTF-8') ?>" required>

                <label for="age">Âge :</label>
                <input id="age" type="number" name="age" min="16" max="100" value="<?= htmlspecialchars((string) $etudiant['age'], ENT_QUOTES, 'UTF-8') ?>" required>

                <label for="formation">Formation :</label>
                <input id="formation" type="text" name="formation" value="<?= htmlspecialchars((string) $etudiant['formation'], ENT_QUOTES, 'UTF-8') ?>" required>

                <label for="moyenne">Moyenne :</label>
                <input id="moyenne" type="number" name="moyenne" min="0" max="20" step="0.01" value="<?= htmlspecialchars((string) $etudiant['moyenne'], ENT_QUOTES, 'UTF-8') ?>" required>

                <button type="submit" class="btn">Enregistrer</button>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>