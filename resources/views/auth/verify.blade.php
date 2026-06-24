<!DOCTYPE html>
<html>
<head>
    <title>Verify OTP</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style>
        body {
            font-family: Arial;
            text-align: center;
            margin-top: 100px;
        }
        input {
            padding: 10px;
            width: 200px;
            margin-bottom: 10px;
        }
        button {
            padding: 10px 20px;
            cursor: pointer;
        }
        #recaptcha-container {
            margin: 15px 0;
        }
    </style>
</head>
<body>

<h2>Verify OTP</h2>

<p>Phone: {{ session('phone') }}</p>

<input type="text" id="otp" placeholder="Enter OTP">
<br>

<button onclick="verifyOTP()">Verify OTP</button>

<br><br>

<button id="resendBtn" onclick="resendOTP()" disabled>
    Resend OTP (30s)
</button>

<div id="recaptcha-container"></div>

<script src="https://www.gstatic.com/firebasejs/10.7.1/firebase-app-compat.js"></script>
<script src="https://www.gstatic.com/firebasejs/10.7.1/firebase-auth-compat.js"></script>

<script>
    //  Firebase Config (PUT YOURS)
// For Firebase JS SDK v7.20.0 and later, measurementId is optional
const firebaseConfig = {
  apiKey: "AIzaSyAsmzZzntxdbggKog_LAAzKRuOz_rkKEAE",
  authDomain: "web-app-4b1c2.firebaseapp.com",
  projectId: "web-app-4b1c2",
  storageBucket: "web-app-4b1c2.firebasestorage.app",
  messagingSenderId: "382560900766",
  appId: "1:382560900766:web:7af4ea0bd148d92226cd8f",
  measurementId: "G-TFKFXCYKJY"
};

    firebase.initializeApp(firebaseConfig);

    const auth = firebase.auth();

    let confirmationResult;

    // 🔹 Setup reCAPTCHA
    window.onload = function () {
        window.recaptchaVerifier = new firebase.auth.RecaptchaVerifier('recaptcha-container', {
            size: 'normal'
        });
    };

    // 🔹 Send OTP again
    function resendOTP() {

        let phone = "{{ session('phone') }}";

        // Add +91 if not present
        if (!phone.startsWith('+')) {
            phone = '+91' + phone;
        }

        auth.signInWithPhoneNumber(phone, window.recaptchaVerifier)
        .then(function (result) {
            confirmationResult = result;
            alert('OTP sent again!');
            startTimer();
        })
        .catch(function (error) {
            alert(error.message);
        });
    }

    // 🔹 Verify OTP
    function verifyOTP() {

        const code = document.getElementById('otp').value;

        if (!confirmationResult) {
            alert("Please click resend OTP first");
            return;
        }

        confirmationResult.confirm(code)
        .then(function (result) {

            let phone = result.user.phoneNumber;

            // 🔥 Send to Laravel backend
            fetch('/firebase-login', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    phone: phone.replace('+91', '') // store clean number
                })
            })
            .then(res => res.json())
            .then(data => {
                window.location.href = '/user/dashboard';
            });

        })
        .catch(function (error) {
            alert("Invalid OTP");
        });
    }

    // 🔹 Resend Timer (30 sec)
    function startTimer() {
        let time = 30;
        const btn = document.getElementById('resendBtn');

        btn.disabled = true;

        const interval = setInterval(() => {
            btn.innerText = `Resend OTP (${time}s)`;
            time--;

            if (time < 0) {
                clearInterval(interval);
                btn.disabled = false;
                btn.innerText = "Resend OTP";
            }
        }, 1000);
    }

    // Start timer on load
    startTimer();

</script>

</body>
</html>