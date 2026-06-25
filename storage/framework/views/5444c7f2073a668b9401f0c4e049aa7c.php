<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo $__env->yieldContent('title', 'FundRaise'); ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>

     <link rel="preconnect" href="https://fonts.googleapis.com"> <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600&family=DM+Mono:wght@400;500;600;700&display=swap" rel="stylesheet">

    
    <!-- <script src="https://js.stripe.com/v3/"></script> -->
    <!-- <script src="https://checkout.razorpay.com/v1/checkout.js"></script> -->

    
    <link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">

    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>

    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>


    <?php echo $__env->yieldPushContent('styles'); ?>

</head>

<body class="flex flex-col min-h-screen bg-gray-100">

    
    <?php echo $__env->make('layouts.navigation', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    
    <main class="flex-grow">
        <?php echo $__env->yieldContent('content'); ?>
    </main>

    
    <?php echo $__env->make('partials.footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>


    <!-- =========================
         CHATBOT UI
    ========================== -->

    <!-- Floating Chat Button -->
    <div class="fixed bottom-6 right-6 z-50">
        <button
            id="chatToggle"
            class="w-16 h-16 rounded-full bg-gradient-to-r from-indigo-500 to-purple-500 text-white shadow-lg hover:scale-110 transition duration-300 flex items-center justify-center text-2xl">
            💬
        </button>
    </div>

    <!-- Chat Window -->
    <div
        id="chatWindow"
        class="hidden fixed bottom-24 right-6 w-96 bg-white rounded-2xl shadow-2xl overflow-hidden z-50">

        <!-- Header -->
        <div class="bg-gradient-to-r from-indigo-500 to-purple-500 text-white p-4">
            <h2 class="font-bold text-lg">DonateBazaar AI Assistant ❤️</h2>
            <p class="text-sm opacity-80">
                Ask about donations, campaigns, fundraising
            </p>
        </div>

        <!-- Messages -->
        <div
            id="chatMessages"
            class="h-80 overflow-y-auto p-4 space-y-3 bg-gray-50">

            <div class="bg-gray-200 text-gray-800 p-3 rounded-lg w-fit max-w-xs">
                Hi 👋 How can I help you today?
            </div>
        </div>

        <!-- Input -->
        <div class="border-t p-3 flex">
            <input
                type="text"
                id="chatInput"
                placeholder="Type your message..."
                class="flex-1 border rounded-lg px-3 py-2 focus:outline-none">

            <button
                id="sendMessage"
                class="ml-2 bg-indigo-500 text-white px-4 rounded-lg hover:bg-indigo-600">
                Send
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
        // AOS Init
        AOS.init({
            once: true,
            duration: 1000
        });

        document.addEventListener("DOMContentLoaded", function () {

            const chatToggle = document.getElementById("chatToggle");
            const chatWindow = document.getElementById("chatWindow");
            const sendBtn = document.getElementById("sendMessage");
            const chatInput = document.getElementById("chatInput");
            const chatMessages = document.getElementById("chatMessages");

            // Open/Close chatbot
            chatToggle.addEventListener("click", () => {
                chatWindow.classList.toggle("hidden");
            });

            // Send button click
            sendBtn.addEventListener("click", sendMessage);

            // Enter key support
            chatInput.addEventListener("keypress", function(e){
                if(e.key === "Enter"){
                    sendMessage();
                }
            });

            async function sendMessage() {
                let message = chatInput.value.trim();

                if(message === "") return;

                // User message
                chatMessages.innerHTML += `
                    <div class="flex justify-end">
                        <div class="bg-indigo-500 text-white p-3 rounded-lg max-w-xs">
                            ${message}
                        </div>
                    </div>
                `;

                chatInput.value = "";

                try {
            let response = await fetch('/chatbot', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
        'Accept': 'application/json'
    },
    body: JSON.stringify({
        message: message
    })
});

console.log(response.status);

let data = await response.json();
console.log(data);

                    // Bot response
                    chatMessages.innerHTML += `
                        <div class="flex justify-start">
                            <div class="bg-gray-200 text-gray-800 p-3 rounded-lg max-w-xs">
                                ${data.reply}
                            </div>
                        </div>
                    `;

                } catch(error) {
                    // console.log(error);
                    console.log("Error:", error);
                    console.log(await response.text());

                    chatMessages.innerHTML += `
                        <div class="flex justify-start">
                            <div class="bg-red-100 text-red-600 p-3 rounded-lg max-w-xs">
                                Something went wrong ❌
                            </div>
                        </div>
                    `;
                }

                chatMessages.scrollTop = chatMessages.scrollHeight;
            }

        });
    </script>

   <?php echo $__env->yieldPushContent('scripts'); ?>
   
   </body>
   </html><?php /**PATH C:\xampp\htdocs\fundraise\resources\views/layouts/app.blade.php ENDPATH**/ ?>