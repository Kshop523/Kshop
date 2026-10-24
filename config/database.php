<?php
// config/database.php

$host = '127.0.0.1';
$dbname = 'kshop_db';
$username = 'root';
$password = ''; // Par défaut sur XAMPP

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
} catch (PDOException $e) {
    // Ne jamais afficher les détails techniques de l'erreur SQL à l'utilisateur
    error_log("Erreur de connexion BDD : " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Erreur interne du serveur.']);
    exit;
}