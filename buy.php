<?php
require_once __DIR__ . '/includes/session.php';
require_once __DIR__ . '/includes/auth.php';

$user = requireAuth();

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>
<!DOCTYPE html>
<html lang="fr" class="dark scroll-smooth">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>K shop - Comment Commander</title>
<script src="https://cdn.tailwindcss.com"></script>
<script>
tailwind.config = {
  theme: {
    extend: {
      colors: {
        gold: {
          400: '#facc15',
          500: '#eab308',
          600: '#ca8a04',
          glow: '#d4af37'
        },
        darkbg: '#111215',
        cardbg: '#1a1c23'
      },
      fontFamily: {
        futura: ['Futura', 'Futura-Medium', 'Futura SemiBold', 'Century Gothic', 'sans-serif']
      }
    }
  }
}
</script>
<style>
@import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap');

body {
  font-family: 'Plus Jakarta Sans', sans-serif;
  background: radial-gradient(circle at 75% 20%, #2a2415 0%, #111215 50%, #0a0a0c 100%);
  color: #e5e7eb;
  overflow-x: hidden;
  min-height: 100vh;
}

.font-futura {
  font-family: 'Futura', 'Futura-Medium', 'Futura SemiBold', 'Century Gothic', sans-serif;
}

@keyframes pulseSparkle {
  0%, 100% { opacity: 0.2; transform: scale(0.8); }
  50% { opacity: 1; transform: scale(1.3); }
}

.sparkle {
  animation: pulseSparkle 3s ease-in-out infinite;
}

.btn-primary {
  background-color: #eab308;
  color: #000000;
  border: 2px solid #eab308;
  transition: all 0.25s ease-in-out;
  box-shadow: 0 4px 20px rgba(234, 179, 8, 0.25);
}

.btn-primary:hover {
  background-color: transparent;
  color: #eab308;
  box-shadow: 0 6px 25px rgba(234, 179, 8, 0.4);
  transform: translateY(-2px);
}

.btn-outline {
  background-color: transparent;
  color: #eab308;
  border: 2px solid #eab308;
  transition: all 0.25s ease-in-out;
}

.btn-outline:hover {
  background-color: #eab308;
  color: #000000;
  box-shadow: 0 6px 25px rgba(234, 179, 8, 0.3);
  transform: translateY(-2px);
}

.glass-panel {
  background: rgba(17, 18, 21, 0.82);
  backdrop-filter: blur(24px);
  -webkit-backdrop-filter: blur(24px);
  border: 1px solid rgba(234, 179, 8, 0.25);
  box-shadow: 0 20px 50px rgba(0, 0, 0, 0.8), 0 0 35px rgba(234, 179, 8, 0.15);
}

.glass-card {
  background: rgba(26, 28, 35, 0.65);
  backdrop-filter: blur(16px);
  -webkit-backdrop-filter: blur(16px);
  border: 1px solid rgba(234, 179, 8, 0.18);
  transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
}

.glass-card:hover {
  border-color: rgba(234, 179, 8, 0.6);
  transform: translateY(-4px);
  box-shadow: 0 20px 40px rgba(0, 0, 0, 0.6), 0 0 25px rgba(234, 179, 8, 0.2);
}

.custom-scrollbar::-webkit-scrollbar {
  width: 5px;
}
.custom-scrollbar::-webkit-scrollbar-track {
  background: rgba(0, 0, 0, 0.2);
}
.custom-scrollbar::-webkit-scrollbar-thumb {
  background: rgba(234, 179, 8, 0.3);
  border-radius: 10px;
}
</style>
</head>
<body class="relative flex flex-col justify-between min-h-screen text-gray-200 selection:bg-gold-500 selection:text-black">

<!-- Éléments décoratifs d'arrière-plan -->
<div class="pointer-events-none fixed inset-0 overflow-hidden z-0">
  <svg class="sparkle absolute top-20 left-[45%] w-6 h-6 text-gold-400 opacity-70" viewBox="0 0 24 24" fill="currentColor">
    <path d="M12 0L14.59 9.41L24 12L14.59 14.59L12 24L9.41 14.59L0 12L9.41 9.41L12 0Z"/>
  </svg>
  <svg class="sparkle absolute top-40 right-[10%] w-5 h-5 text-gold-400 opacity-60" style="animation-delay: 1s;" viewBox="0 0 24 24" fill="currentColor">
    <path d="M12 0L14.59 9.41L24 12L14.59 14.59L12 24L9.41 14.59L0 12L9.41 9.41L12 0Z"/>
  </svg>
  <svg class="sparkle absolute bottom-36 left-[35%] w-4 h-4 text-gold-400 opacity-50" style="animation-delay: 1.8s;" viewBox="0 0 24 24" fill="currentColor">
    <path d="M12 0L14.59 9.41L24 12L14.59 14.59L12 24L9.41 14.59L0 12L9.41 9.41L12 0Z"/>
  </svg>
</div>

<!-- En-tête de la page -->
<header class="relative z-10 w-full max-w-7xl mx-auto px-6 pt-12 pb-6 flex justify-between items-center border-b border-gray-800/60">
  <div class="flex items-center gap-3">
    <span class="text-xl font-extrabold font-futura tracking-wider text-white">K <span class="text-gold-500">SHOP</span></span>
  </div>
  <div class="flex items-center gap-4">
    <span class="text-xs text-yellow-400">Compte : <strong><?php echo htmlspecialchars($user['username']); ?></strong></span>
    <a href="catalogue.php" class="btn-outline px-4 py-2 rounded-xl text-xs font-bold uppercase tracking-wider flex items-center gap-2">
      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
      Retour au catalogue
    </a>
  </div>
</header>

<!-- SECTION PRINCIPALE : COMMENT COMMANDER -->
<main class="relative z-10 w-full max-w-4xl mx-auto px-6 py-16">
  
  <div class="text-center max-w-2xl mx-auto mb-12 space-y-3">
    <span class="text-gold-500 uppercase tracking-widest text-xs font-bold font-futura">Guide d'acquisition</span>
    <h1 class="text-3xl sm:text-4xl font-bold font-futura text-white tracking-tight">
      Comment <span class="text-gold-500">Commander ?</span>
    </h1>
    <p class="text-gray-400 text-sm sm:text-base">
      Suivez ces étapes simples, sécurisées et entièrement anonymes pour obtenir votre carte VIP en toute sérénité.
    </p>
  </div>

  <!-- ZONE ALERTE CLIENT -->
  <div id="order-alert" class="hidden mb-8 p-4 rounded-xl text-xs font-semibold"></div>

  <!-- SÉLECTION DE L'OFFRE -->
  <div class="mb-10">
    <label class="block text-xs font-bold uppercase text-gold-400 mb-3 tracking-wider">Sélectionnez votre carte VIP :</label>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
      <label class="glass-card p-4 rounded-xl cursor-pointer flex flex-col justify-between border-gray-800 hover:border-gold-500/60">
        <div class="flex items-center justify-between mb-2">
          <input type="radio" name="offer_select" value="silver" class="accent-gold-500" checked>
          <span class="text-xs font-bold text-gray-400">300 €</span>
        </div>
        <span class="text-sm font-bold text-white">Silver Express</span>
      </label>

      <label class="glass-card p-4 rounded-xl cursor-pointer flex flex-col justify-between border-gray-800 hover:border-gold-500/60">
        <div class="flex items-center justify-between mb-2">
          <input type="radio" name="offer_select" value="gold" class="accent-gold-500">
          <span class="text-xs font-bold text-gold-400">500 €</span>
        </div>
        <span class="text-sm font-bold text-white">Gold Privilege</span>
      </label>

      <label class="glass-card p-4 rounded-xl cursor-pointer flex flex-col justify-between border-gray-800 hover:border-gold-500/60">
        <div class="flex items-center justify-between mb-2">
          <input type="radio" name="offer_select" value="platinum" class="accent-gold-500">
          <span class="text-xs font-bold text-cyan-400">850 €</span>
        </div>
        <span class="text-sm font-bold text-white">Platinum Anonyme</span>
      </label>

      <label class="glass-card p-4 rounded-xl cursor-pointer flex flex-col justify-between border-gray-800 hover:border-gold-500/60">
        <div class="flex items-center justify-between mb-2">
          <input type="radio" name="offer_select" value="black" class="accent-gold-500">
          <span class="text-xs font-bold text-yellow-500">1 000 €</span>
        </div>
        <span class="text-sm font-bold text-white">Black Obsidian</span>
      </label>
    </div>
  </div>

  <!-- FORMULAIRE DES 4 ÉTAPES -->
  <form id="order-form" class="space-y-6">
    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
    <input type="hidden" id="offer_key_input" name="offer_key" value="silver">

    <!-- ÉTAPE 1 -->
    <div class="glass-card rounded-2xl p-6 sm:p-8 flex flex-col md:flex-row items-start md:items-center justify-between gap-6 relative group">
      <div class="absolute -top-3 -left-3 w-10 h-10 rounded-xl bg-gold-500 text-black font-extrabold font-futura flex items-center justify-center shadow-lg">
        01
      </div>
      <div class="space-y-2 max-w-xl">
        <h3 class="text-xl font-bold font-futura text-white group-hover:text-gold-400 transition-colors">
          Acheter une recharge partenaire
        </h3>
        <p class="text-gray-400 text-sm leading-relaxed">
          Procurez-vous une recharge sécurisée sur l'une de nos plateformes officielles partenaires recommandées ci-dessous :
        </p>
        <div class="flex flex-wrap gap-3 pt-2">
          <a href="https://www.dundle.com" target="_blank" class="btn-outline px-4 py-2 rounded-xl text-xs font-bold tracking-wide flex items-center gap-1.5">
            <span>Dundle.com</span>
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
          </a>
          <a href="https://www.recharge.fr" target="_blank" class="btn-outline px-4 py-2 rounded-xl text-xs font-bold tracking-wide flex items-center gap-1.5">
            <span>Recharge.fr</span>
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
          </a>
          <a href="https://www.clickandrecharge.com" target="_blank" class="btn-outline px-4 py-2 rounded-xl text-xs font-bold tracking-wide flex items-center gap-1.5">
            <span>Click & Recharge</span>
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
          </a>
        </div>
      </div>
      <div class="hidden lg:flex w-16 h-16 rounded-2xl bg-gold-500/10 border border-gold-500/30 items-center justify-center text-gold-400 shrink-0">
        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
      </div>
    </div>

    <!-- ÉTAPE 2 -->
    <div class="glass-card rounded-2xl p-6 sm:p-8 flex flex-col md:flex-row items-start md:items-center justify-between gap-6 relative group">
      <div class="absolute -top-3 -left-3 w-10 h-10 rounded-xl bg-gold-500 text-black font-extrabold font-futura flex items-center justify-center shadow-lg">
        02
      </div>
      <div class="space-y-3 max-w-xl w-full">
        <h3 class="text-xl font-bold font-futura text-white group-hover:text-gold-400 transition-colors">
          Importer la capture du reçu
        </h3>
        <p class="text-gray-400 text-sm leading-relaxed">
          Téléchargez ou prenez une capture d'écran de votre reçu ou code de recharge valide afin de valider l'authenticité de la transaction.
        </p>
        <div>
          <input type="file" id="proof-input" name="proof" accept="image/jpeg,image/png,image/webp" class="hidden">
          <div class="flex items-center gap-3">
            <button type="button" onclick="document.getElementById('proof-input').click()" class="btn-primary px-5 py-2.5 rounded-xl text-xs font-bold uppercase tracking-wide flex items-center gap-2">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
              Importer la capture
            </button>
            <button type="button" id="remove-file-btn" class="hidden text-xs text-red-400 hover:underline">Retirer</button>
          </div>
          <span id="file-name-display" class="text-xs text-emerald-400 mt-2 block hidden"></span>
        </div>
      </div>
      <div class="hidden lg:flex w-16 h-16 rounded-2xl bg-gold-500/10 border border-gold-500/30 items-center justify-center text-gold-400 shrink-0">
        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
      </div>
    </div>

    <!-- ÉTAPE 3 -->
    <div class="glass-card rounded-2xl p-6 sm:p-8 flex flex-col md:flex-row items-start md:items-center justify-between gap-6 relative group">
      <div class="absolute -top-3 -left-3 w-10 h-10 rounded-xl bg-gold-500 text-black font-extrabold font-futura flex items-center justify-center shadow-lg">
        03
      </div>
      <div class="space-y-2 max-w-xl">
        <h3 class="text-xl font-bold font-futura text-white group-hover:text-gold-400 transition-colors">
          Patienter l'étape de vérification
        </h3>
        <p class="text-gray-400 text-sm leading-relaxed">
          Notre équipe examine votre reçu et authentifie la transaction. La vérification est effectuée manuellement pour garantir un anonymat total.
        </p>
      </div>
      <div class="hidden lg:flex w-16 h-16 rounded-2xl bg-gold-500/10 border border-gold-500/30 items-center justify-center text-gold-400 shrink-0">
        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
      </div>
    </div>

    <!-- ÉTAPE 4 -->
    <div class="glass-card rounded-2xl p-6 sm:p-8 flex flex-col md:flex-row items-start md:items-center justify-between gap-6 relative group">
      <div class="absolute -top-3 -left-3 w-10 h-10 rounded-xl bg-gold-500 text-black font-extrabold font-futura flex items-center justify-center shadow-lg">
        04
      </div>
      <div class="space-y-2 max-w-xl w-full">
        <h3 class="text-xl font-bold font-futura text-white group-hover:text-gold-400 transition-colors">
          Finalisation et Envoi
        </h3>
        <p class="text-gray-400 text-sm leading-relaxed mb-4">
          Cliquez ci-dessous pour soumettre votre commande et générer votre récapitulatif.
        </p>
        <button type="submit" id="btn-submit-order" class="btn-primary w-full py-4 rounded-xl font-bold text-sm tracking-wider uppercase">
          Valider ma commande
        </button>
      </div>
      <div class="hidden lg:flex w-16 h-16 rounded-2xl bg-gold-500/10 border border-gold-500/30 items-center justify-center text-gold-400 shrink-0">
        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
      </div>
    </div>
  </form>

  <!-- CONFIRMATION DE COMMANDE -->
  <div id="confirmation-modal" class="hidden mt-12 glass-panel rounded-2xl p-8 border-gold-500/50 space-y-4">
    <div class="flex items-center gap-3 text-emerald-400">
      <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
      <h3 class="text-2xl font-bold font-futura text-white">Commande Enregistrée !</h3>
    </div>
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs text-gray-300 border-t border-b border-gray-800 py-4">
      <div>Référence : <strong id="conf-ref" class="text-gold-400 font-mono text-sm"></strong></div>
      <div>Offre : <strong id="conf-title" class="text-white"></strong></div>
      <div>Montant : <strong id="conf-amount" class="text-white"></strong></div>
      <div>Statut : <strong class="text-yellow-400">En attente de vérification</strong></div>
    </div>
    <p class="text-xs text-gray-400">
      Votre commande a été soumise à l'équipe d'administration. Vous pouvez contacter le support muni de votre référence pour un traitement accéléré.
    </p>
  </div>

  <!-- SECTION : MES COMMANDES RÉCENTES -->
  <div class="mt-16 pt-10 border-t border-gray-800/80">
    <h3 class="text-xl font-bold font-futura text-white mb-6">Mes Commandes Récentes</h3>
    <div id="my-orders-list" class="space-y-4 text-xs">
      <p class="text-gray-500">Chargement de vos commandes...</p>
    </div>
  </div>

  <!-- SECTION ALTERNATIVE : TELEGRAM -->
  <div class="mt-16 pt-10 border-t border-gray-800/80 text-center">
    <div class="glass-panel rounded-2xl p-8 max-w-2xl mx-auto space-y-4 border-gold-500/40 relative overflow-hidden">
      <div class="absolute -right-10 -bottom-10 w-32 h-32 bg-gold-500/10 rounded-full blur-2xl"></div>
      
      <span class="text-gold-500 uppercase tracking-widest text-xs font-bold font-futura">Alternative Rapide</span>
      <h3 class="text-2xl font-bold font-futura text-white">
        Besoin d'un accompagnement direct ?
      </h3>
      <p class="text-gray-400 text-sm">
        Vous pouvez échanger en temps réel et commander directement auprès de l'administrateur en toute confidentialité via Telegram.
      </p>

      <div class="pt-2">
        <a href="https://telegram.me/le_dux0" target="_blank" class="btn-primary inline-flex items-center gap-2.5 px-8 py-3.5 rounded-xl font-bold text-xs uppercase tracking-wider">
          <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24">
            <path d="M12 0C5.373 0 0 5.373 0 12s5.373 12 12 12 12-5.373 12-12S18.627 0 12 0zm5.894 8.221l-1.97 9.28c-.145.658-.537.818-1.084.508l-3-2.21-1.446 1.394c-.16.16-.295.295-.605.295l.213-3.053 5.56-5.023c.242-.213-.054-.333-.373-.121l-6.871 4.326-2.962-.924c-.643-.204-.657-.643.136-.953l11.57-4.461c.537-.194 1.006.131.832.941z"/>
          </svg>
          Contacter sur Telegram
        </a>
      </div>
    </div>
  </div>

</main>

<!-- Pied de page -->
<footer class="relative z-10 w-full max-w-7xl mx-auto px-6 py-8 border-t border-gray-800/60 text-center text-xs text-gray-500">
  <p>&copy; 2026 K Shop - Solutions d'Achat Anonymes & Sécurisées. Tous droits réservés.</p>
</footer>

<script>
document.addEventListener('DOMContentLoaded', () => {
  // Synchronisation des offres
  const offerRadios = document.querySelectorAll('input[name="offer_select"]');
  const offerKeyInput = document.getElementById('offer_key_input');

  offerRadios.forEach(radio => {
    radio.addEventListener('change', (e) => {
      offerKeyInput.value = e.target.value;
    });
  });

  // Gestion du fichier de reçu
  const proofInput = document.getElementById('proof-input');
  const fileNameDisplay = document.getElementById('file-name-display');
  const removeFileBtn = document.getElementById('remove-file-btn');

  proofInput.addEventListener('change', (e) => {
    const file = e.target.files[0];
    if (file) {
      fileNameDisplay.textContent = `Fichier sélectionné : ${file.name}`;
      fileNameDisplay.classList.remove('hidden');
      removeFileBtn.classList.remove('hidden');
    }
  });

  removeFileBtn.addEventListener('click', () => {
    proofInput.value = '';
    fileNameDisplay.classList.add('hidden');
    removeFileBtn.classList.add('hidden');
  });

  // Soumission AJAX
  const orderForm = document.getElementById('order-form');
  const orderAlert = document.getElementById('order-alert');
  const btnSubmit = document.getElementById('btn-submit-order');
  const confirmationModal = document.getElementById('confirmation-modal');

  orderForm.addEventListener('submit', async (e) => {
    e.preventDefault();
    orderAlert.classList.add('hidden');

    if (!proofInput.files || proofInput.files.length === 0) {
      orderAlert.textContent = "Veuillez importer la capture de votre reçu avant de valider.";
      orderAlert.className = "mb-8 p-4 rounded-xl text-xs font-semibold bg-red-500/20 border border-red-500/50 text-red-300";
      orderAlert.classList.remove('hidden');
      return;
    }

    btnSubmit.disabled = true;
    btnSubmit.textContent = "Traitement sécurisé en cours...";

    const formData = new FormData(orderForm);

    try {
      const res = await fetch('orders/submit.php', {
        method: 'POST',
        body: formData
      });
      const data = await res.json();

      if (data.success) {
        document.getElementById('conf-ref').textContent = data.order_ref;
        document.getElementById('conf-title').textContent = data.offer_title;
        document.getElementById('conf-amount').textContent = data.amount;
        confirmationModal.classList.remove('hidden');
        orderForm.reset();
        fileNameDisplay.classList.add('hidden');
        removeFileBtn.classList.add('hidden');
        loadMyOrders();
      } else {
        orderAlert.textContent = data.message || "Erreur lors de la soumission.";
        orderAlert.className = "mb-8 p-4 rounded-xl text-xs font-semibold bg-red-500/20 border border-red-500/50 text-red-300";
        orderAlert.classList.remove('hidden');
      }
    } catch (err) {
      orderAlert.textContent = "Erreur de communication avec le serveur.";
      orderAlert.className = "mb-8 p-4 rounded-xl text-xs font-semibold bg-red-500/20 border border-red-500/50 text-red-300";
      orderAlert.classList.remove('hidden');
    } finally {
      btnSubmit.disabled = false;
      btnSubmit.textContent = "Valider ma commande";
    }
  });

  // Chargement des commandes personnelles
  async function loadMyOrders() {
    const listDiv = document.getElementById('my-orders-list');
    try {
      const res = await fetch('orders/my_orders.php');
      const data = await res.json();

      if (data.success && data.orders.length > 0) {
        listDiv.innerHTML = data.orders.map(o => `
          <div class="glass-panel p-4 rounded-xl flex flex-wrap items-center justify-between gap-4 border-gray-800">
            <div>
              <span class="font-mono text-gold-400 font-bold">${o.order_ref}</span>
              <p class="text-white font-bold">${o.offer_title}</p>
              <span class="text-gray-400 text-[10px]">${o.created_at}</span>
            </div>
            <div class="text-right">
              <span class="text-white font-bold block">${parseFloat(o.amount).toFixed(2)} €</span>
              <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-yellow-500/20 text-yellow-400 border border-yellow-500/40">
                ${o.status.replace('_', ' ')}
              </span>
            </div>
          </div>
        `).join('');
      } else {
        listDiv.innerHTML = '<p class="text-gray-500">Aucune commande enregistrée pour le moment.</p>';
      }
    } catch (e) {
      listDiv.innerHTML = '<p class="text-red-400">Erreur lors du chargement des commandes.</p>';
    }
  }

  loadMyOrders();
});
</script>

</body>
</html>