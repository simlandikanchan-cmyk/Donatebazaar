@extends('layouts.app')

@section('content')

<div class="min-h-screen bg-gradient-to-br from-slate-50 via-white to-indigo-50 py-16 px-6">

    <div class="max-w-7xl mx-auto grid lg:grid-cols-3 gap-12">

        {{-- LEFT SIDE (MAIN CONTENT) --}}
        <div class="lg:col-span-2">

            {{-- IMAGE --}}
            <div class="bg-white rounded-3xl shadow-xl overflow-hidden">
                @if($campaign->cover_image)
                    <img src="{{ asset('storage/' . $campaign->cover_image) }}"
                         class="w-full h-[420px] object-cover">
                @else
                    <div class="h-[420px] bg-gray-200 flex items-center justify-center">
                        <span class="text-gray-500 text-lg">No Image Uploaded</span>
                    </div>
                @endif
            </div>

            {{-- TITLE --}}
            <div class="mt-8">
                <h1 class="text-4xl font-bold text-gray-900 leading-tight">
                    {{ $campaign->title }}
                </h1>
                <p class="text-gray-500 mt-3">
                    Created by You • {{ $campaign->created_at->diffForHumans() }}
                </p>
            </div>

            {{-- DESCRIPTION --}}
            <div class="mt-10 bg-white p-8 rounded-3xl shadow-xl">
                <h2 class="text-2xl font-semibold mb-4">About This Campaign</h2>

                <div class="text-gray-700 leading-relaxed text-lg">
                    {{ $campaign->description }}
                </div>
            </div>

        </div>


        {{-- RIGHT SIDE (DONATION SUMMARY CARD) --}}
        <div class="lg:col-span-1">

            @php
                $percentage = 0;
                if ($campaign->goal_amount > 0) {
                    $percentage = ($campaign->raised_amount / $campaign->goal_amount) * 100;
                }
            @endphp

            <div class="bg-white rounded-3xl shadow-2xl p-8 sticky top-24">

                {{-- RAISED --}}
                <h2 class="text-3xl font-bold text-indigo-600">
                    ₹{{ number_format($campaign->raised_amount ?? 0) }}
                </h2>

                <p class="text-gray-500 mt-1">
                    raised of ₹{{ number_format($campaign->goal_amount) }} goal
                </p>

                {{-- PROGRESS BAR --}}
                <div class="mt-6">
                    <div class="w-full bg-gray-200 rounded-full h-4">
                        <div class="bg-gradient-to-r from-indigo-500 to-purple-600 h-4 rounded-full transition-all duration-500"
                             style="width: {{ min($percentage, 100) }}%"></div>
                    </div>

                    <p class="text-sm text-gray-500 mt-3">
                        {{ round($percentage) }}% Funded
                    </p>
                </div>

                {{-- STATS --}}
                <div class="grid grid-cols-2 gap-6 mt-8 text-center">

                    <div>
                        <p class="text-2xl font-bold text-gray-800">
                            {{ round($percentage) }}%
                        </p>
                        <p class="text-sm text-gray-500">Completed</p>
                    </div>

                    <div>
                        <p class="text-2xl font-bold text-gray-800">
                            ₹{{ number_format(max($campaign->goal_amount - $campaign->raised_amount, 0)) }}
                        </p>
                        <p class="text-sm text-gray-500">Remaining</p>
                    </div>

                </div>

                {{-- ACTION BUTTONS --}}
                <div class="mt-10 space-y-4">

                    <a href="{{ route('campaign.edit', $campaign->id) }}"
                       class="block text-center bg-yellow-500 hover:bg-yellow-600 text-white py-4 rounded-2xl font-semibold shadow-lg transition">
                        Edit Campaign
                    </a>

                    <a href="{{ route('dashboard') }}"
                       class="block text-center bg-gray-100 hover:bg-gray-200 text-gray-700 py-4 rounded-2xl font-medium transition">
                        Back to Dashboard
                    </a>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection
