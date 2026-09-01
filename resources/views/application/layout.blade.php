@extends('layouts.app')

@section('title', 'NGO Application — DonateBazaar')

@push('styles')
@vite('resources/css/public/application.css')
@endpush

@push('scripts')
@vite('resources/js/public/application.js')
@endpush

@section('content')

{{-- ══ SUCCESS OVERLAY ══ --}}
<div class="success-overlay" id="successOverlay">
  <div class="success-modal">
    <div class="success-icon-ring">
      <div class="success-icon-inner">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
          <path d="M20 6L9 17l-5-5"/>
        </svg>
      </div>
    </div>
    <div class="confetti-row">
      <div class="cdot"></div><div class="cdot"></div><div class="cdot"></div>
      <div class="cdot"></div><div class="cdot"></div>
    </div>
    <h2 class="success-title">Application submitted!</h2>
    <p class="success-sub">
      Your NGO application is now <strong>under review</strong>.<br>
      Our verification team will respond within <strong>5–7 business days</strong>.
    </p>
    <div class="success-timeline">
      <div class="success-step">
        <div class="snum">1</div>
        <div class="stext"><strong>Received</strong>Application logged</div>
      </div>
      <div class="success-step">
        <div class="snum">2</div>
        <div class="stext"><strong>Verification</strong>Team reviews docs</div>
      </div>
      <div class="success-step">
        <div class="snum">3</div>
        <div class="stext"><strong>Approved</strong>Go live on platform</div>
      </div>
    </div>
    <x-button variant="primary" href="{{ route('dashboard') }}">
      <svg class="btn-icon-sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <rect x="3" y="3" width="7" height="7" rx="1"/>
        <rect x="14" y="3" width="7" height="7" rx="1"/>
        <rect x="14" y="14" width="7" height="7" rx="1"/>
        <rect x="3" y="14" width="7" height="7" rx="1"/>
      </svg>
      Go to dashboard
    </x-button>
  </div>
</div>

{{-- ══ PAGE SHELL ══ --}}
<div class="page-shell">
  <div class="grid-dots"></div>
  <div class="shell-inner">

    {{-- Header --}}
    <div class="page-header">
      <div class="page-eyebrow"><span></span> NGO Partner Application</div>
      <h1 class="page-title">Register your organisation</h1>
      <p class="page-subtitle">Join thousands of verified NGOs raising funds transparently on DonateBazaar.</p>
    </div>

    {{-- Stepper --}}
    <div class="stepper-wrap">

      {{-- Step 1 --}}
      <div class="stepper-item {{ $currentStep == 1 ? 'active' : ($currentStep > 1 ? 'done' : '') }}" id="sitem-1">
        <div class="stepper-dot {{ $currentStep == 1 ? 'active' : ($currentStep > 1 ? 'done' : '') }}">
          @if($currentStep > 1)
            <svg class="stepper-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 6L9 17l-5-5"/></svg>
          @else
            1
          @endif
        </div>
        <span class="stepper-label {{ $currentStep == 1 ? 'active' : ($currentStep > 1 ? 'done' : '') }}">Org Info</span>
      </div>

      {{-- Step 2 --}}
      <div class="stepper-item {{ $currentStep == 2 ? 'active' : ($currentStep > 2 ? 'done' : '') }}" id="sitem-2">
        <div class="stepper-dot {{ $currentStep == 2 ? 'active' : ($currentStep > 2 ? 'done' : '') }}">
          @if($currentStep > 2)
            <svg class="stepper-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 6L9 17l-5-5"/></svg>
          @else
            2
          @endif
        </div>
        <span class="stepper-label {{ $currentStep == 2 ? 'active' : ($currentStep > 2 ? 'done' : '') }}">Contact</span>
      </div>

      {{-- Step 3 --}}
      <div class="stepper-item {{ $currentStep == 3 ? 'active' : ($currentStep > 3 ? 'done' : '') }}" id="sitem-3">
        <div class="stepper-dot {{ $currentStep == 3 ? 'active' : ($currentStep > 3 ? 'done' : '') }}">
          @if($currentStep > 3)
            <svg class="stepper-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 6L9 17l-5-5"/></svg>
          @else
            3
          @endif
        </div>
        <span class="stepper-label {{ $currentStep == 3 ? 'active' : ($currentStep > 3 ? 'done' : '') }}">Legal &amp; Certs</span>
      </div>

      {{-- Step 4 --}}
      <div class="stepper-item {{ $currentStep == 4 ? 'active' : '' }}" id="sitem-4">
        <div class="stepper-dot {{ $currentStep == 4 ? 'active' : '' }}">4</div>
        <span class="stepper-label {{ $currentStep == 4 ? 'active' : '' }}">Documents</span>
      </div>

    </div>

    {{-- Form Card --}}
    <div class="form-card">

      {{-- Progress bar --}}
      <div class="progress-track">
        <div class="progress-fill" style="width:{{ $currentStep * 25 }}%"></div>
      </div>

      {{-- Card header --}}
      <div class="card-header">
        <div class="step-badge">Step {{ $currentStep }} of 4</div>
        <div class="step-heading">
          @if($currentStep == 1) Organisation info
          @elseif($currentStep == 2) Contact person
          @elseif($currentStep == 3) Legal &amp; certifications
          @else Documents &amp; review
          @endif
        </div>
        <p class="step-sub">
          @if($currentStep == 1) Tell us about your organisation's core details and mission.
          @elseif($currentStep == 2) Who should we reach out to regarding this application?
          @elseif($currentStep == 3) Legal registrations, certifications, and bank details.
          @else Upload supporting documents and review before submitting.
          @endif
        </p>
      </div>

      {{-- Card body — step content injected here --}}
      <div class="card-body">

        @yield('step_content')

        {{-- Validation errors --}}
        @if ($errors->any())
          <x-alert type="danger">
            <ul>
              @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </x-alert>
        @endif

        {{-- Navigation --}}
        <div class="form-nav">

          {{-- Back button --}}
          @if($currentStep > 1)
            <x-button variant="secondary" href="{{ route('application.step' . ($currentStep - 1)) }}">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M19 12H5M12 19l-7-7 7-7"/>
              </svg>
              Back
            </x-button>
          @else
            <div></div>
          @endif

          <div style="flex:1"></div>

          {{-- Next / Submit button --}}
          @if($currentStep < 4)
            <x-button variant="primary" type="submit">
              Save &amp; continue
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M5 12h14M12 5l7 7-7 7"/>
              </svg>
            </x-button>
          @else
            <x-button variant="primary" type="submit">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M5 13l4 4L19 7"/>
              </svg>
              Submit application
            </x-button>
          @endif

        </div>

      </div>{{-- /.card-body --}}
    </div>{{-- /.form-card --}}

    <span class="step-pill">Step {{ $currentStep }} of 4</span>

  </div>
</div>

@endsection