@extends('layouts.admin')

@section('sidebar_job_applicants', 'active')
@section('page_title', $jobPostApplication->name)
@section('page_subtitle', 'Application details')

@section('topbar_left')
<a href="{{ route('admin.job_post_applications.index') }}" class="btn btn-secondary back-btn">
  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 12H5M12 19l-7-7 7-7"/></svg>
  All Applications
</a>
@endsection

@section('content')

    {{-- ── FLASH ── --}}
    @if(session('success'))
    <div class="flash flash-success">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
      {{ session('success') }}
    </div>
    @endif
    @if(session('error'))
    <div class="flash flash-error">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
      {{ session('error') }}
    </div>
    @endif

    {{-- ── HERO STRIP ── --}}
    <div class="hero-strip">
      <div class="hs-left">
        <div class="hs-avatar">{{ strtoupper(substr($jobPostApplication->name, 0, 1)) }}</div>
        <div>
          <div class="hs-name">{{ $jobPostApplication->name }}</div>
          <div class="hs-sub">Applied {{ $jobPostApplication->created_at->diffForHumans() }} &middot; {{ $jobPostApplication->created_at->format('d M Y, h:i A') }}</div>
        </div>
      </div>
      <div class="hs-right">
        <span class="badge b-{{ $jobPostApplication->status }}">{{ $jobPostApplication->status }}</span>
        @if($jobPostApplication->cv_path)
        <a href="{{ route('admin.job_post_applications.downloadCv', $jobPostApplication) }}" class="cv-btn" style="padding:8px 14px;font-size:12px;">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
          Download CV
        </a>
        @endif
      </div>
    </div>

    {{-- ── CONTENT GRID ── --}}
    <div class="content-grid">

      {{-- LEFT COLUMN --}}
      <div style="display:flex;flex-direction:column;gap:16px;">

        {{-- Contact Info --}}
        <div class="card" style="animation-delay:.05s;">
          <div class="card-header">
            <div class="card-header-left">
              <div class="card-hico" style="background:var(--a-lt);">
                <svg viewBox="0 0 24 24" fill="none" stroke="var(--a)" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
              </div>
              <span class="card-title">Applicant Details</span>
            </div>
          </div>
          <div class="card-body">
            <div class="applicant-grid">
              <div class="field">
                <div class="field-lbl">Full Name</div>
                <div class="field-val">{{ $jobPostApplication->name }}</div>
              </div>
              <div class="field">
                <div class="field-lbl">Email Address</div>
                <div class="field-val"><a href="mailto:{{ $jobPostApplication->email }}">{{ $jobPostApplication->email }}</a></div>
              </div>
              @if($jobPostApplication->phone)
              <div class="field">
                <div class="field-lbl">Phone</div>
                <div class="field-val"><a href="tel:{{ $jobPostApplication->phone }}">{{ $jobPostApplication->phone }}</a></div>
              </div>
              @endif
              <div class="field">
                <div class="field-lbl">Applied On</div>
                <div class="field-val">{{ $jobPostApplication->created_at->format('d M Y \a\t h:i A') }}</div>
              </div>
            </div>
          </div>
        </div>

        {{-- Cover Letter --}}
        @if($jobPostApplication->cover_letter)
        <div class="card" style="animation-delay:.10s;">
          <div class="card-header">
            <div class="card-header-left">
              <div class="card-hico" style="background:var(--blue-lt);">
                <svg viewBox="0 0 24 24" fill="none" stroke="var(--blue)" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
              </div>
              <span class="card-title">Cover Letter</span>
            </div>
          </div>
          <div class="card-body">
            <p class="cover-letter">{{ $jobPostApplication->cover_letter }}</p>
          </div>
        </div>
        @endif

        {{-- Job Post --}}
        <div class="card" style="animation-delay:.15s;">
          <div class="card-header">
            <div class="card-header-left">
              <div class="card-hico" style="background:var(--green-lt);">
                <svg viewBox="0 0 24 24" fill="none" stroke="var(--green)" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
              </div>
              <span class="card-title">Applied For</span>
            </div>
            <a href="{{ route('admin.job_posts.show', $jobPostApplication->jobPost) }}" class="card-link">
              View Post
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
            </a>
          </div>
          <div class="card-body">
            <div class="field-val" style="font-size:15px;font-weight:700;">{{ $jobPostApplication->jobPost->title }}</div>
            <div class="job-chips">
              <span class="job-chip">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                {{ ucfirst($jobPostApplication->jobPost->type) }}
              </span>
              @if($jobPostApplication->jobPost->location)
              <span class="job-chip">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                {{ $jobPostApplication->jobPost->location }}
              </span>
              @endif
              @if($jobPostApplication->jobPost->salary)
              <span class="job-chip">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                &#8377;{{ $jobPostApplication->jobPost->salary }}
              </span>
              @endif
            </div>
          </div>
        </div>

      </div>

      {{-- RIGHT COLUMN --}}
      <div style="display:flex;flex-direction:column;gap:16px;">

        {{-- Review & Decision --}}
        <div class="card" style="animation-delay:.08s;">
          <div class="card-header">
            <div class="card-header-left">
              <div class="card-hico" style="background:var(--amber-lt);">
                <svg viewBox="0 0 24 24" fill="none" stroke="var(--amber)" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
              </div>
              <span class="card-title">Review &amp; Decision</span>
            </div>
          </div>
          <div class="card-body">
            <div class="status-preview">
              <span class="sp-label">Current Status</span>
              <span class="badge b-{{ $jobPostApplication->status }}">{{ $jobPostApplication->status }}</span>
            </div>
            <form method="POST" action="{{ route('admin.job_post_applications.updateStatus', $jobPostApplication) }}">
              @csrf
              @method('PATCH')
              <div class="form-group">
                <label class="form-lbl">Update Status</label>
                <select name="status" class="form-select">
                  @foreach(['pending','shortlisted','rejected','hired'] as $s)
                    <option value="{{ $s }}" @selected($jobPostApplication->status === $s)>{{ ucfirst($s) }}</option>
                  @endforeach
                </select>
              </div>
              <div class="form-group">
                <label class="form-lbl">Admin Notes</label>
                <textarea name="admin_notes" class="form-textarea" placeholder="Internal notes about this applicant…">{{ old('admin_notes', $jobPostApplication->admin_notes) }}</textarea>
              </div>
              <button type="submit" class="btn-save">Save Decision</button>
            </form>
          </div>
        </div>

        {{-- CV Download --}}
        @if($jobPostApplication->cv_path)
        <div class="card" style="animation-delay:.12s;">
          <div class="card-header">
            <div class="card-header-left">
              <div class="card-hico" style="background:var(--green-lt);">
                <svg viewBox="0 0 24 24" fill="none" stroke="var(--green)" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
              </div>
              <span class="card-title">Resume / CV</span>
            </div>
          </div>
          <div class="card-body">
            <a href="{{ route('admin.job_post_applications.downloadCv', $jobPostApplication) }}" class="cv-btn" style="width:100%;justify-content:center;">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
              Download CV
            </a>
          </div>
        </div>
        @endif

        {{-- Activity Timeline --}}
        <div class="card" style="animation-delay:.16s;">
          <div class="card-header">
            <div class="card-header-left">
              <div class="card-hico" style="background:var(--a-lt);">
                <svg viewBox="0 0 24 24" fill="none" stroke="var(--a)" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
              </div>
              <span class="card-title">Timeline</span>
            </div>
          </div>
          <div class="card-body" style="padding:0;">
            <div class="timeline" style="padding:0 20px;">
              <div class="tl-item">
                <div class="tl-dot" style="background:var(--a-lt);">
                  <svg viewBox="0 0 24 24" fill="none" stroke="var(--a)" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                </div>
                <div>
                  <div class="tl-label">Application submitted</div>
                  <div class="tl-time">{{ $jobPostApplication->created_at->format('d M Y, h:i A') }}</div>
                </div>
              </div>
              @if($jobPostApplication->updated_at && $jobPostApplication->updated_at->ne($jobPostApplication->created_at))
              <div class="tl-item">
                <div class="tl-dot" style="background:var(--amber-lt);">
                  <svg viewBox="0 0 24 24" fill="none" stroke="var(--amber)" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                </div>
                <div>
                  <div class="tl-label">Status updated to <strong>{{ $jobPostApplication->status }}</strong></div>
                  <div class="tl-time">{{ $jobPostApplication->updated_at->format('d M Y, h:i A') }}</div>
                </div>
              </div>
              @endif
              <div class="tl-item" style="border-bottom:none;">
                @php
                  $isResolved = in_array($jobPostApplication->status, ['shortlisted','rejected','hired']);
                @endphp
                <div class="tl-dot" style="background:{{ $isResolved ? 'var(--green-lt)' : 'var(--surface3)' }};">
                  <svg viewBox="0 0 24 24" fill="none" stroke="{{ $isResolved ? 'var(--green)' : 'var(--text3)' }}" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                  <div class="tl-label" style="{{ $isResolved ? '' : 'color:var(--text3)' }}">{{ $isResolved ? 'Review complete' : 'Awaiting decision' }}</div>
                  <div class="tl-time">{{ $isResolved ? $jobPostApplication->updated_at->format('d M Y') : 'Pending' }}</div>
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>{{-- /.content-grid --}}

@endsection

@push('page_styles')
<style>
.back-btn{display:inline-flex;align-items:center;gap:7px;height:34px;padding:0 13px;border-radius:var(--r-sm);border:1px solid var(--border2);background:var(--surface2);font-size:12.5px;font-weight:500;color:var(--text2);font-family:var(--font);cursor:pointer;transition:all var(--ease);text-decoration:none;}
.back-btn:hover{border-color:var(--a);color:var(--a);background:var(--a-lt);}
.back-btn svg{width:13px;height:13px;}

.b-shortlisted{background:rgba(5,196,138,.85);color:#fff;}
.b-hired{background:rgba(37,99,235,.85);color:#fff;}

/* ── HERO STRIP ── */
.hero-strip{border-radius:18px;padding:22px 28px;margin-bottom:24px;display:flex;align-items:center;justify-content:space-between;gap:16px;position:relative;overflow:hidden;background:#07080f;animation:fadeUp .35s ease both;}
.hero-strip::before{content:'';position:absolute;inset:0;background:radial-gradient(ellipse 55% 90% at 85% -10%,rgba(37,99,235,.50) 0%,transparent 60%),radial-gradient(ellipse 40% 60% at 10% 110%,rgba(13,148,136,.30) 0%,transparent 55%);}
.hero-strip::after{content:'';position:absolute;inset:0;background-image:linear-gradient(rgba(255,255,255,.025) 1px,transparent 1px),linear-gradient(90deg,rgba(255,255,255,.025) 1px,transparent 1px);background-size:28px 28px;}
.hs-left{position:relative;z-index:2;display:flex;align-items:center;gap:16px;}
.hs-avatar{width:54px;height:54px;border-radius:14px;background:linear-gradient(135deg,var(--a),var(--a2));color:#fff;font-size:20px;font-weight:800;font-family:var(--mono);display:flex;align-items:center;justify-content:center;flex-shrink:0;box-shadow:0 4px 18px rgba(37,99,235,.45);}
.hs-name{font-family:var(--mono);font-size:20px;font-weight:800;color:#fff;letter-spacing:-.02em;line-height:1.15;background:linear-gradient(135deg,#fff 30%,rgba(184,169,255,.85));-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;}
.hs-sub{font-size:12.5px;color:rgba(255,255,255,.5);margin-top:3px;font-family:var(--mono);}
.hs-right{position:relative;z-index:2;display:flex;align-items:center;gap:10px;flex-wrap:wrap;}

/* ── CONTENT GRID ── */
.content-grid{display:grid;grid-template-columns:1fr 320px;gap:18px;align-items:start;}

/* ── CARDS ── */
.card{background:var(--surface);border:1px solid var(--border);border-radius:var(--r);box-shadow:var(--sh);overflow:hidden;animation:fadeUp .4s ease both;}
.card-header{padding:14px 20px;border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between;gap:10px;}
.card-header-left{display:flex;align-items:center;gap:10px;}
.card-hico{width:30px;height:30px;border-radius:8px;display:flex;align-items:center;justify-content:center;flex-shrink:0;}
.card-hico svg{width:14px;height:14px;}
.card-title{font-family:var(--font);font-size:12px;font-weight:700;color:var(--text);letter-spacing:-.01em;}
.card-title-sm{font-size:10px;color:var(--text3);font-family:var(--mono);font-weight:600;text-transform:uppercase;letter-spacing:.1em;}
.card-link{font-size:12px;color:var(--a);font-weight:600;text-decoration:none;display:inline-flex;align-items:center;gap:4px;transition:opacity var(--ease);}
.card-link:hover{opacity:.75;}
.card-link svg{width:11px;height:11px;}
.card-body{padding:20px;}
.card-body + .card-body{border-top:1px solid var(--border);}

/* ── FIELD ROWS ── */
.field{margin-bottom:16px;}
.field:last-child{margin-bottom:0;}
.field-lbl{font-family:var(--mono);font-size:9.5px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:var(--text3);margin-bottom:6px;}
.field-val{font-size:13.5px;color:var(--text);line-height:1.5;}
.field-val a{color:var(--a);font-weight:500;}
.field-val a:hover{text-decoration:underline;}
.field-divider{height:1px;background:var(--border);margin:16px 0;}

/* ── COVER LETTER ── */
.cover-letter{font-size:13.5px;line-height:1.85;color:var(--text2);white-space:pre-wrap;border-left:3px solid var(--a-lt);padding-left:16px;}

/* ── JOB CHIPS ── */
.job-chips{display:flex;flex-wrap:wrap;gap:8px;margin-top:10px;}
.job-chip{display:inline-flex;align-items:center;gap:5px;padding:4px 10px;border-radius:100px;font-size:11px;font-weight:500;color:var(--text2);background:var(--surface2);border:1px solid var(--border);font-family:var(--mono);}
.job-chip svg{width:11px;height:11px;color:var(--text3);}

/* ── CV BUTTON ── */
.cv-btn{display:inline-flex;align-items:center;gap:8px;padding:10px 16px;border-radius:var(--r-sm);background:var(--green-lt);border:1px solid rgba(5,196,138,.25);color:var(--green);font-size:13px;font-weight:600;text-decoration:none;transition:all var(--ease);font-family:var(--font);}
.cv-btn:hover{background:var(--green);color:#fff;border-color:var(--green);transform:translateY(-1px);box-shadow:0 4px 14px rgba(5,196,138,.3);}
.cv-btn svg{width:15px;height:15px;}

/* ── FORM ── */
.form-group{margin-bottom:16px;}
.form-group:last-child{margin-bottom:0;}
.form-lbl{font-family:var(--mono);font-size:9.5px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:var(--text3);margin-bottom:8px;display:block;}
.form-select,.form-textarea{width:100%;background:var(--surface2);border:1px solid var(--border2);border-radius:var(--r-sm);padding:10px 13px;font-size:13px;color:var(--text);font-family:var(--font);outline:none;transition:border-color var(--ease),box-shadow var(--ease);}
.form-select:focus,.form-textarea:focus{border-color:var(--a);box-shadow:0 0 0 3px var(--a-glow);}
.form-textarea{resize:vertical;min-height:100px;line-height:1.55;}
.form-select option{background:var(--surface);}
.btn-save{width:100%;padding:12px;border-radius:var(--r-sm);background:linear-gradient(135deg,var(--a),var(--a2));color:#fff;border:none;font-size:13.5px;font-weight:600;font-family:var(--font);cursor:pointer;transition:all var(--ease);box-shadow:0 4px 16px rgba(37,99,235,.3);}
.btn-save:hover{transform:translateY(-2px);box-shadow:0 8px 24px rgba(37,99,235,.45);}
.btn-save:active{transform:scale(.98);}

/* ── STATUS PREVIEW ── */
.status-preview{display:flex;align-items:center;justify-content:space-between;padding:12px 14px;border-radius:var(--r-sm);background:var(--surface2);border:1px solid var(--border);margin-bottom:16px;}
.sp-label{font-size:11px;color:var(--text3);font-family:var(--mono);}

/* ── TIMELINE ── */
.timeline{display:flex;flex-direction:column;gap:0;}
.tl-item{display:flex;gap:12px;padding:12px 0;}
.tl-item:not(:last-child){border-bottom:1px solid var(--border);}
.tl-dot{width:28px;height:28px;border-radius:8px;display:flex;align-items:center;justify-content:center;flex-shrink:0;margin-top:1px;}
.tl-dot svg{width:13px;height:13px;}
.tl-label{font-size:12.5px;font-weight:600;color:var(--text);line-height:1.3;}
.tl-time{font-size:11px;color:var(--text3);font-family:var(--mono);margin-top:2px;}

/* ── FLASH ── */
.flash{padding:12px 16px;border-radius:var(--r-sm);margin-bottom:20px;font-size:13px;font-weight:500;display:flex;align-items:center;gap:10px;animation:fadeUp .3s ease both;}
.flash-success{background:rgba(5,196,138,.10);border:1px solid rgba(5,196,138,.25);color:#059669;}
.flash-error{background:var(--red-lt);border:1px solid rgba(240,68,68,.25);color:var(--red);}
[data-theme="dark"] .flash-success{color:#34d399;}
[data-theme="dark"] .flash-error{color:#f87171;}
.flash svg{width:14px;height:14px;flex-shrink:0;}

/* ── RESPONSIVE ── */
@media(max-width:860px){.content-grid{grid-template-columns:1fr}}
.applicant-grid{display:grid;grid-template-columns:1fr 1fr;gap:16px;}
@media(max-width:600px){.applicant-grid{grid-template-columns:1fr;}}
@media(max-width:600px){.hs-avatar{width:42px;height:42px;font-size:16px}.hs-name{font-size:16px}}
@media(max-width:480px){.content-grid{gap:14px}.hs{padding:16px}.hs-avatar{width:38px;height:38px;font-size:14px}.hs-name{font-size:15px}.hs-sub{font-size:11px}.hs-badges{gap:4px}.hs-badge{font-size:9px;padding:3px 8px}.applicant-grid{gap:10px}.info-card{padding:14px}.info-label{font-size:9px}.info-value{font-size:13px}.sec-title{font-size:13px}.question-card{padding:14px}.question-card h5{font-size:13px}.q-meta{font-size:11px}.files-strip{gap:6px}.file-link{font-size:11px;padding:8px 12px}.back-link{font-size:11px;height:32px;padding:0 10px}.card-actions{flex-direction:column;gap:6px}.card-actions .btn{width:100%;justify-content:center}}
@media(max-width:380px){.hs{padding:12px}.hs-avatar{width:34px;height:34px;font-size:13px}.hs-name{font-size:14px}.hs-sub{font-size:10px}.applicant-grid{grid-template-columns:1fr}.info-card{padding:10px}.info-value{font-size:12px}.info-label{font-size:8px}.question-card{padding:12px}.question-card h5{font-size:12px}.q-meta{font-size:10px}.files-strip{flex-direction:column}.file-link{width:100%;justify-content:center}.flash{font-size:12px;padding:10px 12px}.empty-state{padding:30px 16px}}
</style>
@endpush
