<?php
require_once __DIR__ . '/connexion.php';

$message = '';
$type = 'erreur';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $message = "Action non autorisée. La suppression doit être effectuée via le formulaire.";
} else {
    $id = filter_var($_POST['id'] ?? null, FILTER_VALIDATE_INT);

    if ($id === false || $id <= 0) {
        $message = "Identifiant d'étudiant invalide.";
    } else {
        try {
            $sql = "DELETE FROM etudiants WHERE id = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['id' => $id]);

            if ($stmt->rowCount() > 0) {
                $message = "L'étudiant a été supprimé avec succès.";
                $type = 'succes';
            } else {
                $message = "Aucun étudiant trouvé pour cet identifiant.";
            }
        } catch (PDOException $e) {
            $message = "Une erreur est survenue lors de la suppression.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Suppression d'un étudiant</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <h1>Suppression d'un étudiant</h1>
        <p class="message <?= htmlspecialchars($type, ENT_QUOTES, 'UTF-8') ?>">
            <?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?>
        </p>
        <nav class="menu">
            <a href="liste.php" class="btn">Retour à la liste</a>
            <a href="index.php" class="btn btn-secondary">Accueil</a>
        </nav>
    </div>
</body>
</html>