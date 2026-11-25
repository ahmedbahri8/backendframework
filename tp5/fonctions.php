<?php
function getEtudiantById(PDO $pdo, int $id): ?array
{
    $sql = "SELECT id, nom, prenom, age, formation, moyenne
            FROM etudiants
            WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id' => $id]);
    $etudiant = $stmt->fetch();

    return $etudiant !== false ? $etudiant : null;
}

function getEtudiants(PDO $pdo, ?float $minMoyenne = null, string $tri = 'nom'): array
{
    $ordres = [
        'nom' => 'nom ASC, prenom ASC',
        'moyenne_desc' => 'moyenne DESC, nom ASC',
        'moyenne_asc' => 'moyenne ASC, nom ASC'
    ];
    $orderBy = $ordres[$tri] ?? $ordres['nom'];

    $sql = "SELECT id, nom, prenom, age, formation, moyenne FROM etudiants";
    $params = [];

    if ($minMoyenne !== null) {
        $sql .= " WHERE moyenne >= :min_moyenne";
        $params['min_moyenne'] = $minMoyenne;
    }

    $sql .= " ORDER BY " . $orderBy;

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);

    return $stmt->fetchAll();
}

function getStatistiques(PDO $pdo): array
{
    $sql = "SELECT
                COUNT(*) AS nombre_etudiants,
                AVG(moyenne) AS moyenne_classe,
                MAX(moyenne) AS meilleure_moyenne,
                MIN(moyenne) AS plus_faible_moyenne
            FROM etudiants";
    $stmt = $pdo->query($sql);

    return $stmt->fetch();
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
        $erreurs[] = "L'âge doit être un entier compris entre 16 et 100.";
    }

    $moyenne = filter_var($moyenneBrute, FILTER_VALIDATE_FLOAT);
    if ($moyenne === false || $moyenne < 0 || $moyenne > 20) {
        $erreurs[] = "La moyenne doit être un nombre compris entre 0 et 20.";
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