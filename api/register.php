<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/telegram.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Méthode non autorisée.']);
    exit;
}

// Vérification CSRF
if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    echo json_encode(['success' => false, 'message' => 'Erreur de sécurité (CSRF token invalide).']);
    exit;
}

$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';
$confirm  = $_POST['confirm'] ?? '';

// Validation côté serveur
if (empty($username) || empty($password) || empty($confirm)) {
    echo json_encode(['success' => false, 'message' => 'Veuillez remplir tous les champs.']);
    exit;
}

if ($password !== $confirm) {
    echo json_encode(['success' => false, 'message' => 'Les mots de passe ne correspondent pas.']);
    exit;
}

if (strlen($password) < 6) {
    echo json_encode(['success' => false, 'message' => 'Le mot de passe doit contenir au moins 6 caractères.']);
    exit;
}

try {
    // Vérification de l'unicité du nom d'utilisateur
    $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->execute([$username]);
    if ($stmt->fetch()) {
        echo json_encode(['success' => false, 'message' => 'Ce nom d\'utilisateur est déjà pris.']);
        exit;
    }

    // Hachage sécurisé du mot de passe
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insertion dans la base de données
    $stmt = $pdo->prepare("INSERT INTO users (username, password_hash) VALUES (?, ?)");
    $stmt->execute([$username, $hashed_password]);
    $user_id = $pdo->lastInsertId();

    // Création de la session sécurisée
    session_regenerate_id(true);
    $_SESSION['user_id'] = $user_id;
    $_SESSION['username'] = $username;

    // Envoi de la notification Telegram
    if (defined('TELEGRAM_BOT_TOKEN') && TELEGRAM_BOT_TOKEN !== '8651777274:AAF84J769hvys1Qkiyhy6e7vXoo1MbHlW7g' &&
        defined('TELEGRAM_CHAT_ID') && TELEGRAM_CHAT_ID !== '7687411765') {
        $message = "🔔 Nouvel utilisateur inscrit sur K Shop :\n👤 Nom d'utilisateur : " . $username;
        $telegram_url = "https://api.telegram.org/bot" . TELEGRAM_BOT_TOKEN . "/sendMessage";
        
        $data = [
            'chat_id' => TELEGRAM_CHAT_ID,
            'text' => $message,
            'parse_mode' => 'Markdown'
        ];

        $options = [
            'http' => [
                'header'  => "Content-Type: application/x-www-form-urlencoded\r\n",
                'method'  => 'POST',
                'content' => http_build_query($data),
                'timeout' => 5,
            ]
        ];
        @file_get_contents($telegram_url, false, stream_context_create($options));
    }

    echo json_encode(['success' => true, 'redirect' => 'catalogue.php']);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Erreur serveur lors de l\'inscription.']);
}