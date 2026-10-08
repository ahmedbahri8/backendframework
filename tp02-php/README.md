# TP 2 — Tableaux et fonctions en PHP

## Lancer le projet
```bash
cd ~/tpphp/tp02-php
php -S localhost:8000
```
Puis ouvrir <http://localhost:8000>.

## Structure
```
tp02-php/
├── index.php                 Activité 1 + liens
├── fonctions.php             Toutes les fonctions réutilisables
├── gestion-etudiants.php     Mini-projet (Activité 19 + défi)
├── questions.txt             Questions de réflexion
├── README.md                 Ce fichier (réponses)
└── exercices/
    ├── tableaux.php          Activités 1 à 8
    ├── etudiants.php         Activités 9 à 18
    ├── notes.php             Maison – Exercice 1
    └── classe.php            Maison – Exercices 2 et 3
```

---

# Réponses aux questions

## Activité 1 — Premier tableau
Tableau : `["Ahmed", "Sami", "Nour", "Amine"]`
1. **4 éléments.**
2. Le premier indice est **0**.
3. Le dernier indice est **3** (`count($etudiants) - 1`).
4. `echo $etudiants[2];` affiche **Nour**.
5. L'indice 4 n'existe pas : PHP émet un *Warning: Undefined array key 4* et la valeur est `null` (rien n'est affiché). On peut s'en protéger avec `isset($etudiants[4])` ou `$etudiants[4] ?? "défaut"`.

## Activité 2 — Parcours avec `for`
1. `count()` renvoie le **nombre d'éléments** du tableau.
2. Les indices vont de 0 à `count - 1` : avec `<=` on lirait un indice inexistant (`$etudiants[4]`).
3. Écrire `4` en dur obligerait à modifier le code à chaque ajout ou suppression ; `count()` s'adapte automatiquement.

## Activité 3 — `foreach`
`foreach` est plus pratique quand on n'a **pas besoin de l'indice** : il est plus court, plus lisible, sans risque d'erreur de borne, et il fonctionne aussi avec les **tableaux associatifs** (`foreach ($t as $cle => $valeur)`) où les indices ne sont pas 0, 1, 2…

## Activité 4 — Ajouter / supprimer
- `$t[] = x` et `array_push($t, x)` ajoutent à la fin ; `array_pop($t)` supprime et renvoie le dernier élément.
- Voir `exercices/tableaux.php` (5 étudiants, ajout, suppression, nombre final, affichage).

## Activité 5 — Recherche
`in_array($nomRecherche, $etudiants)` renvoie `true`/`false` → affichage de « Étudiant trouvé » ou « Étudiant non trouvé ».

## Activité 9 — Première fonction
Remarque : PHP **interdit de déclarer deux fonctions du même nom** (`afficherBonjour()` puis `afficherBonjour($nom)` → erreur *Cannot redeclare*). Solution utilisée : un paramètre facultatif `$nom = ""`.
1. Le **paramètre** est une variable locale qui reçoit la valeur fournie à l'appel ; il rend la fonction générique (elle fonctionne pour n'importe quel nom).
2. Le code est écrit **une seule fois** et appelé autant de fois que nécessaire : moins de répétition, un seul endroit à corriger.

## Activité 10 — `echo` vs `return`
`echo` **affiche** directement du texte (la valeur est perdue pour le programme). `return` **renvoie** une valeur à l'appelant (qu'on peut stocker dans une variable, utiliser dans un calcul, tester ou afficher ensuite) et **termine** la fonction.

## Activité 14 — Moyenne de la classe
1. **Un seul** paramètre : `$etudiants`.
2. Un **tableau de tableaux associatifs** : la liste de tous les étudiants.
3. Pour **parcourir chaque étudiant** et cumuler sa moyenne dans `$somme`.
4. La **moyenne générale** : somme des moyennes ÷ nombre d'étudiants (un nombre décimal ; 0 dans ma version si le tableau est vide, pour éviter une division par zéro).

## Activité 18 — Séparer les fonctions
1. Pour **organiser** le code, éviter la duplication, **réutiliser** les fonctions dans plusieurs pages et faciliter la maintenance.
2. `require_once` **inclut** le fichier indiqué et l'exécute **une seule fois** (pas de redéclaration de fonctions) ; si le fichier est introuvable, une erreur fatale arrête le script.
3. `exercices/` est un sous-dossier : `../` remonte d'un niveau vers `tp02-php/` où se trouve `fonctions.php`.
   > Avec `php -S`, un chemin relatif commençant par `../` est résolu par rapport au dossier de lancement du serveur, pas au script. J'ai donc utilisé `__DIR__ . "/../fonctions.php"`, qui fonctionne partout.

## Mini-projet et défi
`gestion-etudiants.php` : 10 étudiants dans un tableau HTML (nom, prénom, âge, moyenne, résultat, appréciation), nombre d'étudiants, moyenne de la classe, nombre d'admis, recherche par nom, filtre par moyenne minimale, et **meilleur étudiant** grâce à `trouverMeilleurEtudiant()`.

---

# Questions de réflexion (également dans `questions.txt`)

1. **Pourquoi un tableau plutôt que `$etudiant1`, `$etudiant2`… ?**
   Un tableau regroupe les données sous un seul nom, se parcourt avec une boucle, accepte l'ajout/la suppression dynamique et peut être passé en bloc à une fonction. Avec des variables numérotées, le code serait répétitif et le nombre d'étudiants figé.

2. **Différence entre `$etudiants[0]` et `$etudiant["nom"]` ?**
   `$etudiants[0]` : accès par **indice numérique** (tableau indexé) → le premier étudiant. `$etudiant["nom"]` : accès par **clé textuelle** (tableau associatif) → le nom de cet étudiant.

3. **Pourquoi une fonction améliore-t-elle la réutilisation ?**
   Elle est écrite une fois et appelée avec différents paramètres : code plus court, plus lisible, plus facile à tester et à corriger.

4. **Différence entre `echo` et `return` ?**
   `echo` affiche ; `return` renvoie une valeur utilisable par le programme et quitte la fonction.

5. **Pourquoi séparer les fonctions dans un fichier ?**
   Pour les réutiliser dans plusieurs pages avec `require_once`, éviter les doublons, et séparer le traitement de l'affichage.

---

# Travail à la maison
- **Exercice 1** — `exercices/notes.php` : somme, moyenne, maximum, minimum et nombre de notes ≥ 10 pour `[12, 15, 9, 14, 16]` (résultats attendus : 66, 13.2, 16, 9, 4).
- **Exercice 2** — `exercices/classe.php` : 10 étudiants, affichage, moyenne, admis, recherche, filtre.
- **Exercice 3** — fonctions `meilleureMoyenne`, `plusFaibleMoyenne`, `pourcentageAdmis` et comptage des moyennes ≥ 14, affichés dans `exercices/classe.php`.
