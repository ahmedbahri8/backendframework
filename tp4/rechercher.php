<?php
session_start();
require_once __DIR__ . '/fonctions.php';

protegerPage();
initialiserDonnees();

$nomRecherche = trim((string) ($_GET['nom'] ?? ''));
$resultats = [];

if ($nomRecherche !== '') {
    foreach ($_SESSION['etudiants'] as $etudiant) {
        if (stripos($etudiant['nom'], $nomRecherche) !== false) {
            $resultats[] = $etudiant;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Rechercher un étudiant</title>
</head>
<body>
    <h1>Rechercher un étudiant</h1>
    <p><a href="dashboard.php">Tableau de bord</a> | <a href="liste.php">Liste</a></p>

    <form method="GET">
        <label for="nom">Nom :</label>
        <input id="nom" type="text" name="nom" value="<?= htmlspecialchars($nomRecherche, ENT_QUOTES, 'UTF-8') ?>">
        <button type="submit">Rechercher</button>
    </form>

    <?php if ($nomRecherche !== ''): ?>
        <?php if (empty($resultats)): ?>
            <p>Aucun étudiant trouvé.</p>
        <?php else: ?>
            <table border="1" cellpadding="6">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>Âge</th>
                        <th>Formation</th>
                        <th>Moyenne</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($resultats as $etudiant): ?>
                        <tr>
                            <td><?= (int) $etudiant['id'] ?></td>
                            <td><?= htmlspecialchars($etudiant['nom'], ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= htmlspecialchars($etudiant['prenom'], ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= (int) $etudiant['age'] ?></td>
                            <td><?= htmlspecialchars($etudiant['formation'], ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= htmlspecialchars(number_format((float) $etudiant['moyenne'], 2), ENT_QUOTES, 'UTF-8') ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    <?php endif; ?>
</body>
</html>