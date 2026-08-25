import { csrfFetch } from '../shared/api.js';

(function(){
'use strict';

const pageData = JSON.parse(document.getElementById('phoneData').textContent);
const sendUrl = pageData.sendUrl;
const verifyUrl = pageData.verifyUrl;

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

    csrfFetch(sendUrl, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json'
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
        window.location.href = data.redirect || verifyUrl;
    })
    .catch(() => {
        showMessage('Something went wrong. Please try again.', true);
        btn.disabled = false;
        btn.innerText = 'Send OTP';
    });
}

document.addEventListener('click', function(e){
    if (e.target.closest('[data-action="send-otp"]')) sendOTP();
});

})();