 <?php
// api/orders/save_whatsapp.php
require_once __DIR__ . '/../../includes/session.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../includes/telegram.php';

header('Content-Type: application/json; charset=utf-8');
$user = requireAuth();

$whatsapp = trim($_POST['whatsapp'] ?? '');
$orderRef = trim($_POST['order_ref'] ?? '');

if (empty($whatsapp) || empty($orderRef)) {
    echo json_encode(['success' => false, 'message' => 'Informations manquantes.']);
    exit;
}

try {
    // 1. Sauvegarder le numéro en BDD et passer le statut en 'termine' ou 'en_attente_livraison'
    $stmt = $pdo->prepare("UPDATE orders SET whatsapp_number = ?, status = 'en_attente_livraison' WHERE order_ref = ? AND user_id = ?");
    $stmt->execute([$whatsapp, $orderRef, $user['id']]);

    // 2. Envoyer le numéro WhatsApp sur votre bot Telegram personnel
    $token = TELEGRAM_BOT_TOKEN;
    $chatId = TELEGRAM_CHAT_ID;
    $text = "📱 **Numéro WhatsApp reçu !**\n\n🆔 **Commande :** `{$orderRef}`\n👤 **Client :** {$user['username']}\n📞 **WhatsApp :** `{$whatsapp}`";
    
    file_get_contents("https://api.telegram.org/bot{$token}/sendMessage?chat_id={$chatId}&text=" . urlencode($text) . "&parse_mode=Markdown");

    echo json_encode(['success' => true]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Erreur serveur.']);
}