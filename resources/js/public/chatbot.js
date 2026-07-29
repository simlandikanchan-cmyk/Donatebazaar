document.addEventListener('DOMContentLoaded', function () {
    // ── DOM refs ──
    var chatToggle = document.getElementById('chatToggle');
    var chatWindow = document.getElementById('chatWindow');
    var chatClose = document.getElementById('chatClose');
    var chatClear = document.getElementById('chatClear');
    var sendBtn = document.getElementById('sendMessage');
    var chatInput = document.getElementById('chatInput');
    var chatMessages = document.getElementById('chatMessages');
    var chatBadge = document.getElementById('chatBadge');
    var scrollBottom = document.getElementById('scrollBottom');
    var suggestionsContainer = document.getElementById('chatSuggestions');
    var metaTag = document.querySelector('meta[name="csrf-token"]');
    var csrfToken = metaTag ? metaTag.getAttribute('content') : '';

    if (!chatToggle || !chatWindow) return;

    // ── State ──
    var isSending = false;
    var isOpen = false;
    var unreadCount = 0;
    var lastUserMessage = '';
    var touchStartY = 0;

    var questions = [
        'How do I create a campaign?',
        'What payment methods are accepted?',
        'How do donations work?',
        'What is the refund policy?'
    ];

    // ── Init ──
    injectSuggestions();
    restoreOrGreet();

    // ──────────────────────────────────────────
    //  SUGGESTIONS
    // ──────────────────────────────────────────
    function injectSuggestions() {
        if (!suggestionsContainer) return;
        suggestionsContainer.innerHTML = '';
        questions.forEach(function (q) {
            var chip = document.createElement('button');
            chip.className = 'chat-suggestion-chip';
            chip.textContent = q;
            chip.addEventListener('click', function () {
                chatInput.value = q;
                autoResize();
                sendMessage();
            });
            suggestionsContainer.appendChild(chip);
        });
    }

    // ──────────────────────────────────────────
    //  TOGGLE
    // ──────────────────────────────────────────
    chatToggle.addEventListener('click', function (e) {
        e.stopPropagation();
        toggleChat();
    });

    function toggleChat() {
        isOpen = !isOpen;
        chatWindow.classList.toggle('open', isOpen);
        chatWindow.setAttribute('aria-hidden', !isOpen);
        if (isOpen) {
            chatBadge.classList.remove('show');
            unreadCount = 0;
            setTimeout(function () { chatInput.focus(); }, 300);
            scrollToBottom();
        }
    }

    if (chatClose) {
        chatClose.addEventListener('click', closeChat);
    }

    function closeChat() {
        isOpen = false;
        chatWindow.classList.remove('open');
        chatWindow.setAttribute('aria-hidden', 'true');
    }

    // ──────────────────────────────────────────
    //  CLEAR CHAT
    // ──────────────────────────────────────────
    if (chatClear) {
        chatClear.addEventListener('click', clearChat);
    }

    function clearChat() {
        chatMessages.innerHTML = '';
        try { localStorage.removeItem('chatbot_messages'); } catch (e) {}
        injectSuggestions();
        addGreeting();
    }

    // ──────────────────────────────────────────
    //  KEYBOARD
    // ──────────────────────────────────────────
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && isOpen) closeChat();
    });

    chatInput.addEventListener('keydown', function (e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            sendMessage();
        }
    });

    chatInput.addEventListener('input', autoResize);

    // ──────────────────────────────────────────
    //  CLICK OUTSIDE
    // ──────────────────────────────────────────
    document.addEventListener('click', function (e) {
        if (isOpen &&
            !chatWindow.contains(e.target) &&
            !chatToggle.parentElement.contains(e.target)) {
            closeChat();
        }
    });

    // ──────────────────────────────────────────
    //  SWIPE DOWN TO CLOSE (mobile)
    // ──────────────────────────────────────────
    chatWindow.addEventListener('touchstart', function (e) {
        touchStartY = e.touches[0].clientY;
    }, { passive: true });

    chatWindow.addEventListener('touchend', function (e) {
        if (!isOpen) return;
        if (e.changedTouches[0].clientY - touchStartY > 80) closeChat();
    }, { passive: true });

    // ──────────────────────────────────────────
    //  FOCUS TRAP
    // ──────────────────────────────────────────
    document.addEventListener('keydown', function (e) {
        if (!isOpen || e.key !== 'Tab') return;
        var focusable = chatWindow.querySelectorAll(
            'button, textarea, [href], input, [tabindex]:not([tabindex="-1"])'
        );
        if (focusable.length < 2) return;
        var first = focusable[0];
        var last = focusable[focusable.length - 1];
        if (e.shiftKey && document.activeElement === first) {
            e.preventDefault();
            last.focus();
        } else if (!e.shiftKey && document.activeElement === last) {
            e.preventDefault();
            first.focus();
        }
    });

    // ──────────────────────────────────────────
    //  SEND BUTTON & SCROLL
    // ──────────────────────────────────────────
    sendBtn.addEventListener('click', function () { sendMessage(); });

    chatMessages.addEventListener('scroll', function () {
        var threshold = 100;
        var atBottom = chatMessages.scrollHeight - chatMessages.scrollTop - chatMessages.clientHeight < threshold;
        scrollBottom.classList.toggle('show', !atBottom);
    });

    scrollBottom.addEventListener('click', scrollToBottom);

    function scrollToBottom() {
        chatMessages.scrollTop = chatMessages.scrollHeight;
        scrollBottom.classList.remove('show');
    }

    // ──────────────────────────────────────────
    //  HELPERS
    // ──────────────────────────────────────────
    function autoResize() {
        chatInput.style.height = 'auto';
        chatInput.style.height = Math.min(chatInput.scrollHeight, 100) + 'px';
    }

    function getTimestamp() {
        return new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
    }

    function escapeHtml(text) {
        var div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    function renderMarkdown(text) {
        var html = escapeHtml(text);
        html = html.replace(/```([\s\S]*?)```/g, '<pre><code>$1</code></pre>');
        html = html.replace(/`([^`]+)`/g, '<code>$1</code>');
        html = html.replace(/\*\*([^*]+)\*\*/g, '<strong>$1</strong>');
        html = html.replace(/\*([^*]+)\*/g, '<em>$1</em>');
        html = html.replace(/\[([^\]]+)\]\(([^)]+)\)/g, '<a href="$2" target="_blank" rel="noopener">$1</a>');
        html = html.replace(/\n/g, '<br>');
        return html;
    }

    function getBubbleText(bubbleEl) {
        var clone = bubbleEl.cloneNode(true);
        var remove = clone.querySelectorAll('.timestamp, .copy-btn, .chat-retry-btn');
        for (var i = 0; i < remove.length; i++) { remove[i].remove(); }
        return (clone.textContent || '').trim();
    }

    // ──────────────────────────────────────────
    //  PERSISTENCE
    // ──────────────────────────────────────────
    function saveMessages() {
        var items = [];
        var els = chatMessages.querySelectorAll('.chat-msg-user, .chat-msg-bot');
        els.forEach(function (el) {
            var type = el.classList.contains('chat-msg-user') ? 'user' : 'bot';
            var bubble = el.querySelector('.bubble');
            if (!bubble) return;
            var text = getBubbleText(bubble);
            var tsEl = bubble.querySelector('.timestamp');
            var time = tsEl ? tsEl.textContent : '';
            items.push({ type: type, text: text, time: time });
        });
        try { localStorage.setItem('chatbot_messages', JSON.stringify(items)); } catch (e) {}
    }

    function restoreOrGreet() {
        try {
            var saved = localStorage.getItem('chatbot_messages');
            if (saved) {
                var messages = JSON.parse(saved);
                if (messages.length) {
                    messages.forEach(function (msg) {
                        if (msg.type === 'user') {
                            var div = document.createElement('div');
                            div.className = 'chat-msg-user';
                            div.innerHTML = '<div class="bubble">' +
                                escapeHtml(msg.text) +
                                (msg.time ? '<div class="timestamp">' + msg.time + '</div>' : '') +
                                '</div>';
                            chatMessages.appendChild(div);
                        } else {
                            var div = document.createElement('div');
                            div.className = 'chat-msg-bot';
                            div.innerHTML =
                                '<div class="avatar"><i class="fa-solid fa-robot"></i></div>' +
                                '<div class="bubble">' +
                                renderMarkdown(msg.text) +
                                (msg.time ? '<div class="timestamp">' + msg.time + '</div>' : '') +
                                '</div>';
                            chatMessages.appendChild(div);
                            addCopyButton(div.querySelector('.bubble'));
                        }
                    });
                    return;
                }
            }
        } catch (e) {}
        addGreeting();
    }

    // ──────────────────────────────────────────
    //  GREETING
    // ──────────────────────────────────────────
    function addGreeting() {
        var div = document.createElement('div');
        div.className = 'chat-msg-bot';
        div.innerHTML =
            '<div class="avatar"><i class="fa-solid fa-robot"></i></div>' +
            '<div class="bubble">Hi! I\'m the DonateBazaar AI assistant. How can I help you today?' +
            '<div class="timestamp">' + getTimestamp() + '</div></div>';
        chatMessages.appendChild(div);
        scrollToBottom();
        addCopyButton(div.querySelector('.bubble'));
    }

    // ──────────────────────────────────────────
    //  COPY BUTTON
    // ──────────────────────────────────────────
    function addCopyButton(bubbleEl) {
        if (!bubbleEl || bubbleEl.querySelector('.copy-btn')) return;
        var btn = document.createElement('button');
        btn.className = 'copy-btn';
        btn.innerHTML = '<i class="fa-solid fa-copy"></i>';
        btn.setAttribute('aria-label', 'Copy message');
        btn.addEventListener('click', function (e) {
            e.stopPropagation();
            var text = getBubbleText(bubbleEl);
            navigator.clipboard.writeText(text).then(function () {
                btn.innerHTML = '<i class="fa-solid fa-check" style="color:#22c55e"></i>';
                setTimeout(function () {
                    btn.innerHTML = '<i class="fa-solid fa-copy"></i>';
                }, 1500);
            });
        });
        bubbleEl.appendChild(btn);
    }

    // ──────────────────────────────────────────
    //  TYPING INDICATOR
    // ──────────────────────────────────────────
    function showTyping() {
        var el = document.createElement('div');
        el.className = 'chat-typing';
        el.id = 'chatTyping';
        el.innerHTML =
            '<div class="avatar"><i class="fa-solid fa-robot"></i></div>' +
            '<div class="dots"><span></span><span></span><span></span></div>';
        chatMessages.appendChild(el);
        scrollToBottom();
    }

    function hideTyping() {
        var el = document.getElementById('chatTyping');
        if (el) el.remove();
    }

    // ──────────────────────────────────────────
    //  SEND MESSAGE
    // ──────────────────────────────────────────
    function addUserMessage(text) {
        var div = document.createElement('div');
        div.className = 'chat-msg-user';
        div.innerHTML = '<div class="bubble">' +
            escapeHtml(text) +
            '<div class="timestamp">' + getTimestamp() + '</div></div>';
        chatMessages.appendChild(div);
        scrollToBottom();
    }

    function addErrorMessage() {
        var div = document.createElement('div');
        div.className = 'chat-msg-bot';
        div.innerHTML =
            '<div class="avatar"><i class="fa-solid fa-robot"></i></div>' +
            '<div class="bubble bg-red-50 text-red-600">' +
            'Something went wrong. Please try again.' +
            '<button class="chat-retry-btn"><i class="fa-solid fa-rotate-right"></i> Retry</button>' +
            '<div class="timestamp">' + getTimestamp() + '</div>' +
            '</div>';
        chatMessages.appendChild(div);
        scrollToBottom();

        div.querySelector('.chat-retry-btn').addEventListener('click', function () {
            div.remove();
            if (lastUserMessage) sendMessage(lastUserMessage);
        });
    }

    async function sendMessage(message) {
        if (!message) {
            message = chatInput.value.trim();
            if (!message || isSending) return;
        } else {
            if (isSending) return;
        }

        lastUserMessage = message;
        chatInput.value = '';
        chatInput.style.height = 'auto';

        isSending = true;
        sendBtn.disabled = true;
        chatInput.disabled = true;

        addUserMessage(message);
        showTyping();

        try {
            var response = await fetch('/chatbot', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'text/event-stream'
                },
                body: JSON.stringify({ message: message })
            });

            if (!response.ok) throw new Error('HTTP ' + response.status);

            hideTyping();

            var botDiv = document.createElement('div');
            botDiv.className = 'chat-msg-bot';
            botDiv.innerHTML =
                '<div class="avatar"><i class="fa-solid fa-robot"></i></div>' +
                '<div class="bubble"></div>';
            chatMessages.appendChild(botDiv);
            var botBubble = botDiv.querySelector('.bubble');
            scrollToBottom();

            var reader = response.body.getReader();
            var decoder = new TextDecoder();
            var buffer = '';
            var fullText = '';

            while (true) {
                var result = await reader.read();
                if (result.done) break;

                buffer += decoder.decode(result.value, { stream: true });
                var lines = buffer.split('\n');
                buffer = lines.pop() || '';

                for (var i = 0; i < lines.length; i++) {
                    var line = lines[i];
                    if (!line.startsWith('data: ')) continue;
                    var payload = line.slice(6);
                    if (payload === '[DONE]') continue;
                    try {
                        var parsed = JSON.parse(payload);
                        if (parsed.text) {
                            fullText += parsed.text;
                            botBubble.textContent = fullText;
                            scrollToBottom();
                        }
                    } catch (e) {}
                }
            }

            var ts = getTimestamp();
            botBubble.innerHTML = renderMarkdown(fullText) +
                '<div class="timestamp">' + ts + '</div>';
            addCopyButton(botBubble);
            scrollToBottom();
            saveMessages();

            if (!isOpen) {
                unreadCount++;
                chatBadge.textContent = unreadCount;
                chatBadge.classList.add('show');
            }

        } catch (error) {
            hideTyping();
            addErrorMessage();
        } finally {
            isSending = false;
            sendBtn.disabled = false;
            chatInput.disabled = false;
            chatInput.focus();
        }
    }
});
