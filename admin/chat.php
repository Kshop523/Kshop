<?php
// admin/chat.php
require_once __DIR__ . '/../includes/auth.php';
$admin = requireAdmin();

// Suppression d'un message
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
    $msgId = (int)$_POST['message_id'];
    $delStmt = $pdo->prepare("DELETE FROM chat_messages WHERE id = ?");
    $delStmt->execute([$msgId]);
    header('Location: chat.php');
    exit;
}

$messages = $pdo->query("
    SELECT m.id, m.message_text, m.media_path, m.media_type, m.sender_type, m.created_at, u.username 
    FROM chat_messages m
    JOIN users u ON m.user_id = u.id
    ORDER BY m.created_at DESC
    LIMIT 100
")->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr" class="dark">
<head>
    <meta charset="UTF-8">
    <title>Administration du Chat - K Shop</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#111215] text-gray-200 p-8">
    <div class="max-w-6xl mx-auto space-y-6">
        <div class="flex justify-between items-center border-b border-gray-800 pb-4">
            <h1 class="text-2xl font-bold text-white">Administration du Salon - K Shop</h1>
            <div class="flex items-center gap-4">
                <span class="text-yellow-400 font-semibold">Admin : <?php echo htmlspecialchars($admin['username']); ?></span>
                <a href="../catalogue.php" class="bg-gray-800 hover:bg-gray-700 px-4 py-2 rounded-lg text-sm transition">Retour au catalogue</a>
                <a href="../api/logout.php" class="bg-red-600 hover:bg-red-700 px-4 py-2 rounded-lg text-sm transition">Se déconnecter</a>
            </div>
        </div>

        <div class="bg-gray-900/60 border border-gray-800 rounded-xl p-6 shadow-xl">
            <h2 class="text-lg font-bold text-white mb-4">Modération des messages récents</h2>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-gray-300">
                    <thead class="bg-black/40 text-yellow-500 uppercase">
                        <tr>
                            <th class="p-3">ID</th>
                            <th class="p-3">Expéditeur</th>
                            <th class="p-3">Type</th>
                            <th class="p-3">Message / Média</th>
                            <th class="p-3">Date</th>
                            <th class="p-3 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-800">
                        <?php foreach ($messages as $msg): ?>
                        <tr class="hover:bg-white/5">
                            <td class="p-3 font-mono"><?php echo $msg['id']; ?></td>
                            <td class="p-3 font-bold text-white">
                                <?php echo htmlspecialchars($msg['username']); ?>
                                <?php if($msg['sender_type'] === 'admin'): ?>
                                    <span class="ml-1 bg-yellow-500/20 text-yellow-400 px-1.5 py-0.5 rounded text-[10px]">Support</span>
                                <?php endif; ?>
                            </td>
                            <td class="p-3"><?php echo htmlspecialchars($msg['sender_type']); ?></td>
                            <td class="p-3 max-w-xs truncate">
                                <?php if (!empty($msg['message_text'])): ?>
                                    <div><?php echo htmlspecialchars($msg['message_text']); ?></div>
                                <?php endif; ?>
                                <?php if (!empty($msg['media_path'])): ?>
                                    <a href="../<?php echo htmlspecialchars($msg['media_path']); ?>" target="_blank" class="text-yellow-400 underline text-[11px]">Voir le média</a>
                                <?php endif; ?>
                            </td>
                            <td class="p-3"><?php echo $msg['created_at']; ?></td>
                            <td class="p-3 text-right">
                                <form method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce message ?');">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="message_id" value="<?php echo $msg['id']; ?>">
                                    <button type="submit" class="bg-red-500/20 text-red-400 hover:bg-red-500 hover:text-white px-3 py-1 rounded transition">Supprimer</button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>