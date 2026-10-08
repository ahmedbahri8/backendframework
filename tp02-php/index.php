<?php
$etudiants = ["Ahmed", "Sami", "Nour", "Amine"];
require_once __DIR__ . "/fonctions.php";
enteteHtml("TP 2 — Tableaux et fonctions en PHP");
echo "<p>Premier élément du tableau : " . $etudiants[0] . "</p>";
?>
<ul>
  <li><a href="exercices/tableaux.php">Activités 1 à 8 : tableaux</a></li>
  <li><a href="exercices/etudiants.php">Activités 9 à 18 : fonctions</a></li>
  <li><a href="gestion-etudiants.php">Activité 19 : mini-projet Gestion des étudiants</a></li>
  <li><a href="exercices/notes.php">Maison 1 : gestion des notes</a></li>
  <li><a href="exercices/classe.php">Maison 2 et 3 : classe et statistiques</a></li>
</ul>
<?php piedHtml(); ?>
