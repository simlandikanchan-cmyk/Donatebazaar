/* ═══════════════════════════════════════════════════════════════════
   Admin Profile Show page — moved from admin/profile/show.blade.php
   inline <script>. window.openDeleteModal/closeDeleteModal bridges
   converted to internal functions with data-action delegation;
   inline DOM-op onclick handlers moved to data-action/direct listeners.
   ═══════════════════════════════════════════════════════════════════ */

(function(){
'use strict';

/* ── Avatar upload: preview + validate + loading ── */
var avInput=document.getElementById('avatarInput');
var avForm=document.getElementById('avatarForm');
var avCam=document.getElementById('avCamBtn');
var avErr=document.getElementById('avErr');
var avImg=document.getElementById('adminAvatarImg');
var MAX=2*1024*1024, TYPES=['image/jpeg','image/png','image/webp'];

avCam.addEventListener('click',function(){avInput.click();});

avInput.addEventListener('change',function(){
  avErr.style.display='none';
  var file=this.files&&this.files[0];
  if(!file)return;
  if(TYPES.indexOf(file.type)===-1){avErr.textContent='Use a JPG, PNG or WebP image.';avErr.style.display='block';this.value='';return;}
  if(file.size>MAX){avErr.textContent='Image must be under 2 MB.';avErr.style.display='block';this.value='';return;}
  var reader=new FileReader();
  reader.onload=function(e){
    if(avImg){avImg.src=e.target.result;}
    else{
      var wrap=avCam.parentElement;
      var img=document.createElement('img');img.id='adminAvatarImg';img.src=e.target.result;img.alt='Avatar';
      var letter=wrap.querySelector('.av-letter');if(letter)letter.remove();
      wrap.insertBefore(img,avCam);
      avImg=img;
    }
  };
  reader.readAsDataURL(file);
  avCam.classList.add('loading');
  avForm.submit();
});

/* ── Password visibility toggles ── */
document.querySelectorAll('.pw-toggle').forEach(function(btn){
  btn.addEventListener('click',function(){
    var inp=document.getElementById(btn.dataset.target);
    if(!inp)return;
    var show=inp.type==='password';
    inp.type=show?'text':'password';
    btn.querySelector('.ic-show').style.display=show?'none':'block';
    btn.querySelector('.ic-hide').style.display=show?'block':'none';
  });
});

/* ── Password strength meter ── */
var pwInp=document.getElementById('password');
var pwStrength=document.getElementById('pwStrength');
var pwFill=document.getElementById('pwFill');
var pwLbl=document.getElementById('pwLbl');
var levels=[{c:'var(--red)',t:'Weak'},{c:'var(--amber)',t:'Fair'},{c:'#3b82f6',t:'Good'},{c:'var(--green)',t:'Strong'}];
function scorePw(p){
  var s=0;
  if(p.length>=8)s++; if(p.length>=12)s++;
  if(/[a-z]/.test(p)&&/[A-Z]/.test(p))s++;
  if(/\d/.test(p))s++;
  if(/[^a-zA-Z0-9]/.test(p))s++;
  return Math.min(s,4);
}
pwInp.addEventListener('input',function(){
  var v=this.value;
  if(!v){pwStrength.style.display='none';return;}
  pwStrength.style.display='block';
  var sc=scorePw(v);
  pwFill.style.width=((sc/4)*100)+'%';
  pwFill.style.background=levels[sc-1].c;
  pwLbl.textContent='Strength: '+levels[sc-1].t;
  pwLbl.style.color=levels[sc-1].c;
});

/* ── Delete modal ── */
function openDeleteModal(){document.getElementById('deleteModal').classList.add('open');document.getElementById('delete_password').focus();}
function closeDeleteModal(){document.getElementById('deleteModal').classList.remove('open');}
document.getElementById('deleteModal').addEventListener('click',function(e){if(e.target===this)closeDeleteModal();});
document.addEventListener('keydown',function(e){if(e.key==='Escape')closeDeleteModal();});

/* ── delegated actions ── */
document.addEventListener('click',function(e){
  var el=e.target.closest('[data-action]');
  if(!el)return;
  var action=el.getAttribute('data-action');

  if(action==='dismiss-flash'){el.parentElement.remove();}
  else if(action==='open-delete-modal'){openDeleteModal();}
  else if(action==='close-delete-modal'){closeDeleteModal();}
});

})();