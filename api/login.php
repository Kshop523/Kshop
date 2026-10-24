<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// api/login.php
require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../config/database.php';

header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Méthode non autorisée.']);
    exit;
}

$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';

// 1. Vérification que les champs sont remplis
if (empty($username) || empty($password)) {
    echo json_encode(['success' => false, 'message' => 'Veuillez remplir tous les champs.']);
    exit;
}

try {
    // 2. Recherche de l'utilisateur dans la base de données
    $stmt = $pdo->prepare("SELECT id, username, password_hash FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    // 3. Vérification du mot de passe
    if ($user && password_verify($password, $user['password_hash'])) {
        // Connexion réussie : création de la session
        session_regenerate_id(true);
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];

        echo json_encode(['success' => true, 'redirect' => 'catalogue.php']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Nom d\'utilisateur ou mot de passe incorrect.']);
    }

} catch (PDOException $e) {
    error_log("Erreur de connexion BDD : " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Une erreur est survenue lors de la connexion.']);
}