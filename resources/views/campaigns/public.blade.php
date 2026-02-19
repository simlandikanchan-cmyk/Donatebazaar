@extends('layouts.app')

@section('content')

<div class="min-h-screen bg-gradient-to-br from-slate-50 via-white to-indigo-50 py-16 px-6">

    <div class="max-w-7xl mx-auto grid lg:grid-cols-3 gap-12">

        {{-- LEFT CONTENT --}}
        <div class="lg:col-span-2">

            {{-- IMAGE --}}
            <div class="bg-white rounded-3xl shadow-xl overflow-hidden">
                @if($campaign->cover_image)
                    <img src="{{ asset('storage/' . $campaign->cover_image) }}"
                         class="w-full h-[420px] object-cover">
                @endif
            </div>

            {{-- TITLE --}}
            <div class="mt-8">
                <h1 class="text-4xl font-bold text-gray-900 leading-tight">
                    {{ $campaign->title }}
                </h1>

                <p class="text-gray-500 mt-2">
                    Location: {{ $campaign->location ?? 'Not specified' }}
                </p>
            </div>

            {{-- DESCRIPTION --}}
            <div class="mt-10 bg-white p-8 rounded-3xl shadow-xl">
                <h2 class="text-2xl font-semibold mb-4">
                    Campaign Story
                </h2>

                <div class="text-gray-700 leading-relaxed text-lg">
                    {!! nl2br(e($campaign->description)) !!}
                </div>
            </div>

        </div>


        {{-- RIGHT SIDE DONATION CARD --}}
        <div class="lg:col-span-1">

            @php
                $percentage = 0;
                if ($campaign->goal_amount > 0) {
                    $percentage = ($campaign->raised_amount / $campaign->goal_amount) * 100;
                }
            @endphp

            <div class="bg-white rounded-3xl shadow-2xl p-8 sticky top-24">

                {{-- AMOUNT --}}
                <h2 class="text-3xl font-bold text-indigo-600">
                    ₹{{ number_format($campaign->raised_amount ?? 0) }}
                </h2>

                <p class="text-gray-500 mt-1">
                    raised of ₹{{ number_format($campaign->goal_amount) }}
                </p>

                {{-- PROGRESS --}}
                <div class="mt-6">
                    <div class="w-full bg-gray-200 rounded-full h-4">
                        <div class="bg-gradient-to-r from-indigo-500 to-purple-600 h-4 rounded-full"
                             style="width: {{ min($percentage, 100) }}%">
                        </div>
                    </div>

                    <p class="text-sm text-gray-500 mt-3">
                        {{ round($percentage) }}% Funded
                    </p>
                </div>

                {{-- DONATE FORM (Basic) --}}
                <form method="POST" action="#">
                    @csrf

                    <div class="mt-8">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Enter Amount (₹)
                        </label>

                        <input type="number"
                               name="amount"
                               min="1"
                               class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:outline-none"
                               placeholder="Enter donation amount">
                    </div>

                    <button type="submit"
                            class="w-full mt-6 bg-indigo-600 hover:bg-indigo-700 text-white py-4 rounded-2xl shadow-lg font-semibold transition">
                        Donate Now
                    </button>
                </form>

                {{-- SHARE --}}
                <div class="mt-6 text-center">
                    <p class="text-gray-400 text-sm">
                        Share this campaign to help more people
                    </p>
                </div>

            </div>

        </div>

    </div>

</div>

@endsection
