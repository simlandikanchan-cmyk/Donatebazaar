<!DOCTYPE html>
<html>
<head>
    <title>Login with OTP</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style>
        body {
            font-family: Arial;
            text-align: center;
            margin-top: 100px;
        }
        input {
            padding: 10px;
            width: 250px;
            margin-bottom: 10px;
        }
        button {
            padding: 10px 20px;
            cursor: pointer;
        }
        #recaptcha-container {
            margin-top: 20px;
        }
    </style>
</head>
<body>

<h2>Login with Phone OTP</h2>

<input type="text" id="phone" placeholder="Enter phone number (9876543210)">
<br>

<button onclick="sendOTP()">Send OTP</button>

<div id="recaptcha-container"></div>

<!--  Firebase Compat SDK (IMPORTANT) -->
<script src="https://www.gstatic.com/firebasejs/10.12.2/firebase-app-compat.js"></script>
<script src="https://www.gstatic.com/firebasejs/10.12.2/firebase-auth-compat.js"></script>

<script>
    //  Your Firebase Config
    const firebaseConfig = {
        apiKey: "AIzaSyAsmzZzntxdbggKog_LAAzKRuOz_rkKEAE",
        authDomain: "web-app-4b1c2.firebaseapp.com",
        projectId: "web-app-4b1c2",
        storageBucket: "web-app-4b1c2.firebasestorage.app",
        messagingSenderId: "382560900766",
        appId: "1:382560900766:web:7af4ea0bd148d92226cd8f"
    };

    // Initialize Firebase
    firebase.initializeApp(firebaseConfig);
    const auth = firebase.auth();

    let confirmationResult;

    // Setup reCAPTCHA
    window.onload = function () {
        window.recaptchaVerifier = new firebase.auth.RecaptchaVerifier(
            'recaptcha-container',
            {
                size: 'normal'
            }
        );
    };

    // Send OTP
    function sendOTP() {

        let phone = document.getElementById('phone').value.trim();

        // remove spaces
        phone = phone.replace(/\s+/g, '');

        // add +91 if not present
        if (!phone.startsWith('+91')) {
            phone = '+91' + phone;
        }

        auth.signInWithPhoneNumber(phone, window.recaptchaVerifier)
        .then(function (result) {

            confirmationResult = result;

            alert("OTP sent successfully!");

            // save phone in session (Laravel)
            fetch('/store-phone', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    phone: phone.replace('+91', '')
                })
            });

            // redirect to verify page
            window.location.href = '/verify-otp';

        })
        .catch(function (error) {
            console.log(error);
            alert(error.message);
        });
    }
</script>

</body>
</html>