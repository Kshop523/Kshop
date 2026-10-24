<?php
// admin/orders.php
require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../config/database.php';

$admin = requireAdmin();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_status') {
    $orderId = (int)$_POST['order_id'];
    $newStatus = trim($_POST['status']);
    $note = trim($_POST['internal_note'] ?? '');
    
    $stmt = $pdo->prepare("SELECT status FROM orders WHERE id = ?");
    $stmt->execute([$orderId]);
    $curr = $stmt->fetch();
    
    if ($curr) {
        $up = $pdo->prepare("UPDATE orders SET status = ?, internal_note = ? WHERE id = ?");
        $up->execute([$newStatus, $note, $orderId]);
        
        $hist = $pdo->prepare("INSERT INTO order_history (order_id, previous_status, new_status, changed_by_user_id, note) VALUES (?, ?, ?, ?, ?)");
        $hist->execute([$orderId, $curr['status'], $newStatus, $admin['id'], $note]);
    }
}

$orders = $pdo->query("SELECT o.*, u.username FROM orders o JOIN users u ON o.user_id = u.id ORDER BY o.created_at DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr" class="dark">
<head>
<meta charset="UTF-8">
<title>Admin - Commandes K Shop</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#111215] text-gray-200 p-8">
<div class="max-w-7xl mx-auto space-y-6">
  <div class="flex justify-between items-center border-b border-gray-800 pb-4">
    <h1 class="text-2xl font-bold text-white">Administration des Commandes</h1>
    <a href="../catalogue.php" class="bg-gray-800 px-4 py-2 rounded text-xs">Catalogue</a>
  </div>
  <div class="bg-gray-900/60 border border-gray-800 rounded-xl p-6 overflow-x-auto">
    <table class="w-full text-left text-xs text-gray-300">
      <thead class="text-yellow-500 uppercase">
        <tr>
          <th class="p-3">Réf</th><th class="p-3">Client</th><th class="p-3">Offre</th><th class="p-3">Montant</th><th class="p-3">Reçu</th><th class="p-3">Statut</th><th class="p-3 text-right">Action</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-gray-800">
        <?php foreach ($orders as $o): ?>
        <tr>
          <td class="p-3 font-mono text-white"><?php echo htmlspecialchars($o['order_ref']); ?></td>
          <td class="p-3"><?php echo htmlspecialchars($o['username']); ?></td>
          <td class="p-3"><?php echo htmlspecialchars($o['offer_title']); ?></td>
          <td class="p-3 font-bold text-yellow-400"><?php echo number_format($o['amount'], 2, ',', ' '); ?> €</td>
          <td class="p-3"><a href="../orders/download_proof.php?id=<?php echo $o['id']; ?>" target="_blank" class="text-cyan-400 underline">Voir</a></td>
          <td class="p-3 font-bold uppercase"><?php echo str_replace('_', ' ', $o['status']); ?></td>
          <td class="p-3 text-right">
            <form method="POST" class="inline-flex gap-2">
              <input type="hidden" name="action" value="update_status">
              <input type="hidden" name="order_id" value="<?php echo $o['id']; ?>">
              <select name="status" class="bg-gray-800 text-white rounded p-1 text-xs">
                <option value="en_attente_verification" <?php if($o['status']=='en_attente_verification') echo 'selected';?>>En attente</option>
                <option value="approuvee" <?php if($o['status']=='approuvee') echo 'selected';?>>Approuver</option>
                <option value="refusee" <?php if($o['status']=='refusee') echo 'selected';?>>Refuser</option>
              </select>
              <button type="submit" class="bg-yellow-500 text-black px-2 py-1 rounded font-bold">OK</button>
            </form>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
</body>
</html>