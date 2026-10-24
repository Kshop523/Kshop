<?php
// orders/my_orders.php
require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../config/database.php';

header('Content-Type: application/json; charset=utf-8');
$user = requireAuth();

$stmt = $pdo->prepare("SELECT order_ref, offer_title, amount, status, created_at FROM orders WHERE user_id = ? ORDER BY created_at DESC");
$stmt->execute([$user['id']]);
echo json_encode(['success' => true, 'orders' => $stmt->fetchAll()]);