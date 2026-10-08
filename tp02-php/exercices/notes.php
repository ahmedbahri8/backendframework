<?php
require_once __DIR__ . "/../fonctions.php";

function sommeNotes($notes)
{
    $somme = 0;
    foreach ($notes as $note) {
        $somme += $note;
    }
    return $somme;
}

function moyenneNotes($notes)
{
    return count($notes) === 0 ? 0 : sommeNotes($notes) / count($notes);
}

function maximumNotes($notes)
{
    $max = $notes[0];
    foreach ($notes as $note) {
        if ($note > $max) {
            $max = $note;
        }
    }
    return $max;
}

function minimumNotes($notes)
{
    $min = $notes[0];
    foreach ($notes as $note) {
        if ($note < $min) {
            $min = $note;
        }
    }
    return $min;
}

function compterNotesSuperieuresA10($notes)
{
    $nb = 0;
    foreach ($notes as $note) {
        if ($note >= 10) {
            $nb++;
        }
    }
    return $nb;
}

$notes = [12, 15, 9, 14, 16];
enteteHtml("Exercice 1 — Gestion des notes");
?>
<p>Notes : <?= implode(", ", $notes) ?></p>
<ul>
  <li>Somme : <?= sommeNotes($notes) ?></li>
  <li>Moyenne : <?= moyenneNotes($notes) ?></li>
  <li>Maximum : <?= maximumNotes($notes) ?></li>
  <li>Minimum : <?= minimumNotes($notes) ?></li>
  <li>Notes ≥ 10 : <?= compterNotesSuperieuresA10($notes) ?></li>
</ul>
<?php piedHtml(); ?>
