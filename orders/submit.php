<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// orders/submit.php
require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/telegram.php';

header('Content-Type: application/json; charset=utf-8');

$user = requireAuth(); // Vérifie l'utilisateur connecté

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Méthode non autorisée.']);
    exit;
}

// 1. Validation de la clé CSRF
$csrfToken = $_POST['csrf_token'] ?? '';
if (empty($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $csrfToken)) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Jeton de sécurité CSRF invalide.']);
    exit;
}

// 2. Offres officielles (Table de référence côté serveur)
$OFFERS = [
    'silver'   => ['title' => 'Carte Silver Express', 'amount' => 300.00],
    'gold'     => ['title' => 'Carte Gold Privilege', 'amount' => 500.00],
    'platinum' => ['title' => 'Platinum Anonyme', 'amount' => 850.00],
    'black'    => ['title' => 'Black Obsidian', 'amount' => 1000.00]
];

$offerKey = trim($_POST['offer_key'] ?? '');
$paymentMethod = trim($_POST['payment_method'] ?? 'Recharge Partenaire');

if (!array_key_exists($offerKey, $OFFERS)) {
    echo json_encode(['success' => false, 'message' => 'Offre sélectionnée invalide.']);
    exit;
}

$selectedOffer = $OFFERS[$offerKey];

// 3. Traitement sécurisé du fichier de justificatif
if (!isset($_FILES['proof']) || $_FILES['proof']['error'] === UPLOAD_ERR_NO_FILE) {
    echo json_encode(['success' => false, 'message' => 'Veuillez importer la capture de votre reçu/justificatif.']);
    exit;
}

$file = $_FILES['proof'];

if ($file['error'] !== UPLOAD_ERR_OK) {
    echo json_encode(['success' => false, 'message' => 'Erreur lors du transfert du fichier.']);
    exit;
}

// Limite de taille : 5 Mo
if ($file['size'] > 5 * 1024 * 1024) {
    echo json_encode(['success' => false, 'message' => 'Le fichier est trop volumineux (5 Mo maximum).']);
    exit;
}

// Verification MIME
$finfo = finfo_open(FILEINFO_MIME_TYPE);
$mimeType = finfo_file($finfo, $file['tmp_name']);
finfo_close($finfo);

$allowedTypes = [
    'image/jpeg' => 'jpg',
    'image/png'  => 'png',
    'image/webp' => 'webp'
];

if (!array_key_exists($mimeType, $allowedTypes)) {
    echo json_encode(['success' => false, 'message' => 'Format non supporté. Seules les images JPG, PNG et WEBP sont acceptées.']);
    exit;
}

$extension = $allowedTypes[$mimeType];
$uploadDir = __DIR__ . '/../uploads/proofs/';

if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

$randomName = bin2hex(random_bytes(16)) . '.' . $extension;
$destination = $uploadDir . $randomName;

if (!move_uploaded_file($file['tmp_name'], $destination)) {
    echo json_encode(['success' => false, 'message' => "Erreur lors de l'enregistrement du fichier sur le serveur."]);
    exit;
}

// 4. Génération de l'identifiant unique de commande
$orderRef = 'KS-' . strtoupper(bin2hex(random_bytes(4)));

try {
    $stmt = $pdo->prepare("
        INSERT INTO orders (order_ref, user_id, offer_key, offer_title, amount, currency, payment_method, proof_path, status)
        VALUES (?, ?, ?, ?, ?, 'EUR', ?, ?, 'en_attente_verification')
    ");
    $stmt->execute([
        $orderRef,
        $user['id'],
        $offerKey,
        $selectedOffer['title'],
        $selectedOffer['amount'],
        $paymentMethod,
        $randomName
    ]);

    $orderId = $pdo->lastInsertId();

    // Envoi de la notification Telegram avec la photo et le bouton interactif
    if (function_exists('sendTelegramOrderNotification')) {
        sendTelegramOrderNotification(
            $orderRef, 
            $user['username'], 
            $selectedOffer['title'], 
            $selectedOffer['amount'], 
            $paymentMethod, 
            $destination
        );
    }

    echo json_encode([
        'success' => true,
        'order_ref' => $orderRef,
        'offer_title' => $selectedOffer['title'],
        'amount' => number_format($selectedOffer['amount'], 2, ',', ' ') . ' €',
        'status_text' => 'En attente de vérification',
        'created_at' => date('d/m/Y H:i')
    ]);

} catch (PDOException $e) {
    error_log("Erreur création commande : " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Erreur lors de l\'enregistrement de la commande.']);
}