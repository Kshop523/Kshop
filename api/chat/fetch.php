<?php
// api/chat/fetch.php
require_once __DIR__ . '/../../includes/auth.php';

header('Content-Type: application/json; charset=utf-8');
requireAuth();

$lastId = isset($_GET['last_id']) ? (int)$_GET['last_id'] : 0;

try {
    $stmt = $pdo->prepare("
        SELECT m.id, m.user_id, m.message_text, m.media_path, m.media_type, m.sender_type, m.created_at, u.username 
        FROM chat_messages m
        JOIN users u ON m.user_id = u.id
        WHERE m.id > ?
        ORDER BY m.id ASC
        LIMIT 100
    ");
    $stmt->execute([$lastId]);
    $messages = $stmt->fetchAll();

    echo json_encode(['success' => true, 'messages' => $messages]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Erreur lors de la récupération des messages.']);
}