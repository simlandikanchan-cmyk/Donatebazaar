/* ═══════════════════════════════════════════════════════════════════
   Admin Blogs Create — Redesigned JS
   ═══════════════════════════════════════════════════════════════════ */

(function(){
'use strict';

/* ── HELPERS ── */
function $(id){ return document.getElementById(id); }
function wordCount(t){ return t.trim()===''?0:t.trim().split(/\s+/).length; }
function sentences(t){ return t.split(/[.!?]+/).filter(function(s){ return s.trim().split(/\s+/).length>2; }); }
function avgWPS(t){ var s=sentences(t); if(!s.length) return 0; return Math.round(s.reduce(function(a,b){ return a+b.trim().split(/\s+/).length; },0)/s.length); }
function longSents(t){ return sentences(t).filter(function(s){ return s.trim().split(/\s+/).length>20; }).length; }
function paraCount(t){ return t.split(/\n\s*\n/).filter(function(p){ return p.trim().length>0; }).length||(t.trim().length>0?1:0); }
function readScore(t){ if(wordCount(t)<10) return 0; var avg=avgWPS(t); if(avg<=12) return 95; if(avg<=15) return 82; if(avg<=20) return 65; if(avg<=25) return 45; return 25; }
function slugify(t){ return t.toLowerCase().replace(/[^a-z0-9\s]/g,'').trim().replace(/\s+/g,'-').slice(0,50)||'your-title'; }
function barColor(p){ return p>=70?'var(--green)':p>=40?'var(--amber)':'var(--red)'; }
function titleBarColor(l){ if(l>=40&&l<=60) return'var(--green)'; if(l>60&&l<=70) return'var(--amber)'; if(l>70) return'var(--red)'; return'var(--border2)'; }
function descBarColor(l){ if(l>=120&&l<=160) return'var(--green)'; if(l>160) return'var(--red)'; if(l>=50) return'var(--amber)'; return'var(--border2)'; }

/* ═══════════════════════════════════════════════════════════════════
   COLLAPSIBLE SECTIONS
   ═══════════════════════════════════════════════════════════════════ */
document.querySelectorAll('.section-header[data-toggle]').forEach(function(hdr){
    hdr.addEventListener('click', function(){
        var sectionId = this.getAttribute('data-toggle');
        var section = document.getElementById(sectionId);
        if(!section) return;
        section.classList.toggle('is-open');
    });
});

/* ═══════════════════════════════════════════════════════════════════
   PROGRESS STEPPER — click to jump + section fill counts
   ═══════════════════════════════════════════════════════════════════ */
document.querySelectorAll('.stepper-step[data-section]').forEach(function(step){
    step.addEventListener('click', function(){
        var sectionId = this.getAttribute('data-section');
        var section = document.getElementById(sectionId);
        if(!section) return;
        // Open section if closed
        if(!section.classList.contains('is-open')){
            section.classList.add('is-open');
        }
        // Scroll into view
        section.scrollIntoView({ behavior: 'smooth', block: 'start' });
    });
});

function updateStepper(){
    var titleVal = ($('title')||{value:''}).value;
    var contentVal = ($('blogContent')||{value:''}).value;
    var excerptVal = ($('excerpt')||{value:''}).value;
    var metaTitleVal = ($('meta_title')||{value:''}).value;
    var metaDescVal = ($('meta_description')||{value:''}).value;
    var catEl = $('category_id');
    var hasCat = catEl && catEl.value && catEl.value !== '';
    var imgEl = $('coverUpload');
    var hasImg = imgEl && imgEl.files && imgEl.files.length > 0;
    var hasTags = document.querySelectorAll('#tagContainer .tag-chip input[type=checkbox]:checked').length > 0;

    // Section 1: Basic Info (title + category = 2 required)
    var basicDone = 0;
    var basicTotal = 2;
    if(titleVal.length >= 5) basicDone++;
    if(hasCat) basicDone++;
    var secBasicCount = $('sec-basic-count');
    if(secBasicCount) secBasicCount.textContent = basicDone + '/' + basicTotal;
    var stepBasicCount = $('stepper-basic-count');
    if(stepBasicCount) stepBasicCount.textContent = basicDone + '/' + basicTotal;

    // Section 2: Cover (1 optional)
    var coverDone = hasImg ? 1 : 0;
    var secCoverCount = $('sec-cover-count');
    if(secCoverCount) secCoverCount.textContent = coverDone + '/1';
    var stepCoverCount = $('stepper-cover-count');
    if(stepCoverCount) stepCoverCount.textContent = coverDone + '/1';

    // Section 3: Content (1 required)
    var contentDone = contentVal.trim().length >= 50 ? 1 : 0;
    var secContentCount = $('sec-content-count');
    if(secContentCount) secContentCount.textContent = contentDone + '/1';
    var stepContentCount = $('stepper-content-count');
    if(stepContentCount) stepContentCount.textContent = contentDone + '/1';

    // Section 4: SEO (optional counters)
    var seoCount = 0;
    if(metaTitleVal.length > 0) seoCount++;
    if(metaDescVal.length > 0) seoCount++;
    var secSeoCount = $('sec-seo-count');
    if(secSeoCount) secSeoCount.textContent = seoCount + '/2';
    var stepSeoCount = $('stepper-seo-count');
    if(stepSeoCount) stepSeoCount.textContent = seoCount + '/2';

    // Section done classes
    setSectionDone('sec-basic', basicDone >= basicTotal);
    setSectionDone('sec-cover', coverDone >= 1);
    setSectionDone('sec-content', contentDone >= 1);
    setSectionDone('sec-seo', seoCount >= 2);

    // Stepper done classes
    setStepperDone('stepper-basic', basicDone >= basicTotal);
    setStepperDone('stepper-cover', coverDone >= 1);
    setStepperDone('stepper-content', contentDone >= 1);
    setStepperDone('stepper-seo', seoCount >= 2);

    // Progress bar
    var totalFields = basicTotal + 1 + 1 + 2; // basic(2) + cover(1) + content(1) + seo(2) = 6
    var totalDone = basicDone + coverDone + contentDone + seoCount;
    var pct = Math.round((totalDone / totalFields) * 100);
    var bar = $('progressBarFill');
    if(bar){
        bar.style.width = pct + '%';
        bar.classList.toggle('complete', pct >= 100);
    }
}

function setSectionDone(id, done){
    var el = $(id);
    if(el) el.classList.toggle('section-done', done);
}
function setStepperDone(key, done){
    // Find stepper step by data-section mapping
    var map = { 'stepper-basic':'sec-basic', 'stepper-cover':'sec-cover', 'stepper-content':'sec-content', 'stepper-seo':'sec-seo' };
    var sectionId = map[key];
    if(!sectionId) return;
    var step = document.querySelector('.stepper-step[data-section="'+sectionId+'"]');
    if(step) step.classList.toggle('done', done);
}

/* ═══════════════════════════════════════════════════════════════════
   SCHEDULE TOGGLES
   ═══════════════════════════════════════════════════════════════════ */
function toggleSchedule(){
    var schedEl = $('scheduleToggle');
    if($('publishNow').checked && schedEl){ schedEl.checked = false; toggleScheduleDate(); }
}
function toggleScheduleDate(){
    var row = $('scheduleRow');
    var checked = $('scheduleToggle').checked;
    row.classList.toggle('show', checked);
    if(checked) $('publishNow').checked = false;
}
var publishNowEl = $('publishNow');
if(publishNowEl) publishNowEl.addEventListener('change', toggleSchedule);
var scheduleToggleEl = $('scheduleToggle');
if(scheduleToggleEl) scheduleToggleEl.addEventListener('change', toggleScheduleDate);

/* ═══════════════════════════════════════════════════════════════════
   SCORE / CHECKLIST / SERP / READABILITY
   ═══════════════════════════════════════════════════════════════════ */
function setQCheck(id, state){
    var el = $(id); if(!el) return;
    var icon = el.querySelector('.q-check-icon');
    icon.className = 'q-check-icon ' + state;
    icon.innerHTML = state === 'done'
        ? '<svg viewBox="0 0 10 10" fill="none"><circle cx="5" cy="5" r="4.5" fill="rgba(5,196,138,0.2)"/><path d="M2.5 5l1.5 1.5 3-3" stroke="var(--green)" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round"/></svg>'
        : '<svg viewBox="0 0 10 10" fill="none"><circle cx="5" cy="5" r="3.5" stroke="var(--text3)" stroke-width="1"/></svg>';
}

function setCLItem(id, state, valText){
    var el = $(id); if(!el) return;
    el.className = 'cl-item ' + state;
    var dot = el.querySelector('.cl-dot');
    dot.className = 'cl-dot ' + state;
    dot.innerHTML = state === 'done'
        ? '<svg viewBox="0 0 10 10" fill="none"><path d="M2 5l2 2 4-4" stroke="var(--green)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>'
        : state === 'warn'
        ? '<svg viewBox="0 0 10 10"><rect x="4.25" y="1.5" width="1.5" height="4" rx=".75" fill="var(--amber)"/><rect x="4.25" y="7" width="1.5" height="1.5" rx=".75" fill="var(--amber)"/></svg>'
        : '<svg viewBox="0 0 10 10" fill="none"><path d="M3 3l4 4M7 3L3 7" stroke="var(--red)" stroke-width="1.5" stroke-linecap="round"/></svg>';
    var v = el.querySelector('.cl-val');
    v.className = 'cl-val ' + state;
    v.textContent = valText;
}

function update(){
    var titleVal     = ($('title')||{value:''}).value;
    var contentVal   = ($('blogContent')||{value:''}).value;
    var excerptVal   = ($('excerpt')||{value:''}).value;
    var metaTitleVal = ($('meta_title')||{value:''}).value;
    var metaDescVal  = ($('meta_description')||{value:''}).value;
    var catEl        = $('category_id');
    var hasCat       = catEl && catEl.value && catEl.value !== '';
    var hasTags      = document.querySelectorAll('#tagContainer .tag-chip input[type=checkbox]:checked').length > 0;
    var imgEl        = $('coverUpload');
    var hasImg       = imgEl && imgEl.files && imgEl.files.length > 0;

    var wc         = wordCount(contentVal);
    var tLen       = titleVal.length;
    var hasTitle   = tLen >= 40 && tLen <= 70;
    var hasWords   = wc >= 300;
    var hasExcerpt = excerptVal.trim().length > 0;
    var hasMeta    = metaDescVal.trim().length > 0;

    /* ── Score ── */
    var score = 0;
    if(hasTitle) score += 20; else if(tLen > 0) score += Math.round(tLen/70*20);
    if(hasWords) score += 30; else score += Math.round(Math.min(wc/300,1)*30);
    if(hasExcerpt) score += 15;
    if(hasImg)     score += 20;
    if(hasMeta)    score += 15;
    score = Math.min(100, Math.round(score));

    var circ = 188.5, offset = circ - (circ * score / 100);
    var ringEl = $('scoreRingFill');
    if(ringEl){
        ringEl.style.strokeDashoffset = offset.toFixed(1);
        ringEl.style.stroke = barColor(score);
    }
    var scoreNum = $('scoreNum');
    if(scoreNum) scoreNum.textContent = score;

    var lbl, sub;
    if(score >= 85){ lbl='Excellent'; sub='Great post — ready to publish!'; }
    else if(score >= 65){ lbl='Good'; sub='Almost there, small tweaks needed'; }
    else if(score >= 40){ lbl='Fair'; sub='Keep going, more content needed'; }
    else if(score > 0){ lbl='Weak'; sub='Fill in more details to improve'; }
    else { lbl='Not started'; sub='Fill in the form to build score'; }
    var sl = $('scoreLabel'); if(sl) sl.textContent = lbl;
    var ss = $('scoreSub'); if(ss) ss.textContent = sub;

    setQCheck('qc-title', hasTitle ? 'done' : 'wait');
    var qtv = $('qc-title-v'); if(qtv) qtv.textContent = tLen + ' chars';
    setQCheck('qc-words', hasWords ? 'done' : 'wait');
    var qwv = $('qc-words-v'); if(qwv) qwv.textContent = wc + ' words';
    setQCheck('qc-excerpt', hasExcerpt ? 'done' : 'wait');
    setQCheck('qc-image', hasImg ? 'done' : 'wait');
    setQCheck('qc-meta', hasMeta ? 'done' : 'wait');

    /* ── Char count + read time ── */
    var cLen = contentVal.length;
    var cc = $('charCount');
    if(cc){
        cc.textContent = cLen.toLocaleString() + ' chars';
        cc.className = 'char-count' + (cLen > 0 && cLen < 50 ? ' warn' : cLen >= 50 ? ' ok' : '');
    }
    var mins = Math.max(1, Math.ceil(wc / 200));
    var rt = $('readTimeText'); if(rt) rt.textContent = mins + ' min';
    var ri = $('readTimeInput'); if(ri) ri.value = mins;

    /* ── Readability ── */
    var rs = readScore(contentVal);
    var rb = $('readBar');
    if(rb){ rb.style.width = rs + '%'; rb.style.background = barColor(rs); }
    var rl = rs >= 80 ? 'Easy to read' : rs >= 60 ? 'Fairly readable' : rs >= 40 ? 'Moderate' : rs > 0 ? 'Difficult' : 'No content';
    var rlbl = $('readLabel'); if(rlbl) rlbl.textContent = rl;
    var rscore = $('readScore'); if(rscore) rscore.textContent = rs > 0 ? rs + '/100' : '\u2014';
    var aw = $('avgWords'); if(aw) aw.textContent = contentVal.trim() ? avgWPS(contentVal) + 'w' : '\u2014';
    var ls = $('longSents'); if(ls) ls.textContent = contentVal.trim() ? longSents(contentVal) : '\u2014';
    var pc = $('paraCount'); if(pc) pc.textContent = contentVal.trim() ? paraCount(contentVal) : '\u2014';

    /* ── SERP ── */
    var dispTitle = metaTitleVal || titleVal;
    var dispDesc  = metaDescVal  || excerptVal;
    var slug      = slugify(titleVal);
    var serpUrl = $('serpUrl'); if(serpUrl) serpUrl.textContent = 'DonateBazaar.com \u203a blog \u203a ' + slug;

    var serpT = $('serpTitle');
    if(serpT){
        if(dispTitle){ serpT.textContent = dispTitle.length > 65 ? dispTitle.slice(0,65)+'\u2026' : dispTitle; serpT.className = 'serp-title'; }
        else { serpT.textContent = 'Your title will appear here'; serpT.className = 'serp-title empty'; }
    }
    var serpD = $('serpDesc');
    if(serpD){
        if(dispDesc){ serpD.textContent = dispDesc.length > 155 ? dispDesc.slice(0,155)+'\u2026' : dispDesc; serpD.className = 'serp-desc'; }
        else { serpD.textContent = 'Your meta description will appear here\u2026'; serpD.className = 'serp-desc empty'; }
    }
    var tBarLen = (metaTitleVal || titleVal).length, dBarLen = metaDescVal.length;
    var tb = $('titleBar');
    if(tb){ tb.style.width = Math.min(100, Math.round(tBarLen/60*100)) + '%'; tb.style.background = titleBarColor(tBarLen); }
    var tbn = $('titleBarNum'); if(tbn) tbn.textContent = tBarLen + '/60';
    var db = $('descBar');
    if(db){ db.style.width = Math.min(100, Math.round(dBarLen/160*100)) + '%'; db.style.background = descBarColor(dBarLen); }
    var dbn = $('descBarNum'); if(dbn) dbn.textContent = dBarLen + '/160';
    var dl = metaDescVal.length, dc = $('metaDescCounter');
    if(dc){ dc.textContent = dl + ' / 160'; dc.className = 'char-inline desc-status' + (dl > 160 ? ' over' : dl >= 120 ? ' ok' : ''); }

    /* ── Title / Excerpt / Meta char counters ── */
    var tc = $('titleCounter');
    if(tc){ tc.textContent = tLen + '/255'; tc.className = 'char-inline' + (tLen > 255 ? ' over' : tLen > 200 ? ' warn' : ''); }
    var ec = $('excerptCounter');
    if(ec){ ec.textContent = excerptVal.length; ec.className = 'char-inline' + (excerptVal.length > 160 ? ' warn' : ''); }
    var mtc = $('metaTitleCounter');
    if(mtc){ mtc.textContent = metaTitleVal.length; mtc.className = 'char-inline' + (metaTitleVal.length > 60 ? ' over' : metaTitleVal.length >= 50 ? ' ok' : ''); }

    /* ── Checklist ── */
    var reqDone = 0;
    if(tLen > 0){ setCLItem('cl-title','done', tLen+' chars'); reqDone++; } else setCLItem('cl-title','fail','Missing');
    if(hasCat){ setCLItem('cl-cat','done','Selected'); reqDone++; } else setCLItem('cl-cat','fail','Missing');
    if(wc >= 100){ setCLItem('cl-content','done', wc+' words'); reqDone++; }
    else if(wc > 0) setCLItem('cl-content','warn', wc+' words');
    else setCLItem('cl-content','fail','0 words');
    if(hasImg){ setCLItem('cl-cover','done','Uploaded'); reqDone++; } else setCLItem('cl-cover','fail','Not set');
    if(hasExcerpt) setCLItem('cl-excerpt','done','Added'); else setCLItem('cl-excerpt','warn','Optional');
    if(hasMeta) setCLItem('cl-seo','done','Added'); else setCLItem('cl-seo','warn','Optional');
    if(hasTags) setCLItem('cl-tags','done','Tagged'); else setCLItem('cl-tags','warn','Optional');
    var rb2 = $('readyBadge');
    if(rb2){
        rb2.textContent = reqDone + ' / 4 done';
        rb2.className = 'ready-badge ' + (reqDone >= 4 ? 'full' : reqDone >= 2 ? 'part' : 'none');
    }

    /* ── Unsaved indicator ── */
    var unsavedDot = document.querySelector('.unsaved-dot');
    if(unsavedDot){
        var hasChanges = tLen > 0 || contentVal.length > 0 || excerptVal.length > 0 || metaTitleVal.length > 0 || metaDescVal.length > 0 || hasCat || hasTags || hasImg;
        unsavedDot.className = 'unsaved-dot' + (hasChanges ? ' dirty' : '');
        var unsavedText = unsavedDot.parentElement.querySelector('.unsaved-text');
        if(unsavedText) unsavedText.textContent = hasChanges ? 'Unsaved changes' : 'All saved';
    }

    /* ── Stepper ── */
    updateStepper();
}

/* ═══════════════════════════════════════════════════════════════════
   INPUT LISTENERS
   ═══════════════════════════════════════════════════════════════════ */
['title','blogContent','excerpt','meta_title','meta_description'].forEach(function(id){
    var el = document.getElementById(id);
    if(el) el.addEventListener('input', update);
});
var catEl = document.getElementById('category_id');
if(catEl) catEl.addEventListener('change', update);

/* ═══════════════════════════════════════════════════════════════════
   TAG CHIPS
   ═══════════════════════════════════════════════════════════════════ */
document.querySelectorAll('#tagContainer .tag-chip').forEach(function(chip){
    chip.addEventListener('click', function(e){
        var cb = this.querySelector('input[type=checkbox]');
        if(cb){
            cb.checked = !cb.checked;
            update();
        }
    });
});

/* ═══════════════════════════════════════════════════════════════════
   COVER IMAGE UPLOAD
   ═══════════════════════════════════════════════════════════════════ */
var upload        = $('coverUpload');
var zone          = $('uploadZone');
var preview       = $('uploadPreview');
var previewWrap   = $('uploadPreviewWrap');
var placeholder   = $('uploadPlaceholder');
var removeBtn     = $('uploadRemove');

if(upload){
    upload.addEventListener('change', function(){
        var file = this.files[0];
        if(!file) return;
        var reader = new FileReader();
        reader.onload = function(e){
            preview.src = e.target.result;
            previewWrap.style.display = 'block';
            placeholder.style.display = 'none';
            zone.classList.add('has-file');
            var upText = $('uploadText');
            if(upText) upText.textContent = file.name;
            update();
        };
        reader.readAsDataURL(file);
    });
    if(removeBtn){
        removeBtn.addEventListener('click', function(e){
            e.stopPropagation();
            upload.value = '';
            previewWrap.style.display = 'none';
            placeholder.style.display = 'flex';
            zone.classList.remove('has-file');
            var upText = $('uploadText');
            if(upText) upText.textContent = 'Click to upload or drag & drop image';
            update();
        });
    }
    zone.addEventListener('dragover', function(e){ e.preventDefault(); zone.style.borderColor = 'var(--a)'; });
    zone.addEventListener('dragleave', function(){ zone.style.borderColor = ''; });
    zone.addEventListener('drop', function(e){
        e.preventDefault(); zone.style.borderColor = '';
        var file = e.dataTransfer.files[0];
        if(file && file.type.startsWith('image/')){
            var dt = new DataTransfer(); dt.items.add(file); upload.files = dt.files;
            upload.dispatchEvent(new Event('change'));
        }
    });
}

/* ═══════════════════════════════════════════════════════════════════
   OG IMAGE UPLOAD
   ═══════════════════════════════════════════════════════════════════ */
var ogUpload  = $('ogUpload');
var ogZone    = $('ogZone');
var ogPreview = $('ogPreview');
var ogText    = $('ogText');

if(ogUpload){
    ogUpload.addEventListener('change', function(){
        var file = this.files[0]; if(!file) return;
        var reader = new FileReader();
        reader.onload = function(e){
            ogPreview.src = e.target.result;
            ogPreview.style.display = 'block';
            if(ogText) ogText.textContent = file.name;
            if(ogZone) ogZone.classList.add('has-file');
        };
        reader.readAsDataURL(file);
    });
    if(ogZone){
        ogZone.addEventListener('dragover', function(e){ e.preventDefault(); ogZone.style.borderColor = 'var(--a)'; });
        ogZone.addEventListener('dragleave', function(){ ogZone.style.borderColor = ''; });
        ogZone.addEventListener('drop', function(e){
            e.preventDefault(); ogZone.style.borderColor = '';
            var file = e.dataTransfer.files[0];
            if(file && file.type.startsWith('image/')){
                var dt = new DataTransfer(); dt.items.add(file); ogUpload.files = dt.files;
                ogUpload.dispatchEvent(new Event('change'));
            }
        });
    }
}

/* ═══════════════════════════════════════════════════════════════════
   EDITOR TOOLBAR
   ═══════════════════════════════════════════════════════════════════ */
var toolbar  = $('editorToolbar');
var textarea = $('blogContent');
if(toolbar && textarea){
    toolbar.addEventListener('click', function(e){
        var btn = e.target.closest('.tb-btn');
        if(!btn) return;
        var cmd = btn.dataset.cmd;
        if(cmd === 'preview') return;
        e.preventDefault();
        var start    = textarea.selectionStart;
        var end      = textarea.selectionEnd;
        var text     = textarea.value;
        var selected = text.substring(start, end);
        var before   = text.substring(0, start);
        var after    = text.substring(end);
        var wrap;
        switch(cmd){
            case 'bold':      wrap = ['**', '**']; break;
            case 'italic':    wrap = ['*', '*']; break;
            case 'underline': wrap = ['<u>', '</u>']; break;
            case 'heading':   wrap = ['\n## ', '\n']; selected = selected || 'Heading'; break;
            case 'bullet':    wrap = ['\n- ', '']; selected = selected || 'List item'; break;
            case 'link':
                var url = prompt('Enter URL:', 'https://');
                if(!url) return;
                wrap = ['[', ']('+url+')']; selected = selected || 'link text';
                break;
        }
        if(wrap){
            var insertion = (cmd === 'heading' || cmd === 'bullet') && start === 0 ? wrap[0].trim() : wrap[0] + selected + wrap[1];
            textarea.value = before + insertion + after;
            var pos = start + insertion.length;
            textarea.setSelectionRange(pos, pos);
            textarea.focus();
            update();
        }
    });

    // Keyboard shortcuts
    textarea.addEventListener('keydown', function(e){
        if((e.ctrlKey || e.metaKey) && !e.shiftKey && e.key === 'b'){
            e.preventDefault();
            var btn = toolbar.querySelector('[data-cmd="bold"]');
            if(btn) btn.click();
        }
        if((e.ctrlKey || e.metaKey) && !e.shiftKey && e.key === 'i'){
            e.preventDefault();
            var btn = toolbar.querySelector('[data-cmd="italic"]');
            if(btn) btn.click();
        }
        if((e.ctrlKey || e.metaKey) && !e.shiftKey && e.key === 'u'){
            e.preventDefault();
            var btn = toolbar.querySelector('[data-cmd="underline"]');
            if(btn) btn.click();
        }
    });
}

/* ═══════════════════════════════════════════════════════════════════
   SLUG AUTO-GENERATE
   ═══════════════════════════════════════════════════════════════════ */
var slugField  = $('slug');
var titleField = $('title');
var slugEdited = slugField && slugField.value !== '';
if(slugField && titleField){
    slugField.addEventListener('input', function(){ slugEdited = this.value !== ''; });
    titleField.addEventListener('input', function(){ if(!slugEdited) slugField.value = slugify(this.value); });
}

/* ═══════════════════════════════════════════════════════════════════
   ALERT CLOSE
   ═══════════════════════════════════════════════════════════════════ */
document.addEventListener('click', function(e){
    var btn = e.target.closest('[data-action="alert-close"]');
    if(btn) btn.closest('.alert').remove();
});

/* ═══════════════════════════════════════════════════════════════════
   AUTO-EXPAND SECTIONS WITH ERRORS
   ═══════════════════════════════════════════════════════════════════ */
document.querySelectorAll('.form-section').forEach(function(sec){
    if(sec.querySelector('.is-error')){
        sec.classList.add('is-open');
    }
});

/* ═══════════════════════════════════════════════════════════════════
   INIT
   ═══════════════════════════════════════════════════════════════════ */
update();

})();
