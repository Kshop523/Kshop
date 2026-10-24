<?php
// orders/download_proof.php
require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../config/database.php';

$user = requireAuth();

$orderId = (int)($_GET['id'] ?? 0);

if ($orderId <= 0) {
    http_response_code(400);
    exit("Commande invalide.");
}

$stmt =$pdo->prepare("SELECT * FROM orders WHERE id = ?");
$stmt->execute([$orderId]);
$order =$stmt->fetch();

if (!$order) {
    http_response_code(404);
    exit("Commande introuvable.");
}

// Seul l'administrateur ou le propriétaire de la commande peut voir le justificatif
if ((int)$user['role'] !== 1 && (int)$order['user_id'] !== (int)$user['id']) {
    http_response_code(403);
    exit("Accès refusé.");
}

$filePath = __DIR__ . '/../uploads/proofs/' . basename($order['proof_path']);

if (!file_exists($filePath)) {
    http_response_code(404);
    exit("Fichier introuvable.");
}

$finfo = finfo_open(FILEINFO_MIME_TYPE);$mimeType = finfo_file($finfo,$filePath);
finfo_close($finfo);

header('Content-Type: ' . $mimeType);
header('Content-Length: ' . filesize($filePath));
header('Content-Disposition: inline; filename="justificatif_' . $order['order_ref'] . '"');
readfile($filePath);
exit;