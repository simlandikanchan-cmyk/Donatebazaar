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
        #message {
            margin-top: 10px;
            font-size: 14px;
        }
        .error { color: #c0392b; }
        .success { color: #27ae60; }
    </style>
</head>
<body>

<h2>Verify OTP</h2>

<p>Phone: {{ $phone ?? session('otp_phone') }}</p>

<input type="text" id="otp" placeholder="Enter OTP" maxlength="6" inputmode="numeric">
<br>

<button id="verifyBtn" onclick="verifyOTP()">Verify OTP</button>

<br><br>

<button id="resendBtn" onclick="resendOTP()" disabled>
    Resend OTP (30s)
</button>

<div id="message"></div>

<script>
    const phone = "{{ $phone ?? session('otp_phone') }}";
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    function showMessage(text, isError) {
        const el = document.getElementById('message');
        el.textContent = text;
        el.className = isError ? 'error' : 'success';
    }

    // 🔹 Verify OTP — calls your Laravel /verify-otp route
    function verifyOTP() {
        const otp = document.getElementById('otp').value.trim();

        if (!otp || otp.length !== 6) {
            showMessage('Please enter the 6-digit OTP.', true);
            return;
        }

        const btn = document.getElementById('verifyBtn');
        btn.disabled = true;
        btn.innerText = 'Verifying...';

        fetch('{{ route("otp.verify.post") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({ phone: phone, otp: otp })
        })
        .then(async (res) => {
            if (res.redirected) {
                // Controller issued redirect()->intended(...) on success
                window.location.href = res.url;
                return;
            }
            if (!res.ok) {
                const data = await res.json();
                const msg = data.errors?.otp?.[0] || 'Invalid OTP. Please try again.';
                showMessage(msg, true);
                btn.disabled = false;
                btn.innerText = 'Verify OTP';
                return;
            }
            window.location.href = '/user/dashboard';
        })
        .catch(() => {
            showMessage('Something went wrong. Please try again.', true);
            btn.disabled = false;
            btn.innerText = 'Verify OTP';
        });
    }

    // 🔹 Resend OTP — calls your Laravel /resend-otp route
    function resendOTP() {
        fetch('{{ route("otp.resend") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({ phone: phone })
        })
        .then(res => res.json().then(data => ({ status: res.status, data })))
        .then(({ status, data }) => {
            if (status === 429) {
                showMessage('Too many requests. Please wait a moment.', true);
                return;
            }
            showMessage(data.status || 'OTP sent again.', false);
            startTimer();
        })
        .catch(() => showMessage('Could not resend OTP. Try again.', true));
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

    // Start timer on page load (OTP was already sent by sendOtp() before redirect here)
    startTimer();
</script>

</body>
</html>