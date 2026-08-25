/* ═══════════════════════════════════════════════════════════════════
   Gift Card Redeem page — moved from gift-cards/redeem.blade.php inline
   <script>. onclick handlers converted to data-action delegation; the
   validate-code route URL and CSRF token come from data attributes.
   ═══════════════════════════════════════════════════════════════════ */

import { csrfFetch } from '../shared/api.js';
import { escapeHtml } from '../shared/helpers.js';

(function () {
'use strict';

var validatedCode = null;
var selectedCampaignId = null;
var selectedCampaignTitle = '';
var selectedCampaignImg = '';
var giftAmount = 0;
var currentStep = 1;
var totalSteps = 4;
var stepTitles = ['Enter your gift card code', 'Choose a campaign', 'Your details', 'Review & confirm'];

var ICON_CHECK = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><circle cx="12" cy="12" r="10"/><path d="m8.5 12 2.6 2.6 4.9-5.4"/></svg>';
var ICON_ERROR = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><circle cx="12" cy="12" r="10"/><path d="m9 9 6 6M15 9l-6 6"/></svg>';
var ICON_CHECKMINI = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" aria-hidden="true"><path d="m5 12 5 5 9-10"/></svg>';

function setStatus(state, html) {
    var el = document.getElementById('codeStatus');
    el.className = 'gr-status';
    if (state) { el.classList.add(state, 'show'); }
    el.innerHTML = html;
}

function updateProgress() {
    var pct = Math.round((currentStep / totalSteps) * 100);
    document.getElementById('progressFill').style.width = pct + '%';
    document.getElementById('progressStepLabel').textContent = 'Step ' + currentStep + ' of ' + totalSteps;
    document.getElementById('progressTitleLabel').textContent = stepTitles[currentStep - 1];
    document.querySelector('.gr-progress').setAttribute('aria-valuenow', pct);

    document.querySelectorAll('.gr-step-node').forEach(function (d) {
        var n = parseInt(d.dataset.node, 10);
        var isDone = n < currentStep;
        var isActive = n === currentStep;
        d.classList.toggle('done', isDone);
        d.classList.toggle('active', isActive);
        d.querySelector('.gr-step-dot').innerHTML = isDone ? ICON_CHECKMINI : n;
        d.setAttribute('aria-current', isActive ? 'step' : 'false');
        if (isDone) {
            d.onclick = function () { gotoStep(n); };
        } else {
            d.onclick = null;
        }
    });
    document.querySelectorAll('.gr-step-line').forEach(function (l) {
        l.classList.toggle('active', parseInt(l.dataset.line, 10) < currentStep);
    });
}

function showStep(n, direction) {
    document.querySelectorAll('.gr-step').forEach(function (s) {
        var stepNum = parseInt(s.dataset.step, 10);
        if (stepNum === n) {
            s.style.display = 'block';
            s.classList.remove('slide-in-left', 'slide-in-right');
            void s.offsetWidth;
            s.classList.add(direction === 'back' ? 'slide-in-left' : 'slide-in-right');
            s.setAttribute('aria-current', 'step');
        } else {
            s.style.display = 'none';
            s.removeAttribute('aria-current');
        }
    });
    currentStep = n;
    updateProgress();
    var wiz = document.getElementById('wizard');
    var top = wiz.getBoundingClientRect().top + window.scrollY - 20;
    window.scrollTo({ top: top, behavior: 'smooth' });
}

function gotoStep(n) {
    if (n === currentStep) return;
    showStep(n, n < currentStep ? 'back' : 'forward');
}

function formatCode(raw) {
    raw = raw.toUpperCase().replace(/[^A-Z0-9]/g, '').substring(0, 12);
    var groups = raw.match(/.{1,4}/g) || [];
    return groups.join('-');
}

var giftCodeInput = document.getElementById('giftCode');

giftCodeInput.addEventListener('input', function (e) {
    e.target.value = formatCode(e.target.value);
    if (/^[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{4}$/.test(e.target.value)) {
        checkCode();
    }
});

giftCodeInput.addEventListener('keypress', function (e) {
    if (e.key === 'Enter') {
        e.preventDefault();
        checkCode();
    }
});

function checkCode() {
    var code = giftCodeInput.value.trim().toUpperCase();
    var btn = document.getElementById('checkBtn');

    if (!code) {
        setStatus('err', ICON_ERROR + '<span>Please enter a code.</span>');
        return;
    }

    btn.disabled = true;
    btn.textContent = 'Checking…';
    setStatus('info', '<span class="gr-spin"></span><span>Checking your code…</span>');

    var url = btn.getAttribute('data-validate-url') || '';
    csrfFetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ code: code })
    })
    .then(function (r) { return r.json(); })
    .then(function (data) {
        btn.disabled = false;
        btn.textContent = 'Check';

        if (data.valid) {
            validatedCode = data.code;
            giftAmount = Number(data.amount);
            setStatus('ok', ICON_CHECK + '<span>Valid! This gift card is worth <strong>₹' + giftAmount.toLocaleString('en-IN') + '</strong>.</span>');
            document.getElementById('hiddenCode').value = data.code;

            var emailInput = document.getElementById('donorEmail');
            emailInput.readOnly = false;
            emailInput.style.background = '';
            emailInput.style.cursor = '';
            document.getElementById('emailHint').innerHTML = 'This gift card was sent to <strong>' + escapeHtml(data.recipient_email_masked) + '</strong> — enter your full email above.';

            setTimeout(function () { gotoStep(2); }, 350);
        } else {
            validatedCode = null;
            setStatus('err', ICON_ERROR + '<span>' + escapeHtml(data.message || 'Invalid code.') + '</span>');

            var emailInput = document.getElementById('donorEmail');
            emailInput.value = '';
            emailInput.readOnly = false;
            emailInput.style.background = '';
            emailInput.style.cursor = '';
            document.getElementById('emailHint').textContent = '';
        }
    })
    .catch(function () {
        btn.disabled = false;
        btn.textContent = 'Check';
        setStatus('err', ICON_ERROR + '<span>Something went wrong. Please try again.</span>');
    });
}

function selectCampaign(id, el, title, imageUrl) {
    selectedCampaignId = id;
    selectedCampaignTitle = title;
    selectedCampaignImg = imageUrl;
    document.getElementById('hiddenCampaignId').value = id;

    document.querySelectorAll('.gr-camp-card').forEach(function (c) {
        c.classList.remove('selected', 'just-selected');
        c.setAttribute('aria-pressed', 'false');
    });
    el.classList.add('selected', 'just-selected');
    el.setAttribute('aria-pressed', 'true');

    document.getElementById('step2NextBtn').disabled = false;
}

function filterCampaigns(query) {
    query = query.trim().toLowerCase();
    var cards = document.querySelectorAll('.gr-camp-card');
    var visibleCount = 0;
    cards.forEach(function (card) {
        var match = card.dataset.title.indexOf(query) !== -1;
        card.style.display = match ? '' : 'none';
        if (match) visibleCount++;
    });
    document.getElementById('searchEmpty').style.display = visibleCount === 0 ? 'block' : 'none';
}

function tryGotoReview() {
    var name = document.getElementById('donorName');
    var email = document.getElementById('donorEmail');
    if (!name.value.trim()) { name.reportValidity(); name.focus(); return; }
    if (!email.value.trim()) { email.reportValidity(); email.focus(); return; }

    document.getElementById('reviewCode').textContent = validatedCode || '';
    document.getElementById('reviewCampaign').textContent = selectedCampaignTitle;
    document.getElementById('reviewCampImg').style.backgroundImage = selectedCampaignImg ? "url('" + selectedCampaignImg + "')" : 'none';
    document.getElementById('reviewName').textContent = name.value.trim();
    document.getElementById('reviewEmail').textContent = email.value.trim();
    document.getElementById('reviewAmount').textContent = '₹' + giftAmount.toLocaleString('en-IN');

    gotoStep(4);
}

/* ── delegated actions ── */
document.addEventListener('click', function (e) {
    var el = e.target.closest('[data-action]');
    if (!el) return;
    var action = el.getAttribute('data-action');

    if (action === 'check-code') {
        checkCode();
    } else if (action === 'goto-step') {
        gotoStep(parseInt(el.getAttribute('data-step'), 10));
    } else if (action === 'goto-review') {
        tryGotoReview();
    } else if (action === 'select-campaign') {
        selectCampaign(el.getAttribute('data-id'), el, el.getAttribute('data-title'), el.getAttribute('data-image'));
    }
});

/* Keyboard support for campaign cards */
document.addEventListener('keydown', function (e) {
    if ((e.key === 'Enter' || e.key === ' ') && e.target.classList.contains('gr-camp-card')) {
        e.preventDefault();
        e.target.click();
    }
});

/* Campaign search */
var campSearch = document.getElementById('campSearch');
if (campSearch) campSearch.addEventListener('input', function () { filterCampaigns(this.value); });

/* Prevent double submission */
document.getElementById('redeemForm').addEventListener('submit', function (e) {
    var btn = document.getElementById('redeemBtn');
    if (btn.dataset.submitted === 'true') { e.preventDefault(); return; }
    btn.dataset.submitted = 'true';
    btn.disabled = true;
    btn.textContent = 'Processing…';
});

showStep(1, 'forward');
})();