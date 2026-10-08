<?php
require_once __DIR__ . "/../fonctions.php";

$etudiants = obtenirEtudiants();
$nom = trim($_GET['nom'] ?? '');
$min = trim($_GET['min'] ?? '');

enteteHtml("Exercices 2 et 3 — Classe et statistiques");
?>
<h2>Tous les étudiants (<?= count($etudiants) ?>)</h2>
<?php afficherTableauEtudiants($etudiants); ?>

<h2>Statistiques</h2>
<ul>
  <li>Moyenne de la classe : <?= round(calculerMoyenneClasse($etudiants), 2) ?></li>
  <li>Nombre d'admis : <?= compterAdmis($etudiants) ?></li>
  <li>Pourcentage d'admis : <?= round(pourcentageAdmis($etudiants), 1) ?> %</li>
  <li>Meilleure moyenne : <?= meilleureMoyenne($etudiants) ?></li>
  <li>Plus faible moyenne : <?= plusFaibleMoyenne($etudiants) ?></li>
  <li>Étudiants avec moyenne ≥ 14 : <?= count(filtrerParMoyenne($etudiants, 14)) ?></li>
</ul>

<h2>Recherche et filtrage</h2>
<form method="get">
  <input name="nom" placeholder="Nom" value="<?= h($nom) ?>">
  <input type="number" name="min" step="0.01" placeholder="Moyenne min." value="<?= h($min) ?>">
  <button type="submit">Valider</button>
</form>
<?php
if ($nom !== '') {
    $e = rechercherEtudiant($etudiants, $nom);
    if ($e !== null) { afficherEtudiant($e); } else { echo "<p class='ko'>Étudiant introuvable.</p>"; }
}
if ($min !== '' && is_numeric($min)) {
    afficherTableauEtudiants(filtrerParMoyenne($etudiants, (float) $min));
}
piedHtml();
