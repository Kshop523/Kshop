<?php
// api/telegram_webhook.php
require_once __DIR__ . '/../config/database.php';

define('TELEGRAM_BOT_TOKEN', 'VOTRE_TOKEN_BOT');

$content = file_get_contents("php://input");
$update = json_decode($content, true);

if (isset($update['callback_query'])) {
    $callbackQuery = $update['callback_query'];
    $callbackData = $callbackQuery['data']; // ex: validate_KS-1234AB
    $chatId = $callbackQuery['message']['chat']['id'];
    $messageId = $callbackQuery['message']['message_id'];

    if (strpos($callbackData, 'validate_') === 0) {
        $orderRef = str_replace('validate_', '', $callbackData);

        // Mettre à jour le statut dans MySQL
        $stmt = $pdo->prepare("UPDATE orders SET status = 'valide_attente_whatsapp' WHERE order_ref = ?");
        $stmt->execute([$orderRef]);

        // Répondre à Telegram pour enlever le sablier sur le bouton
        $answerUrl = "https://api.telegram.org/bot" . TELEGRAM_BOT_TOKEN . "/answerCallbackQuery";
        file_get_contents($answerUrl . "?callback_query_id=" . $callbackQuery['id'] . "&text=Commande validée ! En attente du numéro WhatsApp du client.");

        // Modifier le message Telegram pour le marquer comme validé
        $editUrl = "https://api.telegram.org/bot" . TELEGRAM_BOT_TOKEN . "/editMessageCaption";
        $newCaption = $callbackQuery['message']['caption'] . "\n\n🟢 **STATUT :** Paiement validé par l'admin (En attente WhatsApp)";
        
        $postData = [
            'chat_id' => $chatId,
            'message_id' => $messageId,
            'caption' => $newCaption,
            'parse_mode' => 'Markdown'
        ];

        $ch = curl_init($editUrl);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_exec($ch);
        curl_close($ch);
    }
}