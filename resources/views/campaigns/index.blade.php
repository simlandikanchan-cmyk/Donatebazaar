@extends('layouts.app')

@section('content')


<div class="max-w-7xl mx-auto px-6 py-10">
    <h2 class="text-3xl font-bold mb-8">Active Campaigns</h2>

    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
        @forelse($campaigns as $campaign)
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-xl transition">

                {{-- Cover Image --}}
                <div class="relative">
                    <img src="{{ asset('storage/' . $campaign->cover_image) }}"
                         class="w-full h-56 object-cover">

                    <span class="absolute top-3 left-3 bg-green-600 text-white text-xs px-3 py-1 rounded-full">
                        Verified
                    </span>
                </div>

                <div class="p-5">

                    {{-- Title --}}
                    <h3 class="text-lg font-semibold mb-2 line-clamp-2">
                        {{ $campaign->title }}
                    </h3>

                    {{-- Short Description --}}
                    <p class="text-gray-600 text-sm mb-4 line-clamp-2">
                        {{ $campaign->description }}
                    </p>

                    {{-- Raised Amount --}}
                    <div class="mb-2">
                        <div class="flex justify-between text-sm font-medium">
                            <span>₹12,500 Raised</span>
                            <span>₹{{ number_format($campaign->goal_amount) }}</span>
                        </div>

                        {{-- Progress Bar --}}
                        <div class="w-full bg-gray-200 rounded-full h-2 mt-1">
                            <div class="bg-blue-600 h-2 rounded-full"
                                 style="width: 40%"></div>
                        </div>
                    </div>

                    {{-- Donate Button --}}
                    <a href="#"
                       class="mt-4 block text-center bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition font-semibold">
                        Donate Now
                    </a>
                </div>
            </div>
        @empty
            <p>No campaigns available.</p>
        @endforelse
    </div>
</div>
@endsection