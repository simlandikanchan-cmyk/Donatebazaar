@extends('layouts.app')

@section('content')

<style>
:root{
  --purple-deep:#0f766e;
  --purple-main:#2563eb ;
  --purple-soft:#dbeafe;
  --ink:#1f2233;
  --muted:#6b7188;
  --line:rgba(20,20,40,.10);
  --ok:#16a34a;
  --err:#dc2626;
}
.vol-page{font-family:'Inter',system-ui,sans-serif;color:var(--ink);background:
  radial-gradient(1200px 500px at 50% -10%, rgba(37,99,235,.10), transparent 60%),
  #fbfaff;min-height:100vh;}
.vol-hero{max-width:1080px;margin:0 auto;padding:64px 22px 18px;text-align:center;}
.vol-eyebrow{display:inline-block;font-size:12px;font-weight:600;letter-spacing:.16em;text-transform:uppercase;color:var(--purple-main);background:var(--purple-soft);padding:7px 14px;border-radius:30px;margin-bottom:18px;}
.vol-hero h1{font-family:'Inter',system-ui,sans-serif;font-size:clamp(30px,5vw,46px);font-weight:600;line-height:1.1;margin-bottom:14px;}
.vol-hero p{max-width:620px;margin:0 auto;color:var(--muted);font-size:16px;line-height:1.6;}

.vol-wrap{max-width:1080px;margin:0 auto;padding:26px 22px 70px;display:grid;grid-template-columns:1.15fr .85fr;gap:26px;align-items:start;}
@media(max-width:860px){.vol-wrap{grid-template-columns:1fr;}}

.vol-card{background:#fff;border:1px solid var(--line);border-radius:22px;padding:30px;box-shadow:0 18px 50px rgba(76,29,149,.08);}
.vol-notice{background:var(--purple-soft);border:1px solid rgba(37,99,235,.18);color:var(--purple-deep);font-size:13.5px;padding:13px 15px;border-radius:13px;margin-bottom:20px;line-height:1.55;}
.vol-notice a{color:var(--purple-main);font-weight:600;text-decoration:underline;}

.vol-form .vol-field{margin-bottom:18px;}
.vol-form label{display:block;font-size:13px;font-weight:600;color:var(--ink);margin-bottom:8px;}
.vol-form input,.vol-form select,.vol-form textarea{
  width:100%;border:1px solid var(--line);border-radius:12px;padding:13px 14px;font-size:14px;font-family:inherit;color:var(--ink);background:#fff;outline:none;transition:border-color .15s,box-shadow .15s;resize:vertical;
}
.vol-form input:focus,.vol-form select:focus,.vol-form textarea:focus{border-color:var(--purple-main);box-shadow:0 0 0 3px rgba(37,99,235,.16);}
.vol-form .vol-error{color:var(--err);font-size:12px;margin-top:5px;}
.vol-submit{width:100%;margin-top:6px;padding:14px;border:none;border-radius:13px;background:linear-gradient(135deg,var(--purple-main),var(--purple-deep));color:#fff;font-size:15px;font-weight:600;cursor:pointer;transition:transform .15s,box-shadow .15s;box-shadow:0 10px 26px rgba(37,99,235,.32);}
.vol-submit:hover{transform:translateY(-2px);box-shadow:0 14px 32px rgba(37,99,235,.40);}

.vol-aside{display:flex;flex-direction:column;gap:16px;}
.vol-benefit{background:#fff;border:1px solid var(--line);border-radius:18px;padding:20px 22px;display:flex;gap:14px;align-items:flex-start;box-shadow:0 10px 30px rgba(76,29,149,.05);}
.vol-benefit .vb-ico{width:38px;height:38px;border-radius:11px;background:var(--purple-soft);color:var(--purple-main);display:flex;align-items:center;justify-content:center;flex-shrink:0;}
.vol-benefit .vb-ico svg{width:19px;height:19px;}
.vol-benefit h4{font-size:15px;font-weight:600;margin-bottom:4px;}
.vol-benefit p{font-size:13px;color:var(--muted);line-height:1.55;}

/* ── Toast (mirrors partnership page) ── */
.toast-stack{position:fixed;bottom:24px;right:24px;z-index:9999;display:flex;flex-direction:column;gap:12px;max-width:360px;}
.toast{position:relative;display:flex;align-items:flex-start;gap:12px;background:#fff;border:1px solid var(--line);border-left:4px solid var(--purple-main);border-radius:14px;padding:14px 16px;box-shadow:0 14px 40px rgba(20,20,40,.18);overflow:hidden;animation:toastIn .35s cubic-bezier(.34,1.56,.64,1) both;}
.toast.dismissing{animation:toastOut .3s ease forwards;}
.toast::after{content:"";position:absolute;left:0;bottom:0;height:3px;width:100%;background:rgba(20,20,40,.12);transform-origin:left;animation:toastProgress var(--toast-dur,5s) linear forwards;}
.toast-success{border-left-color:var(--ok);}
.toast-error{border-left-color:var(--err);}
.toast-warning{border-left-color:#f59e0b;}
.toast-info{border-left-color:var(--purple-main);}
.toast-icon{width:22px;height:22px;border-radius:50%;display:flex;align-items:center;justify-content:center;flex-shrink:0;color:#fff;}
.toast-success .toast-icon{background:var(--ok);}
.toast-error .toast-icon{background:var(--err);}
.toast-warning .toast-icon{background:#f59e0b;}
.toast-info .toast-icon{background:var(--purple-main);}
.toast-icon svg{width:14px;height:14px;}
.toast-body{flex:1;min-width:0;}
.toast-title{font-weight:600;font-size:14px;line-height:1.3;margin-bottom:3px;}
.toast-msg{font-size:12.5px;opacity:.88;line-height:1.5;}
.toast-close{background:rgba(20,20,40,.06);border:none;color:var(--muted);width:22px;height:22px;border-radius:7px;cursor:pointer;font-size:12px;flex-shrink:0;}
.toast-close:hover{background:rgba(20,20,40,.14);}
@keyframes toastIn{from{opacity:0;transform:translateY(14px) scale(.96);}to{opacity:1;transform:none;}}
@keyframes toastOut{to{opacity:0;transform:translateX(20px);}}
@keyframes toastProgress{from{transform:scaleX(1);}to{transform:scaleX(0);}}
</style>

<div class="vol-page">
  <section class="vol-hero">
    <span class="vol-eyebrow">Join the movement</span>
    <h1>Volunteer With Us</h1>
    <p>Turn compassion into action. Lend your time and skills to campaigns that matter — from on-ground relief to community storytelling.</p>
  </section>

  <section class="vol-wrap">
    <div class="vol-card">
      @guest
        <div class="vol-notice">
          You need to <a href="{{ route('login') }}">log in</a> or
          <a href="{{ route('register') }}">create an account</a> to submit a volunteer application.
        </div>
      @endguest

      <form method="POST" action="{{ route('volunteer.apply.store') }}" class="vol-form">
        @csrf

        <div class="vol-field">
          <label for="campaign_id">Campaign (optional)</label>
          <select id="campaign_id" name="campaign_id">
            <option value="">General volunteering</option>
            @foreach($campaigns as $c)
              <option value="{{ $c->id }}" @selected(old('campaign_id', $c->id) == $c->id)>{{ \Illuminate\Support\Str::limit($c->title, 70) }}</option>
            @endforeach
          </select>
        </div>

        <div class="vol-field">
          <label for="phone">Phone Number <span style="color:var(--err);">*</span></label>
          <input id="phone" name="phone" type="tel" placeholder="10-digit mobile number" value="{{ old('phone') }}" required maxlength="10" pattern="[0-9]{10}">
          @error('phone') <div class="vol-error">{{ $message }}</div> @enderror
        </div>

        <div class="vol-field">
          <label for="state">State (optional)</label>
          <select id="state" name="state" class="vol-input">
            <option value="">Select your state...</option>
          </select>
        </div>

        <div class="vol-field">
          <label for="city">City (optional)</label>
          <div class="vol-city-wrap">
            <input id="city" name="city" type="text" placeholder="Your city" value="{{ old('city') }}" maxlength="120" autocomplete="off">
            <div id="city-suggestions" class="vol-city-suggest"></div>
          </div>
          @error('city') <div class="vol-error">{{ $message }}</div> @enderror
        </div>

        <div class="vol-field">
          <label for="availability">Availability <span style="color:var(--err);">*</span></label>
          <select id="availability" name="availability" required>
            <option value="">Select your availability…</option>
            <option value="full_time" @selected(old('availability') == 'full_time')>Full time</option>
            <option value="part_time" @selected(old('availability') == 'part_time')>Part time</option>
            <option value="weekends" @selected(old('availability') == 'weekends')>Weekends only</option>
          </select>
          @error('availability') <div class="vol-error">{{ $message }}</div> @enderror
        </div>

        <div class="vol-field">
          <label for="message">Why do you want to volunteer? (optional)</label>
          <textarea id="message" name="message" rows="5" placeholder="Tell us a bit about yourself, your skills, or the cause you care about…">{{ old('message') }}</textarea>
        </div>

        <button type="submit" class="vol-submit">Submit Application</button>
      </form>
    </div>

    <aside class="vol-aside">
      <div class="vol-benefit">
        <div class="vb-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21s-7-4.35-9.5-8.5C.5 9 2 5 5.5 5 7.5 5 9 6.5 12 9c3-2.5 4.5-4 6.5-4C22 5 23.5 9 21.5 12.5 19 16.65 12 21 12 21z"/></svg></div>
        <div><h4>Make real impact</h4><p>Support verified campaigns and see exactly how your effort helps communities.</p></div>
      </div>
      <div class="vol-benefit">
        <div class="vb-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0"/></svg></div>
        <div><h4>Flexible commitments</h4><p>From one-off events to ongoing roles — volunteer at a pace that fits your life.</p></div>
      </div>
      <div class="vol-benefit">
        <div class="vb-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></div>
        <div><h4>Get recognized</h4><p>Verified volunteers receive impact certificates and priority access to new initiatives.</p></div>
      </div>
    </aside>
  </section>
</div>

<div class="toast-stack" id="toastStack" role="status" aria-live="polite" aria-atomic="false"></div>

<script>
(function(){
  'use strict';

  var stack = document.getElementById('toastStack');

  function toast(opts){
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

    t.addEventListener('mouseenter', function(){ clearTimeout(t._timer); t.style.setProperty('--toast-dur','0s'); t.style.animationPlayState='paused'; });
    t.addEventListener('mouseleave', function(){ t._timer = setTimeout(function(){ dismiss(t); }, 2000); });
  }

  function dismiss(t){
    if (!t.parentNode) return;
    t.classList.add('dismissing');
    setTimeout(function(){ if(t.parentNode) t.parentNode.removeChild(t); }, 320);
  }

  window._toast = toast;

  @if(session('success'))
    setTimeout(function(){
      toast({ type:'success', title:'Application Submitted!', message: @json(session('success')), duration:6000 });
    }, 300);
  @endif

  @if(session('error'))
    setTimeout(function(){
      toast({ type:'error', title:'Something went wrong', message: @json(session('error')), duration:7000 });
    }, 300);
  @endif

  @if(isset($errors) && $errors->any())
    setTimeout(function(){
      toast({
        type: 'error',
        title: 'Please fix {{ $errors->count() }} error{{ $errors->count() > 1 ? "s" : "" }}',
        message: 'Check the form fields highlighted below.',
        duration: 8000
      });
    }, 300);
  @endif
})();
</script>

@endsection

@vite(['resources/css/volunteer-apply.css', 'resources/js/volunteer-city.js'])
