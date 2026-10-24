<?php
// includes/auth.php
require_once __DIR__ . '/session.php';
require_once __DIR__ . '/../config/database.php';

function getCurrentUser() {
    global $pdo;
    if (!isset($_SESSION['user_id'])) {
        return null;
    }
    
    $stmt = $pdo->prepare("SELECT id, username, role FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    return $stmt->fetch() ?: null;
}

function requireAuth() {
    $user = getCurrentUser();
    if (!$user) {
        header('Location: index.php');
        exit;
    }
    return $user;
}

function requireAdmin() {
    $user = requireAuth();
    if ((int)$user['role'] !== 1) {
        http_response_code(403);
        echo "Accès refusé. Espace réservé à l'administrateur.";
        exit;
    }
    return $user;
}