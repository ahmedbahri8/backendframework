<?php
session_start();
require_once __DIR__ . '/fonctions.php';

if (estConnecte()) {
    header("Location: dashboard.php");
} else {
    header("Location: login.php");
}
exit;