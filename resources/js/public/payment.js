import { csrfFetch } from '../shared/api.js';

(function () {
  'use strict';

  /* ── SERVER DATA (from #paymentData JSON block) ── */
  var data = {};
  (function () {
    var dataEl = document.getElementById('paymentData');
    if (!dataEl) return;
    try { data = JSON.parse(dataEl.textContent); } catch (e) { /* keep defaults */ }
  })();

  const payBtn = document.getElementById('rzp-button');
  const cancelLink = document.getElementById('cancel-link');
  const datetimeEl = document.getElementById('payment-datetime');

  /* paymentStatus from data-* attribute — not a JS string literal.
     Keeps DB state in the DOM rather than floating in JS global scope. */
  const paymentStatus = payBtn.dataset.paymentStatus;

  const campaignUrl = data.campaignUrl;

  const idleButtonHtml = `
      <span class="flex items-center justify-center gap-2">
          <svg xmlns="http://www.w3.org/2000/svg"
               width="17"
               height="17"
               viewBox="0 0 24 24"
               fill="none"
               stroke="currentColor"
               stroke-width="2">
              <rect x="3" y="11" width="18" height="11" rx="2"/>
              <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
          </svg>
          Pay ₹${data.amountLabel} Securely
      </span>
  `;

  function resetButton() {
      payBtn.disabled = false;
      payBtn.style.background = "linear-gradient(135deg,#4F46E5 0%,#7C3AED 100%)";
      payBtn.innerHTML = idleButtonHtml;
  }

  var options = {

      key: data.key,
      amount: data.amount,
      currency: "INR",

      name: "DonateBazaar",

      description: data.description,

      image: data.image,

      order_id: data.orderId,

      prefill: {
          name:  data.donorName,
          email: data.donorEmail
      },

      notes: {
          campaign_id: data.campaignId,
          donation_id: data.donationId
      },

      theme: {
          color: "#4F46E5"
      },

      modal: {
          ondismiss: function () {
              resetButton();
          }
      },

      handler: function (response) {

          payBtn.disabled = true;
          cancelLink.style.visibility = "hidden";

          payBtn.innerHTML = `
              <span class="spinner"><span class="btn__label">Verifying Payment...</span></span>`;

          // Guarantee the "Verifying" state is visible for at least 1s,
          // even if the server responds instantly, so the user actually
          // registers what's happening instead of seeing a flash.
          const minDelay = new Promise(resolve => setTimeout(resolve, 1000));

          const verifyRequest = csrfFetch(data.verifyUrl, {

              method: "POST",

              headers: {
                  "Content-Type": "application/json"
              },

              body: JSON.stringify({
                  razorpay_payment_id: response.razorpay_payment_id,
                  razorpay_order_id:   response.razorpay_order_id,
                  razorpay_signature:  response.razorpay_signature,
                  donation_id:         data.donationId
              })
          })
          .then(res => res.json());

          Promise.all([verifyRequest, minDelay])

          .then(([data]) => {

              if (data.success) {

                  const completedAt = data.paid_at
                      ? new Date(data.paid_at)
                      : new Date();

                  datetimeEl.textContent = completedAt.toLocaleString('en-IN', {
                      day: '2-digit', month: 'short', year: 'numeric',
                      hour: '2-digit', minute: '2-digit', hour12: true
                  });

                  payBtn.innerHTML = `
                      <span class="flex items-center justify-center gap-2">
                          <svg xmlns="http://www.w3.org/2000/svg"
                               width="18" height="18"
                               viewBox="0 0 24 24"
                               fill="none"
                               stroke="currentColor"
                               stroke-width="2.5">
                              <polyline points="20 6 9 17 4 12"/>
                          </svg>
                          Payment Successful
                      </span>
                  `;

                  payBtn.style.background = "#059669";

                  // No auto-redirect. The user stays on this confirmation
                  // state and decides themselves when to leave.
                  cancelLink.style.visibility = "visible";
                  cancelLink.textContent      = "Back to Campaign";
                  cancelLink.href             = campaignUrl;
                  cancelLink.style.borderColor = "#A7F3D0";
                  cancelLink.style.color       = "#047857";
                  cancelLink.style.fontWeight  = "600";

              } else {

                  resetButton();
                  cancelLink.style.visibility = "visible";
                  alert(data.message || 'Payment verification failed.');
              }
          })

          .catch(() => {

              resetButton();
              cancelLink.style.visibility = "visible";
              alert('Something went wrong. Please try again.');
          });
      }
  };

  const rzp = new Razorpay(options);

  payBtn.addEventListener('click', function (e) {

      e.preventDefault();

      payBtn.disabled = true;

      payBtn.innerHTML = `
          <span class="spinner"><span class="btn__label">Opening Payment Gateway...</span></span>`;

      rzp.open();
  });

  // Auto-open on page load ONLY when the payment is still pending.
  // Without this check, refreshing the page after a completed payment
  // would re-open the modal and could trigger a duplicate payment.
  window.addEventListener('load', function () {
      if (paymentStatus === 'pending') {
          rzp.open();
      }
  });

  /* ── Cancel link hover (replaces inline onmouseover/onmouseout) ── */
  document.addEventListener('mouseover', function (e) {
      const el = e.target.closest('#cancel-link');
      if (!el) return;
      el.style.background = '#F9FAFB';
  });
  document.addEventListener('mouseout', function (e) {
      const el = e.target.closest('#cancel-link');
      if (!el) return;
      el.style.background = 'transparent';
  });

})();