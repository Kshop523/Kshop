<?php

// includes/telegram.php

define('TELEGRAM_BOT_TOKEN', '8651777274:AAF84J769hvys1Qkiyhy6e7vXoo1MbHlW7g'); // Remplacez par le token de votre bot BotFather
define('TELEGRAM_CHAT_ID', '7687411765');     // Votre ID Telegram personnel (admin)

function sendTelegramOrderNotification($orderRef, $username, $offerTitle, $amount, $paymentMethod, $proofFilePath) {
    $token = TELEGRAM_BOT_TOKEN;
    $chatId = TELEGRAM_CHAT_ID;

    $caption  = "🚨 **Nouvelle commande reçue !**\n\n";
    $caption .= "🆔 **Ref :** `{$orderRef}`\n";
    $caption .= "👤 **Client :** {$username}\n";
    $caption .= "📦 **Offre :** {$offerTitle}\n";
    $caption .= "💰 **Montant :** {$amount} €\n";
    $caption .= "💳 **Méthode :** {$paymentMethod}\n";

    // Bouton interactif pour valider directement depuis Telegram
    $keyboard = [
        'inline_keyboard' => [
            [
                ['text' => '✅ Valider le paiement', 'callback_data' => 'validate_' . $orderRef]
            ]
        ]
    ];

    $url = "https://api.telegram.org/bot{$token}/sendPhoto";

    // Envoi de la photo de justificatif avec les détails
    $postFields = [
        'chat_id' => $chatId,
        'photo' => new CURLFile($proofFilePath),
        'caption' => $caption,
        'parse_mode' => 'Markdown',
        'reply_markup' => json_encode($keyboard)
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);

    return $response;
}