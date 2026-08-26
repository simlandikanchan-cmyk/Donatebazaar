@push('page_css')
@vite('resources/css/admin/entries/volunteers.css')
@endpush

@extends('layouts.admin')

@section('sidebar_volunteer_assignments', 'active')
@section('page_title', 'New Assignment')
@section('page_subtitle', 'Assign a volunteer to an event or campaign')

@section('topbar_left')
  <a href="{{ route('admin.volunteer_assignments.index') }}" class="btn btn-secondary back-btn">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 12H5M12 19l-7-7 7-7"/></svg>
    Assignments
  </a>
@endsection

@php
  $isEdit = isset($assignment);
  $vol = $isEdit ? $assignment->volunteer_id : ($preselected->id ?? old('volunteer_id'));
  $ev = $isEdit ? $assignment->event_id : old('event_id');
  $cp = $isEdit ? $assignment->campaign_id : old('campaign_id');
  $role = $isEdit ? $assignment->role : old('role');
  $sd = $isEdit ? ($assignment->start_date?->format('Y-m-d')) : old('start_date');
  $ed = $isEdit ? ($assignment->end_date?->format('Y-m-d')) : old('end_date');
  $status = $isEdit ? $assignment->status : old('status', 'active');
@endphp

@push('page_styles')
<style>
.back-btn{display:inline-flex;align-items:center;gap:7px;height:36px;padding:0 16px;background:var(--surface2);color:var(--text2);border:1px solid var(--border2);border-radius:var(--r-sm);font-size:12.5px;font-weight:600;cursor:pointer;transition:all var(--ease);font-family:var(--font);text-decoration:none;}
.back-btn:hover{border-color:var(--a);color:var(--a);background:var(--a-lt);}
.back-btn svg{width:13px;height:13px;}
.card{background:var(--surface);border:1px solid var(--border);border-radius:var(--r);box-shadow:var(--sh);overflow:hidden;max-width:760px;}
.card-head{display:flex;align-items:center;gap:10px;padding:14px 20px;border-bottom:1px solid var(--border);background:var(--surface2);}
.card-head-icon{width:30px;height:30px;border-radius:8px;background:var(--a-lt);color:var(--a);display:flex;align-items:center;justify-content:center;}
.card-head-icon svg{width:14px;height:14px;}
.card-head-title{font-size:11.5px;font-weight:700;color:var(--text2);text-transform:uppercase;letter-spacing:.09em;font-family:var(--mono);}
.card-body{padding:22px;}
.field{margin-bottom:18px;}
.field:last-child{margin-bottom:0;}
.f-label{display:block;font-size:11.5px;font-weight:600;color:var(--text2);margin-bottom:7px;font-family:var(--mono);text-transform:uppercase;letter-spacing:.06em;}
.f-label .req{color:var(--red);margin-left:2px;}
.f-input{width:100%;background:var(--surface2);border:1px solid var(--border2);border-radius:var(--r-sm);padding:10px 13px;font-size:13px;color:var(--text);font-family:var(--font);outline:none;transition:border-color .2s,box-shadow .2s,background .2s;}
.f-input:focus{border-color:var(--a);box-shadow:0 0 0 3px var(--a-glow);background:var(--surface);}
.f-input.err{border-color:var(--red);}
.f-hint{font-size:11px;color:var(--text3);margin-top:5px;line-height:1.5;}
.f-error{font-size:11.5px;color:var(--red);margin-top:5px;font-family:var(--mono);}
.submit-btn{display:inline-flex;align-items:center;justify-content:center;gap:8px;padding:12px 22px;background:linear-gradient(135deg,var(--a),var(--a2));color:#fff;border:none;border-radius:var(--r-sm);font-size:14px;font-weight:700;cursor:pointer;font-family:var(--mono);transition:opacity .2s,transform .15s;box-shadow:0 4px 18px rgba(37,99,235,.35);}
.submit-btn:hover{opacity:.88;transform:translateY(-1px);}
.submit-btn svg{width:15px;height:15px;}
.alert-error{background:var(--red-lt);border:1px solid rgba(240,68,68,.22);color:#b91c1c;padding:12px 16px;border-radius:var(--r-sm);font-size:13px;margin-bottom:20px;display:flex;align-items:flex-start;gap:10px;}
.alert-error svg{width:15px;height:15px;flex-shrink:0;margin-top:1px;}
[data-theme="dark"] .alert-error{color:#f87171;}
.grid-2{display:grid;grid-template-columns:1fr 1fr;gap:18px;}
@media(max-width:640px){.grid-2{grid-template-columns:1fr;}}
</style>
@endpush

@section('content')
@if($errors->any())
<div class="alert-error">
  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
  <div>
    <strong>Please fix the following:</strong>
    <ul style="margin-top:4px;padding-left:16px;">
      @foreach($errors->all() as $e)<li style="font-size:12px;margin-top:2px;">{{ $e }}</li>@endforeach
    </ul>
  </div>
</div>
@endif

<form method="POST" action="{{ $isEdit ? route('admin.volunteer_assignments.update', $assignment->id) : route('admin.volunteer_assignments.store') }}">
  @if($isEdit) @csrf @method('PUT') @else @csrf @endif
  <div class="card">
    <div class="card-head">
      <div class="card-head-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0"/></svg></div>
      <span class="card-head-title">Assignment Details</span>
    </div>
    <div class="card-body">
      <div class="field">
        <label class="f-label" for="volunteer_id">Volunteer <span class="req">*</span></label>
        <select id="volunteer_id" name="volunteer_id" class="f-input {{ $errors->has('volunteer_id')?'err':'' }}" required>
          <option value="">Select volunteer…</option>
          @foreach($volunteers as $v)
            <option value="{{ $v->id }}" {{ $vol==$v->id?'selected':'' }}>{{ $v->user->name ?? 'Volunteer #'.$v->id }} ({{ $v->user->email ?? '' }})</option>
          @endforeach
        </select>
        @error('volunteer_id')<p class="f-error">{{ $message }}</p>@enderror
      </div>

      <div class="grid-2">
        <div class="field">
          <label class="f-label" for="event_id">Event</label>
          <select id="event_id" name="event_id" class="f-input {{ $errors->has('event_id')?'err':'' }}">
            <option value="">— None —</option>
            @foreach($events as $e)
              <option value="{{ $e->id }}" {{ $ev==$e->id?'selected':'' }}>{{ $e->title }} ({{ $e->event_date?->format('M d, Y') }})</option>
            @endforeach
          </select>
          <p class="f-hint">Assign to an event or a campaign (at least one).</p>
          @error('event_id')<p class="f-error">{{ $message }}</p>@enderror
        </div>
        <div class="field">
          <label class="f-label" for="campaign_id">Campaign</label>
          <select id="campaign_id" name="campaign_id" class="f-input {{ $errors->has('campaign_id')?'err':'' }}">
            <option value="">— None —</option>
            @foreach($campaigns as $c)
              <option value="{{ $c->id }}" {{ $cp==$c->id?'selected':'' }}>{{ $c->title }}</option>
            @endforeach
          </select>
          @error('campaign_id')<p class="f-error">{{ $message }}</p>@enderror
        </div>
      </div>

      <div class="field">
        <label class="f-label" for="role">Role <span class="req">*</span></label>
        <input id="role" name="role" type="text" value="{{ $role }}" class="f-input {{ $errors->has('role')?'err':'' }}" placeholder="e.g. On-site coordinator, Social media, Logistics" required>
        @error('role')<p class="f-error">{{ $message }}</p>@enderror
      </div>

      <div class="grid-2">
        <div class="field">
          <label class="f-label" for="start_date">Start Date</label>
          <input id="start_date" name="start_date" type="date" value="{{ $sd }}" class="f-input {{ $errors->has('start_date')?'err':'' }}">
          @error('start_date')<p class="f-error">{{ $message }}</p>@enderror
        </div>
        <div class="field">
          <label class="f-label" for="end_date">End Date</label>
          <input id="end_date" name="end_date" type="date" value="{{ $ed }}" class="f-input {{ $errors->has('end_date')?'err':'' }}">
          @error('end_date')<p class="f-error">{{ $message }}</p>@enderror
        </div>
      </div>

      <div class="field">
        <label class="f-label" for="status">Status <span class="req">*</span></label>
        <select id="status" name="status" class="f-input {{ $errors->has('status')?'err':'' }}" required>
          <option value="active" {{ $status==='active'?'selected':'' }}>Active</option>
          <option value="completed" {{ $status==='completed'?'selected':'' }}>Completed</option>
          <option value="cancelled" {{ $status==='cancelled'?'selected':'' }}>Cancelled</option>
        </select>
        @error('status')<p class="f-error">{{ $message }}</p>@enderror
      </div>
    </div>
  </div>

  <div style="margin-top:18px;display:flex;gap:10px;">
    <button type="submit" class="btn btn-primary submit-btn">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M20 6L9 17l-5-5"/></svg>
      {{ $isEdit ? 'Save Changes' : 'Create Assignment' }}
    </button>
    <a href="{{ route('admin.volunteer_assignments.index') }}" class="btn btn-secondary back-btn" style="text-decoration:none;">Cancel</a>
  </div>
</form>
@endsection
