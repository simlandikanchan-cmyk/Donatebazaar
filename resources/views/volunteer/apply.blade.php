@extends('layouts.app')

@section('title', 'Volunteer With Us — DonateBazaar')

@section('content')

<div class="vol-page">

  {{-- HERO --}}
  <section class="vol-hero">
    <div class="vol-hero-bg">
      <img src="https://images.unsplash.com/photo-1522071820081-009f0129c71c?w=1600&q=80" alt="Volunteers collaborating" loading="lazy">
    </div>
    <div class="vol-hero-inner">
      <div class="vol-hero-content">
        <span class="vol-eyebrow">Join the movement</span>
        <h1>Volunteer With Us</h1>
        <p>Turn compassion into action. Lend your time and skills to campaigns that matter — from on-ground relief to community storytelling. Every hour you give creates ripples of change.</p>
        <div class="vol-trust-badge">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
          Trusted by 12,500+ Volunteers
        </div>
        <div class="vol-hero-stats">
          <div class="vol-stat">
            <div class="vol-stat-num" data-count="12500">0</div>
            <div class="vol-stat-label">Volunteers</div>
          </div>
          <div class="vol-stat">
            <div class="vol-stat-num" data-count="340">0</div>
            <div class="vol-stat-label">Campaigns</div>
          </div>
          <div class="vol-stat">
            <div class="vol-stat-num" data-count="85">0</div>
            <div class="vol-stat-label">Partner NGOs</div>
          </div>
        </div>
      </div>
    </div>
  </section>

  {{-- MAIN CONTENT --}}
  <section class="vol-wrap">

    {{-- LEFT: FORM --}}
    <div class="vol-form-card">
      @guest
        <div class="vol-notice">
          You need to <a href="{{ route('login') }}">log in</a> or
          <a href="{{ route('register') }}">create an account</a> to submit a volunteer application.
        </div>
      @endguest

      <form method="POST" action="{{ route('volunteer.apply.store') }}" class="vol-form" id="volunteerForm">
        @csrf

        {{-- Personal Information --}}
        <div class="vol-section">
          <div class="vol-section-title">
            <div class="vol-sec-icon">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
            </div>
            <h3>Personal Information</h3>
          </div>

          <div class="vol-field">
            <label for="phone">Phone Number <span class="req">*</span></label>
            <input id="phone" name="phone" type="tel" placeholder="10-digit mobile number" value="{{ old('phone') }}" required maxlength="10" pattern="[0-9]{10}">
            @error('phone') <div class="vol-error">{{ $message }}</div> @enderror
          </div>

          <div class="vol-field">
            <label for="campaign_id">Campaign (optional)</label>
            <select id="campaign_id" name="campaign_id">
              <option value="">General volunteering</option>
              @foreach($campaigns as $c)
                <option value="{{ $c->id }}" @selected(old('campaign_id', $c->id) == $c->id)>{{ \Illuminate\Support\Str::limit($c->title, 70) }}</option>
              @endforeach
            </select>
          </div>
        </div>

        {{-- Location --}}
        <div class="vol-section">
          <div class="vol-section-title">
            <div class="vol-sec-icon">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>
            </div>
            <h3>Location</h3>
          </div>

          <div class="vol-field">
            <label for="country">Country</label>
            <select id="country" name="country">
              <option value="India" @selected(old('country', 'India') === 'India')>India</option>
              <option value="other" @selected(old('country') === 'other')>Other</option>
            </select>
            @error('country') <div class="vol-error">{{ $message }}</div> @enderror
          </div>

          <div class="vol-field" id="stateField">
            <label for="state">State</label>
            <select id="state" name="state">
              <option value="">Select state</option>
            </select>
            @error('state') <div class="vol-error">{{ $message }}</div> @enderror
          </div>

          <div class="vol-field">
            <label for="city">City</label>
            <div class="vol-city-wrap">
              <input id="city" name="city" type="text" placeholder="Your city" value="{{ old('city') }}" maxlength="120" autocomplete="off">
              <div id="city-suggestions" class="vol-city-suggest"></div>
            </div>
            @error('city') <div class="vol-error">{{ $message }}</div> @enderror
          </div>
        </div>

        {{-- Skills --}}
        <div class="vol-section">
          <div class="vol-section-title">
            <div class="vol-sec-icon">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87L18.18 22 12 18.56 5.82 22 7 14.14l-5-4.87 6.91-1.01L12 2z"/></svg>
            </div>
            <h3>Skills</h3>
          </div>

          <div class="vol-field">
            <label for="skills">Skills (comma separated)</label>
            <input id="skills" name="skills" type="text" placeholder="e.g. Teaching, Photography, Event Management" value="{{ old('skills') }}">
            <div class="vol-hint">Optional — helps us match you with the right campaigns</div>
            @error('skills') <div class="vol-error">{{ $message }}</div> @enderror
          </div>
        </div>

        {{-- Availability --}}
        <div class="vol-section">
          <div class="vol-section-title">
            <div class="vol-sec-icon">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
            </div>
            <h3>Availability</h3>
          </div>

          <div class="vol-field">
            <label for="availability">Availability <span class="req">*</span></label>
            <select id="availability" name="availability" required>
              <option value="">Select your availability…</option>
              <option value="full_time" @selected(old('availability') == 'full_time')>Full time</option>
              <option value="part_time" @selected(old('availability') == 'part_time')>Part time</option>
              <option value="weekends" @selected(old('availability') == 'weekends')>Weekends only</option>
            </select>
            @error('availability') <div class="vol-error">{{ $message }}</div> @enderror
          </div>
        </div>

        {{-- About You --}}
        <div class="vol-section">
          <div class="vol-section-title">
            <div class="vol-sec-icon">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z"/></svg>
            </div>
            <h3>About You</h3>
          </div>

          <div class="vol-field">
            <label for="bio">Bio</label>
            <textarea id="bio" name="bio" rows="3" placeholder="Tell us a bit about yourself…">{{ old('bio') }}</textarea>
            @error('bio') <div class="vol-error">{{ $message }}</div> @enderror
          </div>

          <div class="vol-field">
            <label for="message">Why do you want to volunteer? (optional)</label>
            <textarea id="message" name="message" rows="4" placeholder="Tell us about your motivation, skills, or the cause you care about…">{{ old('message') }}</textarea>
          </div>
        </div>

        {{-- Submit --}}
        <button type="submit" class="vol-cta" id="volSubmitBtn">
          <span class="spinner"></span>
          <span class="cta-text">Join Our Volunteer Network</span>
        </button>

        <div class="vol-privacy">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="12" height="12" style="vertical-align:middle;margin-right:4px;"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
          Your data is encrypted and secure. We never share your information with third parties.
          <a href="{{ route('privacy') }}">Privacy Policy</a>
        </div>
      </form>

      {{-- Success State --}}
      <div class="vol-success" id="volSuccess">
        <div class="vol-success-icon">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
        </div>
        <h3>Application Submitted!</h3>
        <p>Thank you for joining our volunteer network. We'll review your application and get back to you within 48 hours.</p>
      </div>
    </div>

    {{-- RIGHT: SIDEBAR --}}
    <aside class="vol-aside">

      {{-- Benefits --}}
      <div class="vol-benefit">
        <div class="vb-ico">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 21s-7-4.35-9.5-8.5C.5 9 2 5 5.5 5 7.5 5 9 6.5 12 9c3-2.5 4.5-4 6.5-4C22 5 23.5 9 21.5 12.5 19 16.65 12 21 12 21z"/></svg>
        </div>
        <div>
          <h4>Make Real Impact</h4>
          <p>Support verified campaigns and see exactly how your effort helps communities in need.</p>
        </div>
      </div>
      <div class="vol-benefit">
        <div class="vb-ico">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0"/></svg>
        </div>
        <div>
          <h4>Flexible Commitments</h4>
          <p>From one-off events to ongoing roles — volunteer at a pace that fits your life.</p>
        </div>
      </div>
      <div class="vol-benefit">
        <div class="vb-ico">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <div>
          <h4>Get Recognized</h4>
          <p>Verified volunteers receive impact certificates and priority access to new initiatives.</p>
        </div>
      </div>

      {{-- How It Works --}}
      <div class="vol-timeline">
        <h4>How It Works</h4>
        <div class="vol-timeline-step">
          <div class="vol-tl-dot">1</div>
          <div class="vol-tl-content">
            <h5>Apply</h5>
            <p>Fill out the form with your details and choose how you'd like to contribute.</p>
          </div>
        </div>
        <div class="vol-timeline-step">
          <div class="vol-tl-dot">2</div>
          <div class="vol-tl-content">
            <h5>Verify</h5>
            <p>We review your application and get back to you within 48 hours.</p>
          </div>
        </div>
        <div class="vol-timeline-step">
          <div class="vol-tl-dot">3</div>
          <div class="vol-tl-content">
            <h5>Start Volunteering</h5>
            <p>Get matched with campaigns and start making a difference in your community.</p>
          </div>
        </div>
      </div>

      {{-- Testimonial --}}
      <div class="vol-testimonial">
        <blockquote>"Volunteering with DonateBazaar changed my perspective. The team is organized, the campaigns are impactful, and I've met amazing people along the way."</blockquote>
        <div class="vol-test-author">
          <div class="vol-test-avatar">SR</div>
          <div>
            <div class="vol-test-name">Sunita R.</div>
            <div class="vol-test-role">Volunteer since 2023</div>
          </div>
        </div>
      </div>

      {{-- Community Stats --}}
      <div class="vol-stats">
        <h4>Community Impact</h4>
        <div class="vol-stats-grid">
          <div class="vol-stat-item">
            <div class="vol-stat-num">12,500+</div>
            <div class="vol-stat-label">Active Volunteers</div>
          </div>
          <div class="vol-stat-item">
            <div class="vol-stat-num">340+</div>
            <div class="vol-stat-label">Campaigns Supported</div>
          </div>
          <div class="vol-stat-item">
            <div class="vol-stat-num">85+</div>
            <div class="vol-stat-label">Partner NGOs</div>
          </div>
          <div class="vol-stat-item">
            <div class="vol-stat-num">98%</div>
            <div class="vol-stat-label">Satisfaction Rate</div>
          </div>
        </div>
      </div>

      {{-- FAQ --}}
      <div class="vol-faq">
        <h4>Frequently Asked Questions</h4>
        <div class="vol-faq-item">
          <button class="vol-faq-q" aria-expanded="false">
            <span>Do I need any prior experience?</span>
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
          </button>
          <div class="vol-faq-a">No prior experience is needed. We provide training and guidance for all volunteer roles.</div>
        </div>
        <div class="vol-faq-item">
          <button class="vol-faq-q" aria-expanded="false">
            <span>How much time do I need to commit?</span>
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
          </button>
          <div class="vol-faq-a">You choose your availability — full-time, part-time, or weekends only. We respect your schedule.</div>
        </div>
        <div class="vol-faq-item">
          <button class="vol-faq-q" aria-expanded="false">
            <span>Will I receive a certificate?</span>
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
          </button>
          <div class="vol-faq-a">Yes! Verified volunteers receive impact certificates and priority access to new initiatives.</div>
        </div>
        <div class="vol-faq-item">
          <button class="vol-faq-q" aria-expanded="false">
            <span>Is my data safe?</span>
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
          </button>
          <div class="vol-faq-a">Absolutely. Your data is encrypted and never shared with third parties. See our Privacy Policy for details.</div>
        </div>
      </div>

      {{-- Trust Badges --}}
      <div class="vol-trust">
        <span class="vol-trust-badge-sm">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
          SSL Secured
        </span>
        <span class="vol-trust-badge-sm">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
          GDPR Compliant
        </span>
        <span class="vol-trust-badge-sm">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
          Verified NGO
        </span>
      </div>

    </aside>
  </section>
</div>

<div class="toast-stack" id="toastStack" role="status" aria-live="polite" aria-atomic="false"></div>

<script type="application/json" id="volunteerApplyData">
@php
    $volunteerApplyData = [
        'cities' => $cities,
        'oldState' => old('state'),
        'oldCity' => old('city'),
        'success' => session('success'),
        'error' => session('error'),
        'errorsCount' => isset($errors) && $errors->any() ? $errors->count() : 0,
    ];
@endphp
@json($volunteerApplyData)
</script>

@vite(['resources/css/public/volunteer-apply.css', 'resources/js/public/volunteer-city.js'])
@endsection

@push('scripts')
@vite('resources/js/public/volunteer-apply.js')
@endpush