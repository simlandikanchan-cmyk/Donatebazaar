<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'DonateBazaar')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Tailwind + JS --}}
    @vite(['resources/css/public/app.css', 'resources/css/public/footer.css', 'resources/js/public/app.js', 'resources/css/public/chatbot.css', 'resources/js/public/chatbot.js', 'resources/css/public/navbar.css', 'resources/js/public/navbar.js'])

    {{-- Preconnects --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://cdnjs.cloudflare.com">
    <link rel="preconnect" href="https://cdn.jsdelivr.net">

    {{-- Google Fonts (Inter + DM Sans/Mono fallbacks) --}}
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    {{-- Font Awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>


    @stack('styles')

</head>

<body class="flex flex-col min-h-screen" style="background:#f4f5fb">

    @if(!request()->routeIs('events.edit') && !request()->routeIs('events.show'))
    {{-- Navigation --}}
    @include('layouts.navigation')
    @endif

    {{-- Main Content --}}
    <main class="flex-grow">
        @yield('content')
    </main>

    @if(!request()->routeIs('events.edit') && !request()->routeIs('events.show'))
    {{-- Footer --}}
    @include('partials.footer')


    <!-- =========================
         CHATBOT UI
    ========================== -->

    <!-- Floating Chat Button -->
    @endif
    <div class="fixed bottom-5 sm:bottom-6 right-4 sm:right-6 z-50 chat-float-wrap">
        <div class="relative">
            <button
                id="chatToggle"
                type="button"
                class="w-14 h-14 rounded-full bg-gradient-to-r from-blue-600 to-teal-600 text-white shadow-lg hover:scale-110 transition duration-300 flex items-center justify-center text-xl sm:text-2xl chat-toggle-pulse"
                aria-label="Open chat"
                aria-expanded="false"
                aria-controls="chatWindow">
                <i class="fa-solid fa-comment-dots"></i>
            </button>
            <span id="chatBadge" class="chat-badge" aria-hidden="true">0</span>
        </div>
    </div>

    <!-- Chat Window -->
    <div
        id="chatWindow"
        role="dialog"
        aria-modal="true"
        aria-label="DonateBazaar AI Chat"
        aria-hidden="true"
        class="fixed bottom-20 sm:bottom-24 right-4 sm:right-6 w-96 max-w-[calc(100vw-2rem)] bg-white rounded-2xl shadow-2xl overflow-hidden z-50">

        <!-- Header -->
        <div class="bg-gradient-to-r from-blue-600 to-teal-600 text-white px-4 py-3.5 flex items-center gap-3">
            <div class="w-10 h-10 rounded-full bg-white/15 flex items-center justify-center flex-shrink-0 border border-white/20">
                <i class="fa-solid fa-robot text-lg"></i>
            </div>
            <div class="flex-1 min-w-0">
                <h2 class="font-bold text-sm leading-tight tracking-tight">DonateBazaar AI</h2>
                <p class="text-[11px] opacity-90 flex items-center gap-1.5 mt-0.5">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-300 inline-block flex-shrink-0" aria-hidden="true"></span>
                    <span>Online</span>
                </p>
            </div>
            <button id="chatClear" type="button" class="text-white/60 hover:text-white transition p-2 rounded-lg hover:bg-white/10 min-w-[36px] min-h-[36px] flex items-center justify-center" aria-label="Clear chat history">
                <i class="fa-solid fa-eraser text-sm"></i>
            </button>
            <button id="chatClose" type="button" class="text-white/80 hover:text-white transition p-2 rounded-lg hover:bg-white/10 min-w-[36px] min-h-[36px] flex items-center justify-center" aria-label="Close chat">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        <!-- Messages -->
        <div id="chatMessages" class="h-80 overflow-y-auto p-4 bg-gray-50/80" role="log" aria-live="polite" aria-label="Chat messages"></div>

        <!-- Scroll to bottom -->
        <button id="scrollBottom" type="button" class="chat-scroll-bottom" aria-label="Scroll to latest messages">
            <i class="fa-solid fa-chevron-down"></i>
        </button>

        <!-- Suggestions -->
        <div id="chatSuggestions" class="px-4 pb-2.5 pt-2 bg-gray-50/80 border-t border-gray-100 flex flex-wrap gap-2"></div>

        <!-- Input -->
        <div class="border-t border-gray-200 p-3 flex items-end gap-2 bg-white">
            <label for="chatInput" class="sr-only">Type a message</label>
            <textarea
                id="chatInput"
                rows="1"
                placeholder="Type a message..."
                class="flex-1 border border-gray-300 rounded-xl px-3.5 py-2.5 text-sm focus:outline-none focus:border-indigo-400 transition"
                style="scrollbar-width: thin; min-height: 44px;"
                aria-label="Message input"></textarea>
            <button
                id="sendMessage"
                type="button"
                class="w-11 h-11 rounded-xl bg-gradient-to-r from-blue-600 to-teal-600 text-white flex items-center justify-center hover:scale-105 transition disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:scale-100 flex-shrink-0 shadow-sm"
                aria-label="Send message">
                <i class="fa-solid fa-paper-plane text-sm"></i>
            </button>
        </div>
    </div>


    <!-- =========================
         SCRIPTS
    ========================== -->

    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
        document.documentElement.classList.add('js-enabled');
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (window.lucide) lucide.createIcons();
        });
    </script>

   @stack('scripts')
   
   </body>
   </html>