/* ═══════════════════════════════════════════════════════════════════
   Admin Categories Create page — extracted from inline <script>.
   Reads server data from #categoryCreateData (JSON) injected by the blade.
   ═══════════════════════════════════════════════════════════════════ */

(function(){
'use strict';

var pageData = JSON.parse(document.getElementById('categoryCreateData').textContent);

var state={icon:pageData.icon,color:pageData.color,name:'',active:true};

function updatePreview(){
  var c=state.color,ic=state.icon,nm=state.name||'';
  document.getElementById('previewBox').style.background=c;
  document.getElementById('previewIcon').className='fa '+ic;
  var el=document.getElementById('previewName');
  if(nm){el.textContent=nm;el.classList.remove('empty');}else{el.textContent='Category name…';el.classList.add('empty');}
  var badge=document.getElementById('previewBadge');
  if(state.active){badge.className='prev-badge pb-active';badge.innerHTML='<span style="width:5px;height:5px;border-radius:50%;background:currentColor;display:inline-block;"></span> Active';document.getElementById('prevMetaStatus').style.color='var(--green)';document.getElementById('prevMetaStatus').textContent='Active';}
  else{badge.className='prev-badge pb-inactive';badge.innerHTML='<span style="width:5px;height:5px;border-radius:50%;background:currentColor;display:inline-block;"></span> Inactive';document.getElementById('prevMetaStatus').style.color='var(--text3)';document.getElementById('prevMetaStatus').textContent='Inactive';}
  document.getElementById('prevMetaIcon').innerHTML='<i class="fa '+ic+'" style="color:var(--a);"></i> '+ic;
  document.getElementById('prevColorDot').style.background=c;
  document.getElementById('prevColorHex').textContent=c;
  document.getElementById('pubIconBox').style.background=c;
  document.getElementById('pubIcon').className='fa '+ic;
  document.getElementById('pubName').textContent=nm||'Category name';
}

function updatePreviewName(v){state.name=v.trim();updatePreview();};
function updatePreviewStatus(v){state.active=v;updatePreview();};
function selectIcon(el,icon){state.icon=icon;document.getElementById('iconInput').value=icon;document.querySelectorAll('.icon-tile').forEach(function(t){t.classList.remove('selected');});el.classList.add('selected');document.getElementById('iconPreview').innerHTML='<i class="fa '+icon+'"></i>';document.getElementById('iconName').innerText=icon;updatePreview();};
function selectColor(el,hex){state.color=hex;document.getElementById('colorInput').value=hex;document.getElementById('colorPicker').value=hex;document.getElementById('hexInput').value=hex;document.querySelectorAll('.c-swatch').forEach(function(s){s.classList.remove('selected');});el.classList.add('selected');updatePreview();};
function selectCustomColor(hex){state.color=hex;document.getElementById('colorInput').value=hex;document.getElementById('hexInput').value=hex;document.querySelectorAll('.c-swatch').forEach(function(s){s.classList.remove('selected');});updatePreview();};
function syncHexInput(val){if(/^#[0-9a-fA-F]{6}$/.test(val)){state.color=val;document.getElementById('colorInput').value=val;document.getElementById('colorPicker').value=val;document.querySelectorAll('.c-swatch').forEach(function(s){s.classList.remove('selected');});updatePreview();}};

/* ── delegated handlers for data-action attributes ── */

/* icon tiles (was onclick="selectIcon(this,'...')") */
document.addEventListener('click',function(e){
  var el=e.target.closest('[data-action="select-icon"]');
  if(!el) return;
  selectIcon(el, el.getAttribute('data-icon'));
});

/* color swatches (was onclick="selectColor(this,'...')") */
document.addEventListener('click',function(e){
  var el=e.target.closest('[data-action="select-color"]');
  if(!el) return;
  selectColor(el, el.getAttribute('data-color'));
});

/* name input (was oninput="updatePreviewName(this.value)") */
document.addEventListener('input',function(e){
  var el=e.target.closest('[data-action="preview-name"]');
  if(!el) return;
  updatePreviewName(el.value);
});

/* active toggle (was onchange="updatePreviewStatus(this.checked)") */
document.addEventListener('change',function(e){
  var el=e.target.closest('[data-action="preview-status"]');
  if(!el) return;
  updatePreviewStatus(el.checked);
});

/* custom color picker (was oninput="selectCustomColor(this.value)") */
document.addEventListener('input',function(e){
  var el=e.target.closest('[data-action="custom-color"]');
  if(!el) return;
  selectCustomColor(el.value);
});

/* hex input (was oninput="syncHexInput(this.value)") */
document.addEventListener('input',function(e){
  var el=e.target.closest('[data-action="sync-hex"]');
  if(!el) return;
  syncHexInput(el.value);
});

updatePreview();
})();