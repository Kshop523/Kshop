document.addEventListener('DOMContentLoaded', () => {
    const chatToggleBtn = document.getElementById('chat-toggle-btn');
    const chatWidget = document.getElementById('chat-widget');
    const chatCloseBtn = document.getElementById('chat-close-btn');
    const chatOnlineBadge = document.getElementById('chat-online-badge');
    const chatOnlineCount = document.getElementById('chat-online-count');
    const chatForm = document.getElementById('chat-form');
    const chatInput = document.getElementById('chat-input');
    const chatMessages = document.getElementById('chat-messages');
    const mediaInput = document.getElementById('media-input');
    const uploadBtn = document.getElementById('upload-btn');
    const mediaPreviewContainer = document.getElementById('media-preview-container');
    const mediaPreview = document.getElementById('media-preview');
    const cancelMediaBtn = document.getElementById('cancel-media-btn');

    let selectedFile = null;
    let lastId = 0;
    let pollingInterval = null;
    let heartbeatInterval = null;
    let isChatOpen = false;

    if (!chatWidget) return;

    // Toggle Chat
    chatToggleBtn.addEventListener('click', () => {
        chatWidget.classList.toggle('hidden');
        isChatOpen = !chatWidget.classList.contains('hidden');
        if (isChatOpen) {
            chatMessages.scrollTop = chatMessages.scrollHeight;
            fetchMessages();
            startPolling();
        } else {
            stopPolling();
        }
    });

    chatCloseBtn.addEventListener('click', () => {
        chatWidget.classList.add('hidden');
        isChatOpen = false;
        stopPolling();
    });

    // Heartbeat & Compteur réel
    async function updateHeartbeat() {
        try {
            const res = await fetch('api/chat/heartbeat.php');
            const data = await res.json();
            if (data.success) {
                if (chatOnlineBadge) chatOnlineBadge.textContent = `${data.online_count} en ligne`;
                if (chatOnlineCount) chatOnlineCount.textContent = `${data.online_count} personnes en ligne`;
            }
        } catch (e) {
            console.error('Erreur heartbeat', e);
        }
    }

    updateHeartbeat();
    heartbeatInterval = setInterval(updateHeartbeat, 15000);

    // Polling des messages
    function startPolling() {
        if (pollingInterval) clearInterval(pollingInterval);
        pollingInterval = setInterval(fetchMessages, 2500);
    }

    function stopPolling() {
        if (pollingInterval) {
            clearInterval(pollingInterval);
            pollingInterval = null;
        }
    }

    async function fetchMessages() {
        try {
            const res = await fetch(`api/chat/fetch.php?last_id=${lastId}`);
            const data = await res.json();
            if (data.success && data.messages.length > 0) {
                data.messages.forEach(msg => {
                    lastId = Math.max(lastId, parseInt(msg.id));
                    appendMessage(msg);
                });
                chatMessages.scrollTop = chatMessages.scrollHeight;
            }
        } catch (e) {
            console.error('Erreur fetch messages', e);
        }
    }

    // Affichage sécurisé d'un message (protection XSS via textContent)
    function appendMessage(msg) {
        const wrapper = document.createElement('div');
        wrapper.className = 'flex flex-col items-start gap-1 mb-3';

        const senderSpan = document.createElement('span');
        senderSpan.className = 'text-[10px] text-gold-400 font-semibold px-1';
        senderSpan.textContent = msg.username + (msg.sender_type === 'admin' ? ' (Support)' : '');

        const bubble = document.createElement('div');
        bubble.className = msg.sender_type === 'admin' 
            ? 'bg-yellow-500/20 border border-yellow-500/50 text-yellow-200 rounded-2xl rounded-tl-none p-3 max-w-[85%]'
            : 'bg-gray-800/80 border border-gray-700/60 rounded-2xl rounded-tl-none p-3 text-gray-200 max-w-[85%];';

        if (msg.media_path) {
            if (msg.media_type === 'image') {
                const img = document.createElement('img');
                img.src = msg.media_path;
                img.className = 'rounded-lg max-h-40 w-full object-cover mb-2 border border-gold-500/30';
                bubble.appendChild(img);
            } else if (msg.media_type === 'video') {
                const video = document.createElement('video');
                video.src = msg.media_path;
                video.controls = true;
                video.className = 'rounded-lg max-h-40 w-full mb-2 border border-gold-500/30';
                bubble.appendChild(video);
            }
        }

        if (msg.message_text) {
            const textSpan = document.createElement('span');
            textSpan.textContent = msg.message_text;
            bubble.appendChild(textSpan);
        }

        wrapper.appendChild(senderSpan);
        wrapper.appendChild(bubble);
        chatMessages.appendChild(wrapper);
    }

    // Aperçu média
    uploadBtn.addEventListener('click', () => mediaInput.click());

    mediaInput.addEventListener('change', (e) => {
        const file = e.target.files[0];
        if (file) {
            selectedFile = file;
            mediaPreview.innerHTML = '';
            const url = URL.createObjectURL(file);

            if (file.type.startsWith('image/')) {
                const img = document.createElement('img');
                img.src = url;
                img.className = 'w-12 h-12 object-cover rounded-lg border border-gold-500/50';
                mediaPreview.appendChild(img);
            } else if (file.type.startsWith('video/')) {
                const video = document.createElement('video');
                video.src = url;
                video.className = 'w-12 h-12 object-cover rounded-lg border border-gold-500/50';
                mediaPreview.appendChild(video);
            }

            const info = document.createElement('span');
            info.className = 'text-[11px] text-gray-300 truncate max-w-[150px]';
            info.textContent = file.name;
            mediaPreview.appendChild(info);

            mediaPreviewContainer.classList.remove('hidden');
        }
    });

    cancelMediaBtn.addEventListener('click', () => {
        selectedFile = null;
        mediaInput.value = '';
        mediaPreviewContainer.classList.add('hidden');
    });

    // Envoi du message
    chatForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        const text = chatInput.value.trim();
        if (!text && !selectedFile) return;

        const formData = new FormData();
        formData.append('message', text);
        if (selectedFile) {
            formData.append('media', selectedFile);
        }

        try {
            const res = await fetch('api/chat/send.php', {
                method: 'POST',
                body: formData
            });
            const data = await res.json();

            if (data.success) {
                chatInput.value = '';
                selectedFile = null;
                mediaInput.value = '';
                mediaPreviewContainer.classList.add('hidden');
                fetchMessages();
            } else {
                alert(data.message || "Erreur lors de l'envoi.");
            }
        } catch (err) {
            console.error('Erreur envoi', err);
        }
    });
});