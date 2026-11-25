<?php
session_start();
require_once __DIR__ . '/fonctions.php';

protegerPage();
initialiserDonnees();

$etudiants = $_SESSION['etudiants'];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des étudiants</title>
</head>
<body>
    <h1>Liste des étudiants</h1>
    <p>
        <a href="dashboard.php">Tableau de bord</a> |
        <a href="ajouter.php">Ajouter</a> |
        <a href="rechercher.php">Rechercher</a> |
        <a href="logout.php">Se déconnecter</a>
    </p>

    <?php if (empty($etudiants)): ?>
        <p>Aucun étudiant enregistré.</p>
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
                        <td>
                            <a href="modifier.php?id=<?= (int) $etudiant['id'] ?>">Modifier</a>
                            <form method="POST" action="supprimer.php" style="display:inline;" onsubmit="return confirm('Confirmer la suppression ?');">
                                <input type="hidden" name="id" value="<?= (int) $etudiant['id'] ?>">
                                <button type="submit">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</body>
</html>