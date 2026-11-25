<?php
require_once __DIR__ . '/utilisateur.php';

function initialiserDonnees(): void
{
    global $utilisateur;

    if (!isset($_SESSION['utilisateurs'])) {
        $_SESSION['utilisateurs'] = [
            $utilisateur['username'] => $utilisateur['password']
        ];
    }

    if (!isset($_SESSION['etudiants'])) {
        $_SESSION['etudiants'] = [
            1 => [
                'id' => 1,
                'nom' => 'Dupont',
                'prenom' => 'Ahmed',
                'age' => 21,
                'formation' => 'Informatique',
                'moyenne' => 14.0
            ],
            2 => [
                'id' => 2,
                'nom' => 'Martin',
                'prenom' => 'Sami',
                'age' => 22,
                'formation' => 'Informatique',
                'moyenne' => 11.5
            ],
            3 => [
                'id' => 3,
                'nom' => 'Ben Salah',
                'prenom' => 'Ali',
                'age' => 20,
                'formation' => 'Réseaux',
                'moyenne' => 16.0
            ]
        ];
        $_SESSION['next_id'] = 4;
    }
}

function estConnecte(): bool
{
    return isset($_SESSION['connecte']) && $_SESSION['connecte'] === true;
}

function protegerPage(): void
{
    if (!estConnecte()) {
        header("Location: login.php");
        exit;
    }
}

function validerEtudiant(array $donnees): array
{
    $nom = trim((string) ($donnees['nom'] ?? ''));
    $prenom = trim((string) ($donnees['prenom'] ?? ''));
    $ageBrut = trim((string) ($donnees['age'] ?? ''));
    $formation = trim((string) ($donnees['formation'] ?? ''));
    $moyenneBrute = trim((string) ($donnees['moyenne'] ?? ''));

    $erreurs = [];

    if ($nom === '') {
        $erreurs[] = "Le nom est obligatoire.";
    }
    if ($prenom === '') {
        $erreurs[] = "Le prénom est obligatoire.";
    }
    if ($formation === '') {
        $erreurs[] = "La formation est obligatoire.";
    }

    $age = filter_var($ageBrut, FILTER_VALIDATE_INT);
    if ($age === false || $age < 16 || $age > 100) {
        $erreurs[] = "L'âge doit être un entier entre 16 et 100.";
    }

    $moyenne = filter_var($moyenneBrute, FILTER_VALIDATE_FLOAT);
    if ($moyenne === false || $moyenne < 0 || $moyenne > 20) {
        $erreurs[] = "La moyenne doit être comprise entre 0 et 20.";
    }

    return [
        'valeurs' => [
            'nom' => $nom,
            'prenom' => $prenom,
            'age' => $age !== false ? $age : $ageBrut,
            'formation' => $formation,
            'moyenne' => $moyenne !== false ? $moyenne : $moyenneBrute
        ],
        'erreurs' => $erreurs
    ];
}