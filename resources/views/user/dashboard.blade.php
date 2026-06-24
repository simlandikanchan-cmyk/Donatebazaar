@extends('layouts.app')

@section('content')
<!-- {{ dd($campaigns) }} -->

<div class="min-h-screen bg-gradient-to-br from-slate-50 via-white to-indigo-50 py-10 px-6">

    <div class="max-w-7xl mx-auto">

        {{-- HEADER --}}
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-10 gap-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">
                    Welcome {{ auth()->user()->name }} 
                </h1>
                <p class="text-gray-500 mt-2">
                    Manage your campaigns and track performance.
                </p>
            </div>

            <a href="{{ route('campaign.create') }}"
               class="inline-flex items-center justify-center bg-indigo-600 text-white px-6 py-3 rounded-xl shadow-md hover:bg-indigo-700 hover:shadow-lg transition font-medium">
               + Create Campaign
            </a>
        </div>

        {{-- SUMMARY STATS --}}
        @php
            $totalRaised = $campaigns->sum('raised_amount');
            $totalGoal = $campaigns->sum('goal_amount');
            $activeCampaigns = $campaigns->where('status', 'approved')->count();
            $totalDonors = $campaigns->sum('donors_count') ?? 0; // if you have donors column
        @endphp

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">

            <div class="bg-white rounded-2xl shadow-sm p-6">
                <p class="text-sm text-gray-500">Total Raised</p>
                <h2 class="text-2xl font-bold text-gray-900 mt-2">
                    ₹{{ number_format($totalRaised, 2) }}
                </h2>
            </div>

            <div class="bg-white rounded-2xl shadow-sm p-6">
                <p class="text-sm text-gray-500">Total Goal</p>
                <h2 class="text-2xl font-bold text-gray-900 mt-2">
                    ₹{{ number_format($totalGoal, 2) }}
                </h2>
            </div>

            <div class="bg-white rounded-2xl shadow-sm p-6">
                <p class="text-sm text-gray-500">Active Campaigns</p>
                <h2 class="text-2xl font-bold text-gray-900 mt-2">
                    {{ $activeCampaigns }}
                </h2>
            </div>

            <div class="bg-white rounded-2xl shadow-sm p-6">
                <p class="text-sm text-gray-500">Total Campaigns</p>
                <h2 class="text-2xl font-bold text-gray-900 mt-2">
                    {{ $campaigns->count() }}
                </h2>
            </div>

        </div>

        {{-- CAMPAIGNS SECTION --}}
        <h2 class="text-2xl font-semibold text-gray-800 mb-6">Your Campaigns</h2>

        @if($campaigns->count() > 0)

            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-8">
                @foreach($campaigns as $campaign)

                    @php
                        $raised = $campaign->raised_amount ?? 0;
                        $goal = $campaign->goal_amount;
                        $percentage = $goal > 0 ? min(100, ($raised / $goal) * 100) : 0;
                    @endphp

                    <div class="bg-white rounded-2xl shadow-md hover:shadow-xl transition duration-300 overflow-hidden flex flex-col">

                        {{-- IMAGE --}}
                        @if($campaign->cover_image)
                            <img src="{{ asset('storage/' . $campaign->cover_image) }}"
                                 alt="{{ $campaign->title }}"
                                 class="w-full h-48 object-cover">
                        @endif

                        <div class="p-6 flex flex-col flex-1">

                            {{-- TITLE --}}
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">
                                {{ $campaign->title }}
                            </h3>

{{-- STATUS BADGE --}}
<div class="mb-4">

    @if(strtolower($campaign->campaign_state) == 'pending')

        <span class="px-3 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-700">
            Pending
        </span>

    @elseif(strtolower($campaign->campaign_state) == 'active')

        <span class="px-3 py-1 text-xs font-medium rounded-full bg-green-100 text-green-700">
            Active
        </span>

    @elseif(strtolower($campaign->campaign_state) == 'paused')

        <span class="px-3 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-700">
            Paused
        </span>

    @elseif(strtolower($campaign->campaign_state) == 'rejected')

        <span class="px-3 py-1 text-xs font-medium rounded-full bg-red-100 text-red-700">
            Rejected
        </span>

    @elseif(strtolower($campaign->campaign_state) == 'completed')

        <span class="px-3 py-1 text-xs font-medium rounded-full bg-emerald-100 text-emerald-700">
            Completed
        </span>

    @elseif(strtolower($campaign->campaign_state) == 'expired')

        <span class="px-3 py-1 text-xs font-medium rounded-full bg-gray-200 text-gray-700">
            Expired
        </span>

    @else

        <span class="px-3 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-600">
            {{ ucfirst($campaign->campaign_state) }}
        </span>

    @endif

</div>

                            {{-- RAISED + GOAL --}}
                            <div class="mb-3">
                                <p class="text-sm text-gray-600">
                                    ₹{{ number_format($raised, 2) }}
                                    <span class="text-gray-400">raised of ₹{{ number_format($goal, 2) }}</span>
                                </p>
                            </div>

                            {{-- PROGRESS BAR --}}
                            <div class="w-full bg-gray-200 rounded-full h-3 mb-4">
                                <div class="bg-gradient-to-r from-indigo-500 to-purple-600 h-3 rounded-full transition-all duration-500"
                                     style="width: {{ $percentage }}%">
                                </div>
                            </div>

                            <p class="text-xs text-gray-500 mb-6">
                                {{ number_format($percentage, 0) }}% funded
                            </p>

                            {{-- ACTION BUTTONS --}}
                            <div class="mt-auto flex gap-3">
                                <a href="#"
                                   class="flex-1 text-center bg-indigo-600 text-white py-2 rounded-lg hover:bg-indigo-700 transition text-sm">
                                    View
                                </a>

                                <a href="#"
                                   class="flex-1 text-center bg-gray-100 text-gray-700 py-2 rounded-lg hover:bg-gray-200 transition text-sm">
                                    Edit
                                </a>
                            </div>

                        </div>
                    </div>

                @endforeach
            </div>

        @else

            {{-- EMPTY STATE --}}
            <div class="bg-white rounded-2xl shadow-md p-16 text-center">
                <h3 class="text-xl font-semibold text-gray-800 mb-3">
                    Start your first fundraiser 
                </h3>
               
                <p class="text-gray-500 mb-6">
                    You haven’t created any campaigns yet.
                </p>
                <br><br>
                <a href="{{ route('campaign.create') }}"
                   class="inline-block bg-indigo-600 text-white px-6 py-3 rounded-xl hover:bg-indigo-700 transition">
                    Create Campaign
                </a>
            </div>

        @endif

    </div>

</div>

@endsection
