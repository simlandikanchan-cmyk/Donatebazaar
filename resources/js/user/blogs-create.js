(function(){
'use strict';

function $(id){ return document.getElementById(id); }

function wordCount(text){
    return text.trim() === '' ? 0 : text.trim().split(/\s+/).length;
}
function sentences(text){
    return text.split(/[.!?]+/).filter(function(s){ return s.trim().split(/\s+/).length > 2; });
}
function avgWPS(text){
    var s = sentences(text);
    if(!s.length) return 0;
    return Math.round(s.reduce(function(a,b){ return a + b.trim().split(/\s+/).length; },0) / s.length);
}
function longSents(text){
    return sentences(text).filter(function(s){ return s.trim().split(/\s+/).length > 20; }).length;
}
function paraCount(text){
    return text.split(/\n\s*\n/).filter(function(p){ return p.trim().length > 0; }).length || (text.trim().length > 0 ? 1 : 0);
}
function readScore(text){
    if(wordCount(text) < 10) return 0;
    var avg = avgWPS(text);
    if(avg <= 12) return 95; if(avg <= 15) return 82; if(avg <= 20) return 65;
    if(avg <= 25) return 45; return 25;
}
function slugify(t){
    return t.toLowerCase().replace(/[^a-z0-9\s]/g,'').trim().replace(/\s+/g,'-').slice(0,45) || 'your-title';
}
function barColor(pct){
    if(pct >= 70) return 'var(--green)'; if(pct >= 40) return 'var(--yellow)'; return 'var(--red)';
}
function titleBarColor(len){
    if(len >= 40 && len <= 60) return 'var(--green)';
    if(len > 60 && len <= 70) return 'var(--yellow)';
    if(len > 70) return 'var(--red)';
    return 'var(--border2)';
}
function descBarColor(len){
    if(len >= 120 && len <= 160) return 'var(--green)';
    if(len > 160) return 'var(--red)';
    if(len >= 50) return 'var(--yellow)';
    return 'var(--border2)';
}

function setQCheck(id, state){
    var el = $(id);
    var icon = el.querySelector('.q-check-icon');
    icon.className = 'q-check-icon ' + state;
    if(state === 'done'){
        icon.innerHTML = '<svg viewBox="0 0 10 10" fill="none"><circle cx="5" cy="5" r="4.5" fill="rgba(16,185,129,0.2)"/><path d="M2.5 5l1.5 1.5 3-3" stroke="var(--green)" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round"/></svg>';
    } else {
        icon.innerHTML = '<svg viewBox="0 0 10 10" fill="none"><circle cx="5" cy="5" r="3.5" stroke="var(--text3)" stroke-width="1"/></svg>';
    }
}

function setCLItem(id, state, valText){
    var el = $(id);
    el.className = 'cl-item ' + state;
    var dot = el.querySelector('.cl-dot');
    dot.className = 'cl-dot ' + state;
    if(state === 'done'){
        dot.innerHTML = '<svg viewBox="0 0 10 10" fill="none"><path d="M2 5l2 2 4-4" stroke="var(--green)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>';
    } else if(state === 'warn'){
        dot.innerHTML = '<svg viewBox="0 0 10 10"><rect x="4.25" y="1.5" width="1.5" height="4" rx=".75" fill="var(--yellow)"/><rect x="4.25" y="7" width="1.5" height="1.5" rx=".75" fill="var(--yellow)"/></svg>';
    } else {
        dot.innerHTML = '<svg viewBox="0 0 10 10" fill="none"><path d="M3 3l4 4M7 3L3 7" stroke="var(--red)" stroke-width="1.5" stroke-linecap="round"/></svg>';
    }
    var vEl = el.querySelector('.cl-val');
    vEl.className = 'cl-val ' + state;
    vEl.textContent = valText;
}

function update(){
    var titleVal   = ($('title') || {value:''}).value;
    var contentVal = ($('blogContent') || {value:''}).value;
    var excerptVal = ($('excerpt') || {value:''}).value;
    var metaTitleVal = ($('meta_title') || {value:''}).value;
    var metaDescVal  = ($('meta_description') || {value:''}).value;
    var catEl      = $('category_id');
    var hasCat     = catEl && catEl.value && catEl.value !== '';
    var tagEl      = $('tag_ids');
    var hasTags    = tagEl && [].some.call(tagEl.querySelectorAll('input[type=checkbox]'), function(o){ return o.checked; });
    var imgEl      = $('coverUpload');
    var hasImg     = imgEl && imgEl.files && imgEl.files.length > 0;

    var wc       = wordCount(contentVal);
    var tLen     = titleVal.length;
    var hasWords = wc >= 300;
    var hasTitle = tLen >= 40 && tLen <= 70;
    var hasExcerpt = excerptVal.trim().length > 0;
    var hasMeta    = metaDescVal.trim().length > 0;

    var score = 0;
    if(hasTitle) score += 20; else if(tLen > 0) score += Math.round(tLen/70*20);
    if(hasWords) score += 30; else score += Math.round(Math.min(wc/300,1)*30);
    if(hasExcerpt) score += 15;
    if(hasImg)     score += 20;
    if(hasMeta)    score += 15;
    score = Math.min(100, Math.round(score));

    var circ   = 188.5;
    var offset = circ - (circ * score / 100);
    var ringEl = $('scoreRingFill');
    ringEl.style.strokeDashoffset = offset.toFixed(1);
    ringEl.style.stroke = barColor(score);
    $('scoreNum').textContent = score;

    var lbl, sub;
    if(score >= 85){ lbl='Excellent'; sub='Great blog — ready to publish!'; }
    else if(score >= 65){ lbl='Good'; sub='Almost there, a few tweaks needed'; }
    else if(score >= 40){ lbl='Fair'; sub='Keep going, more content needed'; }
    else if(score > 0){ lbl='Weak'; sub='Fill in more details to improve'; }
    else { lbl='Not started'; sub='Fill in the form to build your score'; }
    $('scoreLabel').textContent = lbl;
    $('scoreSub').textContent   = sub;

    setQCheck('qc-title',   hasTitle ? 'done' : 'wait');
    $('qc-title-v').textContent = tLen + ' chars';
    setQCheck('qc-words',   hasWords ? 'done' : 'wait');
    $('qc-words-v').textContent = wc + ' words';
    setQCheck('qc-excerpt', hasExcerpt ? 'done' : 'wait');
    setQCheck('qc-image',   hasImg ? 'done' : 'wait');
    setQCheck('qc-meta',    hasMeta ? 'done' : 'wait');

    var cLen = contentVal.length;
    $('charCount').textContent = cLen.toLocaleString() + ' chars';
    $('charCount').className = 'char-count' + (cLen > 0 && cLen < 50 ? ' warn' : cLen >= 50 ? ' ok' : '');

    var mins = Math.max(1, Math.ceil(wc / 200));
    $('readTimeText').textContent = mins + ' min';
    $('readTimeInput').value = mins;

    var rs = readScore(contentVal);
    $('readBar').style.width = rs + '%';
    $('readBar').style.background = barColor(rs);
    var rl;
    if(rs >= 80) rl='Easy to read'; else if(rs >= 60) rl='Fairly readable'; else if(rs >= 40) rl='Moderate'; else if(rs > 0) rl='Difficult'; else rl='No content';
    $('readLabel').textContent = rl;
    $('readScore').textContent = rs > 0 ? rs + '/100' : '\u2014';
    $('avgWords').textContent  = contentVal.trim() ? avgWPS(contentVal) + 'w' : '\u2014';
    $('longSents').textContent = contentVal.trim() ? longSents(contentVal) : '\u2014';
    $('paraCount').textContent = contentVal.trim() ? paraCount(contentVal) : '\u2014';

    var dispTitle = metaTitleVal || titleVal;
    var dispDesc  = metaDescVal  || excerptVal;
    var slug      = slugify(titleVal);
    $('serpUrl').textContent = 'DonateBazaar.com › blog › ' + slug;

    var serpT = $('serpTitle');
    if(dispTitle){
        serpT.textContent = dispTitle.length > 65 ? dispTitle.slice(0,65)+'…' : dispTitle;
        serpT.className = 'serp-title';
    } else {
        serpT.textContent = 'Your title will appear here';
        serpT.className = 'serp-title empty';
    }
    var serpD = $('serpDesc');
    if(dispDesc){
        serpD.textContent = dispDesc.length > 155 ? dispDesc.slice(0,155)+'…' : dispDesc;
        serpD.className = 'serp-desc';
    } else {
        serpD.textContent = 'Your meta description will appear here\u2026';
        serpD.className = 'serp-desc empty';
    }

    var tBarLen = (metaTitleVal || titleVal).length;
    var dBarLen = metaDescVal.length;
    $('titleBar').style.width = Math.min(100, Math.round(tBarLen/60*100)) + '%';
    $('titleBar').style.background = titleBarColor(tBarLen);
    $('titleBarNum').textContent = tBarLen + '/60';
    $('descBar').style.width = Math.min(100, Math.round(dBarLen/160*100)) + '%';
    $('descBar').style.background = descBarColor(dBarLen);
    $('descBarNum').textContent = dBarLen + '/160';

    var reqDone = 0;
    if(tLen > 0){ setCLItem('cl-title','done', tLen+' chars'); reqDone++; }
    else setCLItem('cl-title','fail','Missing');

    if(hasCat){ setCLItem('cl-cat','done','Selected'); reqDone++; }
    else setCLItem('cl-cat','fail','Missing');

    if(wc >= 100){ setCLItem('cl-content','done', wc+' words'); reqDone++; }
    else if(wc > 0) setCLItem('cl-content','warn', wc+' words');
    else setCLItem('cl-content','fail','0 words');

    if(hasImg){ setCLItem('cl-cover','done','Uploaded'); reqDone++; }
    else setCLItem('cl-cover','fail','Not set');

    if(hasExcerpt) setCLItem('cl-excerpt','done','Added');
    else setCLItem('cl-excerpt','warn','Optional');

    if(hasTags) setCLItem('cl-tags','done','Tagged');
    else setCLItem('cl-tags','warn','Optional');

    var rb = $('readyBadge');
    rb.textContent = reqDone + ' / 4 done';
    rb.className   = 'ready-badge ' + (reqDone >= 4 ? 'full' : reqDone >= 2 ? 'part' : 'none');

    var dl = metaDescVal.length;
    var dc = $('metaDescCounter');
    dc.textContent = dl + ' / 160';
    dc.className = 'char-inline desc-status' + (dl > 160 ? ' over' : dl >= 120 ? ' ok' : '');

    var tc = $('titleCounter');
    tc.textContent = tLen + '/255';
    tc.className = 'char-inline' + (tLen > 255 ? ' over' : tLen > 200 ? ' warn' : '');

    var ec = $('excerptCounter');
    ec.textContent = excerptVal.length;
    ec.className = 'char-inline' + (excerptVal.length > 160 ? ' warn' : '');

    var mtc = $('metaTitleCounter');
    mtc.textContent = metaTitleVal.length;
    mtc.className = 'char-inline' + (metaTitleVal.length > 60 ? ' over' : metaTitleVal.length >= 50 ? ' ok' : '');

    var unsavedDot = document.querySelector('.unsaved-dot');
    if(unsavedDot) {
        var hasChanges = tLen > 0 || contentVal.length > 0 || excerptVal.length > 0 || metaTitleVal.length > 0 || metaDescVal.length > 0 || hasCat || hasTags || hasImg;
        unsavedDot.className = 'unsaved-dot' + (hasChanges ? ' dirty' : '');
        unsavedDot.parentElement.querySelector('.unsaved-text').textContent = hasChanges ? 'Unsaved changes' : 'All saved';
    }
}

['title','blogContent','excerpt','meta_title','meta_description'].forEach(function(id){
    var el = document.getElementById(id);
    if(el) el.addEventListener('input', update);
});
var catEl = document.getElementById('category_id');
if(catEl) catEl.addEventListener('change', update);

document.querySelectorAll('#tagContainer input[type=checkbox]').forEach(function(cb){
    cb.addEventListener('change', update);
});

var upload  = document.getElementById('coverUpload');
var zone    = document.getElementById('uploadZone');
var preview = document.getElementById('uploadPreview');
var previewWrap = document.getElementById('uploadPreviewWrap');
var placeholder = document.getElementById('uploadPlaceholder');
var removeBtn   = document.getElementById('uploadRemove');

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
            update();
        });
    }

    zone.addEventListener('dragover', function(e){ e.preventDefault(); zone.style.borderColor='var(--accent)'; });
    zone.addEventListener('dragleave', function(){ zone.style.borderColor=''; });
    zone.addEventListener('drop', function(e){
        e.preventDefault(); zone.style.borderColor='';
        var file = e.dataTransfer.files[0];
        if(file && file.type.startsWith('image/')){
            var dt = new DataTransfer(); dt.items.add(file); upload.files = dt.files;
            upload.dispatchEvent(new Event('change'));
        }
    });
}

// Editor toolbar
var toolbar = document.getElementById('editorToolbar');
var textarea = document.getElementById('blogContent');
if(toolbar && textarea){
    toolbar.addEventListener('click', function(e){
        var btn = e.target.closest('.tb-btn');
        if(!btn) return;
        var cmd = btn.dataset.cmd;
        if(cmd === 'preview') return;
        e.preventDefault();
        var start = textarea.selectionStart;
        var end = textarea.selectionEnd;
        var text = textarea.value;
        var selected = text.substring(start, end);
        var before = text.substring(0, start);
        var after = text.substring(end);
        var wrap;
        switch(cmd){
            case 'bold': wrap = ['**', '**']; break;
            case 'italic': wrap = ['*', '*']; break;
            case 'underline': wrap = ['<u>', '</u>']; break;
            case 'heading': wrap = ['\n## ', '\n']; selected = selected || 'Heading'; break;
            case 'bullet': wrap = ['\n- ', '']; selected = selected || 'List item'; break;
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
}

update();

document.addEventListener('click', function(e){
    var btn = e.target.closest('[data-action="alert-close"]');
    if (btn) btn.parentElement.remove();
});

})();
