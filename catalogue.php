<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once 'includes/auth.php';
$user = requireAuth();
?>
<!DOCTYPE html>
<html lang="fr" class="dark scroll-smooth">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>K shop - Catalogue & Salon Privé</title>
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
/* Custom Keyframe Animations & Styles */
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

/* Floating animation for graphics */
@keyframes float {
  0% { transform: translateY(0px) rotate(0deg); }
  50% { transform: translateY(-16px) rotate(1.5deg); }
  100% { transform: translateY(0px) rotate(0deg); }
}

.animate-float {
  animation: float 6s ease-in-out infinite;
}

/* Sparkle animation */
@keyframes pulseSparkle {
  0%, 100% { opacity: 0.2; transform: scale(0.8); }
  50% { opacity: 1; transform: scale(1.3); }
}

.sparkle {
  animation: pulseSparkle 3s ease-in-out infinite;
}

/* Slow spin badge */
@keyframes spinSlow {
  from { transform: rotate(0deg); }
  to { transform: rotate(360deg); }
}

.animate-spin-slow {
  animation: spinSlow 20s linear infinite;
}

/* Subtitle animation */
.slide-in-left {
  opacity: 0;
  transform: translateX(-80px);
  transition: opacity 0.9s cubic-bezier(0.16, 1, 0.3, 1), transform 0.9s cubic-bezier(0.16, 1, 0.3, 1);
}

.slide-in-left.visible {
  opacity: 1;
  transform: translateX(0);
}

/* Primary Button */
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

.btn-primary:active {
  transform: scale(0.96);
  background-color: #ca8a04;
  color: #000;
}

/* Outline Button */
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

.btn-outline:active {
  transform: scale(0.96);
  background-color: #d4af37;
  color: #000000;
}

/* Typewriter blinker */
.cursor-blink {
  display: inline-block;
  width: 3px;
  background-color: #eab308;
  animation: blink 0.7s infinite;
  margin-left: 2px;
}

@keyframes blink {
  0%, 100% { opacity: 1; }
  50% { opacity: 0; }
}

/* Glassmorphism styling */
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
  transform: translateY(-8px);
  box-shadow: 0 20px 40px rgba(0, 0, 0, 0.6), 0 0 25px rgba(234, 179, 8, 0.2);
}

.glass-input {
  background: rgba(255, 255, 255, 0.04);
  border: 1px solid rgba(234, 179, 8, 0.2);
  transition: all 0.3s ease;
}

.glass-input:focus {
  background: rgba(255, 255, 255, 0.08);
  border-color: #eab308;
  box-shadow: 0 0 15px rgba(234, 179, 8, 0.25);
  outline: none;
}

/* Custom Scrollbar for Chat */
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
.custom-scrollbar::-webkit-scrollbar-thumb:hover {
  background: rgba(234, 179, 8, 0.6);
}
</style>
</head>


<!-- HEADER ET COMPTE UTILISATEUR -->


<body class="relative flex flex-col justify-between min-h-screen text-gray-200 selection:bg-gold-500 selection:text-black">
<header class="w-full max-w-7xl mx-auto px-6 py-6 flex justify-between items-center border-b border-gray-800">
    <h1 class="text-2xl font-extrabold font-futura text-white">K <span class="text-gold-500">shop</span></h1>
    <div class="flex items-center gap-4">
        <span class="text-xs text-yellow-400">Connecté : <strong><?php echo htmlspecialchars($user['username']); ?></strong></span>
        <?php if ((int)$user['role'] === 1): ?>
            <a href="admin/chat.php" class="bg-yellow-500/20 text-yellow-400 border border-yellow-500/40 px-3 py-1.5 rounded-lg text-xs font-semibold hover:bg-yellow-500 hover:text-black transition">Admin Chat</a>
        <?php endif; ?>
        <a href="api/logout.php" class="bg-red-600/20 text-red-400 border border-red-500/40 px-3 py-1.5 rounded-lg text-xs font-semibold hover:bg-red-600 hover:text-white transition">Déconnexion</a>
    </div>
</header>
<div class="pointer-events-none fixed inset-0 overflow-hidden z-0">
  <svg class="sparkle absolute top-20 left-[45%] w-6 h-6 text-gold-400 opacity-70" viewBox="0 0 24 24" fill="currentColor">
    <path d="M12 0L14.59 9.41L24 12L14.59 14.59L12 24L9.41 14.59L0 12L9.41 9.41L12 0Z"/>
  </svg>
  <svg class="sparkle absolute top-40 right-[10%] w-5 h-5 text-gold-400 opacity-60" style="animation-delay: 1s;" viewBox="0 0 24 24" fill="currentColor">
    <path d="M12 0L14.59 9.41L24 12L14.59 14.59L12 24L9.41 14.59L0 12L9.41 9.41L12 0Z"/>
  </svg>
  <svg class="sparkle absolute top-[60%] left-[8%] w-5 h-5 text-gold-400 opacity-50" style="animation-delay: 2.2s;" viewBox="0 0 24 24" fill="currentColor">
    <path d="M12 0L14.59 9.41L24 12L14.59 14.59L12 24L9.41 14.59L0 12L9.41 9.41L12 0Z"/>
  </svg>
  <svg class="sparkle absolute bottom-36 left-[35%] w-4 h-4 text-gold-400 opacity-50" style="animation-delay: 1.8s;" viewBox="0 0 24 24" fill="currentColor">
    <path d="M12 0L14.59 9.41L24 12L14.59 14.59L12 24L9.41 14.59L0 12L9.41 9.41L12 0Z"/>
  </svg>
  <svg class="sparkle absolute bottom-20 right-[25%] w-5 h-5 text-gold-400 opacity-60" style="animation-delay: 0.5s;" viewBox="0 0 24 24" fill="currentColor">
    <path d="M12 0L14.59 9.41L24 12L14.59 14.59L12 24L9.41 14.59L0 12L9.41 9.41L12 0Z"/>
  </svg>
</div>

    <div class="md:col-span-5 flex flex-wrap items-center justify-start md:justify-end gap-6 pt-4 md:pt-0">
      <div class="relative w-20 h-20 flex items-center justify-center">
        <svg class="animate-spin-slow w-full h-full text-gold-500 opacity-80" viewBox="0 0 100 100">
          <path id="textPath" d="M 50, 50 m -37, 0 a 37,37 0 1,1 74,0 a 37,37 0 1,1 -74,0" fill="none"/>
          <text font-size="10.5" font-weight="bold" fill="currentColor" letter-spacing="2">
            <textPath href="#textPath">
              CATALOGUE • PRIVÉ • K SHOP •
            </textPath>
          </text>
        </svg>
        <div class="absolute inset-0 flex items-center justify-center text-gold-400">
          <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
          </svg>
        </div>
      </div>
    </div>
  </div>
</main>

<section id="catalogue" class="relative z-10 w-full max-w-7xl mx-auto px-6 py-20 border-t border-gray-800/60">
  
  <div class="text-center max-w-2xl mx-auto mb-16 space-y-3">
    <span class="text-gold-500 uppercase tracking-widest text-xs font-bold font-futura">Offres Exclusives</span>
    <h2 class="text-3xl sm:text-4xl font-bold font-futura text-white tracking-tight">
      Nos Cartes <span class="text-gold-500">VIP Anonymes</span>
    </h2>
    <p class="text-gray-400 text-sm sm:text-base">
      Choisissez la carte adaptée à vos besoins. Toutes nos cartes sont pré-activées, 100% anonymes et livrées sous pli confidentiel.
    </p>
  </div>

  <!-- GRID OF 4 CARDS -->
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">

    <!-- CARD 1: SILVER EXPRESS -->
    <div class="glass-card rounded-2xl p-6 flex flex-col justify-between relative group overflow-hidden">
      <div class="absolute -top-12 -right-12 w-28 h-28 bg-gray-400/10 rounded-full blur-2xl group-hover:bg-gold-500/20 transition-all"></div>
      
      <div>
        <!-- Card Header -->
        <div class="flex justify-between items-start mb-4">
          <span class="px-3 py-1 bg-gray-800/80 text-gray-300 border border-gray-600/40 rounded-full text-xs font-semibold">Standard</span>
          <span class="text-2xl font-bold text-white font-futura">300 <span class="text-gold-500 text-sm font-normal">€</span></span>
        </div>

        <!-- SVG Visual 1 -->
        <div class="my-5 relative flex justify-center transform group-hover:scale-105 transition-transform duration-300">
          <svg class="w-full h-40 drop-shadow-lg" viewBox="0 0 300 180" fill="none">
            <rect width="300" height="180" rx="14" fill="url(#silverGrad)" stroke="#9ca3af" stroke-width="1" />
            <defs>
              <linearGradient id="silverGrad" x1="0%" y1="0%" x2="100%" y2="100%">
                <stop offset="0%" stop-color="#374151" />
                <stop offset="50%" stop-color="#1f2937" />
                <stop offset="100%" stop-color="#111827" />
              </linearGradient>
            </defs>
            <rect x="24" y="60" width="40" height="30" rx="5" fill="#d1d5db" />
            <text x="24" y="38" font-family="sans-serif" font-size="11" fill="#9ca3af" letter-spacing="2">SILVER EXPRESS</text>
            <text x="24" y="125" font-family="monospace" font-size="14" fill="#f3f4f6" letter-spacing="3">•••• •••• 4821</text>
            <text x="24" y="152" font-family="monospace" font-size="10" fill="#9ca3af">VAL: 12/26</text>
            <circle cx="250" cy="140" r="16" fill="#9ca3af" opacity="0.3"/>
            <circle cx="230" cy="140" r="16" fill="#e5e7eb" opacity="0.3"/>
          </svg>
        </div>

        <h3 class="text-xl font-bold text-white font-futura mb-4 group-hover:text-gold-400 transition-colors">
          Carte Silver Express
        </h3>

        <!-- Specs List -->
        <ul class="space-y-2.5 text-xs text-gray-300 mb-6">
          <li class="flex items-center gap-2.5">
            <svg class="w-4 h-4 text-gold-500 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/></svg>
            <span>Plafond : <strong class="text-white">2 500 € / mois</strong></span>
          </li>
          <li class="flex items-center gap-2.5">
            <svg class="w-4 h-4 text-gold-500 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/></svg>
            <span>Validité : <strong class="text-white">12 Mois</strong></span>
          </li>
          <li class="flex items-center gap-2.5">
            <svg class="w-4 h-4 text-gold-500 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/></svg>
            <span>Retraits : <strong class="text-white">2 retraits / jour max</strong></span>
          </li>
        </ul>
      </div>

      <button  class="btn-outline w-full py-2.5 rounded-xl font-bold text-xs tracking-wider uppercase"><a href="buy.php">
        Commander</a>
      </button>
    </div>

    <!-- CARD 2: GOLD PRIVILEGE (POPULAR) -->
    <div class="glass-card rounded-2xl p-6 flex flex-col justify-between relative group border-gold-500/50 shadow-lg shadow-gold-500/10">
      <div class="absolute top-0 right-6 transform -translate-y-1/2">
        <span class="bg-gold-500 text-black font-extrabold text-[10px] tracking-widest uppercase px-3 py-1 rounded-full shadow-md">
          Plus Populaire
        </span>
      </div>

      <div>
        <!-- Card Header -->
        <div class="flex justify-between items-start mb-4">
          <span class="px-3 py-1 bg-gold-500/10 text-gold-400 border border-gold-500/30 rounded-full text-xs font-semibold">Gold</span>
          <span class="text-2xl font-bold text-white font-futura">500 <span class="text-gold-500 text-sm font-normal">€</span></span>
        </div>

        <!-- SVG Visual 2 -->
        <div class="my-5 relative flex justify-center transform group-hover:scale-105 transition-transform duration-300">
          <svg class="w-full h-40 drop-shadow-xl" viewBox="0 0 300 180" fill="none">
            <rect width="300" height="180" rx="14" fill="url(#goldCardGrad)" stroke="#eab308" stroke-width="1.5" />
            <defs>
              <linearGradient id="goldCardGrad" x1="0%" y1="0%" x2="100%" y2="100%">
                <stop offset="0%" stop-color="#713f12" />
                <stop offset="50%" stop-color="#a16207" />
                <stop offset="100%" stop-color="#ca8a04" />
              </linearGradient>
            </defs>
            <rect x="24" y="60" width="40" height="30" rx="5" fill="#fef08a" />
            <text x="24" y="38" font-family="sans-serif" font-size="11" fill="#fef08a" letter-spacing="2">GOLD PRIVILEGE</text>
            <text x="24" y="125" font-family="monospace" font-size="14" fill="#ffffff" letter-spacing="3">•••• •••• 8802</text>
            <text x="24" y="152" font-family="monospace" font-size="10" fill="#fef08a">VAL: 24 MOIS</text>
            <circle cx="250" cy="140" r="16" fill="#fef08a" opacity="0.4"/>
            <circle cx="230" cy="140" r="16" fill="#ffffff" opacity="0.4"/>
          </svg>
        </div>

        <h3 class="text-xl font-bold text-white font-futura mb-4 group-hover:text-gold-400 transition-colors">
          Carte Gold Privilege
        </h3>

        <!-- Specs List -->
        <ul class="space-y-2.5 text-xs text-gray-300 mb-6">
          <li class="flex items-center gap-2.5">
            <svg class="w-4 h-4 text-gold-500 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/></svg>
            <span>Plafond : <strong class="text-white">7 500 € / mois</strong></span>
          </li>
          <li class="flex items-center gap-2.5">
            <svg class="w-4 h-4 text-gold-500 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/></svg>
            <span>Validité : <strong class="text-white">24 Mois</strong></span>
          </li>
          <li class="flex items-center gap-2.5">
            <svg class="w-4 h-4 text-gold-500 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/></svg>
            <span>Retraits : <strong class="text-white">5 retraits / jour max</strong></span>
          </li>
        </ul>
      </div>

      <button  class="btn-primary w-full py-2.5 rounded-xl font-bold text-xs tracking-wider uppercase"><a href="buy.php">
        Commander</a>
      </button>
    </div>

    <!-- CARD 3: PLATINUM ANONYME -->
    <div class="glass-card rounded-2xl p-6 flex flex-col justify-between relative group overflow-hidden">
      <div class="absolute -top-12 -right-12 w-28 h-28 bg-cyan-500/10 rounded-full blur-2xl group-hover:bg-gold-500/20 transition-all"></div>

      <div>
        <!-- Card Header -->
        <div class="flex justify-between items-start mb-4">
          <span class="px-3 py-1 bg-gray-800/80 text-cyan-300 border border-cyan-500/30 rounded-full text-xs font-semibold">Premium</span>
          <span class="text-2xl font-bold text-white font-futura">850 <span class="text-gold-500 text-sm font-normal">€</span></span>
        </div>

        <!-- SVG Visual 3 -->
        <div class="my-5 relative flex justify-center transform group-hover:scale-105 transition-transform duration-300">
          <svg class="w-full h-40 drop-shadow-xl" viewBox="0 0 300 180" fill="none">
            <rect width="300" height="180" rx="14" fill="url(#platGrad)" stroke="#38bdf8" stroke-width="1" />
            <defs>
              <linearGradient id="platGrad" x1="0%" y1="0%" x2="100%" y2="100%">
                <stop offset="0%" stop-color="#0f172a" />
                <stop offset="50%" stop-color="#1e293b" />
                <stop offset="100%" stop-color="#334155" />
              </linearGradient>
            </defs>
            <rect x="24" y="60" width="40" height="30" rx="5" fill="#38bdf8" />
            <text x="24" y="38" font-family="sans-serif" font-size="11" fill="#38bdf8" letter-spacing="2">PLATINUM VIP</text>
            <text x="24" y="125" font-family="monospace" font-size="14" fill="#f8fafc" letter-spacing="3">•••• •••• 9914</text>
            <text x="24" y="152" font-family="monospace" font-size="10" fill="#38bdf8">VAL: 36 MOIS</text>
            <circle cx="250" cy="140" r="16" fill="#38bdf8" opacity="0.4"/>
            <circle cx="230" cy="140" r="16" fill="#ffffff" opacity="0.4"/>
          </svg>
        </div>

        <h3 class="text-xl font-bold text-white font-futura mb-4 group-hover:text-gold-400 transition-colors">
          Platinum Anonyme
        </h3>

        <!-- Specs List -->
        <ul class="space-y-2.5 text-xs text-gray-300 mb-6">
          <li class="flex items-center gap-2.5">
            <svg class="w-4 h-4 text-gold-500 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/></svg>
            <span>Plafond : <strong class="text-white">15 000 € / mois</strong></span>
          </li>
          <li class="flex items-center gap-2.5">
            <svg class="w-4 h-4 text-gold-500 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/></svg>
            <span>Validité : <strong class="text-white">36 Mois</strong></span>
          </li>
          <li class="flex items-center gap-2.5">
            <svg class="w-4 h-4 text-gold-500 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/></svg>
            <span>Retraits : <strong class="text-white">10 retraits / jour max</strong></span>
          </li>
        </ul>
      </div>

      <button  class="btn-outline w-full py-2.5 rounded-xl font-bold text-xs tracking-wider uppercase"><a href="buy.php">
        Commander</a>
      </button>
    </div>

    <!-- CARD 4: BLACK OBSIDIAN -->
    <div class="glass-card rounded-2xl p-6 flex flex-col justify-between relative group border-gray-700/80 hover:border-gold-500/70">
      <div class="absolute -top-12 -right-12 w-28 h-28 bg-gold-600/10 rounded-full blur-2xl group-hover:bg-gold-500/25 transition-all"></div>

      <div>
        <!-- Card Header -->
        <div class="flex justify-between items-start mb-4">
          <span class="px-3 py-1 bg-black text-gold-400 border border-gold-500/40 rounded-full text-xs font-semibold">Ultimate</span>
          <span class="text-2xl font-bold text-white font-futura">1 000 <span class="text-gold-500 text-sm font-normal">€</span></span>
        </div>

        <!-- SVG Visual 4 -->
        <div class="my-5 relative flex justify-center transform group-hover:scale-105 transition-transform duration-300">
          <svg class="w-full h-40 drop-shadow-2xl" viewBox="0 0 300 180" fill="none">
            <rect width="300" height="180" rx="14" fill="#050505" stroke="#ca8a04" stroke-width="1.5" />
            <rect x="24" y="60" width="40" height="30" rx="5" fill="#eab308" />
            <text x="24" y="38" font-family="sans-serif" font-size="11" fill="#eab308" letter-spacing="2">BLACK OBSIDIAN</text>
            <text x="24" y="125" font-family="monospace" font-size="14" fill="#ffffff" letter-spacing="3">•••• •••• 0001</text>
            <text x="24" y="152" font-family="monospace" font-size="10" fill="#eab308">VAL: ILLIMITÉE</text>
            <path d="M230 130 L250 150 M250 130 L230 150" stroke="#eab308" stroke-width="2"/>
            <circle cx="240" cy="140" r="15" stroke="#eab308" stroke-width="1.5"/>
          </svg>
        </div>

        <h3 class="text-xl font-bold text-white font-futura mb-4 group-hover:text-gold-400 transition-colors">
          Black Obsidian
        </h3>

        <!-- Specs List -->
        <ul class="space-y-2.5 text-xs text-gray-300 mb-6">
          <li class="flex items-center gap-2.5">
            <svg class="w-4 h-4 text-gold-500 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/></svg>
            <span>Plafond : <strong class="text-white">25 000 €</strong></span>
          </li>
          <li class="flex items-center gap-2.5">
            <svg class="w-4 h-4 text-gold-500 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/></svg>
            <span>Validité : <strong class="text-white">A vie (48 Mois+)</strong></span>
          </li>
          <li class="flex items-center gap-2.5">
            <svg class="w-4 h-4 text-gold-500 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/></svg>
            <span>Retraits : <strong class="text-white">Illimité</strong></span>
          </li>
        </ul>
      </div>

      <button  class="btn-primary w-full py-2.5 rounded-xl font-bold text-xs tracking-wider uppercase"><a href="buy.php">
        Commander</a>
      </button>
    </div>

  </div>
</section>



    



<!-- BULLE ET WIDGET DE CHAT -->
<div class="fixed bottom-6 right-6 z-40">
  <button id="chat-toggle-btn" class="glass-panel text-white flex items-center gap-3 px-5 py-3.5 rounded-full shadow-2xl hover:scale-105 transition border border-gold-500/40">
    <div class="relative flex items-center justify-center">
      <svg class="w-6 h-6 text-gold-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
      <span class="absolute -top-1 -right-1 flex h-3 w-3"><span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span><span class="relative inline-flex rounded-full h-3 w-3 bg-emerald-500"></span></span>
    </div>
    <span class="text-xs sm:text-sm font-bold font-futura">Discuter avec d'autre utilisateur</span>
    <span id="chat-online-badge" class="bg-gold-500/20 text-gold-400 border border-gold-500/30 text-[10px] font-bold px-2 py-0.5 rounded-full">1 en ligne</span>
  </button>

  <div id="chat-widget" class="glass-panel fixed bottom-20 right-6 w-[90vw] sm:w-[380px] h-[520px] rounded-2xl flex flex-col hidden overflow-hidden shadow-2xl border border-gold-500/30 z-50">
    <div class="p-4 bg-black/40 border-b border-gray-800 flex items-center justify-between">
      <div class="flex items-center gap-3">
        <div class="w-9 h-9 rounded-full bg-gold-500/10 border border-gold-500/40 flex items-center justify-center text-gold-400">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
        </div>
        <div>
          <h4 class="text-sm font-bold font-futura text-white">Salon Public Anonyme</h4>
          <p class="text-[11px] text-emerald-400 flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span><span id="chat-online-count">1 personne en ligne</span></p>
        </div>
      </div>
      <button id="chat-close-btn" class="text-gray-400 hover:text-gold-400 p-1 rounded-lg"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
    </div>

    <div id="chat-messages" class="flex-1 p-4 overflow-y-auto space-y-4 custom-scrollbar text-xs"></div>

    <div id="media-preview-container" class="hidden px-4 py-2 bg-black/60 border-t border-gray-800 flex items-center justify-between">
      <div id="media-preview" class="flex items-center gap-2 overflow-hidden"></div>
      <button id="cancel-media-btn" class="text-xs text-red-400 hover:text-red-300 font-bold">Annuler</button>
    </div>

    <form id="chat-form" class="p-3 bg-black/50 border-t border-gray-800 flex items-center gap-2" enctype="multipart/form-data">
      <input type="file" id="media-input" accept="image/*,video/*" class="hidden">
      <button type="button" id="upload-btn" class="p-2 text-gray-400 hover:text-gold-400 rounded-xl hover:bg-white/5"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg></button>
      <input type="text" id="chat-input" placeholder="Écrivez votre message..." class="glass-input flex-1 px-3 py-2 rounded-xl text-xs text-white placeholder-gray-500 focus:outline-none">
      <button type="submit" class="bg-yellow-500 text-black p-2 rounded-xl font-bold flex items-center justify-center shrink-0"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg></button>
    </form>
  </div>
</div>

<script src="assets/js/chat.js"></script>
</body>
</html>