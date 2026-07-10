@extends('layouts.admin')

@section('sidebar_job_posts', 'active')
@section('page_title', 'Edit Job Post')
@section('page_subtitle')
    Edit Job Post › Edit Listing #{{ $jobPost->id }}
@endsection

@push('page_styles')
<style>
/* ── PAGE HEADER ── */
.page-hdr{display:flex;align-items:flex-start;justify-content:space-between;gap:16px;margin-bottom:28px;flex-wrap:wrap;animation:fadeUp .35s ease both;}
.page-hdr-icon{width:52px;height:52px;border-radius:15px;background:linear-gradient(135deg,rgba(37,99,235,.18),rgba(37,99,235,.08));border:1px solid rgba(37,99,235,.25);display:flex;align-items:center;justify-content:center;margin-bottom:14px;}
.page-hdr-icon svg{width:24px;height:24px;color:var(--a);}
.page-ttl{font-family:var(--mono);font-size:26px;font-weight:800;color:var(--text);letter-spacing:-.03em;line-height:1.1;}
.page-sub{font-size:13.5px;color:var(--text3);margin-top:6px;}
.page-hdr-right{display:flex;gap:8px;align-items:center;flex-wrap:wrap;}
.btn-back{display:inline-flex;align-items:center;gap:7px;padding:9px 16px;border-radius:var(--r-sm);font-size:12.5px;font-weight:600;border:1px solid var(--border2);background:var(--surface);color:var(--text2);cursor:pointer;transition:all var(--ease);text-decoration:none;font-family:var(--font);}
.btn-back:hover{background:var(--surface2);color:var(--text);}
.btn-back svg{width:13px;height:13px;}
.edit-badge{display:inline-flex;align-items:center;gap:6px;padding:6px 14px;border-radius:100px;font-size:11px;font-weight:700;font-family:var(--mono);background:var(--a-lt);border:1px solid rgba(37,99,235,.25);color:var(--a);}
.edit-badge svg{width:11px;height:11px;}

/* ── BREADCRUMB ── */
.breadcrumb{display:flex;align-items:center;gap:6px;font-size:12px;color:var(--text3);font-family:var(--mono);margin-bottom:22px;animation:fadeUp .3s ease both;}
.breadcrumb a{color:var(--text3);transition:color var(--ease);}
.breadcrumb a:hover{color:var(--a);}
.breadcrumb svg{width:12px;height:12px;flex-shrink:0;}
.breadcrumb span{color:var(--text2);font-weight:600;}

/* ── FORM LAYOUT ── */
.form-layout{display:grid;grid-template-columns:1fr 320px;gap:20px;align-items:start;}

/* ── CARDS ── */
.card{background:var(--surface);border:1px solid var(--border);border-radius:var(--r);padding:26px;box-shadow:var(--sh);animation:fadeUp .4s ease both;}
.card+.card{margin-top:16px;}
.card-hdr{display:flex;align-items:center;gap:12px;margin-bottom:22px;padding-bottom:18px;border-bottom:1px solid var(--border);}
.card-ico{width:38px;height:38px;border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0;}
.card-ico svg{width:17px;height:17px;}
.ci-teal{background:rgba(5,196,138,.12);color:#05c48a;}
.ci-purple{background:var(--a-lt);color:var(--a);}
.ci-amber{background:var(--amber-lt);color:var(--amber);}
.card-ttl{font-family:var(--mono);font-size:14.5px;font-weight:700;color:var(--text);letter-spacing:-.01em;}
.card-sub{font-size:11px;color:var(--text3);margin-top:2px;font-family:var(--mono);}

/* ── FIELDS ── */
.field{margin-bottom:20px;}
.field:last-child{margin-bottom:0;}
.field-row{display:grid;grid-template-columns:1fr 1fr;gap:16px;}
label.lbl{display:block;font-size:12px;font-weight:600;color:var(--text2);margin-bottom:8px;font-family:var(--mono);letter-spacing:.04em;text-transform:uppercase;}
label.lbl span{color:var(--red);margin-left:2px;}
.inp,.sel,.ta{width:100%;padding:11px 14px;border:1px solid var(--border2);border-radius:var(--r-sm);font-size:13.5px;color:var(--text);font-family:var(--font);background:var(--surface2);outline:none;transition:border-color var(--ease),box-shadow var(--ease),background var(--ease);line-height:1.5;}
.inp:focus,.sel:focus,.ta:focus{border-color:var(--a);box-shadow:0 0 0 3px var(--a-glow);background:var(--surface);}
.inp::placeholder,.ta::placeholder{color:var(--text3);}
.sel{cursor:pointer;appearance:none;background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%239096b4' stroke-width='2'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E");background-repeat:no-repeat;background-position:right 12px center;background-size:14px;padding-right:38px;}
.ta{resize:vertical;min-height:120px;}
.inp.err,.sel.err,.ta.err{border-color:var(--red);box-shadow:0 0 0 3px rgba(240,68,68,.12);}
.inp-wrap{position:relative;}
.inp-wrap .inp-icon{position:absolute;left:13px;top:50%;transform:translateY(-50%);width:15px;height:15px;color:var(--text3);pointer-events:none;}
.inp-wrap .inp{padding-left:40px;}
.field-hint{font-size:11px;color:var(--text3);margin-top:6px;font-family:var(--mono);line-height:1.5;}
.field-error{font-size:11.5px;color:var(--red);margin-top:6px;font-family:var(--mono);font-weight:600;display:none;}
.field-error.show{display:block;}
.char-counter{float:right;font-size:11px;color:var(--text3);font-family:var(--mono);transition:color var(--ease);}
.char-counter.warn{color:var(--amber);}
.char-counter.over{color:var(--red);}

/* ── SLUG ── */
.slug-preview{display:flex;align-items:center;gap:8px;margin-top:8px;padding:8px 12px;background:var(--surface2);border:1px solid var(--border);border-radius:var(--r-xs);font-family:var(--mono);font-size:11px;color:var(--text3);overflow:hidden;}
.slug-preview .slug-base{color:var(--text3);flex-shrink:0;}
.slug-preview .slug-val{color:var(--a);font-weight:600;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;}
.slug-lock-btn{margin-left:auto;flex-shrink:0;padding:3px 8px;border-radius:5px;font-size:10px;font-weight:700;border:1px solid var(--border2);background:var(--surface);color:var(--text3);cursor:pointer;font-family:var(--mono);transition:all var(--ease);}
.slug-lock-btn:hover{color:var(--a);border-color:var(--a);}

/* ── TOGGLE ── */
.toggle-row{display:flex;align-items:center;justify-content:space-between;padding:14px 16px;background:var(--surface2);border:1px solid var(--border2);border-radius:var(--r-sm);gap:14px;}
.toggle-row-title{font-size:13.5px;font-weight:600;color:var(--text);}
.toggle-row-sub{font-size:11px;color:var(--text3);margin-top:2px;font-family:var(--mono);}
.toggle-switch{position:relative;flex-shrink:0;}
.toggle-switch input{position:absolute;opacity:0;width:0;height:0;}
.toggle-switch label{display:block;width:44px;height:24px;border-radius:100px;background:var(--border2);cursor:pointer;position:relative;transition:background .2s;}
.toggle-switch label::after{content:'';position:absolute;top:3px;left:3px;width:18px;height:18px;border-radius:50%;background:#fff;transition:transform .2s cubic-bezier(.4,0,.2,1);box-shadow:0 1px 4px rgba(0,0,0,.18);}
.toggle-switch input:checked+label{background:var(--a);}
.toggle-switch input:checked+label::after{transform:translateX(20px);}
.toggle-row.active-toggle{background:var(--a-lt);border-color:rgba(37,99,235,.25);}

/* ── STATUS PILLS ── */
.status-pills{display:flex;gap:8px;flex-wrap:wrap;}
.status-pill{display:flex;align-items:center;gap:8px;padding:10px 16px;border-radius:var(--r-sm);border:1.5px solid var(--border2);background:var(--surface2);cursor:pointer;transition:all .15s;flex:1;min-width:80px;}
.status-pill input{display:none;}
.status-pill-dot{width:8px;height:8px;border-radius:50%;flex-shrink:0;}
.status-pill-lbl{font-size:12.5px;font-weight:600;color:var(--text2);font-family:var(--font);}
.status-pill-sub{font-size:10px;color:var(--text3);font-family:var(--mono);margin-top:1px;}
.status-pill:has(input:checked){border-color:currentColor;}
.sp-draft:has(input:checked){background:rgba(107,114,128,.08);border-color:rgba(107,114,128,.35);color:#6b7280;}
.sp-draft:has(input:checked) .status-pill-lbl{color:#6b7280;}
.sp-draft .status-pill-dot{background:#6b7280;}
.sp-active:has(input:checked){background:var(--green-lt);border-color:rgba(5,196,138,.35);}
.sp-active:has(input:checked) .status-pill-lbl{color:var(--green);}
.sp-active .status-pill-dot{background:var(--green);}
.sp-closed:has(input:checked){background:var(--red-lt);border-color:rgba(240,68,68,.3);}
.sp-closed:has(input:checked) .status-pill-lbl{color:var(--red);}
.sp-closed .status-pill-dot{background:var(--red);}

/* ── SUBMIT ROW ── */
.submit-row{display:flex;align-items:center;justify-content:space-between;gap:12px;margin-top:20px;padding-top:20px;border-top:1px solid var(--border);flex-wrap:wrap;}
.submit-info{font-size:12px;color:var(--text3);font-family:var(--mono);}
.submit-info span{color:var(--red);}
.submit-btns{display:flex;gap:9px;}

/* ── BUTTONS ── */
.btn{display:inline-flex;align-items:center;gap:7px;padding:11px 22px;border-radius:var(--r-sm);font-size:13px;font-weight:600;cursor:pointer;border:none;transition:all var(--ease);font-family:var(--font);}
.btn:active{transform:scale(.97);}
.btn svg{width:14px;height:14px;}
.btn-secondary{background:var(--surface2);color:var(--text2);border:1px solid var(--border2);}
.btn-secondary:hover{background:var(--surface3);color:var(--text);}
.btn-danger{background:var(--red-lt);color:var(--red);border:1px solid rgba(240,68,68,.2);width:100%;justify-content:center;}
.btn-danger:hover{background:rgba(240,68,68,.18);border-color:rgba(240,68,68,.4);}
.btn-primary{background:linear-gradient(135deg,var(--a),var(--a2));color:#fff;box-shadow:0 4px 18px rgba(37,99,235,.35);}
.btn-primary:hover{transform:translateY(-2px);box-shadow:0 8px 28px rgba(37,99,235,.45);}
.btn-primary:disabled{opacity:.6;cursor:not-allowed;transform:none;box-shadow:none;}

/* ── ALERT ── */
.alert{display:flex;align-items:flex-start;gap:12px;padding:14px 16px;border-radius:var(--r-sm);margin-bottom:20px;font-size:13px;animation:fadeUp .3s ease both;}
.alert svg{width:16px;height:16px;flex-shrink:0;margin-top:1px;}
.alert-error{background:var(--red-lt);border:1px solid rgba(240,68,68,.22);color:var(--red);}
.alert ul{padding-left:16px;margin-top:6px;}
.alert ul li{margin-bottom:3px;font-size:12px;}

/* ── SIDE STACK ── */
.side-stack{display:flex;flex-direction:column;gap:16px;position:sticky;top:80px;}
.preview-card{background:var(--surface);border:1px solid var(--border);border-radius:var(--r);padding:20px;box-shadow:var(--sh);animation:fadeUp .4s .08s ease both;}
.preview-ttl-row{display:flex;align-items:center;gap:8px;margin-bottom:14px;}
.preview-ttl-row svg{width:14px;height:14px;color:var(--text3);}
.preview-ttl-row span{font-size:12px;font-weight:700;color:var(--text2);font-family:var(--mono);text-transform:uppercase;letter-spacing:.08em;}
.prev-job-title{font-family:var(--mono);font-size:16px;font-weight:700;color:var(--text);margin-bottom:6px;min-height:24px;}
.prev-meta{display:flex;flex-wrap:wrap;gap:6px;margin-bottom:10px;}
.prev-chip{display:inline-flex;align-items:center;gap:4px;font-size:11px;font-weight:600;padding:4px 10px;border-radius:100px;font-family:var(--mono);background:var(--surface2);border:1px solid var(--border2);color:var(--text3);}
.prev-chip svg{width:10px;height:10px;}
.prev-chip.remote-chip{background:var(--a-lt);border-color:rgba(37,99,235,.25);color:var(--a);}
.prev-desc{font-size:12px;color:var(--text3);line-height:1.6;min-height:36px;}
.prev-status-row{display:flex;align-items:center;justify-content:space-between;margin-top:12px;padding-top:12px;border-top:1px solid var(--border);}
.prev-status-dot{width:8px;height:8px;border-radius:50%;margin-right:5px;}
.prev-status-lbl{font-size:11px;font-weight:600;font-family:var(--mono);}

/* ── META CARD ── */
.meta-card{background:linear-gradient(135deg,rgba(59,130,246,.06),rgba(59,130,246,.02));border:1px solid rgba(59,130,246,.15);border-radius:var(--r);padding:18px;animation:fadeUp .4s .10s ease both;}
.meta-row{display:flex;align-items:center;justify-content:space-between;padding:7px 0;border-bottom:1px solid var(--border);}
.meta-row:last-child{border-bottom:none;padding-bottom:0;}
.meta-row:first-child{padding-top:0;}
.meta-lbl{font-size:11px;color:var(--text3);font-family:var(--mono);}
.meta-val{font-size:12px;font-weight:600;color:var(--text2);font-family:var(--mono);}

/* ── TIPS ── */
.tips-card{background:linear-gradient(135deg,rgba(37,99,235,.06),rgba(13,148,136,.03));border:1px solid rgba(37,99,235,.15);border-radius:var(--r);padding:18px;animation:fadeUp .4s .12s ease both;}
.tips-hdr{display:flex;align-items:center;gap:8px;margin-bottom:12px;}
.tips-hdr svg{width:15px;height:15px;color:var(--a);}
.tips-hdr span{font-size:12px;font-weight:700;color:var(--a);font-family:var(--mono);text-transform:uppercase;letter-spacing:.08em;}
.tip-item{display:flex;align-items:flex-start;gap:8px;margin-bottom:9px;font-size:12px;color:var(--text2);line-height:1.5;}
.tip-item:last-child{margin-bottom:0;}
.tip-bullet{width:16px;height:16px;border-radius:5px;background:var(--a-lt);color:var(--a);font-size:9px;font-weight:700;display:flex;align-items:center;justify-content:center;flex-shrink:0;margin-top:1px;font-family:var(--mono);}

/* ── DANGER CARD ── */
.danger-card{background:linear-gradient(135deg,rgba(240,68,68,.06),rgba(240,68,68,.02));border:1px solid rgba(240,68,68,.18);border-radius:var(--r);padding:18px;animation:fadeUp .4s .14s ease both;}
.danger-hdr{display:flex;align-items:center;gap:8px;margin-bottom:12px;}
.danger-hdr svg{width:15px;height:15px;color:var(--red);}
.danger-hdr span{font-size:12px;font-weight:700;color:var(--red);font-family:var(--mono);text-transform:uppercase;letter-spacing:.08em;}
.danger-desc{font-size:12px;color:var(--text3);line-height:1.5;margin-bottom:14px;}

/* ── MODAL ── */
.modal-overlay{position:fixed;inset:0;background:rgba(0,0,0,.55);backdrop-filter:blur(4px);z-index:9000;display:none;align-items:center;justify-content:center;padding:20px;}
.modal-overlay.open{display:flex;}
.modal{background:var(--surface);border:1px solid var(--border2);border-radius:var(--r);box-shadow:var(--sh-lg);padding:28px;max-width:420px;width:100%;animation:modalIn .2s ease both;}
@keyframes modalIn{from{opacity:0;transform:scale(.95) translateY(10px)}to{opacity:1;transform:none}}
.modal-ico{width:48px;height:48px;border-radius:14px;background:var(--red-lt);border:1px solid rgba(240,68,68,.22);display:flex;align-items:center;justify-content:center;margin-bottom:16px;}
.modal-ico svg{width:22px;height:22px;color:var(--red);}
.modal-ttl{font-family:var(--mono);font-size:18px;font-weight:800;color:var(--text);margin-bottom:8px;letter-spacing:-.02em;}
.modal-desc{font-size:13.5px;color:var(--text2);line-height:1.6;margin-bottom:20px;}
.modal-desc strong{color:var(--text);font-weight:700;}
.modal-btns{display:flex;gap:10px;}
.modal-btns .btn{flex:1;justify-content:center;}
.btn-modal-cancel{background:var(--surface2);color:var(--text2);border:1px solid var(--border2);}
.btn-modal-cancel:hover{background:var(--surface3);}
.btn-modal-delete{background:linear-gradient(135deg,#dc2626,#f04444);color:#fff;border:none;box-shadow:0 4px 18px rgba(240,68,68,.30);}
.btn-modal-delete:hover{transform:translateY(-1px);box-shadow:0 6px 22px rgba(240,68,68,.40);}

/* ── TOAST ── */
.toast-wrap{position:fixed;top:20px;right:20px;z-index:9999;display:flex;flex-direction:column;gap:8px;pointer-events:none;}
.toast{display:flex;align-items:center;gap:10px;padding:13px 16px;border-radius:14px;font-size:13px;font-weight:500;color:#fff;min-width:270px;box-shadow:var(--sh-lg);pointer-events:all;animation:toastIn .3s ease both;}
.toast svg{width:15px;height:15px;flex-shrink:0;}
.toast-ok{background:linear-gradient(135deg,#059669,#10b981);}
.toast-err{background:linear-gradient(135deg,#dc2626,#f04444);}
.toast-x{margin-left:auto;width:18px;height:18px;border-radius:5px;background:rgba(255,255,255,.22);border:none;cursor:pointer;color:#fff;font-size:11px;display:flex;align-items:center;justify-content:center;}

/* ── NUMBER INPUT ── */
.inp.num{appearance:textfield;}
.inp.num::-webkit-outer-spin-button,.inp.num::-webkit-inner-spin-button{appearance:none;margin:0;}

/* ── SKILLS PREVIEW ── */
.skill-preview{display:flex;flex-wrap:wrap;gap:6px;margin-top:10px;}
.skill-preview:empty{display:none;margin-top:0;}
.skill-tag-prev{display:inline-flex;align-items:center;gap:6px;padding:4px 10px;border-radius:100px;font-size:11px;font-weight:600;font-family:var(--mono);background:var(--surface2);border:1px solid var(--border2);color:var(--text2);animation:fadeUp .2s ease both;}
.skill-tag-prev svg{width:9px;height:9px;color:var(--text3);cursor:pointer;transition:color var(--ease);}
.skill-tag-prev svg:hover{color:var(--red);}

/* ── UNCHANGED PREVIEW SKILLS ── */
.prev-skills{display:flex;flex-wrap:wrap;gap:5px;margin-bottom:10px;}
.prev-skills:empty{display:none;margin-bottom:0;}

/* ── UNSAVED BADGE ── */
.unsaved-badge{display:none;align-items:center;gap:6px;padding:6px 14px;border-radius:100px;font-size:11px;font-weight:700;font-family:var(--mono);background:rgba(245,158,11,.12);border:1px solid rgba(245,158,11,.3);color:#d97706;}
.unsaved-badge.show{display:inline-flex;animation:fadeUp .2s ease both;}
.unsaved-badge .ud-dot{width:7px;height:7px;border-radius:50%;background:#f59e0b;box-shadow:0 0 0 3px rgba(245,158,11,.18);}

/* ── STICKY SAVE (mobile) ── */
@media(max-width:600px){
  .submit-row{position:sticky;bottom:0;background:var(--surface);z-index:5;margin:16px -26px -26px;padding:16px 26px;}
  .submit-btns{flex:1;}
  .submit-btns .btn{flex:1;justify-content:center;}
}

/* ── RESPONSIVE ── */
@media(max-width:1100px){.form-layout{grid-template-columns:1fr;}.side-stack{position:static;}}
@media(max-width:600px){.field-row{grid-template-columns:1fr;}}
</style>
@endpush

@section('content')

{{-- DELETE MODAL --}}
<div class="modal-overlay" id="deleteModal">
  <div class="modal">
    <div class="modal-ico">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
    </div>
    <div class="modal-ttl">Delete Job Post?</div>
    <div class="modal-desc">You are about to permanently delete <strong>"{{ $jobPost->title }}"</strong>. This will also remove all associated applications. This action <strong>cannot be undone</strong>.</div>
    <div class="modal-btns">
      <button class="btn btn-modal-cancel" onclick="closeDeleteModal()">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>Cancel
      </button>
      <form action="{{ route('admin.job_posts.destroy', $jobPost->id) }}" method="POST" style="flex:1;">
        @csrf @method('DELETE')
        <button type="submit" class="btn btn-modal-delete" style="width:100%;">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>Yes, Delete
        </button>
      </form>
    </div>
  </div>
</div>

{{-- DISCARD MODAL --}}
<div class="modal-overlay" id="discardModal">
  <div class="modal">
    <div class="modal-ico" style="background:rgba(245,158,11,.12);border-color:rgba(245,158,11,.22);">
      <svg viewBox="0 0 24 24" fill="none" stroke="#d97706" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
    </div>
    <div class="modal-ttl">Discard Changes?</div>
    <div class="modal-desc">You have <strong>unsaved changes</strong> to this job post. If you leave now, your edits will be lost.</div>
    <div class="modal-btns">
      <button class="btn btn-modal-cancel" onclick="closeDiscardModal()">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>Keep Editing
      </button>
      <a href="{{ route('admin.job_posts.index') }}" class="btn btn-modal-delete" style="background:linear-gradient(135deg,#f59e0b,#fbbf24);box-shadow:0 4px 18px rgba(245,158,11,.30);">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 17l-5-5 5-5m7 5l-5-5 5-5"/></svg>Discard &amp; Leave
      </a>
    </div>
  </div>
</div>

<div class="breadcrumb">
  <a href="{{ route('admin.dashboard') }}">Dashboard</a>
  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
  <a href="{{ route('admin.job_posts.index') }}">Job Posts</a>
  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
  <span>Edit #{{ $jobPost->id }}</span>
</div>

<div class="page-hdr">
  <div class="page-hdr-left">
    <div class="page-hdr-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg></div>
    <div class="page-ttl">Edit Job Post</div>
    <div class="page-sub">Update the listing details for <strong style="color:var(--text2);">{{ $jobPost->title }}</strong></div>
  </div>
  <div class="page-hdr-right">
    <div class="edit-badge"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/></svg>ID #{{ $jobPost->id }}</div>
    <span class="unsaved-badge" id="unsavedBadge"><span class="ud-dot"></span>Unsaved changes</span>
    <a href="{{ route('admin.job_posts.index') }}" class="btn-back"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>Back to Listings</a>
  </div>
</div>

@if($errors->any())
<div class="alert alert-error">
  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
  <div><strong>Please fix the following errors:</strong><ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
</div>
@endif

<form id="jobForm" action="{{ route('admin.job_posts.update', $jobPost->id) }}" method="POST" novalidate>
  @csrf
  @method('PUT')

  <div class="form-layout">

    {{-- LEFT --}}
    <div>

      {{-- CARD 1: BASIC INFO --}}
      <div class="card" style="animation-delay:.05s">
        <div class="card-hdr">
          <div class="card-ico ci-teal"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg></div>
          <div><div class="card-ttl">Basic Information</div><div class="card-sub">Core job details visible to all applicants</div></div>
        </div>

        {{-- Title --}}
        <div class="field">
          <label class="lbl" for="title">Job Title <span>*</span></label>
          <div class="inp-wrap">
            <svg class="inp-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
            <input type="text" id="title" name="title" class="inp @error('title') err @enderror" placeholder="e.g. Senior Product Designer" value="{{ old('title', $jobPost->title) }}" maxlength="150" autocomplete="off">
          </div>
          <span class="char-counter" id="titleCounter">{{ strlen(old('title', $jobPost->title)) }} / 150</span>
          @error('title')<p class="field-error show">{{ $message }}</p>@enderror
          <div class="field-hint">Be specific — a clear title attracts better candidates.</div>
        </div>

        {{-- Slug --}}
        <div class="field">
          <label class="lbl" for="slug">URL Slug <span>*</span></label>
          <div class="inp-wrap">
            <svg class="inp-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
            <input type="text" id="slug" name="slug" class="inp @error('slug') err @enderror" placeholder="url-slug-here" value="{{ old('slug', $jobPost->slug) }}" maxlength="255" autocomplete="off">
          </div>
          @error('slug')<p class="field-error show">{{ $message }}</p>@enderror
          <div class="slug-preview" id="slugPreview">
            <span class="slug-base">/jobs/</span>
            <span class="slug-val" id="slugDisplay">{{ old('slug', $jobPost->slug) }}</span>
            <button type="button" class="slug-lock-btn" id="slugLockBtn" style="color:var(--amber);border-color:var(--amber);">Manual</button>
          </div>
          <div class="field-hint">Edit carefully — changing the slug will break existing links to this job. Must be unique.</div>
        </div>

        {{-- Type + Location --}}
        <div class="field-row field">
          <div>
            <label class="lbl" for="type">Job Type <span>*</span></label>
              <select id="type" name="type" class="sel @error('type') err @enderror">
              <option value="" disabled>Select type…</option>
              @foreach(['full-time','part-time','contract','internship','volunteer','freelance','remote'] as $t)
                <option value="{{ $t }}" {{ old('type', $jobPost->type) === $t ? 'selected' : '' }}>{{ ucfirst($t) }}</option>
              @endforeach
            </select>
            @error('type')<p class="field-error show">{{ $message }}</p>@enderror
          </div>
          <div>
            <label class="lbl" for="location">Location</label>
            <div class="inp-wrap">
              <svg class="inp-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
              <input type="text" id="location" name="location" class="inp @error('location') err @enderror" placeholder="e.g. Mumbai, India" value="{{ old('location', $jobPost->location) }}" autocomplete="off">
            </div>
            @error('location')<p class="field-error show">{{ $message }}</p>@enderror
          </div>
        </div>

        {{-- Remote Toggle --}}
        <div class="field">
          <label class="lbl">Remote Work</label>
          <label class="toggle-row" id="remoteToggleRow">
            <div class="toggle-row-info">
              <div class="toggle-row-title">This is a remote position</div>
              <div class="toggle-row-sub">Enables the "Remote" badge on the listing</div>
            </div>
            <div class="toggle-switch">
              <input type="checkbox" id="is_remote" name="is_remote" value="1" {{ old('is_remote', $jobPost->is_remote) ? 'checked' : '' }}>
              <label for="is_remote"></label>
            </div>
          </label>
          @error('is_remote')<p class="field-error show">{{ $message }}</p>@enderror
        </div>

        {{-- Salary --}}
        <div class="field">
          <label class="lbl" for="salary">Salary / Compensation</label>
          <div class="inp-wrap">
            <svg class="inp-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <input type="text" id="salary" name="salary" class="inp @error('salary') err @enderror" placeholder="e.g. ₹6–10 LPA or $60,000–$80,000/yr" value="{{ old('salary', $jobPost->salary) }}" autocomplete="off">
          </div>
          @error('salary')<p class="field-error show">{{ $message }}</p>@enderror
          <div class="field-hint">Leave blank if you'd prefer not to disclose. Ranges perform better than fixed figures.</div>
        </div>

        {{-- Application Deadline --}}
        <div class="field">
          <label class="lbl" for="application_deadline">Application Deadline</label>
          <div class="inp-wrap">
            <svg class="inp-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            <input type="date" id="application_deadline" name="application_deadline" class="inp @error('application_deadline') err @enderror" value="{{ old('application_deadline', $jobPost->application_deadline ? \Carbon\Carbon::parse($jobPost->application_deadline)->format('Y-m-d') : '') }}">
          </div>
          @error('application_deadline')<p class="field-error show">{{ $message }}</p>@enderror
          <div class="field-hint">Leave blank for a rolling / no-deadline listing.</div>
        </div>

      </div>{{-- /.card --}}

      {{-- CARD 2: DESCRIPTION --}}
      <div class="card" style="animation-delay:.10s">
        <div class="card-hdr">
          <div class="card-ico ci-purple"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 10h16M4 14h16M4 18h10"/></svg></div>
          <div><div class="card-ttl">Job Description</div><div class="card-sub">Detailed overview, responsibilities &amp; requirements</div></div>
        </div>
        <div class="field">
          <label class="lbl" for="description">Description <span>*</span>
            <span class="char-counter" id="descCounter" style="text-transform:none;letter-spacing:0;">{{ strlen(old('description', $jobPost->description)) }} chars</span>
          </label>
          <textarea id="description" name="description" class="ta @error('description') err @enderror" rows="10" placeholder="Describe the role, key responsibilities, required qualifications, benefits…">{{ old('description', $jobPost->description) }}</textarea>
          @error('description')<p class="field-error show">{{ $message }}</p>@enderror
          <div class="field-hint">Tip: Use plain text. Include sections like "About the Role", "Responsibilities", "Requirements", and "Benefits".</div>
        </div>
      </div>{{-- /.card --}}

      {{-- CARD: ROLE DETAILS & REQUIREMENTS --}}
      <div class="card" style="animation-delay:.12s">
        <div class="card-hdr">
          <div class="card-ico ci-blue"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg></div>
          <div><div class="card-ttl">Role Details &amp; Requirements</div><div class="card-sub">Department, experience, vacancies &amp; skills</div></div>
        </div>

        {{-- Department + Experience --}}
        <div class="field-row field">
          <div>
            <label class="lbl" for="department">Department</label>
            <div class="inp-wrap">
              <svg class="inp-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 21h18M5 21V7l8-4v18M19 21V11l-6-4"/></svg>
              <input type="text" id="department" name="department" class="inp @error('department') err @enderror" placeholder="e.g. Engineering" value="{{ old('department', $jobPost->department) }}" autocomplete="off">
            </div>
            @error('department')<p class="field-error show">{{ $message }}</p>@enderror
          </div>
          <div>
            <label class="lbl" for="experience_required">Experience Required</label>
            <div class="inp-wrap">
              <svg class="inp-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
              <input type="text" id="experience_required" name="experience_required" class="inp @error('experience_required') err @enderror" placeholder="e.g. 3+ years" value="{{ old('experience_required', $jobPost->experience_required) }}" autocomplete="off">
            </div>
            @error('experience_required')<p class="field-error show">{{ $message }}</p>@enderror
          </div>
        </div>

        {{-- Vacancies --}}
        <div class="field">
          <label class="lbl" for="vacancies">Number of Vacancies</label>
          <div class="inp-wrap" style="max-width:200px;">
            <svg class="inp-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0"/></svg>
            <input type="number" id="vacancies" name="vacancies" class="inp num @error('vacancies') err @enderror" placeholder="1" min="1" value="{{ old('vacancies', $jobPost->vacancies) }}">
          </div>
          @error('vacancies')<p class="field-error show">{{ $message }}</p>@enderror
          <div class="field-hint">How many open positions are available for this role.</div>
        </div>

        {{-- Featured toggle --}}
        <div class="field">
          <label class="lbl">Featured Listing</label>
          <label class="toggle-row" id="featuredToggleRow">
            <div class="toggle-row-info">
              <div class="toggle-row-title">Show on homepage &amp; featured sections</div>
              <div class="toggle-row-sub">Highlights this post across the site</div>
            </div>
            <div class="toggle-switch">
              <input type="checkbox" id="featured" name="featured" value="1" {{ old('featured', $jobPost->featured) ? 'checked' : '' }}>
              <label for="featured"></label>
            </div>
          </label>
          @error('featured')<p class="field-error show">{{ $message }}</p>@enderror
        </div>

        {{-- Skills --}}
        <div class="field">
          <label class="lbl" for="skills">Skills</label>
          <div class="inp-wrap">
            <svg class="inp-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg>
            <input type="text" id="skills" name="skills" class="inp @error('skills') err @enderror" placeholder="React, Node.js, Figma" value="{{ old('skills', is_array($jobPost->skills) ? implode(', ', $jobPost->skills) : $jobPost->skills) }}" autocomplete="off">
          </div>
          <div class="skill-preview" id="skillPreview"></div>
          @error('skills')<p class="field-error show">{{ $message }}</p>@enderror
          <div class="field-hint">Comma-separated. Each becomes a tag on the public listing.</div>
        </div>
      </div>{{-- /.card --}}

      {{-- CARD: SEO & META --}}
      <div class="card" style="animation-delay:.13s">
        <div class="card-hdr">
          <div class="card-ico ci-gray"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/></svg></div>
          <div><div class="card-ttl">SEO &amp; Meta</div><div class="card-sub">Optimise how this listing appears in search</div></div>
        </div>
        <div class="field">
          <label class="lbl" for="meta_title">Meta Title <span class="char-counter" id="metaTitleCounter" style="text-transform:none;letter-spacing:0;">{{ strlen(old('meta_title', $jobPost->meta_title ?? '')) }} / 255</span></label>
          <div class="inp-wrap">
            <svg class="inp-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 7V5a1 1 0 011-1h14a1 1 0 011 1v2M9 20h6M12 5v15"/></svg>
            <input type="text" id="meta_title" name="meta_title" class="inp @error('meta_title') err @enderror" placeholder="Optional search title" maxlength="255" value="{{ old('meta_title', $jobPost->meta_title) }}" autocomplete="off">
          </div>
          @error('meta_title')<p class="field-error show">{{ $message }}</p>@enderror
        </div>
        <div class="field">
          <label class="lbl" for="meta_description">Meta Description <span class="char-counter" id="metaDescCounter" style="text-transform:none;letter-spacing:0;">{{ strlen(old('meta_description', $jobPost->meta_description ?? '')) }} / 500</span></label>
          <textarea id="meta_description" name="meta_description" class="ta @error('meta_description') err @enderror" rows="3" placeholder="Optional summary shown in search results" maxlength="500">{{ old('meta_description', $jobPost->meta_description) }}</textarea>
          @error('meta_description')<p class="field-error show">{{ $message }}</p>@enderror
        </div>
      </div>{{-- /.card --}}

      {{-- CARD 3: STATUS --}}
      <div class="card" style="animation-delay:.15s">
        <div class="card-hdr">
          <div class="card-ico ci-amber"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></div>
          <div><div class="card-ttl">Publication Status</div><div class="card-sub">Control visibility of this job listing</div></div>
        </div>
        <div class="field">
          <label class="lbl">Status <span>*</span></label>
          <div class="status-pills">
            <label class="status-pill sp-draft">
              <input type="radio" name="status" value="draft" {{ old('status', $jobPost->status) === 'draft' ? 'checked' : '' }}>
              <div class="status-pill-dot"></div>
              <div><div class="status-pill-lbl">Draft</div><div class="status-pill-sub">Not visible yet</div></div>
            </label>
            <label class="status-pill sp-active">
              <input type="radio" name="status" value="active" {{ old('status', $jobPost->status) === 'active' ? 'checked' : '' }}>
              <div class="status-pill-dot"></div>
              <div><div class="status-pill-lbl">Active</div><div class="status-pill-sub">Live &amp; accepting</div></div>
            </label>
            <label class="status-pill sp-closed">
              <input type="radio" name="status" value="closed" {{ old('status', $jobPost->status) === 'closed' ? 'checked' : '' }}>
              <div class="status-pill-dot"></div>
              <div><div class="status-pill-lbl">Closed</div><div class="status-pill-sub">No longer hiring</div></div>
            </label>
          </div>
          @error('status')<p class="field-error show">{{ $message }}</p>@enderror
        </div>
        <div class="submit-row">
          <div class="submit-info">Fields marked <span>*</span> are required</div>
          <div class="submit-btns">
            <a href="{{ route('admin.job_posts.index') }}" class="btn btn-secondary" id="discardBtn">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>Discard
            </a>
            <button type="submit" class="btn btn-primary" id="saveBtn">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/></svg>Save Changes
            </button>
          </div>
        </div>
      </div>{{-- /.card --}}

    </div>{{-- /left col --}}

    {{-- RIGHT SIDEBAR --}}
    <div class="side-stack">

      {{-- LIVE PREVIEW --}}
      <div class="preview-card">
        <div class="preview-ttl-row">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
          <span>Live Preview</span>
        </div>
        <div class="prev-job-title" id="prevTitle">{{ $jobPost->title }}</div>
        <div class="prev-meta">
          <span class="prev-chip" id="prevType" style="{{ $jobPost->type ? '' : 'display:none;' }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
            <span id="prevTypeVal">{{ $jobPost->type ?? '—' }}</span>
          </span>
          <span class="prev-chip" id="prevLoc" style="{{ $jobPost->location ? '' : 'display:none;' }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            <span id="prevLocVal">{{ $jobPost->location ?? '—' }}</span>
          </span>
          <span class="prev-chip" id="prevSal" style="{{ $jobPost->salary ? '' : 'display:none;' }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span id="prevSalVal">{{ $jobPost->salary ?? '—' }}</span>
          </span>
          <div class="prev-skills" id="prevSkills"></div>
          <span class="prev-chip remote-chip" id="prevRemote" style="{{ $jobPost->is_remote ? '' : 'display:none;' }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064"/><circle cx="12" cy="12" r="9"/></svg>
            Remote
          </span>
          <span class="prev-chip" id="prevDeadline" style="{{ $jobPost->application_deadline ? '' : 'display:none;' }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            <span id="prevDeadlineVal">{{ $jobPost->application_deadline ? 'Deadline: ' . \Carbon\Carbon::parse($jobPost->application_deadline)->format('M d, Y') : '' }}</span>
          </span>
        </div>
        <div class="prev-desc" id="prevDesc" style="{{ $jobPost->description ? 'color:var(--text2);' : '' }}">{{ $jobPost->description ? Str::limit($jobPost->description, 160) : 'Description preview will appear here…' }}</div>
        <div class="prev-status-row">
          <div style="display:flex;align-items:center;">
            <div class="prev-status-dot" id="prevDot" style="background:{{ $jobPost->status === 'active' ? '#05c48a' : ($jobPost->status === 'closed' ? '#f04444' : '#6b7280') }};"></div>
            <span class="prev-status-lbl" id="prevStatus" style="color:{{ $jobPost->status === 'active' ? '#05c48a' : ($jobPost->status === 'closed' ? '#f04444' : '#6b7280') }};">{{ ucfirst($jobPost->status ?? 'draft') }}</span>
          </div>
          <span style="font-size:11px;color:var(--text3);font-family:var(--mono);">Preview</span>
        </div>
      </div>

      {{-- POST META --}}
      <div class="meta-card">
        <div style="display:flex;align-items:center;gap:8px;margin-bottom:14px;">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="var(--blue)" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
          <span style="font-size:12px;font-weight:700;color:#3b82f6;font-family:var(--mono);text-transform:uppercase;letter-spacing:0.08em;">Post Info</span>
        </div>
        <div class="meta-row"><span class="meta-lbl">Post ID</span><span class="meta-val">#{{ $jobPost->id }}</span></div>
        <div class="meta-row"><span class="meta-lbl">Created</span><span class="meta-val">{{ $jobPost->created_at->format('M d, Y') }}</span></div>
        <div class="meta-row"><span class="meta-lbl">Last Updated</span><span class="meta-val">{{ $jobPost->updated_at->format('M d, Y') }}</span></div>
        <div class="meta-row"><span class="meta-lbl">Applications</span><span class="meta-val" style="color:var(--amber);">{{ $jobPost->applications()->count() ?? 0 }}</span></div>
      </div>

      {{-- TIPS --}}
      <div class="tips-card">
        <div class="tips-hdr">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg>
          <span>Edit Tips</span>
        </div>
        <div class="tip-item"><div class="tip-bullet">1</div><div>Changing status to <strong>Closed</strong> immediately stops new applications.</div></div>
        <div class="tip-item"><div class="tip-bullet">2</div><div>The <strong>slug</strong> is in Manual mode — change it carefully to avoid broken links.</div></div>
        <div class="tip-item"><div class="tip-bullet">3</div><div>Existing applicants are <strong>not notified</strong> of edits — contact them separately if needed.</div></div>
        <div class="tip-item"><div class="tip-bullet">4</div><div>Use <strong>Delete</strong> only to permanently remove this listing and all its applications.</div></div>
      </div>

      {{-- DANGER ZONE --}}
      <div class="danger-card">
        <div class="danger-hdr">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
          <span>Danger Zone</span>
        </div>
        <div class="danger-desc">Permanently delete this job post and all associated applications. This action cannot be undone.</div>
        <button type="button" class="btn btn-danger" onclick="openDeleteModal()">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
          Delete This Job Post
        </button>
      </div>

      {{-- QUICK LINKS --}}
      <div class="preview-card" style="animation-delay:.18s;">
        <div class="preview-ttl-row">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
          <span>Quick Links</span>
        </div>
        <div style="display:flex;flex-direction:column;gap:6px;margin-top:4px;">
          <a href="{{ route('admin.job_posts.index') }}" class="s-link" style="color:var(--text2);border-radius:var(--r-xs);padding:7px 10px;font-size:12.5px;"><svg class="s-ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 10h16M4 14h16M4 18h10"/></svg>All Job Posts</a>
          <a href="{{ route('admin.job_posts.create') }}" class="s-link" style="color:var(--text2);border-radius:var(--r-xs);padding:7px 10px;font-size:12.5px;"><svg class="s-ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>Post New Job</a>
          <a href="{{ route('admin.job_post_applications.index') }}" class="s-link" style="color:var(--text2);border-radius:var(--r-xs);padding:7px 10px;font-size:12.5px;"><svg class="s-ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0"/></svg>Job Applicants</a>
        </div>
      </div>

    </div>{{-- /side-stack --}}
  </div>{{-- /.form-layout --}}
</form>

@endsection

@push('page_scripts')
<script>
(function(){
'use strict';

/* Delete Modal */
window.openDeleteModal=function(){document.getElementById('deleteModal').classList.add('open');};
window.closeDeleteModal=function(){document.getElementById('deleteModal').classList.remove('open');};
document.getElementById('deleteModal').addEventListener('click',function(e){if(e.target===this)closeDeleteModal();});
document.addEventListener('keydown',function(e){if(e.key==='Escape')closeDeleteModal();});

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
function escapeHtml(str){return String(str).replace(/[&<>"']/g,function(c){return {'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c];});}
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
window.openDiscardModal=function(){document.getElementById('discardModal').classList.add('open');};
window.closeDiscardModal=function(){document.getElementById('discardModal').classList.remove('open');};
document.getElementById('discardModal').addEventListener('click',function(e){if(e.target===this)closeDiscardModal();});
document.addEventListener('keydown',function(e){if(e.key==='Escape')closeDiscardModal();});
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
</script>
@endpush