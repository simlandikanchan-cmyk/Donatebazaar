@extends('layouts.app')

@section('content')

@push('styles') @vite(['resources/css/public/campaigns-index.css']) @endpush


<section class="campaign-section">
    <div class="max-w-7xl mx-auto px-6">

        <h2 class="section-title">All Campaigns</h2>

        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">

            @forelse($campaigns as $campaign)

                @php
                    $raised = $campaign->donations_sum_total_amount ?? $campaign->raised_amount ?? 0;
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