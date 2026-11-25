<?php
require_once __DIR__ . '/connexion.php';

$nomRecherche = trim((string) ($_GET['nom'] ?? ''));
$etudiants = [];
$rechercheEffectuee = false;

if ($nomRecherche !== '') {
    $rechercheEffectuee = true;
    $sql = "SELECT id, nom, prenom, age, formation, moyenne
            FROM etudiants
            WHERE nom LIKE :nom
            ORDER BY nom, prenom";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'nom' => '%' . $nomRecherche . '%'
    ]);
    $etudiants = $stmt->fetchAll();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Rechercher un étudiant</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <h1>Rechercher un étudiant</h1>
        <nav class="menu">
            <a href="index.php" class="btn">Accueil</a>
            <a href="liste.php" class="btn">Liste des étudiants</a>
            <a href="ajouter.php" class="btn">Ajouter un étudiant</a>
        </nav>

        <form method="GET" class="form-inline">
            <label for="nom">Nom à rechercher :</label>
            <input id="nom" type="text" name="nom" value="<?= htmlspecialchars($nomRecherche, ENT_QUOTES, 'UTF-8') ?>">
            <button type="submit" class="btn">Rechercher</button>
        </form>

        <?php if ($rechercheEffectuee): ?>
            <?php if (empty($etudiants)): ?>
                <p class="message">Aucun étudiant ne correspond à votre recherche.</p>
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
        <?php endif; ?>
    </div>
</body>
</html>