<?php
require_once __DIR__ . "/../fonctions.php";
enteteHtml("Activités 1 à 8 — Tableaux");

/* Activité 1 */
echo "<h2>Activité 1 — Premier tableau</h2>";
$etudiants = ["Ahmed", "Sami", "Nour", "Amine"];
echo "<p>\$etudiants[0] = {$etudiants[0]}</p>";
echo "<p>\$etudiants[2] = {$etudiants[2]}</p>";
echo "<p>\$etudiants[4] = " . ($etudiants[4] ?? "indéfini (Warning : Undefined array key 4 sans l'opérateur ??)") . "</p>";
echo "<p>Nombre d'éléments : " . count($etudiants) . " — dernier indice : " . (count($etudiants) - 1) . "</p>";

/* Activité 2 */
echo "<h2>Activité 2 — Parcours avec for</h2>";
for ($i = 0; $i < count($etudiants); $i++) {
    echo "<p>$etudiants[$i]</p>";
}

/* Activité 3 */
echo "<h2>Activité 3 — Parcours avec foreach</h2>";
foreach ($etudiants as $etudiant) {
    echo "<p>$etudiant</p>";
}

/* Activité 4 */
echo "<h2>Activité 4 — Ajouter et supprimer</h2>";
$liste = ["Ahmed", "Sami", "Nour", "Amine", "Yassine"];   // 5 étudiants
$liste[] = "Salma";                                       // ajout
array_pop($liste);                                        // suppression du dernier
echo "<p>Nombre final : " . count($liste) . "</p>";
foreach ($liste as $nom) {
    echo "<p>$nom</p>";
}
array_push($liste, "Lina");
echo "<p>Après array_push : " . implode(", ", $liste) . "</p>";

/* Activité 5 */
echo "<h2>Activité 5 — Recherche avec in_array</h2>";
$nomRecherche = "Nour";
if (in_array($nomRecherche, $etudiants)) {
    echo "<p class='ok'>Étudiant trouvé</p>";
} else {
    echo "<p class='ko'>Étudiant non trouvé</p>";
}

/* Activité 6 */
echo "<h2>Activité 6 — Tableau associatif</h2>";
$etudiant = [
    "nom" => "Khediri",
    "prenom" => "Ahmed",
    "age" => 20,
    "formation" => "Informatique",
    "moyenne" => 14.5,
];
$etudiant["telephone"] = "20 123 456";
$etudiant["ville"] = "Kairouan";
$etudiant["niveau"] = "Licence 2";
foreach ($etudiant as $cle => $valeur) {
    echo "<p>" . ucfirst($cle) . " : " . h($valeur) . "</p>";
}
echo "<p>Nom : {$etudiant['nom']}</p>";

/* Activité 7 */
echo "<h2>Activité 7 — Plusieurs étudiants</h2>";
$groupe = [
    ["nom" => "Khediri",   "prenom" => "Ahmed", "age" => 20, "moyenne" => 14.5],
    ["nom" => "Ali",       "prenom" => "Sami",  "age" => 21, "moyenne" => 11.5],
    ["nom" => "Ben Salah", "prenom" => "Nour",  "age" => 19, "moyenne" => 16],
];
foreach ($groupe as $e) {
    echo "<p>{$e['prenom']} {$e['nom']}, {$e['age']} ans, moyenne {$e['moyenne']}</p>";
}

/* Activité 8 */
echo "<h2>Activité 8 — Tableau HTML</h2>";
echo "<table><tr><th>Nom</th><th>Prénom</th><th>Âge</th><th>Moyenne</th></tr>";
foreach ($groupe as $e) {
    echo "<tr><td>{$e['nom']}</td><td>{$e['prenom']}</td><td>{$e['age']}</td><td>{$e['moyenne']}</td></tr>";
}
echo "</table>";

piedHtml();
