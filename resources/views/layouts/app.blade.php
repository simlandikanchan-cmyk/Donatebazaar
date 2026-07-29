<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'DonateBazaar')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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

    {{-- AOS --}}
    <link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">

    {{-- Swiper --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>

    {{-- Font Awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>


    @stack('meta')
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
    <div class="fixed bottom-4 sm:bottom-6 right-4 sm:right-6 z-50">
        <div class="relative">
            <button
                id="chatToggle"
                class="w-14 h-14 sm:w-16 sm:h-16 rounded-full bg-gradient-to-r from-blue-600 to-teal-600 text-white shadow-lg hover:scale-110 transition duration-300 flex items-center justify-center text-xl sm:text-2xl chat-toggle-pulse"
                aria-label="Open chat">
                <i class="fa-solid fa-comment-dots"></i>
            </button>
            <span id="chatBadge" class="chat-badge">0</span>
        </div>
    </div>

    <!-- Chat Window -->
    <div
        id="chatWindow"
        class="fixed bottom-20 sm:bottom-24 right-4 sm:right-6 w-96 max-w-[calc(100vw-2rem)] bg-white rounded-2xl shadow-2xl overflow-hidden z-50">

        <!-- Header -->
        <div class="bg-gradient-to-r from-blue-600 to-teal-600 text-white p-4 flex items-center gap-3">
            <div class="w-9 h-9 rounded-full bg-white/20 flex items-center justify-center flex-shrink-0">
                <i class="fa-solid fa-robot text-lg"></i>
            </div>
            <div class="flex-1 min-w-0">
                <h2 class="font-bold text-sm leading-tight">DonateBazaar AI</h2>
                <p class="text-[11px] opacity-80 flex items-center gap-1">
                    <span class="w-1.5 h-1.5 rounded-full bg-green-400 inline-block"></span>
                    Online
                </p>
            </div>
            <button id="chatClear" class="text-white/50 hover:text-white transition p-1 mr-1" aria-label="Clear chat">
                <i class="fa-solid fa-eraser text-sm"></i>
            </button>
            <button id="chatClose" class="text-white/70 hover:text-white transition p-1" aria-label="Close chat">
                <i class="fa-solid fa-xmark text-xl"></i>
            </button>
        </div>

        <!-- Messages -->
        <div id="chatMessages" class="h-80 overflow-y-auto p-4 bg-gray-50/80"></div>

        <!-- Scroll to bottom -->
        <button id="scrollBottom" class="chat-scroll-bottom" aria-label="Scroll to bottom">
            <i class="fa-solid fa-chevron-down"></i>
        </button>

        <!-- Suggestions -->
        <div id="chatSuggestions" class="px-4 pb-2 pt-0 bg-gray-50/80 border-t border-gray-100 flex flex-wrap gap-1.5"></div>

        <!-- Input -->
        <div class="border-t border-gray-200 p-3 flex items-end gap-2 bg-white">
            <textarea
                id="chatInput"
                rows="1"
                placeholder="Type a message..."
                class="flex-1 border border-gray-300 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:border-indigo-400 transition"
                style="scrollbar-width: thin"></textarea>
            <button
                id="sendMessage"
                class="w-10 h-10 rounded-xl bg-gradient-to-r from-blue-600 to-teal-600 text-white flex items-center justify-center hover:scale-105 transition disabled:opacity-50 disabled:cursor-not-allowed flex-shrink-0"
                aria-label="Send message">
                <i class="fa-solid fa-paper-plane text-sm"></i>
            </button>
        </div>
    </div>


    <!-- =========================
         SCRIPTS
    ========================== -->

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/vanilla-tilt/1.7.0/vanilla-tilt.min.js"></script>
    <script src="https://cdn.lordicon.com/lordicon.js"></script>
    <script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>

    <script>
        document.documentElement.classList.add('js-enabled');
        AOS.init({
            once: true,
            duration: 1000
        });
    </script>

    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (window.lucide) lucide.createIcons();
        });
    </script>

   @stack('scripts')
   
   </body>
   </html>