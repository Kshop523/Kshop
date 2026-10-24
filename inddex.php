<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'includes/session.php';

// Si l'utilisateur est déjà connecté, on le redirige vers le catalogue
if (isset($_SESSION['user_id'])) {
    header('Location: catalogue.php');
    exit;
}

// Génération du jeton CSRF pour sécuriser les formulaires de cette page
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>
<!DOCTYPE html>
<html lang="fr" class="dark">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>K shop - Solution d'Achat Anonyme & Rapide</title>
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
/* Custom Keyframe Animations & Responsive CSS */
@import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;700&display=swap');

body {
font-family: 'Plus Jakarta Sans', sans-serif;
background: radial-gradient(circle at 75% 30%, #2a2415 0%, #111215 60%, #0a0a0c 100%);
color: #e5e7eb;
overflow-x: hidden;
min-height: 100vh;
}

.font-futura {
font-family: 'Futura', 'Futura-Medium', 'Futura SemiBold', 'Century Gothic', sans-serif;
}

/* Continuous Floating Animation for 3D Graphic */
@keyframes float {
0% { transform: translateY(0px) rotate(0deg); }
50% { transform: translateY(-16px) rotate(1.5deg); }
100% { transform: translateY(0px) rotate(0deg); }
}

.animate-float {
animation: float 6s ease-in-out infinite;
}

/* Sparkle Pulse */
@keyframes pulseSparkle {
0%, 100% { opacity: 0.2; transform: scale(0.8); }
50% { opacity: 1; transform: scale(1.3); }
}

.sparkle {
animation: pulseSparkle 3s ease-in-out infinite;
}

/* Rotating Circular Badge */
@keyframes spinSlow {
from { transform: rotate(0deg); }
to { transform: rotate(360deg); }
}

.animate-spin-slow {
animation: spinSlow 20s linear infinite;
}

/* Slide In Animation for Subtitle */
.slide-in-left {
opacity: 0;
transform: translateX(-80px);
transition: opacity 0.9s cubic-bezier(0.16, 1, 0.3, 1), transform 0.9s cubic-bezier(0.16, 1, 0.3, 1);
}

.slide-in-left.visible {
opacity: 1;
transform: translateX(0);
}

/* Reverse hover & active state transitions */
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

/* Cursor blinking for Typewriter Effect */
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

/* Glassmorphism styling for popups and inputs */
.glass-panel {
background: rgba(17, 18, 21, 0.75);
backdrop-filter: blur(20px);
-webkit-backdrop-filter: blur(20px);
border: 1px solid rgba(234, 179, 8, 0.25);
box-shadow: 0 20px 50px rgba(0, 0, 0, 0.8), 0 0 30px rgba(234, 179, 8, 0.15);
}

.glass-input {
background: rgba(255, 255, 255, 0.04);
border: 1px solid rgba(234, 179, 8, 0.2);
transition: all 0.3s ease;
}

.glass-input:focus {
background: rgba(255, 255, 255, 0.07);
border-color: #eab308;
box-shadow: 0 0 15px rgba(234, 179, 8, 0.25);
outline: none;
}
</style>
</head>
<body class="relative flex flex-col justify-between min-h-screen text-gray-200 selection:bg-gold-500 selection:text-black">

<!-- Background Stars / Sparkles -->
<div class="pointer-events-none absolute inset-0 overflow-hidden z-0">
<svg class="sparkle absolute top-20 left-[45%] w-6 h-6 text-gold-400 opacity-70" viewBox="0 0 24 24" fill="currentColor">
<path d="M12 0L14.59 9.41L24 12L14.59 14.59L12 24L9.41 14.59L0 12L9.41 9.41L12 0Z"/>
</svg>
<svg class="sparkle absolute top-40 right-[10%] w-5 h-5 text-gold-400 opacity-60" style="animation-delay: 1s;" viewBox="0 0 24 24" fill="currentColor">
<path d="M12 0L14.59 9.41L24 12L14.59 14.59L12 24L9.41 14.59L0 12L9.41 9.41L12 0Z"/>
</svg>
<svg class="sparkle absolute bottom-36 left-[35%] w-4 h-4 text-gold-400 opacity-50" style="animation-delay: 1.8s;" viewBox="0 0 24 24" fill="currentColor">
<path d="M12 0L14.59 9.41L24 12L14.59 14.59L12 24L9.41 14.59L0 12L9.41 9.41L12 0Z"/>
</svg>
<svg class="sparkle absolute bottom-20 right-[25%] w-5 h-5 text-gold-400 opacity-60" style="animation-delay: 0.5s;" viewBox="0 0 24 24" fill="currentColor">
<path d="M12 0L14.59 9.41L24 12L14.59 14.59L12 24L9.41 14.59L0 12L9.41 9.41L12 0Z"/>
</svg>
</div>

<!-- HEADER -->
<header class="relative z-10 w-full max-w-7xl mx-auto px-6 py-8">
<div class="flex items-center justify-between">
<h1 class="text-3xl font-extrabold tracking-wider font-futura text-white">
K <span class="text-gold-500">shop</span>
</h1>
</div>
</header>

<!-- MAIN CONTENT -->
<main class="relative z-10 w-full max-w-7xl mx-auto px-6 my-auto">
<div class="flex flex-col md:grid md:grid-cols-12 md:gap-12 items-center">

<!-- FLOATING 3D GRAPHIC IMAGE -->
<div class="order-1 md:order-2 md:col-span-6 w-full flex justify-center mb-10 md:mb-0">
<div class="relative animate-float w-full max-w-lg">
<div class="absolute -inset-4 bg-gradient-to-r from-gold-600/20 to-yellow-600/10 rounded-full blur-3xl opacity-50"></div>

<svg class="w-full h-auto drop-shadow-[0_25px_35px_rgba(0,0,0,0.8)]" viewBox="0 0 600 450" fill="none" xmlns="http://www.w3.org/2000/svg">
<defs>
<linearGradient id="goldHand" x1="0%" y1="0%" x2="100%" y2="100%">
<stop offset="0%" stop-color="#fef08a" />
<stop offset="50%" stop-color="#ca8a04" />
<stop offset="100%" stop-color="#713f12" />
</linearGradient>
<linearGradient id="cardGold" x1="0%" y1="0%" x2="100%" y2="100%">
<stop offset="0%" stop-color="#fef08a" />
<stop offset="100%" stop-color="#a16207" />
</linearGradient>
<linearGradient id="cardDark" x1="0%" y1="0%" x2="100%" y2="100%">
<stop offset="0%" stop-color="#27272a" />
<stop offset="100%" stop-color="#09090b" />
</linearGradient>
<filter id="shadow" x="-10%" y="-10%" width="130%" height="130%">
<feDropShadow dx="0" dy="15" stdDeviation="15" flood-color="#000000" flood-opacity="0.6"/>
</filter>
</defs>

<!-- Back Card (Gold Edition) -->
<g transform="translate(230, 80) rotate(-12)" filter="url(#shadow)">
<rect width="260" height="165" rx="16" fill="url(#cardGold)" stroke="#fef08a" stroke-width="1.5" />
<rect x="25" y="80" width="42" height="32" rx="6" fill="#ca8a04" stroke="#fef08a" stroke-width="1"/>
<text x="25" y="50" font-family="monospace" font-size="14" fill="#3f2305" font-weight="bold">CREDIT CARD</text>
<text x="25" y="140" font-family="monospace" font-size="15" fill="#3f2305" letter-spacing="3">1234 •••• 06/25</text>
</g>

<!-- Front Card (Premium Matte Black) -->
<g transform="translate(270, 110) rotate(-2)" filter="url(#shadow)">
<rect width="270" height="170" rx="16" fill="url(#cardDark)" stroke="#eab308" stroke-width="1.5" />
<rect x="30" y="70" width="45" height="34" rx="6" fill="#eab308" />
<path d="M 30 87 L 75 87 M 52 70 L 52 104" stroke="#854d0e" stroke-width="1.5"/>
<text x="30" y="45" font-family="sans-serif" font-size="12" fill="#a1a1aa" letter-spacing="2">K SHOP VIP</text>
<text x="30" y="135" font-family="monospace" font-size="17" fill="#ffffff" letter-spacing="3">1234 5678 9012 245</text>
<text x="30" y="155" font-family="monospace" font-size="11" fill="#eab308">06/25</text>
</g>

<!-- Stylized Metallic Golden Hand Support -->
<g filter="url(#shadow)">
<path d="M 120 380 C 180 340 280 330 380 320 C 450 312 490 280 520 230 C 510 240 450 290 380 290 C 280 290 200 320 120 380 Z" fill="url(#goldHand)" />
<path d="M 280 320 C 320 290 390 270 470 240 C 490 232 510 230 520 235 C 500 248 450 280 380 295 C 330 305 290 320 280 320 Z" fill="url(#goldHand)" opacity="0.9" />
<path d="M 240 340 C 290 310 380 290 460 265 C 480 258 500 258 510 265 C 490 275 440 300 370 315 C 300 330 250 345 240 340 Z" fill="url(#goldHand)" opacity="0.8" />
<path d="M 200 360 C 250 330 340 310 430 290 C 450 285 470 288 480 295 C 460 305 410 325 350 340 C 280 355 210 365 200 360 Z" fill="url(#goldHand)" opacity="0.7" />
</g>
</svg>
</div>
</div>

<!-- TEXT CONTENT & BUTTONS -->
<div class="order-2 md:order-1 md:col-span-6 space-y-6">

<div class="min-h-[120px] sm:min-h-[140px] flex items-center">
<h2 class="text-3xl sm:text-5xl lg:text-6xl font-bold font-futura text-white leading-tight tracking-tight">
<span id="typewriter-text"></span><span class="cursor-blink">&nbsp;</span>
</h2>
</div>

<p id="slide-subtitle" class="slide-in-left text-gray-400 text-base sm:text-lg leading-relaxed max-w-xl">
Ce site est un catalogue, il permet uniquement l'achat et certaines interactions entre nos utilisateurs dans l'anonymat. Les livraison sont boucler entre vous et un membre du support en privée.
</p>

<!-- Action triggers for modals -->
<div class="pt-2 flex flex-wrap items-center gap-4 sm:gap-6">
<button id="btn-login" class="btn-primary px-8 py-3.5 rounded-full font-semibold text-base tracking-wide focus:outline-none">
Se connecter
</button>
<button id="btn-register" class="btn-outline px-8 py-3.5 rounded-full font-semibold text-base tracking-wide focus:outline-none">
S'inscrire
</button>
</div>

</div>

</div>

<!-- BOTTOM FEATURES & BADGE SECTION -->
<div class="mt-16 md:mt-24 grid grid-cols-1 md:grid-cols-12 gap-8 items-end border-t border-gray-800/80 pt-10 pb-12">
<div class="md:col-span-7 grid grid-cols-1 sm:grid-cols-2 gap-8">
<div class="space-y-2">
<span class="text-gold-500 font-bold text-lg font-futura">01</span>
<h3 class="text-white text-lg font-bold font-futura">Transaction financière</h3>
<p class="text-gray-400 text-sm leading-relaxed">
Gérez l'ensemble de vos transactions et échanges en toute sécurité et simplicité.
</p>
</div>

<div class="space-y-2">
<span class="text-gold-500 font-bold text-lg font-futura">02</span>
<h3 class="text-white text-lg font-bold font-futura">Système facile à utiliser</h3>
<p class="text-gray-400 text-sm leading-relaxed">
Un catalogue intuitif conçu pour préserver votre anonymat et simplifier vos démarches.
</p>
</div>
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

<!-- MODAL OVERLAY & POPUP CONTAINER -->
<div id="modal-overlay" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/70 backdrop-blur-md opacity-0 pointer-events-none transition-opacity duration-300">

  <!-- MODAL CARD CONTAINER -->
  <div id="modal-card" class="glass-panel w-full max-w-md rounded-2xl p-8 relative transform scale-95 translate-y-4 opacity-0 transition-all duration-300">
    
    <!-- Close Button -->
    <button id="modal-close" class="absolute top-5 right-5 text-gray-400 hover:text-gold-400 transition-colors focus:outline-none p-1 rounded-lg">
      <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
      </svg>
    </button>

    <!-- FORM 1: SE CONNECTER (LOGIN) -->
    <div id="form-login" class="space-y-6">
      <div class="text-center space-y-1">
        <h3 class="text-2xl font-bold font-futura text-white tracking-wide">
          Se <span class="text-gold-500">Connecter</span>
        </h3>
        <p class="text-xs text-gray-400">Accédez à votre espace privé anonyme</p>
      </div>

      <!-- Zone de message d'alerte -->
      <div id="login-alert" class="hidden px-4 py-3 rounded-xl text-xs font-medium"></div>

      <form id="ajax-login-form" class="space-y-4">
        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
        <!-- Nom d'utilisateur -->
        <div class="space-y-1">
          <label class="text-xs font-semibold uppercase tracking-wider text-gold-400">Nom d'utilisateur</label>
          <div class="relative">
            <input type="text" name="username" required placeholder="Ex: AnonymousK" class="glass-input w-full px-4 py-3 rounded-xl text-sm text-white placeholder-gray-500 focus:outline-none">
            <span class="absolute right-3.5 top-3.5 text-gray-500">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
              </svg>
            </span>
          </div>
        </div>

        <!-- Mot de passe -->
        <div class="space-y-1">
          <label class="text-xs font-semibold uppercase tracking-wider text-gold-400">Mot de passe</label>
          <div class="relative">
            <input type="password" name="password" id="login-password" required placeholder="••••••••" class="glass-input w-full px-4 py-3 rounded-xl text-sm text-white placeholder-gray-500 focus:outline-none pr-10">
            <button type="button" onclick="togglePasswordVisibility('login-password', this)" class="absolute right-3.5 top-3.5 text-gray-500 hover:text-gold-400 focus:outline-none">
              <svg class="w-4 h-4 eye-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
              </svg>
            </button>
          </div>
        </div>

        <!-- Bouton Validation -->
        <button type="submit" id="btn-submit-login" class="btn-primary w-full py-3.5 rounded-xl font-bold text-sm tracking-wide mt-2">
          Connexion
        </button>
      </form>

      <!-- Switch vers Inscription -->
      <div class="text-center pt-2 border-t border-gray-800/80">
        <p class="text-xs text-gray-400">
          Pas encore de compte ? 
          <button id="switch-to-register" class="text-gold-500 hover:underline font-semibold ml-1 focus:outline-none">
            S'inscrire
          </button>
        </p>
      </div>
    </div>

    <!-- FORM 2: S'INSCRIRE (REGISTER) -->
    <div id="form-register" class="space-y-5 hidden">
      <div class="text-center space-y-1">
        <h3 class="text-2xl font-bold font-futura text-white tracking-wide">
          Créer un <span class="text-gold-500">Compte</span>
        </h3>
        <p class="text-xs text-gray-400">Rejoignez le réseau en toute confidentialité</p>
      </div>

      <!-- Zone de message d'alerte -->
      <div id="register-alert" class="hidden px-4 py-3 rounded-xl text-xs font-medium"></div>

      <form id="ajax-register-form" class="space-y-3.5">
        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
        <!-- Créer un nom d'utilisateur -->
        <div class="space-y-1">
          <label class="text-xs font-semibold uppercase tracking-wider text-gold-400">Nom d'utilisateur</label>
          <div class="relative">
            <input type="text" name="username" required placeholder="Choisissez un pseudo" class="glass-input w-full px-4 py-3 rounded-xl text-sm text-white placeholder-gray-500 focus:outline-none">
            <span class="absolute right-3.5 top-3.5 text-gray-500">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
              </svg>
            </span>
          </div>
        </div>

        <!-- Créer un mot de passe -->
        <div class="space-y-1">
          <label class="text-xs font-semibold uppercase tracking-wider text-gold-400">Mot de passe</label>
          <div class="relative">
            <input type="password" name="password" id="reg-password" required placeholder="Créez un mot de passe fort" class="glass-input w-full px-4 py-3 rounded-xl text-sm text-white placeholder-gray-500 focus:outline-none pr-10">
            <button type="button" onclick="togglePasswordVisibility('reg-password', this)" class="absolute right-3.5 top-3.5 text-gray-500 hover:text-gold-400 focus:outline-none">
              <svg class="w-4 h-4 eye-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
              </svg>
            </button>
          </div>
        </div>

        <!-- Confirmer le mot de passe -->
        <div class="space-y-1">
          <label class="text-xs font-semibold uppercase tracking-wider text-gold-400">Confirmation</label>
          <div class="relative">
            <input type="password" name="confirm" id="reg-confirm" required placeholder="Confirmez le mot de passe" class="glass-input w-full px-4 py-3 rounded-xl text-sm text-white placeholder-gray-500 focus:outline-none pr-10">
            <button type="button" onclick="togglePasswordVisibility('reg-confirm', this)" class="absolute right-3.5 top-3.5 text-gray-500 hover:text-gold-400 focus:outline-none">
              <svg class="w-4 h-4 eye-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
              </svg>
            </button>
          </div>
        </div>

        <!-- Bouton Validation -->
        <button type="submit" id="btn-submit-register" class="btn-primary w-full py-3.5 rounded-xl font-bold text-sm tracking-wide mt-3">
          Créer mon compte
        </button>
      </form>

      <!-- Switch vers Connexion -->
      <div class="text-center pt-2 border-t border-gray-800/80">
        <p class="text-xs text-gray-400">
          Déjà un compte ? 
          <button id="switch-to-login" class="text-gold-500 hover:underline font-semibold ml-1 focus:outline-none">
            Se connecter
          </button>
        </p>
      </div>
    </div>

  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {

// 1. RAPID TYPEWRITER EFFECT FOR THE MAIN TITLE
const titleText = "Une Solution Pour Un Achat Simple, Rapide et Efficace";
const targetElement = document.getElementById('typewriter-text');
let index = 0;
const speed = 35; 

function typeWriter() {
if (index < titleText.length) {
targetElement.textContent += titleText.charAt(index);
index++;
setTimeout(typeWriter, speed);
}
}
typeWriter();

// 2. SLIDE-IN ANIMATION FOR SUBTITLE
const subtitle = document.getElementById('slide-subtitle');
setTimeout(() => {
if (subtitle) {
subtitle.classList.add('visible');
}
}, 300);

// 3. MODAL LOGIC & ANIMATIONS
const overlay = document.getElementById('modal-overlay');
const card = document.getElementById('modal-card');
const btnLogin = document.getElementById('btn-login');
const btnRegister = document.getElementById('btn-register');
const btnClose = document.getElementById('modal-close');

const formLogin = document.getElementById('form-login');
const formRegister = document.getElementById('form-register');
const switchToRegister = document.getElementById('switch-to-register');
const switchToLogin = document.getElementById('switch-to-login');

function openModal(mode = 'login') {
  if (mode === 'login') {
    formLogin.classList.remove('hidden');
    formRegister.classList.add('hidden');
  } else {
    formLogin.classList.add('hidden');
    formRegister.classList.remove('hidden');
  }

  // Prevent scrolling behind
  document.body.style.overflow = 'hidden';

  // Show overlay
  overlay.classList.remove('pointer-events-none');
  overlay.classList.remove('opacity-0');
  overlay.classList.add('opacity-100');

  // Animate card up
  setTimeout(() => {
    card.classList.remove('scale-95', 'translate-y-4', 'opacity-0');
    card.classList.add('scale-100', 'translate-y-0', 'opacity-100');
  }, 50);
}

function closeModal() {
  card.classList.remove('scale-100', 'translate-y-0', 'opacity-100');
  card.classList.add('scale-95', 'translate-y-4', 'opacity-0');

  setTimeout(() => {
    overlay.classList.remove('opacity-100');
    overlay.classList.add('opacity-0');
    overlay.classList.add('pointer-events-none');
    document.body.style.overflow = '';
  }, 200);
}

// Event Listeners
btnLogin.addEventListener('click', () => openModal('login'));
btnRegister.addEventListener('click', () => openModal('register'));
btnClose.addEventListener('click', closeModal);

// Close when clicking outside of card
overlay.addEventListener('click', (e) => {
  if (e.target === overlay) {
    closeModal();
  }
});

// Close with Escape key
document.addEventListener('keydown', (e) => {
  if (e.key === 'Escape') closeModal();
});

// Switch between forms inside modal
switchToRegister.addEventListener('click', () => {
  formLogin.classList.add('hidden');
  formRegister.classList.remove('hidden');
});

switchToLogin.addEventListener('click', () => {
  formRegister.classList.add('hidden');
  formLogin.classList.remove('hidden');
});

// 4. AJAX SUBMISSION FOR LOGIN
const ajaxLoginForm = document.getElementById('ajax-login-form');
const loginAlert = document.getElementById('login-alert');
const btnSubmitLogin = document.getElementById('btn-submit-login');

ajaxLoginForm.addEventListener('submit', async (e) => {
  e.preventDefault();
  loginAlert.classList.add('hidden');
  btnSubmitLogin.disabled = true;
  btnSubmitLogin.textContent = 'Connexion en cours...';

  const formData = new FormData(ajaxLoginForm);

  try {
    const response = await fetch('api/login.php', {
      method: 'POST',
      body: formData
    });
    const result = await response.json();

    if (result.success) {
      window.location.href = result.redirect;
    } else {
      loginAlert.textContent = result.message;
      loginAlert.className = 'px-4 py-3 rounded-xl text-xs font-medium bg-red-500/20 border border-red-500/50 text-red-300';
      loginAlert.classList.remove('hidden');
      btnSubmitLogin.disabled = false;
      btnSubmitLogin.textContent = 'Connexion';
    }
  } catch (err) {
    loginAlert.textContent = 'Erreur de communication avec le serveur.';
    loginAlert.className = 'px-4 py-3 rounded-xl text-xs font-medium bg-red-500/20 border border-red-500/50 text-red-300';
    loginAlert.classList.remove('hidden');
    btnSubmitLogin.disabled = false;
    btnSubmitLogin.textContent = 'Connexion';
  }
});

// 5. AJAX SUBMISSION FOR REGISTER
const ajaxRegisterForm = document.getElementById('ajax-register-form');
const registerAlert = document.getElementById('register-alert');
const btnSubmitRegister = document.getElementById('btn-submit-register');

ajaxRegisterForm.addEventListener('submit', async (e) => {
  e.preventDefault();
  registerAlert.classList.add('hidden');
  btnSubmitRegister.disabled = true;
  btnSubmitRegister.textContent = 'Création en cours...';

  const formData = new FormData(ajaxRegisterForm);

  try {
    const response = await fetch('api/register.php', {
      method: 'POST',
      body: formData
    });
    const result = await response.json();

    if (result.success) {
      window.location.href = result.redirect;
    } else {
      registerAlert.textContent = result.message;
      registerAlert.className = 'px-4 py-3 rounded-xl text-xs font-medium bg-red-500/20 border border-red-500/50 text-red-300';
      registerAlert.classList.remove('hidden');
      btnSubmitRegister.disabled = false;
      btnSubmitRegister.textContent = 'Créer mon compte';
    }
  } catch (err) {
    registerAlert.textContent = 'Erreur de communication avec le serveur.';
    registerAlert.className = 'px-4 py-3 rounded-xl text-xs font-medium bg-red-500/20 border border-red-500/50 text-red-300';
    registerAlert.classList.remove('hidden');
    btnSubmitRegister.disabled = false;
    btnSubmitRegister.textContent = 'Créer mon compte';
  }
});

});

// Toggle password view
function togglePasswordVisibility(inputId, btn) {
  const input = document.getElementById(inputId);
  if (!input) return;
  
  if (input.type === 'password') {
    input.type = 'text';
    btn.classList.add('text-gold-400');
  } else {
    input.type = 'password';
    btn.classList.remove('text-gold-400');
  }
}
</script>

</body>
</html>