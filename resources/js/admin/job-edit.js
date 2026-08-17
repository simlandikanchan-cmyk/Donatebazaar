/* ═══════════════════════════════════════════════════════════════════
   Admin Job Post Edit page — moved from admin/job_posts/edit.blade.php
   inline <script>. open/close modal handlers converted to data-action
   delegation; all other logic preserved verbatim.
   ═══════════════════════════════════════════════════════════════════ */

import { escapeHtml } from '../shared/helpers.js';

(function(){
'use strict';

/* Delete Modal */
var deleteModalEl=document.getElementById('deleteModal');
function openDeleteModal(){deleteModalEl.classList.add('open');}
function closeDeleteModal(){deleteModalEl.classList.remove('open');}
deleteModalEl.addEventListener('click',function(e){if(e.target===this)closeDeleteModal();});

/* Discard confirm */
var discardModalEl=document.getElementById('discardModal');
function openDiscardModal(){discardModalEl.classList.add('open');}
function closeDiscardModal(){discardModalEl.classList.remove('open');}
discardModalEl.addEventListener('click',function(e){if(e.target===this)closeDiscardModal();});

document.addEventListener('keydown',function(e){
  if(e.key==='Escape'){closeDeleteModal();closeDiscardModal();}
});

/* delegated actions */
document.addEventListener('click',function(e){
  var el=e.target.closest('[data-action]');
  if(!el)return;
  var action=el.getAttribute('data-action');
  if(action==='open-delete')openDeleteModal();
  else if(action==='close-delete')closeDeleteModal();
  else if(action==='open-discard')openDiscardModal();
  else if(action==='close-discard')closeDiscardModal();
});

/* Slug — starts Manual on edit to protect existing URLs */
var titleInp=document.getElementById('title');
var slugInp=document.getElementById('slug');
var slugDisplay=document.getElementById('slugDisplay');
var slugLockBtn=document.getElementById('slugLockBtn');
var slugAuto=false;

function toSlug(str){return str.toLowerCase().replace(/[^a-z0-9\s-]/g,'').trim().replace(/\s+/g,'-').replace(/-+/g,'-').slice(0,255);}
function refreshSlugDisplay(){slugDisplay.textContent=slugInp.value||'your-job-slug-here';}

slugLockBtn.addEventListener('click',function(){
  slugAuto=!slugAuto;
  if(slugAuto){slugInp.value=toSlug(titleInp.value);this.textContent='Auto';this.style.color='';this.style.borderColor='';}
  else{this.textContent='Manual';this.style.color='var(--amber)';this.style.borderColor='var(--amber)';}
  refreshSlugDisplay();
});
titleInp.addEventListener('input',function(){if(slugAuto){slugInp.value=toSlug(this.value);refreshSlugDisplay();}});
slugInp.addEventListener('input',function(){slugAuto=false;slugLockBtn.textContent='Manual';slugLockBtn.style.color='var(--amber)';slugLockBtn.style.borderColor='var(--amber)';refreshSlugDisplay();});
refreshSlugDisplay();

/* Remote toggle */
var remoteToggle=document.getElementById('is_remote');
var remoteToggleRow=document.getElementById('remoteToggleRow');
function updateRemoteRow(){remoteToggleRow.classList.toggle('active-toggle',remoteToggle.checked);}
remoteToggle.addEventListener('change',updateRemoteRow);
updateRemoteRow();

/* Featured toggle */
var featuredToggle=document.getElementById('featured'),featuredRow=document.getElementById('featuredToggleRow');
function updateFeaturedRow(){featuredRow.classList.toggle('active-toggle',featuredToggle.checked);}
featuredToggle.addEventListener('change',function(){updateFeaturedRow();markDirty();});
updateFeaturedRow();

/* Skills preview */
var skillsInp=document.getElementById('skills'),skillPreview=document.getElementById('skillPreview');
function renderSkills(){
  var arr=skillsInp.value.split(',').map(function(s){return s.trim();}).filter(Boolean);
  skillPreview.innerHTML='';
  arr.forEach(function(s,i){
    var span=document.createElement('span');span.className='skill-tag-prev';span.textContent=s;
    var x=document.createElementNS('http://www.w3.org/2000/svg','svg');
    x.setAttribute('viewBox','0 0 24 24');x.setAttribute('fill','none');x.setAttribute('stroke','currentColor');x.setAttribute('stroke-width','2');
    x.innerHTML='<path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>';
    x.addEventListener('click',function(){removeSkill(i);});
    span.appendChild(x);skillPreview.appendChild(span);
  });
}
function removeSkill(i){var arr=skillsInp.value.split(',').map(function(s){return s.trim();}).filter(Boolean);arr.splice(i,1);skillsInp.value=arr.join(', ');renderSkills();markDirty();}
skillsInp.addEventListener('input',function(){renderSkills();markDirty();});
renderSkills();

/* SEO counters */
var metaTitleInp=document.getElementById('meta_title'),metaTitleCounter=document.getElementById('metaTitleCounter'),metaDescInp=document.getElementById('meta_description'),metaDescCounter=document.getElementById('metaDescCounter');
metaTitleInp.addEventListener('input',function(){var len=this.value.length;metaTitleCounter.textContent=len+' / 255';metaTitleCounter.className='char-counter'+(len>230?(len>=255?' over':' warn'):'');});
metaDescInp.addEventListener('input',function(){var len=this.value.length;metaDescCounter.textContent=len+' / 500';metaDescCounter.className='char-counter'+(len>460?(len>=500?' over':' warn'):'');});

/* Live Preview */
var typeInp=document.getElementById('type'),locationInp=document.getElementById('location'),salaryInp=document.getElementById('salary'),deadlineInp=document.getElementById('application_deadline'),descInp=document.getElementById('description');
var prevTitle=document.getElementById('prevTitle'),prevTypeEl=document.getElementById('prevType'),prevTypeVal=document.getElementById('prevTypeVal'),prevLocEl=document.getElementById('prevLoc'),prevLocVal=document.getElementById('prevLocVal'),prevSalEl=document.getElementById('prevSal'),prevSalVal=document.getElementById('prevSalVal'),prevRemoteEl=document.getElementById('prevRemote'),prevDeadlineEl=document.getElementById('prevDeadline'),prevDeadlineVal=document.getElementById('prevDeadlineVal'),prevDesc=document.getElementById('prevDesc'),prevDot=document.getElementById('prevDot'),prevStatus=document.getElementById('prevStatus'),prevSkills=document.getElementById('prevSkills');
var statusColors={draft:'#6b7280',active:'#05c48a',closed:'#f04444'},statusLabels={draft:'Draft',active:'Active',closed:'Closed'};

function formatDate(val){if(!val)return'';var d=new Date(val+'T00:00:00');return d.toLocaleDateString('en-IN',{day:'numeric',month:'short',year:'numeric'});}

function updatePreview(){
  var t=titleInp.value.trim();prevTitle.textContent=t||'Job title will appear here';prevTitle.style.color=t?'':'var(--text3)';
  var ty=typeInp.value;if(ty){prevTypeVal.textContent=ty;prevTypeEl.style.display='inline-flex';}else{prevTypeEl.style.display='none';}
  var lo=locationInp.value.trim();if(lo){prevLocVal.textContent=lo;prevLocEl.style.display='inline-flex';}else{prevLocEl.style.display='none';}
  var sa=salaryInp.value.trim();if(sa){prevSalVal.textContent=sa;prevSalEl.style.display='inline-flex';}else{prevSalEl.style.display='none';}
  prevRemoteEl.style.display=remoteToggle.checked?'inline-flex':'none';
  var dl=deadlineInp.value;if(dl){prevDeadlineVal.textContent='Deadline: '+formatDate(dl);prevDeadlineEl.style.display='inline-flex';}else{prevDeadlineEl.style.display='none';}
  var d=descInp.value.trim();prevDesc.textContent=d?(d.length>160?d.slice(0,160)+'…':d):'Description preview will appear here…';prevDesc.style.color=d?'var(--text2)':'';
  var sel=document.querySelector('input[name="status"]:checked'),sv=sel?sel.value:'draft';
  prevDot.style.background=statusColors[sv];prevStatus.textContent=statusLabels[sv];prevStatus.style.color=statusColors[sv];
  var sk=skillsInp.value.split(',').map(function(s){return s.trim();}).filter(Boolean);
  if(sk.length){prevSkills.innerHTML=sk.map(function(s){return '<span class="prev-chip">'+escapeHtml(s)+'</span>';}).join('');prevSkills.style.display='flex';}else{prevSkills.style.display='none';}
}
titleInp.addEventListener('input',updatePreview);typeInp.addEventListener('change',updatePreview);locationInp.addEventListener('input',updatePreview);salaryInp.addEventListener('input',updatePreview);remoteToggle.addEventListener('change',updatePreview);deadlineInp.addEventListener('change',updatePreview);descInp.addEventListener('input',updatePreview);
document.querySelectorAll('input[name="status"]').forEach(function(r){r.addEventListener('change',updatePreview);});

/* Char counters */
var titleCounter=document.getElementById('titleCounter'),descCounter=document.getElementById('descCounter');
titleInp.addEventListener('input',function(){var len=this.value.length;titleCounter.textContent=len+' / 150';titleCounter.className='char-counter'+(len>130?(len>=150?' over':' warn'):'');});
descInp.addEventListener('input',function(){descCounter.textContent=this.value.length+' chars';});

/* Dirty state + unsaved badge */
var formDirty=false,submitting=false;
var unsavedBadge=document.getElementById('unsavedBadge');
function markDirty(){if(!formDirty){formDirty=true;unsavedBadge.classList.add('show');}}
function clearDirty(){formDirty=false;unsavedBadge.classList.remove('show');}

/* Inline validation helpers */
function setFieldError(input,msg){
  input.classList.add('err');
  var f=input.closest('.field');if(!f)return;
  var el=f.querySelector('.field-error');
  if(!el){el=document.createElement('p');el.className='field-error';f.appendChild(el);}
  el.textContent=msg;el.classList.add('show');
}
function clearFieldError(input){
  input.classList.remove('err');
  var f=input.closest('.field');if(f){var el=f.querySelector('.field-error');if(el)el.classList.remove('show');}
}
[titleInp,slugInp,typeInp,descInp].forEach(function(inp){
  inp.addEventListener('input',function(){if(inp.classList.contains('err'))clearFieldError(inp);});
});
typeInp.addEventListener('change',function(){if(typeInp.classList.contains('err'))clearFieldError(typeInp);});

/* Discard confirm */
var discardBtn=document.getElementById('discardBtn');
discardBtn.addEventListener('click',function(e){if(formDirty){e.preventDefault();openDiscardModal();}});
var discardLeave=document.querySelector('#discardModal a.btn-modal-delete');
if(discardLeave)discardLeave.addEventListener('click',function(){window.__leaving=true;});

/* Unsaved-changes guard */
window.addEventListener('beforeunload',function(e){if(formDirty&&!submitting&&!window.__leaving){e.preventDefault();e.returnValue='';}});

/* Ctrl/Cmd+S to save */
document.addEventListener('keydown',function(e){
  if((e.ctrlKey||e.metaKey)&&e.key.toLowerCase()==='s'){e.preventDefault();if(!submitting)jobForm.requestSubmit();}
});

/* Track edits */
var jobForm=document.getElementById('jobForm');
jobForm.addEventListener('input',markDirty);
jobForm.addEventListener('change',markDirty);

/* Form submit */
var saveBtn=document.getElementById('saveBtn');
jobForm.addEventListener('submit',function(e){
  var valid=true;
  if(!titleInp.value.trim()){setFieldError(titleInp,'Job title is required.');valid=false;}else{clearFieldError(titleInp);}
  if(!slugInp.value.trim()){setFieldError(slugInp,'URL slug is required.');valid=false;}else{clearFieldError(slugInp);}
  if(!typeInp.value){setFieldError(typeInp,'Please select a job type.');valid=false;}else{clearFieldError(typeInp);}
  if(!descInp.value.trim()){setFieldError(descInp,'Job description is required.');valid=false;}else{clearFieldError(descInp);}
  if(!valid){e.preventDefault();toast('Please fix the highlighted fields.','error');jobForm.scrollIntoView({behavior:'smooth',block:'start'});return;}
  submitting=true;clearDirty();
  saveBtn.disabled=true;
  saveBtn.innerHTML='<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="animation:spin .7s linear infinite"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg> Saving…';
});

var style=document.createElement('style');
style.textContent='@keyframes spin{from{transform:rotate(0deg);}to{transform:rotate(360deg);}}';
document.head.appendChild(style);

})();