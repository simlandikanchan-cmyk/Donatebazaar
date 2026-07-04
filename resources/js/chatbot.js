document.addEventListener('DOMContentLoaded', function () {
    var chatToggle = document.getElementById('chatToggle');
    var chatWindow = document.getElementById('chatWindow');
    var chatClose = document.getElementById('chatClose');
    var sendBtn = document.getElementById('sendMessage');
    var chatInput = document.getElementById('chatInput');
    var chatMessages = document.getElementById('chatMessages');
    var chatBadge = document.getElementById('chatBadge');
    var scrollBottom = document.getElementById('scrollBottom');
    var suggestionsContainer = document.getElementById('chatSuggestions');
    var csrfToken = document.querySelector('meta[name=\"csrf-token\"]');

    if (!chatToggle || !chatWindow) return;

    csrfToken = csrfToken ? csrfToken.getAttribute('content') : '';

    var isSending = false;
    var isOpen = false;
    var unreadCount = 0;

    var questions = [
        'How do I create a campaign?',
        'What payment methods are accepted?',
        'How do donations work?',
        'What is the refund policy?'
    ];

    // ---- Inject suggestion chips ----
    if (suggestionsContainer) {
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

    // ---- Toggle ----
    chatToggle.addEventListener('click', function (e) {
        e.stopPropagation();
        isOpen = !isOpen;
        chatWindow.classList.toggle('open', isOpen);
        if (isOpen) {
            chatBadge.classList.remove('show');
            unreadCount = 0;
            setTimeout(function () { chatInput.focus(); }, 300);
            scrollToBottom();
        }
    });

    // ---- Close button ----
    if (chatClose) {
        chatClose.addEventListener('click', function () {
            isOpen = false;
            chatWindow.classList.remove('open');
        });
    }

    // ---- Escape key ----
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && isOpen) {
            isOpen = false;
            chatWindow.classList.remove('open');
        }
    });

    // ---- Click outside ----
    document.addEventListener('click', function (e) {
        if (isOpen &&
            !chatWindow.contains(e.target) &&
            !chatToggle.parentElement.contains(e.target)) {
            isOpen = false;
            chatWindow.classList.remove('open');
        }
    });

    // ---- Auto-resize textarea ----
    function autoResize() {
        chatInput.style.height = 'auto';
        chatInput.style.height = Math.min(chatInput.scrollHeight, 100) + 'px';
    }
    chatInput.addEventListener('input', autoResize);

    // ---- Enter to send, Shift+Enter for newline ----
    chatInput.addEventListener('keydown', function (e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            sendMessage();
        }
    });

    // ---- Send button ----
    sendBtn.addEventListener('click', sendMessage);

    // ---- Scroll-to-bottom ----
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

    // ---- Helpers ----
    function escapeHtml(text) {
        var div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    function showTyping() {
        var el = document.createElement('div');
        el.className = 'chat-typing';
        el.id = 'chatTyping';
        el.innerHTML =
            '<div class=\"avatar\"><i class=\"fa-solid fa-robot\"></i></div>' +
            '<div class=\"dots\"><span></span><span></span><span></span></div>';
        chatMessages.appendChild(el);
        scrollToBottom();
    }

    function hideTyping() {
        var el = document.getElementById('chatTyping');
        if (el) el.remove();
    }

    function addBotMessage(text, isError) {
        var div = document.createElement('div');
        div.className = 'chat-msg-bot';
        var extraClass = isError ? ' bg-red-50 text-red-600' : '';
        div.innerHTML =
            '<div class=\"avatar\"><i class=\"fa-solid fa-robot\"></i></div>' +
            '<div class=\"bubble' + extraClass + '\">' + escapeHtml(text) + '</div>';
        chatMessages.appendChild(div);
        scrollToBottom();
        return div.querySelector('.bubble');
    }

    // ---- Main send ----
    async function sendMessage() {
        var message = chatInput.value.trim();
        if (!message || isSending) return;

        chatInput.style.height = 'auto';
        isSending = true;
        sendBtn.disabled = true;
        chatInput.disabled = true;

        // Append user message
        var userDiv = document.createElement('div');
        userDiv.className = 'chat-msg-user';
        userDiv.innerHTML = '<div class=\"bubble\">' + escapeHtml(message) + '</div>';
        chatMessages.appendChild(userDiv);

        chatInput.value = '';
        scrollToBottom();

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

            if (!response.ok) {
                throw new Error('HTTP ' + response.status);
            }

            hideTyping();
            var botBubble = addBotMessage('');

            var reader = response.body.getReader();
            var decoder = new TextDecoder();
            var buffer = '';

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
                            botBubble.textContent += parsed.text;
                            scrollToBottom();
                        }
                    } catch (e) { /* skip malformed */ }
                }
            }

            scrollToBottom();

            if (!isOpen) {
                unreadCount++;
                chatBadge.textContent = unreadCount;
                chatBadge.classList.add('show');
            }

        } catch (error) {
            hideTyping();
            addBotMessage('Something went wrong. Please try again.', true);
        } finally {
            isSending = false;
            sendBtn.disabled = false;
            chatInput.disabled = false;
            chatInput.focus();
        }
    }
});
