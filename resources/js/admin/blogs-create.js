/* ═══════════════════════════════════════════════════════════════════
   Admin Blogs Create page — moved from admin/blogs/create.blade.php
   inline <script>. window.* bridges converted to internal functions;
   onchange attributes on #publishNow / #scheduleToggle replaced by direct
   change listeners in this module; all logic preserved.
   ═══════════════════════════════════════════════════════════════════ */

(function(){
'use strict';

/* ── SCHEDULE TOGGLES ── */
function toggleSchedule(){
  var schedEl=document.getElementById('scheduleToggle');
  if(document.getElementById('publishNow').checked&&schedEl){schedEl.checked=false;toggleScheduleDate();}
}
function toggleScheduleDate(){
  var row=document.getElementById('scheduleRow');
  var checked=document.getElementById('scheduleToggle').checked;
  row.classList.toggle('show',checked);
  if(checked) document.getElementById('publishNow').checked=false;
}

/* ── direct listeners (onchange attributes removed from markup) ── */
var publishNowEl=document.getElementById('publishNow');
if(publishNowEl)publishNowEl.addEventListener('change',toggleSchedule);
var scheduleToggleEl=document.getElementById('scheduleToggle');
if(scheduleToggleEl)scheduleToggleEl.addEventListener('change',toggleScheduleDate);

/* ── HELPERS ── */
function $$(id){return document.getElementById(id);}
function wordCount(t){return t.trim()===''?0:t.trim().split(/\s+/).length;}
function sentences(t){return t.split(/[.!?]+/).filter(function(s){return s.trim().split(/\s+/).length>2;});}
function avgWPS(t){var s=sentences(t);if(!s.length)return 0;return Math.round(s.reduce(function(a,b){return a+b.trim().split(/\s+/).length;},0)/s.length);}
function longSents(t){return sentences(t).filter(function(s){return s.trim().split(/\s+/).length>20;}).length;}
function paraCount(t){return t.split(/\n\s*\n/).filter(function(p){return p.trim().length>0;}).length||(t.trim().length>0?1:0);}
function readScore(t){if(wordCount(t)<10)return 0;var avg=avgWPS(t);if(avg<=12)return 95;if(avg<=15)return 82;if(avg<=20)return 65;if(avg<=25)return 45;return 25;}
function slugify(t){return t.toLowerCase().replace(/[^a-z0-9\s]/g,'').trim().replace(/\s+/g,'-').slice(0,50)||'your-title';}
function barColor(p){return p>=70?'var(--green)':p>=40?'var(--amber)':'var(--red)';}
function titleBarColor(l){if(l>=40&&l<=60)return'var(--green)';if(l>60&&l<=70)return'var(--amber)';if(l>70)return'var(--red)';return'var(--border2)';}
function descBarColor(l){if(l>=120&&l<=160)return'var(--green)';if(l>160)return'var(--red)';if(l>=50)return'var(--amber)';return'var(--border2)';}

function setQCheck(id,state){
  var el=document.getElementById(id);if(!el)return;
  var icon=el.querySelector('.q-check-icon');
  icon.className='q-check-icon '+state;
  icon.innerHTML=state==='done'
    ?'<svg viewBox="0 0 10 10" fill="none"><circle cx="5" cy="5" r="4.5" fill="rgba(5,196,138,0.2)"/><path d="M2.5 5l1.5 1.5 3-3" stroke="var(--green)" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round"/></svg>'
    :'<svg viewBox="0 0 10 10" fill="none"><circle cx="5" cy="5" r="3.5" stroke="var(--text3)" stroke-width="1"/></svg>';
}

function setCLItem(id,state,valText){
  var el=$$(id);if(!el)return;
  el.className='cl-item '+state;
  var dot=el.querySelector('.cl-dot');
  dot.className='cl-dot '+state;
  dot.innerHTML=state==='done'
    ?'<svg viewBox="0 0 10 10" fill="none"><path d="M2 5l2 2 4-4" stroke="var(--green)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>'
    :state==='warn'
    ?'<svg viewBox="0 0 10 10"><rect x="4.25" y="1.5" width="1.5" height="4" rx=".75" fill="var(--amber)"/><rect x="4.25" y="7" width="1.5" height="1.5" rx=".75" fill="var(--amber)"/></svg>'
    :'<svg viewBox="0 0 10 10" fill="none"><path d="M3 3l4 4M7 3L3 7" stroke="var(--red)" stroke-width="1.5" stroke-linecap="round"/></svg>';
  var v=el.querySelector('.cl-val');
  v.className='cl-val '+state;
  v.textContent=valText;
}

function update(){
  var titleVal=($$('title')||{value:''}).value;
  var contentVal=($$('blogContent')||{value:''}).value;
  var excerptVal=($$('excerpt')||{value:''}).value;
  var metaTitleVal=($$('meta_title')||{value:''}).value;
  var metaDescVal=($$('meta_description')||{value:''}).value;
  var catEl=$$('category_id');var hasCat=catEl&&catEl.value&&catEl.value!=='';
  var tagEl=$$('tag_ids');var hasTags=tagEl&&[].some.call(tagEl.options,function(o){return o.selected;});
  var imgEl=$$('coverUpload');var hasImg=imgEl&&imgEl.files&&imgEl.files.length>0;
  var wc=wordCount(contentVal);
  var tLen=titleVal.length;
  var hasTitle=tLen>=40&&tLen<=70;
  var hasWords=wc>=300;
  var hasExcerpt=excerptVal.trim().length>0;
  var hasMeta=metaDescVal.trim().length>0;

  /* Score */
  var score=0;
  if(hasTitle)score+=20;else if(tLen>0)score+=Math.round(tLen/70*20);
  if(hasWords)score+=30;else score+=Math.round(Math.min(wc/300,1)*30);
  if(hasExcerpt)score+=15;if(hasImg)score+=20;if(hasMeta)score+=15;
  score=Math.min(100,Math.round(score));
  var circ=188.5,offset=circ-(circ*score/100);
  var ringEl=$$('scoreRingFill');
  ringEl.style.strokeDashoffset=offset.toFixed(1);
  ringEl.style.stroke=barColor(score);
  $$('scoreNum').textContent=score;
  var lbl,sub;
  if(score>=85){lbl='Excellent';sub='Great post — ready to publish!';}
  else if(score>=65){lbl='Good';sub='Almost there, small tweaks needed';}
  else if(score>=40){lbl='Fair';sub='Keep going, more content needed';}
  else if(score>0){lbl='Weak';sub='Fill in more details to improve';}
  else{lbl='Not started';sub='Fill in the form to build score';}
  $$('scoreLabel').textContent=lbl;$$('scoreSub').textContent=sub;
  setQCheck('qc-title',hasTitle?'done':'wait');$$('qc-title-v').textContent=tLen+' chars';
  setQCheck('qc-words',hasWords?'done':'wait');$$('qc-words-v').textContent=wc+' words';
  setQCheck('qc-excerpt',hasExcerpt?'done':'wait');
  setQCheck('qc-image',hasImg?'done':'wait');
  setQCheck('qc-meta',hasMeta?'done':'wait');

  /* Char count + read time */
  var cLen=contentVal.length;
  $$('charCount').textContent=cLen.toLocaleString()+' chars';
  $$('charCount').className='char-count'+(cLen>0&&cLen<50?' warn':cLen>=50?' ok':'');
  var mins=Math.max(1,Math.ceil(wc/200));
  $$('readTimeText').textContent=mins+' min';
  $$('readTimeInput').value=mins;

  /* Readability */
  var rs=readScore(contentVal);
  $$('readBar').style.width=rs+'%';$$('readBar').style.background=barColor(rs);
  var rl=rs>=80?'Easy to read':rs>=60?'Fairly readable':rs>=40?'Moderate':rs>0?'Difficult':'No content';
  $$('readLabel').textContent=rl;$$('readScore').textContent=rs>0?rs+'/100':'—';
  $$('avgWords').textContent=contentVal.trim()?avgWPS(contentVal)+'w':'—';
  $$('longSents').textContent=contentVal.trim()?longSents(contentVal):'—';
  $$('paraCount').textContent=contentVal.trim()?paraCount(contentVal):'—';

  /* SERP */
  var dispTitle=metaTitleVal||titleVal;
  var dispDesc=metaDescVal||excerptVal;
  var slug=slugify(titleVal);
  $$('serpUrl').textContent='DonateBazaar.com › blog › '+slug;
  var serpT=$$('serpTitle');
  if(dispTitle){serpT.textContent=dispTitle.length>65?dispTitle.slice(0,65)+'…':dispTitle;serpT.className='serp-title';}
  else{serpT.textContent='Your title will appear here';serpT.className='serp-title empty';}
  var serpD=$$('serpDesc');
  if(dispDesc){serpD.textContent=dispDesc.length>155?dispDesc.slice(0,155)+'…':dispDesc;serpD.className='serp-desc';}
  else{serpD.textContent='Your meta description will appear here…';serpD.className='serp-desc empty';}
  var tBarLen=(metaTitleVal||titleVal).length,dBarLen=metaDescVal.length;
  $$('titleBar').style.width=Math.min(100,Math.round(tBarLen/60*100))+'%';
  $$('titleBar').style.background=titleBarColor(tBarLen);
  $$('titleBarNum').textContent=tBarLen+'/60';
  $$('descBar').style.width=Math.min(100,Math.round(dBarLen/160*100))+'%';
  $$('descBar').style.background=descBarColor(dBarLen);
  $$('descBarNum').textContent=dBarLen+'/160';
  var dl=metaDescVal.length,dc=$$('descCount');
  if(dc){dc.textContent=dl+' / 160';dc.className='desc-count'+(dl>160?' over':dl>=120?' great':'');}

  /* Checklist */
  var reqDone=0;
  if(tLen>0){setCLItem('cl-title','done',tLen+' chars');reqDone++;}else setCLItem('cl-title','fail','Missing');
  if(hasCat){setCLItem('cl-cat','done','Selected');reqDone++;}else setCLItem('cl-cat','fail','Missing');
  if(wc>=100){setCLItem('cl-content','done',wc+' words');reqDone++;}else if(wc>0)setCLItem('cl-content','warn',wc+' words');else setCLItem('cl-content','fail','0 words');
  if(hasImg){setCLItem('cl-cover','done','Uploaded');reqDone++;}else setCLItem('cl-cover','fail','Not set');
  if(hasExcerpt)setCLItem('cl-excerpt','done','Added');else setCLItem('cl-excerpt','warn','Optional');
  if(hasMeta)setCLItem('cl-seo','done','Added');else setCLItem('cl-seo','warn','Optional');
  if(hasTags)setCLItem('cl-tags','done','Tagged');else setCLItem('cl-tags','warn','Optional');
  var rb=$$('readyBadge');
  rb.textContent=reqDone+' / 4 done';
  rb.className='ready-badge '+(reqDone>=4?'full':reqDone>=2?'part':'none');
}

['title','blogContent','excerpt','meta_title','meta_description'].forEach(function(id){
  var el=document.getElementById(id);if(el)el.addEventListener('input',update);
});
['category_id','tag_ids'].forEach(function(id){
  var el=document.getElementById(id);if(el)el.addEventListener('change',update);
});

function setupUpload(inputId,zoneId,previewId,textId){
  var upload=document.getElementById(inputId);
  var zone=document.getElementById(zoneId);
  var preview=document.getElementById(previewId);
  var upText=document.getElementById(textId);
  if(!upload)return;
  upload.addEventListener('change',function(){
    var file=this.files[0];if(!file)return;
    var reader=new FileReader();
    reader.onload=function(e){preview.src=e.target.result;preview.style.display='block';if(upText)upText.textContent=file.name;if(zone)zone.classList.add('has-file');update();};
    reader.readAsDataURL(file);
  });
  if(zone){
    zone.addEventListener('dragover',function(e){e.preventDefault();zone.style.borderColor='var(--a)';});
    zone.addEventListener('dragleave',function(){zone.style.borderColor='';});
    zone.addEventListener('drop',function(e){
      e.preventDefault();zone.style.borderColor='';
      var file=e.dataTransfer.files[0];
      if(file&&file.type.startsWith('image/')){var dt=new DataTransfer();dt.items.add(file);upload.files=dt.files;upload.dispatchEvent(new Event('change'));}
    });
  }
}
setupUpload('coverUpload','uploadZone','uploadPreview','uploadText');
setupUpload('ogUpload','ogZone','ogPreview','ogText');

var slugField=document.getElementById('slug');
var titleField=document.getElementById('title');
var slugEdited=slugField&&slugField.value!=='';
if(slugField&&titleField){
  slugField.addEventListener('input',function(){slugEdited=this.value!=='';});
  titleField.addEventListener('input',function(){if(!slugEdited)slugField.value=slugify(this.value);});
}

update();
})();