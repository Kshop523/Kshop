<?php
// api/chat/send.php
require_once __DIR__ . '/../../includes/auth.php';

header('Content-Type: application/json; charset=utf-8');
$user = requireAuth();

$messageText = trim($_POST['message'] ?? '');
$mediaPath = null;
$mediaType = null;

if (mb_strlen($messageText) > 2000) {
    echo json_encode(['success' => false, 'message' => 'Le message est trop long (maximum 2000 caractères).']);
    exit;
}

// Traitement sécurisé du fichier média
if (isset($_FILES['media']) && $_FILES['media']['error'] !== UPLOAD_ERR_NO_FILE) {
    $file = $_FILES['media'];
    
    if ($file['error'] !== UPLOAD_ERR_OK) {
        echo json_encode(['success' => false, 'message' => 'Erreur lors du transfert du fichier.']);
        exit;
    }

    // Limite de taille : 5 Mo
    if ($file['size'] > 5 * 1024 * 1024) {
        echo json_encode(['success' => false, 'message' => 'Le fichier dépasse la taille maximale autorisée de 5 Mo.']);
        exit;
    }

    // Vérification stricte du type MIME réel via finfo
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mimeType = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);

    $allowedMimes = [
         'image/JPG' => 'image',
        'image/jpg' => 'image',
        'image/jpeg' => 'image',
        'image/png' => 'image',
        'image/webp' => 'image',
        'image/gif' => 'image',
        'video/mp4' => 'video',
        'video/webm' => 'video'
    ];

    if (!array_key_exists($mimeType, $allowedMimes)) {
        echo json_encode(['success' => false, 'message' => 'Format de fichier non autorisé. Seules les images (JPEG, PNG, WEBP, GIF) et vidéos (MP4, WEBM) sont acceptées.']);
        exit;
    }

    $uploadDir = __DIR__ . '/../../uploads/chat/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $safeFilename = bin2hex(random_bytes(16)) . '.' . strtolower($extension);
    $destination = $uploadDir . $safeFilename;

    if (move_uploaded_file($file['tmp_name'], $destination)) {
        $mediaPath = 'uploads/chat/' . $safeFilename;
        $mediaType = $allowedMimes[$mimeType];
    } else {
        echo json_encode(['success' => false, 'message' => "Erreur lors de l'enregistrement du fichier."]);
        exit;
    }
}

if (empty($messageText) && empty($mediaPath)) {
    echo json_encode(['success' => false, 'message' => 'Le message ne peut pas être vide.']);
    exit;
}

$senderType = ((int)$user['role'] === 1) ? 'admin' : 'user';

try {
    $stmt = $pdo->prepare("INSERT INTO chat_messages (user_id, message_text, media_path, media_type, sender_type) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$user['id'], $messageText, $mediaPath, $mediaType, $senderType]);

    echo json_encode(['success' => true]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => "Erreur lors de l'envoi du message."]);
}