<?php
/* ===== Utilitaires HTML ===== */
function h($valeur)
{
    return htmlspecialchars((string) $valeur);
}

function enteteHtml($titre)
{
    echo '<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>' . h($titre) . '</title>
<style>
body{font-family:Arial,sans-serif;max-width:900px;margin:30px auto;padding:0 15px;color:#222}
form{background:#f4f4f8;padding:12px;border-radius:8px;margin:12px 0}
input{padding:6px}button{padding:6px 14px;cursor:pointer}
table{border-collapse:collapse;margin:10px 0}th,td{border:1px solid #999;padding:6px 12px;text-align:left}
th{background:#1a3c6e;color:#fff}.ok{color:green}.ko{color:#b00}
.carte{border:1px solid #ccc;border-radius:8px;padding:6px 14px;margin:8px 0;background:#fafafa}
</style>
</head>
<body>
<h1>' . h($titre) . '</h1>
<p><a href="/">Accueil</a></p>
';
}

function piedHtml()
{
    echo "</body>\n</html>\n";
}

/* ===== Données ===== */
function obtenirEtudiants()
{
    return [
        ["nom" => "Khediri",   "prenom" => "Ahmed",   "age" => 20, "formation" => "Informatique", "moyenne" => 14.5],
        ["nom" => "Ali",       "prenom" => "Sami",    "age" => 21, "formation" => "Informatique", "moyenne" => 11.5],
        ["nom" => "Ben Salah", "prenom" => "Nour",    "age" => 19, "formation" => "Informatique", "moyenne" => 16],
        ["nom" => "Trabelsi",  "prenom" => "Amine",   "age" => 20, "formation" => "Mathématiques", "moyenne" => 8.75],
        ["nom" => "Gharbi",    "prenom" => "Yassine", "age" => 22, "formation" => "Informatique", "moyenne" => 9.5],
        ["nom" => "Mansour",   "prenom" => "Salma",   "age" => 21, "formation" => "Physique",     "moyenne" => 17.25],
        ["nom" => "Jlassi",    "prenom" => "Mariem",  "age" => 20, "formation" => "Informatique", "moyenne" => 12],
        ["nom" => "Hamdi",     "prenom" => "Karim",   "age" => 23, "formation" => "Mathématiques", "moyenne" => 7],
        ["nom" => "Saidi",     "prenom" => "Lina",    "age" => 19, "formation" => "Physique",     "moyenne" => 13.5],
        ["nom" => "Chaouch",   "prenom" => "Omar",    "age" => 21, "formation" => "Informatique", "moyenne" => 15],
    ];
}

/* ===== Résultat / appréciation ===== */
function calculerMoyenne($note1, $note2, $note3)
{
    return ($note1 + $note2 + $note3) / 3;
}

function determinerResultat($moyenne)
{
    if ($moyenne >= 10) {
        return "Admis";
    }
    return "Ajourné";
}

function appreciation($moyenne)
{
    if ($moyenne < 10) {
        return "Insuffisant";
    } elseif ($moyenne < 12) {
        return "Passable";
    } elseif ($moyenne < 14) {
        return "Assez bien";
    } elseif ($moyenne < 16) {
        return "Bien";
    }
    return "Très bien";
}

/* ===== Affichage ===== */
function afficherEtudiant($etudiant)
{
    echo '<div class="carte">';
    echo "<p>Nom : " . h($etudiant['nom']) . "</p>";
    echo "<p>Prénom : " . h($etudiant['prenom']) . "</p>";
    echo "<p>Âge : " . h($etudiant['age']) . "</p>";
    if (isset($etudiant['formation'])) {
        echo "<p>Formation : " . h($etudiant['formation']) . "</p>";
    }
    echo "<p>Moyenne : " . h($etudiant['moyenne']) . "</p>";
    echo "<p>Résultat : " . determinerResultat($etudiant['moyenne']) . "</p>";
    echo "<p>Appréciation : " . appreciation($etudiant['moyenne']) . "</p>";
    echo '</div>';
}

function afficherTableauEtudiants($etudiants)
{
    if (count($etudiants) === 0) {
        echo "<p>Aucun étudiant à afficher.</p>";
        return;
    }
    echo "<table><tr><th>Nom</th><th>Prénom</th><th>Âge</th><th>Moyenne</th><th>Résultat</th><th>Appréciation</th></tr>";
    foreach ($etudiants as $e) {
        $classe = $e['moyenne'] >= 10 ? 'ok' : 'ko';
        echo "<tr><td>" . h($e['nom']) . "</td><td>" . h($e['prenom']) . "</td><td>" . h($e['age'])
            . "</td><td>" . h($e['moyenne']) . "</td><td class=\"$classe\">" . determinerResultat($e['moyenne'])
            . "</td><td>" . appreciation($e['moyenne']) . "</td></tr>";
    }
    echo "</table>";
}

/* ===== Calculs / statistiques ===== */
function calculerMoyenneClasse($etudiants)
{
    if (count($etudiants) === 0) {
        return 0;
    }
    $somme = 0;
    foreach ($etudiants as $etudiant) {
        $somme += $etudiant["moyenne"];
    }
    return $somme / count($etudiants);
}

function compterAdmis($etudiants)
{
    return count(obtenirAdmis($etudiants));
}

function pourcentageAdmis($etudiants)
{
    if (count($etudiants) === 0) {
        return 0;
    }
    return compterAdmis($etudiants) * 100 / count($etudiants);
}

function trouverMeilleurEtudiant($etudiants)
{
    if (count($etudiants) === 0) {
        return null;
    }
    $meilleur = $etudiants[0];
    foreach ($etudiants as $etudiant) {
        if ($etudiant["moyenne"] > $meilleur["moyenne"]) {
            $meilleur = $etudiant;
        }
    }
    return $meilleur;
}

function trouverMoinsBonEtudiant($etudiants)
{
    if (count($etudiants) === 0) {
        return null;
    }
    $pire = $etudiants[0];
    foreach ($etudiants as $etudiant) {
        if ($etudiant["moyenne"] < $pire["moyenne"]) {
            $pire = $etudiant;
        }
    }
    return $pire;
}

function meilleureMoyenne($etudiants)
{
    $e = trouverMeilleurEtudiant($etudiants);
    return $e === null ? null : $e["moyenne"];
}

function plusFaibleMoyenne($etudiants)
{
    $e = trouverMoinsBonEtudiant($etudiants);
    return $e === null ? null : $e["moyenne"];
}

/* ===== Recherche / filtrage ===== */
function rechercherEtudiant($etudiants, $nom)
{
    $nom = mb_strtolower(trim($nom));
    foreach ($etudiants as $etudiant) {
        if (mb_strtolower($etudiant["nom"]) === $nom) {
            return $etudiant;
        }
    }
    return null;
}

function obtenirAdmis($etudiants)
{
    $admis = [];
    foreach ($etudiants as $etudiant) {
        if ($etudiant["moyenne"] >= 10) {
            $admis[] = $etudiant;
        }
    }
    return $admis;
}

function filtrerParMoyenne($etudiants, $moyenneMinimale)
{
    $resultat = [];
    foreach ($etudiants as $etudiant) {
        if ($etudiant["moyenne"] >= $moyenneMinimale) {
            $resultat[] = $etudiant;
        }
    }
    return $resultat;
}
