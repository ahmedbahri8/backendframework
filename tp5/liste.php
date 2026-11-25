<?php
require_once __DIR__ . '/connexion.php';
require_once __DIR__ . '/fonctions.php';

$minMoyenneBrut = trim((string) ($_GET['min_moyenne'] ?? ''));
$minMoyenne = null;
$erreurFiltre = '';

if ($minMoyenneBrut !== '') {
    $val = filter_var($minMoyenneBrut, FILTER_VALIDATE_FLOAT);
    if ($val !== false && $val >= 0 && $val <= 20) {
        $minMoyenne = $val;
    } else {
        $erreurFiltre = "La moyenne minimale doit être comprise entre 0 et 20.";
    }
}

$tri = (string) ($_GET['tri'] ?? 'nom');
$etudiants = getEtudiants($pdo, $minMoyenne, $tri);
$stats = getStatistiques($pdo);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des étudiants</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <h1>Liste des étudiants</h1>
        <nav class="menu">
            <a href="index.php" class="btn">Accueil</a>
            <a href="ajouter.php" class="btn">Ajouter un étudiant</a>
            <a href="rechercher.php" class="btn">Rechercher</a>
        </nav>

        <div class="stats">
            <p><strong>Nombre total :</strong> <?= (int) $stats['nombre_etudiants'] ?></p>
            <p><strong>Moyenne de la classe :</strong> <?= $stats['moyenne_classe'] !== null ? number_format((float) $stats['moyenne_classe'], 2) : '-' ?></p>
            <p><strong>Meilleure moyenne :</strong> <?= $stats['meilleure_moyenne'] !== null ? number_format((float) $stats['meilleure_moyenne'], 2) : '-' ?></p>
            <p><strong>Plus faible moyenne :</strong> <?= $stats['plus_faible_moyenne'] !== null ? number_format((float) $stats['plus_faible_moyenne'], 2) : '-' ?></p>
        </div>

        <form method="GET" class="form-inline">
            <label for="min_moyenne">Moyenne minimale :</label>
            <input id="min_moyenne" type="number" name="min_moyenne" min="0" max="20" step="0.01" value="<?= htmlspecialchars($minMoyenneBrut, ENT_QUOTES, 'UTF-8') ?>">

            <label for="tri">Trier par :</label>
            <select id="tri" name="tri">
                <option value="nom" <?= $tri === 'nom' ? 'selected' : '' ?>>Nom (A-Z)</option>
                <option value="moyenne_desc" <?= $tri === 'moyenne_desc' ? 'selected' : '' ?>>Moyenne décroissante</option>
                <option value="moyenne_asc" <?= $tri === 'moyenne_asc' ? 'selected' : '' ?>>Moyenne croissante</option>
            </select>

            <button type="submit" class="btn">Appliquer</button>
            <a href="liste.php" class="btn btn-secondary">Réinitialiser</a>
        </form>

        <?php if ($erreurFiltre !== ''): ?>
            <p class="message erreur"><?= htmlspecialchars($erreurFiltre, ENT_QUOTES, 'UTF-8') ?></p>
        <?php endif; ?>

        <?php if (empty($etudiants)): ?>
            <p class="message">Aucun étudiant trouvé.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>Âge</th>
                        <th>Formation</th>
                        <th>Moyenne</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($etudiants as $etudiant): ?>
                        <tr>
                            <td><?= (int) $etudiant['id'] ?></td>
                            <td><?= htmlspecialchars($etudiant['nom'], ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= htmlspecialchars($etudiant['prenom'], ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= (int) $etudiant['age'] ?></td>
                            <td><?= htmlspecialchars($etudiant['formation'], ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= htmlspecialchars(number_format((float) $etudiant['moyenne'], 2), ENT_QUOTES, 'UTF-8') ?></td>
                            <td class="actions">
                                <a href="modifier.php?id=<?= (int) $etudiant['id'] ?>" class="btn btn-small">Modifier</a>
                                <form method="POST" action="supprimer.php" class="inline-form" onsubmit="return confirm('Confirmer la suppression de cet étudiant ?');">
                                    <input type="hidden" name="id" value="<?= (int) $etudiant['id'] ?>">
                                    <button type="submit" class="btn btn-danger btn-small">Supprimer</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</body>
</html>