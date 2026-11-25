<?php
session_start();
require_once __DIR__ . '/fonctions.php';

protegerPage();
initialiserDonnees();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = filter_var($_POST['id'] ?? null, FILTER_VALIDATE_INT);
    if ($id !== false && isset($_SESSION['etudiants'][$id])) {
        unset($_SESSION['etudiants'][$id]);
    }
}

header("Location: liste.php");
exit;