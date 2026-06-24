@extends('layouts.app')

@section('content')

<style>
@import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Outfit:wght@300;400;500;600&display=swap');

:root {
    --purple-main: #7c3aed;
    --indigo-main: #4f46e5;
    --purple-light: #a78bfa;
    --border: #ddd6fe;
    --surface: #faf9ff;
    --ink: #1e1b4b;
    --ink-soft: #6d6aaf;
}

body {
    font-family: 'Outfit', sans-serif;
}

/* Section */
.campaign-section {
    background: var(--surface);
    padding: 80px 0;
}

/* Title */
.section-title {
    font-family: 'Playfair Display', serif;
    font-size: 2.5rem;
    font-weight: 700;
    color: var(--ink);
    margin-bottom: 40px;
}

/* Card */
.camp-card {
    background: #fff;
    border-radius: 24px;
    border: 1.5px solid var(--border);
    overflow: hidden;
    transition: 0.3s;
}
.camp-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 20px 50px rgba(124,58,237,.15);
    border-color: var(--purple-light);
}

/* Image */
.camp-img {
    position: relative;
    height: 220px;
}
.camp-img img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

/* Badge */
.badge {
    position: absolute;
    top: 12px;
    left: 12px;
    background: rgba(255,255,255,.9);
    padding: 5px 12px;
    border-radius: 999px;
    font-size: 12px;
    font-weight: 600;
    color: var(--purple-main);
}

/* Content */
.camp-body {
    padding: 20px;
}

.camp-title {
    font-weight: 600;
    font-size: 16px;
    color: var(--ink);
    margin-bottom: 8px;
}

.camp-desc {
    font-size: 13px;
    color: var(--ink-soft);
    margin-bottom: 15px;
}

/* Progress */
.progress-track {
    height: 6px;
    background: #e0e7ff;
    border-radius: 4px;
    overflow: hidden;
}
.progress-fill {
    height: 100%;
    background: linear-gradient(90deg, var(--purple-main), var(--indigo-main));
}

/* Button */
.btn-donate {
    display: block;
    text-align: center;
    background: linear-gradient(135deg, var(--purple-main), var(--indigo-main));
    color: #fff;
    padding: 12px;
    border-radius: 12px;
    font-weight: 600;
    margin-top: 16px;
    text-decoration: none;
    transition: 0.2s;
}
.btn-donate:hover {
    transform: translateY(-2px);
    opacity: 0.9;
}
</style>


<section class="campaign-section">
    <div class="max-w-7xl mx-auto px-6">

        <h2 class="section-title">All Campaigns</h2>

        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">

            @forelse($campaigns as $campaign)

                @php
                    $raised = $campaign->donations->sum('amount');
                    $goal = $campaign->goal_amount;
                    $percent = $goal > 0 ? min(100, round(($raised / $goal) * 100)) : 0;
                @endphp

                <div class="camp-card">

                    {{-- Image --}}
                    <div class="camp-img">
                        @if($campaign->cover_image)
                            <img src="{{ asset('storage/' . $campaign->cover_image) }}" alt="">
                        @else
                            <div class="flex items-center justify-center h-full text-gray-400">
                                No Image
                            </div>
                        @endif

                        <div class="badge">{{ $percent }}% Funded</div>
                    </div>

                    {{-- Content --}}
                    <div class="camp-body">

                        <h3 class="camp-title">{{ $campaign->title }}</h3>

                        <p class="camp-desc">
                            {{ \Illuminate\Support\Str::limit($campaign->description, 80) }}
                        </p>

                        {{-- Amount --}}
                        <div class="flex justify-between text-sm mb-2">
                            <span><strong>₹{{ number_format($raised) }}</strong></span>
                            <span>₹{{ number_format($goal) }}</span>
                        </div>

                        {{-- Progress --}}
                        <div class="progress-track">
                            <div class="progress-fill" style="width: {{ $percent }}%"></div>
                        </div>

                        {{-- Button --}}
                        <a href="#" class="btn-donate">
                            Donate Now
                        </a>

                    </div>

                </div>

            @empty
                <p>No campaigns available.</p>
            @endforelse

        </div>
    </div>
</section>

@endsection