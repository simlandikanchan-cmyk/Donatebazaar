@extends('layouts.user')

@section('page_title', 'Edit Campaign')
@section('page_subtitle', Str::limit($campaign->title, 45))

@section('topbar_left_prefix')
    <a href="{{ route('campaign.show', $campaign->id) }}" class="topbar-back" title="Back to Campaign">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 12H5M12 5l-7 7 7 7"/></svg>
    </a>
@endsection

@section('topbar_right')
    @if($campaign->campaign_state === 'paused')
        <span class="status-chip chip-paused"><span class="dot"></span> Paused</span>
    @elseif($campaign->campaign_state === 'active')
        <span class="status-chip chip-active"><span class="dot"></span> Active</span>
    @elseif($campaign->campaign_state === 'pending')
        <span class="status-chip chip-pending"><span class="dot"></span> Pending</span>
    @elseif($campaign->campaign_state === 'rejected')
        <span class="status-chip chip-rejected"><span class="dot"></span> Rejected</span>
    @endif
    <div class="theme-toggle" title="Toggle dark mode">
        <input type="checkbox" id="themeToggle">
        <label for="themeToggle">
            <div class="theme-icons">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="5"/><path stroke-linecap="round" d="M12 1v2M12 21v2M4.22 4.22l1.42 1.42M18.36 18.36l1.42 1.42M1 12h2M21 12h2M4.22 19.78l1.42-1.42M18.36 5.64l1.42-1.42"/></svg>
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"/></svg>
            </div>
        </label>
    </div>
    <div class="t-avatar">{{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}</div>
@endsection

@push('page_styles')
@vite('resources/css/public/campaigns-edit.css')
@endpush

@section('content')

    <x-page-hero
        tag="Campaign"
        title="Edit Campaign"
        subtitle="{{ $campaign->title }}"
    >
        <x-slot:badges>
            @if($campaign->campaign_state === 'paused')
            <span class="wb-badge wbb-yellow">Paused</span>
            @elseif($campaign->campaign_state === 'active')
            <span class="wb-badge wbb-green">Active</span>
            @elseif($campaign->campaign_state === 'rejected')
            <span class="wb-badge wbb-red">Rejected</span>
            @else
            <span class="wb-badge wbb-purple">{{ ucfirst($campaign->campaign_state) }}</span>
            @endif
        </x-slot:badges>
        <x-slot:actions>
            <x-button variant="primary" href="{{ route('campaign.show', $campaign->id) }}" class="wb-btn wb-btn-primary">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                View Campaign
            </x-button>
        </x-slot:actions>
    </x-page-hero>

    @if ($errors->any())
    <div class="validation-box">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    @if(session('error'))
    <div class="flash flash-error">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
        {{ session('error') }}
    </div>
    @endif
    @if(session('success'))
    <div class="flash flash-success">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
        {{ session('success') }}
    </div>
    @endif

    <form action="{{ route('campaign.update', $campaign->id) }}" method="POST" enctype="multipart/form-data" id="editForm">
        @csrf
        @method('PUT')

        <div class="form-layout">

            {{-- ════ LEFT ════ --}}
            <div>

                {{-- Basic Info --}}
                <div class="card card-mb">
                    <div class="card-header">
                        <div class="card-icon ic-primary">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        </div>
                        <div>
                            <div class="card-title">Basic Information</div>
                            <div class="card-sub">Campaign title, goal amount and description</div>
                        </div>
                    </div>
                    <div class="card-body">

                        <div class="field">
                            <label>Campaign Title</label>
                            <input type="text" name="title"
                                   value="{{ old('title', $campaign->title) }}"
                                   placeholder="Give your campaign a strong title"
                                   {{ $campaign->isPaused() ? 'disabled' : '' }}>
                            @error('title')<div class="field-err">{{ $message }}</div>@enderror
                        </div>

                        <div class="field">
                            <label>Category</label>
                            <select name="category_id" {{ $campaign->isPaused() ? 'disabled' : '' }}>
                                <option value="">Select a category…</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}"
                                        {{ old('category_id', $campaign->category_id) == $cat->id ? 'selected' : '' }}>
                                        {{ $cat->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')<div class="field-err">{{ $message }}</div>@enderror
                        </div>

                        <div class="field">
                            <label>Goal Amount (₹)</label>
                            <input type="number" name="goal_amount"
                                   value="{{ old('goal_amount', $campaign->goal_amount) }}"
                                   placeholder="Enter target amount" min="1"
                                   {{ $campaign->isPaused() ? 'disabled' : '' }}>
                            @error('goal_amount')<div class="field-err">{{ $message }}</div>@enderror
                            @php
                                $userLevel   = auth()->user()->fundraiserLevelName();
                                $userMaxGoal = auth()->user()->maxCampaignGoal();
                            @endphp
                            @if($userMaxGoal)
                            <div class="level-info">
                                Level: <strong>{{ $userLevel }}</strong>
                                — Max goal: <strong>₹{{ number_format($userMaxGoal) }}</strong>
                            </div>
                            @endif
                        </div>

                        <div class="field">
                            <label>Description</label>
                            <textarea name="description" rows="7" id="descField" maxlength="3000"
                                      placeholder="Tell your story — why this campaign matters..."
                                      {{ $campaign->isPaused() ? 'disabled' : '' }}>{{ old('description', $campaign->description) }}</textarea>
                            <div class="char-counter"><span id="descCount">{{ strlen(old('description', $campaign->description ?? '')) }}</span>/3000</div>
                            @error('description')<div class="field-err">{{ $message }}</div>@enderror
                        </div>

                    </div>
                </div>

                {{-- Cover Image --}}
                <div class="card">
                    <div class="card-header">
                        <div class="card-icon ic-primary">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                        </div>
                        <div>
                            <div class="card-title">Cover Image</div>
                            <div class="card-sub">JPG or PNG — max 2MB · Leave empty to keep current</div>
                        </div>
                    </div>
                    <div class="card-body">

                        @if($campaign->cover_image)
                            <img src="{{ asset('storage/' . $campaign->cover_image) }}"
                                 class="cover-current" alt="Current cover" id="currentCover">
                        @else
                            <div class="cover-placeholder" id="currentCover">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                            </div>
                        @endif

                        <div class="file-drop {{ $campaign->isPaused() ? 'file-drop-disabled' : '' }}">
                            <input type="file" name="cover_image" accept="image/*"
                                   {{ $campaign->isPaused() ? 'disabled' : '' }}>
                            <div class="file-drop-icon">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                            </div>
                            <div class="file-drop-label">Click to upload or drag & drop</div>
                            <div class="file-drop-hint">JPG, JPEG or PNG · max 2MB · optional</div>
                        </div>
                        <img id="newPreview" alt="New cover preview">

                    </div>
                </div>

            </div>

            {{-- ════ RIGHT ════ --}}
            <div class="sidebar-stack">

                {{-- Campaign Controls --}}
                <div class="card">
                    <div class="card-header">
                        <div class="card-icon {{ $campaign->isPaused() ? 'ic-yellow' : 'ic-green' }}">
                            @if($campaign->isPaused())
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            @else
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            @endif
                        </div>
                        <div>
                            <div class="card-title">Campaign Controls</div>
                            <div class="card-sub">Manage campaign state</div>
                        </div>
                    </div>
                    <div class="card-body">
                        @if($campaign->isPaused())
                        <div class="warn-banner">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/></svg>
                            <div>
                                <div class="warn-banner-title">Editing disabled while paused</div>
                                <div class="warn-banner-body">{{ $campaign->pause_reason }}</div>
                            </div>
                        </div>
                        <x-button variant="primary" type="button">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/><path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Resume Campaign
                        </x-button>
                        @elseif($campaign->isActive())
                        <x-button variant="primary" type="button">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Pause Campaign
                        </x-button>
                        @elseif($campaign->isPending())
                        <div class="pending-note">
                            Campaign is awaiting admin approval. You can still edit content.
                        </div>
                        @endif
                    </div>
                </div>

                {{-- Save --}}
                <div class="card">
                    <div class="card-header">
                        <div class="card-icon ic-primary">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v11a2 2 0 01-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                        </div>
                        <div>
                            <div class="card-title">Save Changes</div>
                            <div class="card-sub">{{ $campaign->isPaused() ? 'Resume campaign to save' : 'Updates saved immediately' }}</div>
                        </div>
                    </div>
                    <div class="card-body">
                        <x-button variant="primary" type="submit" :disabled="$campaign->isPaused()">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v11a2 2 0 01-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/></svg>
                            Save Changes
                        </x-button>
                        <a href="{{ route('campaign.show', $campaign->id) }}" class="btn btn-ghost btn-ghost-mt">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                            Cancel
                        </a>
                    </div>
                </div>

                {{-- Progress --}}
                <div class="card">
                    <div class="card-header">
                        <div class="card-icon ic-primary">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                        </div>
                        <div>
                            <div class="card-title">Progress</div>
                            <div class="card-sub">Current fundraising status</div>
                        </div>
                    </div>
                    <div class="card-body">
                        @php
                            $raised = $campaign->raised_amount ?? 0;
                            $goal   = $campaign->goal_amount > 0 ? $campaign->goal_amount : 1;
                            $pct    = min(100, round(($raised / $goal) * 100));
                        @endphp
                        <div class="progress-header">
                            <span class="progress-raised">₹{{ number_format($raised) }}</span>
                            <span class="progress-goal">of ₹{{ number_format($campaign->goal_amount) }}</span>
                        </div>
                        <div class="progress-track">
                            <div class="progress-fill-dynamic" style="--progress-width:{{ $pct }}%;"></div>
                        </div>
                        <div class="progress-footer">{{ $pct }}% funded · {{ $campaign->donor_count ?? 0 }} donors</div>
                    </div>
                </div>

            </div>{{-- /.sidebar-stack --}}

        </div>{{-- /.form-layout --}}
    </form>

    {{-- ══ PAUSE MODAL ══ --}}
    <div id="pauseModal" class="overlay" role="dialog" aria-modal="true">
        <div class="modal">
            <button type="button" class="modal-x" data-action="close-modal" data-modal="pauseModal">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
            <div class="modal-head">
                <div class="modal-icon modal-icon-warn">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <div class="modal-ttl">Pause Campaign</div>
                    <div class="modal-sub">Your campaign will stop appearing publicly</div>
                </div>
            </div>
            <form action="{{ route('campaign.pause', $campaign->id) }}" method="POST" id="pauseForm">
                @csrf
                <label class="modal-label">Reason for pausing <span class="required-mark">*</span></label>
                <textarea id="pauseReason" name="reason" rows="3"
                          placeholder="Tell us why you're pausing (min 10 chars)..."
                          class="modal-ta" minlength="10" maxlength="500"></textarea>
                <div class="char-counter"><span id="pauseCount">0</span>/500</div>
                <p id="pauseErr" class="modal-err">Please provide a reason (min 10 characters).</p>
                <div class="modal-acts">
                    <x-button variant="secondary" type="button" class="modal-btn">Cancel</x-button>
                    <x-button variant="primary" type="submit" class="modal-btn modal-y-btn">⏸ Pause Campaign</x-button>
                </div>
            </form>
        </div>
    </div>

    {{-- ══ RESUME MODAL ══ --}}
    <div id="resumeModal" class="overlay" role="dialog" aria-modal="true">
        <div class="modal">
            <button type="button" class="modal-x" data-action="close-modal" data-modal="resumeModal">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
            <div class="modal-head">
                <div class="modal-icon modal-icon-success">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/><path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <div class="modal-ttl">Resume Campaign</div>
                    <div class="modal-sub">Your campaign will become public again</div>
                </div>
            </div>
            <form action="{{ route('campaign.resume', $campaign->id) }}" method="POST" id="resumeForm">
                @csrf
                <label class="modal-label">Reason for resuming <span class="required-mark">*</span></label>
                <textarea id="resumeReason" name="resume_reason" rows="3"
                          placeholder="Tell us why you're resuming (min 10 chars)..."
                          class="modal-ta" minlength="10" maxlength="500"></textarea>
                <div class="char-counter"><span id="resumeCount">0</span>/500</div>
                <p id="resumeErr" class="modal-err">Please provide a reason (min 10 characters).</p>
                <div class="modal-acts">
                    <x-button variant="secondary" type="button" class="modal-btn">Cancel</x-button>
                    <x-button variant="primary" type="submit" class="modal-btn">▶ Resume Campaign</x-button>
                </div>
            </form>
        </div>
    </div>

@endsection

@push('page_scripts')
@vite('resources/js/public/campaigns-edit.js')
@endpush
