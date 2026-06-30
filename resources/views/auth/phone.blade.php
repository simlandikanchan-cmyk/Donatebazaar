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
        #message {
            margin-top: 12px;
            font-size: 14px;
        }
        .error { color: #c0392b; }
    </style>
</head>
<body>

<h2>Login with Phone OTP</h2>

@if (session('status'))
    <p style="color:#27ae60;">{{ session('status') }}</p>
@endif

@if (session('error'))
    <p class="error">{{ session('error') }}</p>
@endif

<input type="text" id="phone" placeholder="Enter phone number (9876543210)" maxlength="10" inputmode="numeric">
<br>

<button id="sendBtn" onclick="sendOTP()">Send OTP</button>

<div id="message"></div>

<script>
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    function showMessage(text, isError) {
        const el = document.getElementById('message');
        el.textContent = text;
        el.className = isError ? 'error' : '';
    }

    function sendOTP() {
        let phone = document.getElementById('phone').value.trim();
        phone = phone.replace(/\s+/g, '').replace(/^\+91/, '');

        if (!/^\d{10}$/.test(phone)) {
            showMessage('Please enter a valid 10-digit phone number.', true);
            return;
        }

        const btn = document.getElementById('sendBtn');
        btn.disabled = true;
        btn.innerText = 'Sending...';

        fetch('{{ route("otp.send") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({ phone: phone })
        })
        .then(async (res) => {
            if (!res.ok) {
                const data = await res.json();
                const msg = data.errors?.phone?.[0] || data.message || 'Could not send OTP. Please try again.';
                showMessage(msg, true);
                btn.disabled = false;
                btn.innerText = 'Send OTP';
                return;
            }
            const data = await res.json();
            window.location.href = data.redirect || '{{ route("otp.verify") }}';
        })
        .catch(() => {
            showMessage('Something went wrong. Please try again.', true);
            btn.disabled = false;
            btn.innerText = 'Send OTP';
        });
    }
</script>

</body>
</html>