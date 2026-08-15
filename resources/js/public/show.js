import { getCsrfToken } from '../shared/csrf.js';

(function () {
    'use strict';

    var sdbTotal = 0;

    document.documentElement.classList.add('js-enabled');

    /* ── Config from data attributes ── */
    var pw = document.querySelector('.page-wrap');
    var cfg = pw ? pw.dataset : {};
    cfg.campaignId = cfg.campaignId || '';
    cfg.campaignTitle = cfg.campaignTitle || '';
    cfg.couponRoute = cfg.couponRoute || '';

    /* ── Scroll Reveal ── */
    var revEls = document.querySelectorAll('.reveal,.reveal-left,.reveal-right');
    if (revEls.length && 'IntersectionObserver' in window) {
        var obs = new IntersectionObserver(function (entries) {
            entries.forEach(function (e) {
                if (e.isIntersecting) {
                    e.target.classList.add('visible');
                    obs.unobserve(e.target);
                }
            });
        }, { threshold: 0.07, rootMargin: '0px 0px -28px 0px' });
        revEls.forEach(function (el) { obs.observe(el); });
        setTimeout(function () { revEls.forEach(function (el) { el.classList.add('visible'); }); }, 4000);
    } else if (revEls.length) {
        revEls.forEach(function (el) { el.classList.add('visible'); });
    }

    /* ── Progress fill on load ── */
    var fills = document.querySelectorAll('.hero-progress-fill,.donate-prog-fill-new');
    fills.forEach(function (el) {
        var w = el.style.width;
        el.style.width = '0%';
        setTimeout(function () { el.style.width = w; }, 500);
    });

    /* ── Scroll to top + progress fill ── */
    var scrollTopBtn = document.getElementById('scrollTopBtn');
    var scrollProgressFill = document.getElementById('scrollProgressFill');

    window.addEventListener('scroll', function () {
        if (scrollTopBtn) scrollTopBtn.classList.toggle('visible', window.scrollY > 600);
        if (scrollProgressFill) {
            var docH = document.documentElement.scrollHeight - window.innerHeight;
            var pct = docH > 0 ? (window.scrollY / docH) * 100 : 0;
            scrollProgressFill.style.width = pct + '%';
        }
    }, { passive: true });

    /* ── Sticky donate bar ── */
    (function () {
        var bar = document.getElementById('stickyBar');
        var btnLbl = document.getElementById('sdbBtnLabel');
        var card = document.getElementById('donateCardEl');
        var shown = false;
        var cardInView = false;

        function update() {
            var scrollY = window.scrollY;
            if (!cardInView) {
                var heroH = document.querySelector('.hero')?.offsetHeight || 500;
                var shouldShow = scrollY > heroH * 0.6;
                if (shouldShow !== shown) {
                    bar.classList.toggle('visible', shouldShow);
                    shown = shouldShow;
                }
            } else if (shown) {
                bar.classList.remove('visible');
                shown = false;
            }
            if (sdbTotal > 0) {
                btnLbl.textContent = 'Donate Now (\u20b9' + sdbTotal.toLocaleString('en-IN') + ')';
            } else {
                var amtOnce = parseFloat(document.getElementById('amtOnce')?.value) || 0;
                btnLbl.textContent = amtOnce > 0 ? 'Donate Now (\u20b9' + amtOnce.toLocaleString('en-IN') + ')' : 'Donate Now';
            }
            updateChatFloat();
        }

        if (card && 'IntersectionObserver' in window) {
            var io = new IntersectionObserver(function (entries) {
                cardInView = entries[0].isIntersecting;
                update();
            });
            io.observe(card);
        }

        var chatFloat = null;
        function updateChatFloat() {
            if (!chatFloat) {
                chatFloat = document.getElementById('chatToggle') ? document.getElementById('chatToggle').closest('.chat-float-wrap') : null;
            }
            if (!chatFloat || !card) return;
            var cr = card.getBoundingClientRect();
            var vh = window.innerHeight, vw = window.innerWidth;
            var collides = cr.bottom > vh - 84 && cr.right > vw - 84;
            chatFloat.classList.toggle('chat-float-hidden', collides || shown);
            if (scrollTopBtn) {
                var nearBottom = (document.documentElement.scrollHeight - window.scrollY - window.innerHeight) < 120;
                scrollTopBtn.classList.toggle('scroll-top-hidden', collides || nearBottom);
            }
        }

        window.addEventListener('resize', updateChatFloat, { passive: true });
        document.addEventListener('DOMContentLoaded', updateChatFloat);
        window.addEventListener('scroll', update, { passive: true });
        update();
    })();

    /* ── Functions (converted to data-action handlers) ── */

    function scrollToDonate() {
        var card = document.getElementById('donateCardEl');
        if (card) {
            card.scrollIntoView({ behavior: 'smooth', block: 'center' });
            card.style.boxShadow = '0 0 0 4px rgba(37,99,235,.45), var(--shadow-lg)';
            setTimeout(function () { card.style.boxShadow = ''; }, 1800);
        }
    }

    function switchMainTab(tab) {
        var panelProducts = document.getElementById('panelProducts');
        var panelMoney = document.getElementById('panelMoney');
        if (tab === 'products') {
            document.getElementById('tabProducts').className = 'main-donate-tab active-products';
            document.getElementById('tabMoney').className = 'main-donate-tab';
            if (panelProducts) panelProducts.style.display = '';
            if (panelMoney) panelMoney.style.display = 'none';
        } else {
            document.getElementById('tabMoney').className = 'main-donate-tab active-money';
            document.getElementById('tabProducts').className = 'main-donate-tab';
            if (panelMoney) panelMoney.style.display = '';
            if (panelProducts) panelProducts.style.display = 'none';
        }
    }

    var productCart = {};

    function addProductToCart(id) {
        var card = document.getElementById('dpCard_' + id);
        var addWrap = document.getElementById('dpAddWrap_' + id);
        var counter = document.getElementById('dpCounter_' + id);
        var price = parseFloat(card.dataset.price) || 0;
        var name = card.dataset.name;
        if (!productCart[id]) {
            productCart[id] = { qty: 1, price: price, name: name };
        } else {
            productCart[id].qty++;
        }
        if (addWrap) addWrap.style.display = 'none';
        if (counter) counter.classList.add('show');
        var countEl = document.getElementById('dpCount_' + id);
        if (countEl) countEl.textContent = productCart[id].qty;
        if (card) card.classList.add('in-cart');
        updateProductCartUI();
    }

    function changeQty(id, delta) {
        if (!productCart[id]) return;
        productCart[id].qty += delta;
        if (productCart[id].qty <= 0) {
            delete productCart[id];
            var aw = document.getElementById('dpAddWrap_' + id);
            var ct = document.getElementById('dpCounter_' + id);
            var cd = document.getElementById('dpCard_' + id);
            if (aw) aw.style.display = '';
            if (ct) ct.classList.remove('show');
            if (cd) cd.classList.remove('in-cart');
        } else {
            var countEl = document.getElementById('dpCount_' + id);
            if (countEl) countEl.textContent = productCart[id].qty;
        }
        updateProductCartUI();
    }

    function updateProductCartUI() {
        var totalItems = 0, totalAmt = 0;
        Object.values(productCart).forEach(function (p) {
            totalItems += p.qty;
            totalAmt += p.qty * p.price;
        });
        var bar = document.getElementById('dpCartBar');
        var itemEl = document.getElementById('dpCartItems');
        var totEl = document.getElementById('dpCartTotal');
        var btn = document.getElementById('dpDonateBtn');
        var btnTxt = document.getElementById('dpDonateBtnText');
        if (totalItems > 0) {
            if (bar) bar.classList.add('show');
            if (itemEl) itemEl.textContent = totalItems + ' item' + (totalItems !== 1 ? 's' : '') + ' selected';
            if (totEl) totEl.textContent = '\u20b9' + totalAmt.toLocaleString('en-IN');
            if (btn) { btn.disabled = false; btn.style.opacity = '1'; btn.style.cursor = 'pointer'; }
            if (btnTxt) btnTxt.textContent = 'Donate Now (\u20b9' + totalAmt.toLocaleString('en-IN') + ')';
        } else {
            if (bar) bar.classList.remove('show');
            if (btn) { btn.disabled = true; btn.style.opacity = '.5'; btn.style.cursor = 'not-allowed'; }
            if (btnTxt) btnTxt.textContent = 'Select Products to Donate';
        }
        sdbTotal = totalItems > 0 ? totalAmt : 0;
    }

    function clearProductCart() {
        Object.keys(productCart).forEach(function (id) {
            delete productCart[id];
            var aw = document.getElementById('dpAddWrap_' + id);
            var ct = document.getElementById('dpCounter_' + id);
            var cd = document.getElementById('dpCard_' + id);
            if (aw) aw.style.display = '';
            if (ct) ct.classList.remove('show');
            if (cd) cd.classList.remove('in-cart');
        });
        updateProductCartUI();
    }

    function submitProductDonation() {
        if (Object.keys(productCart).length === 0) return;
        var total = Object.values(productCart).reduce(function (s, p) { return s + p.qty * p.price; }, 0);
        var ids = Object.keys(productCart).join(',');
        var qtys = Object.values(productCart).map(function (p) { return p.qty; }).join(',');
        var amtEl = document.getElementById('productDonateAmount');
        var idsEl = document.getElementById('productDonateIds');
        var qtysEl = document.getElementById('productDonateQtys');
        if (amtEl) amtEl.value = total;
        if (idsEl) idsEl.value = ids;
        if (qtysEl) qtysEl.value = qtys;
        var form = document.getElementById('productDonateForm');
        if (form) form.submit();
    }

    function toggleDpExpand(id) {
        var card = document.getElementById('dpCard_' + id);
        if (card) card.classList.toggle('expanded');
    }

    function loadMoreProducts() {
        document.querySelectorAll('.dp-card[data-hidden="1"]').forEach(function (el) {
            el.style.display = '';
            el.dataset.hidden = '0';
        });
        var btn = document.getElementById('dpLoadMore');
        if (btn) btn.style.display = 'none';
    }

    function validateDonateForm() {
        var input = document.getElementById('amtOnce');
        if (!input) return true;
        var val = parseFloat(input.value);
        if (!val || val < 1) {
            input.focus();
            input.style.borderColor = 'var(--red)';
            input.style.boxShadow = '0 0 0 3px rgba(239,68,68,.15)';
            setTimeout(function () { input.style.borderColor = ''; input.style.boxShadow = ''; }, 2000);
            return false;
        }
        return true;
    }

    function applyCoupon() {
        var codeEl = document.getElementById('couponCode');
        var msg = document.getElementById('couponMsg');
        var code = codeEl ? codeEl.value.trim() : '';
        var amount = parseFloat(document.getElementById('amtOnce').value);

        if (!code) { if (msg) { msg.textContent = ''; msg.style.color = ''; } return; }
        if (!amount || amount < 1) {
            if (msg) { msg.textContent = 'Enter a donation amount first.'; msg.style.color = '#dc2626'; }
            return;
        }
        if (msg) { msg.textContent = 'Checking\u2026'; msg.style.color = '#6b7280'; }

        fetch(cfg.couponRoute, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': getCsrfToken()
            },
            body: JSON.stringify({
                code: code,
                amount: amount,
                campaign_id: cfg.campaignId
            })
        })
        .then(function (r) { return r.json(); })
        .then(function (d) {
            if (d.valid) {
                if (msg) {
                    msg.textContent = 'Coupon applied! You pay \u20b9'
                        + Number(d.discounted_total).toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 })
                        + ' (saved \u20b9'
                        + Number(d.discount_amount).toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 })
                        + ').';
                    msg.style.color = '#059669';
                }
            } else {
                if (msg) { msg.textContent = d.message || 'Invalid coupon code.'; msg.style.color = '#dc2626'; }
            }
        })
        .catch(function () {
            if (msg) { msg.textContent = 'Could not verify coupon. Try again.'; msg.style.color = '#dc2626'; }
        });
    }

    var currentFreq = 'once';

    function switchFreq(type, tabEl) {
        currentFreq = type;
        document.querySelectorAll('.freq-tab-new').forEach(function (t) { t.classList.remove('active'); });
        if (tabEl) tabEl.classList.add('active');
        ['Once', 'Weekly', 'Monthly'].forEach(function (t) {
            var el = document.getElementById('mForm' + t);
            if (el) el.style.display = (t.toLowerCase() === type) ? 'block' : 'none';
        });
        var weekly = document.getElementById('mFreqWeekly');
        var monthly = document.getElementById('mFreqMonthly');
        if (weekly) weekly.classList.toggle('show', type === 'weekly');
        if (monthly) monthly.classList.toggle('show', type === 'monthly');
        document.querySelectorAll('.amt-btn-new').forEach(function (b) { b.classList.remove('active'); });
        var preview = document.getElementById('impactPreviewNew');
        if (preview) preview.classList.remove('show');
    }

    var impactMap = [
        { min: 10, max: 99, text: 'buys a nutritious meal for a child in need.' },
        { min: 100, max: 499, text: 'provides school stationery for one student for a month.' },
        { min: 500, max: 999, text: 'covers basic medicines for a family for two weeks.' },
        { min: 1000, max: 4999, text: 'sponsors a full medical checkup for one person.' },
        { min: 5000, max: 9999, text: 'equips an entire classroom with learning materials.' },
        { min: 10000, max: 49999, text: 'helps provide emergency shelter for a displaced family.' },
        { min: 50000, max: Infinity, text: 'funds comprehensive relief for 10 families for a month.' }
    ];

    function pickAmtNew(amt, btn) {
        var inputMap = { once: 'amtOnce', weekly: 'amtWeekly', monthly: 'amtMonthly' };
        var inputEl = document.getElementById(inputMap[currentFreq]);
        if (inputEl) inputEl.value = amt;
        document.querySelectorAll('.amt-btn-new').forEach(function (b) { b.classList.remove('active'); });
        if (btn) btn.classList.add('active');
        showImpactNew(amt);
    }

    function syncAmtNew(type) {
        var inputMap = { once: 'amtOnce', weekly: 'amtWeekly', monthly: 'amtMonthly' };
        var val = parseFloat(document.getElementById(inputMap[type])?.value) || 0;
        document.querySelectorAll('.amt-btn-new').forEach(function (b) { b.classList.remove('active'); });
        showImpactNew(val);
    }

    function showImpactNew(amount) {
        var preview = document.getElementById('impactPreviewNew');
        var head = document.getElementById('impactHeadNew');
        var txt = document.getElementById('impactTxtNew');
        if (!preview) return;
        if (!amount || amount < 10) { preview.classList.remove('show'); return; }
        var match = impactMap.find(function (m) { return amount >= m.min && amount <= m.max; });
        if (match) {
            var prefix = currentFreq === 'weekly' ? 'Every week, \u20b9' : currentFreq === 'monthly' ? 'Every month, \u20b9' : '\u20b9';
            if (head) head.textContent = 'Your impact';
            if (txt) txt.textContent = prefix + Number(amount).toLocaleString('en-IN') + ' ' + match.text;
            preview.classList.add('show');
        } else {
            preview.classList.remove('show');
        }
    }

    function toggleFaq(idx) {
        var item = document.getElementById('faq-' + idx);
        if (!item) return;
        var isOpen = item.classList.contains('open');
        document.querySelectorAll('.faq-item').forEach(function (i) { i.classList.remove('open'); });
        if (!isOpen) item.classList.add('open');
    }

    function copyLink(btn) {
        var url = window.location.href;
        var done = function () { if (btn) flashCopied(btn); };
        if (navigator.clipboard && navigator.clipboard.writeText) {
            navigator.clipboard.writeText(url).then(done).catch(function () { fallbackCopy(url, done); });
        } else {
            fallbackCopy(url, done);
        }
    }

    function fallbackCopy(text, cb) {
        var t = document.createElement('textarea');
        t.value = text;
        document.body.appendChild(t);
        t.select();
        try {
            document.execCommand('copy');
            if (cb) cb();
        } catch (e) {
            alert('Copy this link: ' + text);
        }
        document.body.removeChild(t);
    }

    function flashCopied(btn) {
        var orig = btn.innerHTML;
        btn.innerHTML = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg> Copied!';
        btn.style.color = 'var(--green)';
        btn.style.borderColor = 'var(--green)';
        setTimeout(function () {
            btn.innerHTML = orig;
            btn.style.color = '';
            btn.style.borderColor = '';
        }, 2000);
    }

    function shareTo(network) {
        var url = encodeURIComponent(window.location.href);
        var title = encodeURIComponent(cfg.campaignTitle);
        var links = {
            whatsapp: 'https://api.whatsapp.com/send?text=' + title + '%20' + url,
            facebook: 'https://www.facebook.com/sharer/sharer.php?u=' + url,
            x: 'https://twitter.com/intent/tweet?text=' + title + '&url=' + url
        };
        if (links[network]) {
            window.open(links[network], '_blank', 'noopener,width=600,height=540');
        }
    }

    function shareCampaign(ev) {
        var title = cfg.campaignTitle;
        var url = window.location.href;
        var btn = (ev && ev.currentTarget) || (window.event && window.event.currentTarget);
        if (navigator.share) {
            navigator.share({ title: title, url: url }).catch(function () {});
        } else if (navigator.clipboard && navigator.clipboard.writeText) {
            navigator.clipboard.writeText(url).then(function () {
                if (!btn) return;
                var orig = btn.innerHTML;
                btn.innerHTML = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg> Copied!';
                btn.style.color = 'var(--green)';
                btn.style.borderColor = 'var(--green)';
                setTimeout(function () {
                    btn.innerHTML = orig;
                    btn.style.color = '';
                    btn.style.borderColor = '';
                }, 2000);
            }).catch(function () {
                alert('Copy this link: ' + url);
            });
        } else {
            fallbackCopy(url, function () { if (btn) flashCopied(btn); });
        }
    }

    /* ── FAQ keydown ── */
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.faq-q').forEach(function (q) {
            q.addEventListener('keydown', function (e) {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    q.click();
                }
            });
            q.setAttribute('role', 'button');
            q.setAttribute('tabindex', '0');
        });
    });

    /* ── Event delegation ── */
    document.addEventListener('click', function (e) {
        var el = e.target.closest('[data-action]');
        if (!el) return;
        var action = el.getAttribute('data-action');

        if (action === 'scroll-to-donate') {
            e.preventDefault();
            scrollToDonate();
        } else if (action === 'share-campaign') {
            shareCampaign(e);
        } else if (action === 'switch-main-tab') {
            switchMainTab(el.getAttribute('data-tab'));
        } else if (action === 'clear-product-cart') {
            clearProductCart();
        } else if (action === 'add-to-cart') {
            addProductToCart(el.getAttribute('data-id'));
        } else if (action === 'change-qty') {
            changeQty(el.getAttribute('data-id'), parseInt(el.getAttribute('data-delta'), 10));
        } else if (action === 'toggle-dp-expand') {
            toggleDpExpand(el.getAttribute('data-id'));
        } else if (action === 'load-more-products') {
            loadMoreProducts();
        } else if (action === 'submit-product-donation') {
            submitProductDonation();
        } else if (action === 'switch-freq') {
            switchFreq(el.getAttribute('data-freq'), el);
        } else if (action === 'pick-amt') {
            pickAmtNew(parseFloat(el.getAttribute('data-amt')), el);
        } else if (action === 'apply-coupon') {
            applyCoupon();
        } else if (action === 'scroll-top') {
            e.preventDefault();
            window.scrollTo({ top: 0, behavior: 'smooth' });
        } else if (action === 'share-to') {
            shareTo(el.getAttribute('data-network'));
        } else if (action === 'copy-link') {
            copyLink(el);
        } else if (action === 'toggle-faq') {
            toggleFaq(el.getAttribute('data-id'));
        }
    });

    /* ── Input handlers ── */
    document.addEventListener('input', function (e) {
        var el = e.target.closest('[data-input-action]');
        if (!el) return;
        var action = el.getAttribute('data-input-action');
        if (action === 'sync-amt') {
            syncAmtNew(el.getAttribute('data-type'));
        }
    });

    /* ── Form submit handler ── */
    var donateFormOnce = document.getElementById('donateFormOnce');
    if (donateFormOnce) {
        donateFormOnce.addEventListener('submit', function (e) {
            if (!validateDonateForm()) {
                e.preventDefault();
            }
        });
    }

})();
