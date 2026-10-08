<?php
require_once __DIR__ . "/../fonctions.php";   // Activité 18 : fonctions séparées
enteteHtml("Activités 9 à 18 — Fonctions");

/* Activité 9 : une seule déclaration (PHP interdit deux fonctions de même nom) ; le paramètre est facultatif */
function afficherBonjour($nom = "")
{
    echo "<p>" . trim("Bonjour " . h($nom)) . " !</p>";
}
echo "<h2>Activité 9 — Première fonction</h2>";
afficherBonjour();
afficherBonjour("Ahmed");
afficherBonjour("Nour");

/* Activité 10 */
function additionner($a, $b)
{
    return $a + $b;
}
echo "<h2>Activité 10 — return</h2>";
$resultat = additionner(10, 20);
echo "<p>10 + 20 = $resultat</p>";

/* Activité 11 */
echo "<h2>Activité 11 — Moyenne de 3 notes</h2>";
echo "<p>(12, 14, 16) → " . calculerMoyenne(12, 14, 16) . "</p>";
echo "<p>(8, 9, 10) → " . round(calculerMoyenne(8, 9, 10), 2) . "</p>";

/* Activité 12 */
echo "<h2>Activité 12 — Résultat et appréciation</h2>";
foreach ([7, 10, 12.5, 15, 18] as $m) {
    echo "<p>Moyenne $m : " . determinerResultat($m) . " — " . appreciation($m) . "</p>";
}

$etudiants = obtenirEtudiants();

/* Activité 13 */
echo "<h2>Activité 13 — afficherEtudiant</h2>";
foreach (array_slice($etudiants, 0, 3) as $etudiant) {
    afficherEtudiant($etudiant);
}

/* Activité 14 */
echo "<h2>Activité 14 — Moyenne de la classe</h2>";
echo "<p>Moyenne générale : " . round(calculerMoyenneClasse($etudiants), 2) . "</p>";

/* Activité 15 */
echo "<h2>Activité 15 — Recherche</h2>";
$etudiant = rechercherEtudiant($etudiants, "Khediri");
if ($etudiant !== null) {
    afficherEtudiant($etudiant);
} else {
    echo "Étudiant introuvable.";
}

/* Activité 16 */
echo "<h2>Activité 16 — Étudiants admis</h2>";
afficherTableauEtudiants(obtenirAdmis($etudiants));

/* Activité 17 */
echo "<h2>Activité 17 — Moyenne ≥ 14</h2>";
$etudiantsBienClasses = filtrerParMoyenne($etudiants, 14);
afficherTableauEtudiants($etudiantsBienClasses);

piedHtml();
