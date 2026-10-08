<?php
require_once __DIR__ . "/fonctions.php";

$etudiants = obtenirEtudiants();

$nomRecherche = trim($_GET['nom'] ?? '');
$minRecu = trim($_GET['min'] ?? '');
$minValide = $minRecu !== '' && is_numeric($minRecu);

enteteHtml("Gestion des étudiants");
?>
<h2>Tous les étudiants</h2>
<?php afficherTableauEtudiants($etudiants); ?>

<h2>Statistiques</h2>
<ul>
  <li>Nombre d'étudiants : <strong><?= count($etudiants) ?></strong></li>
  <li>Moyenne de la classe : <strong><?= round(calculerMoyenneClasse($etudiants), 2) ?></strong></li>
  <li>Nombre d'admis : <strong><?= compterAdmis($etudiants) ?></strong> (<?= round(pourcentageAdmis($etudiants), 1) ?> %)</li>
  <?php $meilleur = trouverMeilleurEtudiant($etudiants); ?>
  <?php if ($meilleur !== null): ?>
  <li>Meilleur étudiant : <strong><?= h($meilleur['prenom'] . ' ' . $meilleur['nom']) ?></strong> (<?= h($meilleur['moyenne']) ?>)</li>
  <?php endif; ?>
</ul>

<h2>Recherche par nom</h2>
<form method="get">
  <input type="text" name="nom" placeholder="Nom de famille" value="<?= h($nomRecherche) ?>">
  <button type="submit">Rechercher</button>
</form>
<?php
if ($nomRecherche !== '') {
    $trouve = rechercherEtudiant($etudiants, $nomRecherche);
    if ($trouve !== null) {
        afficherEtudiant($trouve);
    } else {
        echo "<p class='ko'>Étudiant introuvable.</p>";
    }
}
?>

<h2>Filtrer par moyenne minimale</h2>
<form method="get">
  <input type="number" name="min" step="0.01" min="0" max="20" placeholder="ex. 14" value="<?= h($minRecu) ?>">
  <button type="submit">Filtrer</button>
</form>
<?php
if ($minRecu !== '') {
    if ($minValide) {
        $filtres = filtrerParMoyenne($etudiants, (float) $minRecu);
        echo "<p>" . count($filtres) . " étudiant(s) avec une moyenne ≥ " . h($minRecu) . " :</p>";
        afficherTableauEtudiants($filtres);
    } else {
        echo "<p class='ko'>Valeur invalide.</p>";
    }
}
piedHtml();
