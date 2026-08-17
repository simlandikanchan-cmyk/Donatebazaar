(function(){
'use strict';

/* ── SERVER DATA (from #partnershipData JSON block) ── */
var data = {};
(function () {
  var dataEl = document.getElementById('partnershipData');
  if (!dataEl) return;
  try { data = JSON.parse(dataEl.textContent); } catch (e) { /* keep defaults */ }
})();

/* ═══════════════════════════════════════
   TOAST ENGINE
═══════════════════════════════════════ */
var stack = document.getElementById('toastStack');

function toast(opts){
    /* opts: { type, title, message, duration } */
    var type     = opts.type    || 'info';
    var title    = opts.title   || '';
    var message  = opts.message || '';
    var duration = opts.duration || 5000;

    var icons = {
        success: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>',
        error:   '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>',
        warning: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>',
        info:    '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>',
    };

    var t = document.createElement('div');
    t.className = 'toast toast-' + type;
    t.style.setProperty('--toast-dur', (duration/1000) + 's');
    t.setAttribute('role','alert');
    t.innerHTML =
        '<div class="toast-icon">' + (icons[type]||icons.info) + '</div>' +
        '<div class="toast-body">' +
            (title   ? '<div class="toast-title">'+ title   +'</div>' : '') +
            (message ? '<div class="toast-msg">'  + message +'</div>' : '') +
        '</div>' +
        '<button class="toast-close" aria-label="Dismiss">✕</button>';

    t.querySelector('.toast-close').addEventListener('click', function(){ dismiss(t); });
    stack.appendChild(t);

    var timer = setTimeout(function(){ dismiss(t); }, duration);
    t._timer = timer;

    /* pause on hover */
    t.addEventListener('mouseenter', function(){ clearTimeout(t._timer); t.style.setProperty('--toast-dur','0s'); t.style.animationPlayState='paused'; });
    t.addEventListener('mouseleave', function(){ t._timer = setTimeout(function(){ dismiss(t); }, 2000); });
}

function dismiss(t){
    if (!t.parentNode) return;
    t.classList.add('dismissing');
    setTimeout(function(){ if(t.parentNode) t.parentNode.removeChild(t); }, 320);
}

/* ═══════════════════════════════════════
   FLASH MESSAGES FROM SERVER
═══════════════════════════════════════ */
if (data.success) {
    setTimeout(function(){
        toast({ type:'success', title:'Request Submitted!', message: data.success, duration:6000 });
    }, 300);
}

if (data.error) {
    setTimeout(function(){
        toast({ type:'error', title:'Something went wrong', message: data.error, duration:7000 });
    }, 300);
}

if (data.errorsCount) {
    setTimeout(function(){
        toast({
            type: 'error',
            title: 'Please fix ' + data.errorsCount + ' error' + (data.errorsCount > 1 ? 's' : ''),
            message: 'Check the form fields highlighted below.',
            duration: 8000
        });
    }, 300);
}

/* ═══════════════════════════════════════
   FORM SUBMIT — loading state + validation toast
═══════════════════════════════════════ */
var form      = document.getElementById('partnerForm');
var submitBtn = document.getElementById('submitBtn');
var submitTxt = document.getElementById('submitBtnText');

if (form) {
    form.addEventListener('submit', function(e){

        /* ── Client-side required check ── */
        var required = ['name','email','phone'];
        var missing  = [];
        required.forEach(function(id){
            var el = document.getElementById(id);
            if (el && !el.value.trim()) {
                el.classList.add('is-error');
                missing.push(el.previousElementSibling
                    ? el.previousElementSibling.textContent.replace('*','').trim()
                    : id);
            } else if (el) {
                el.classList.remove('is-error');
            }
        });

        /* partnership type check */
        // var ptSelected = form.querySelector('input[name="partnership_type"]:checked');
        // if (!ptSelected) missing.push('Partnership type');

             var ptSelected = form.querySelector('select[name="partnership_type"]');

             if (!ptSelected || ptSelected.value.trim() === '') {
             if (ptSelected) {
             ptSelected.classList.add('is-error');
             }

             missing.push('Partnership type');
             } else 

             {

             ptSelected.classList.remove('is-error');


             }

        /* message check */
        var msg = document.getElementById('f_message');
        if (msg && !msg.value.trim()) {
            msg.classList.add('is-error');
            missing.push('Proposal');
        }

        if (missing.length > 0) {
            e.preventDefault();
            toast({
                type: 'warning',
                title: 'Required fields missing',
                message: missing.join(', '),
                duration: 6000
            });


            /* scroll to first error */
            var firstErr = form.querySelector('.is-error');
            if (firstErr) firstErr.scrollIntoView({ behavior:'smooth', block:'center' });
            return;

        }

        /* ── File size check ── */

        var docInput = document.getElementById('docInput');
        if (docInput && docInput.files[0] && docInput.files[0].size > 2 * 1024 * 1024) {
            e.preventDefault();
            toast({ type:'error', title:'File too large', message:'Please upload a document under 2MB.', duration:6000 });
            return;
        }

        /* ── All good: show loading state ── */

        submitBtn.disabled = true;
        submitBtn.innerHTML =
            '<div class="spinner"></div>' +
            '<span>Submitting…</span>';

        toast({
            type: 'info',
            title: 'Sending your request…',
            message: 'Please wait while we process your submission.',
            duration: 10000
        });
    });

    /* Remove error highlight on input */

    form.querySelectorAll('.field-input').forEach(function(el){
        el.addEventListener('input', function(){
            this.classList.remove('is-error');
        });
    });
}

/* ═══════════════════════════════════════
   CHAR COUNTER
═══════════════════════════════════════ */
function updateCharCount(el, max, counterId){
    var len   = el.value.length;
    var el2   = document.getElementById(counterId);
    if (!el2) return;
    el2.textContent = len + ' / ' + max;
    el2.className   = 'char-count';
    if (len > max * 0.9) el2.classList.add('warn');
    if (len >= max)      el2.classList.add('over');
}

/* init on load */

var msgEl = document.getElementById('f_message');
if (msgEl) updateCharCount(msgEl, 1000, 'msgCount');

/* ═══════════════════════════════════════
   FILE UPLOAD
═══════════════════════════════════════ */

var docInput = document.getElementById('docInput');
if (docInput) {
    docInput.addEventListener('change', function(){
        var file = this.files[0];
        if (!file) return;

        /* size guard */
        if (file.size > 2 * 1024 * 1024) {
            toast({ type:'error', title:'File too large', message: file.name + ' exceeds 2MB limit.', duration:6000 });
            this.value = '';
            return;
        }

        document.getElementById('uploadPrompt').style.display  = 'none';
        document.getElementById('uploadFname').textContent      = file.name;
        document.getElementById('uploadFsize').textContent      = (file.size / 1024).toFixed(0) + ' KB';
        document.getElementById('uploadSuccess').classList.add('show');
        document.getElementById('uploadZone').classList.add('has-file');

        toast({ type:'success', title:'File attached', message: file.name + ' is ready to upload.', duration:4000 });
    });
}

function clearFile(e){
    e.stopPropagation();
    var inp = document.getElementById('docInput');
    if (inp) inp.value = '';
    document.getElementById('uploadPrompt').style.display = '';
    document.getElementById('uploadSuccess').classList.remove('show');
    document.getElementById('uploadZone').classList.remove('has-file');
}

})();