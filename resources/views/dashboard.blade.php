@extends('layouts.app')

@section('content')
<!-- <x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{ __("You're logged in!") }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout> -->
<!-- <h1>heading</h1> -->
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-white to-indigo-50 py-10 px-6">

    <div class="max-w-7xl mx-auto">

        {{-- HEADER --}}
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-10 gap-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">
                    Welcome back, {{ auth()->user()->name }} 👋
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

        <!-- <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">

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

        </div> -->
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">

    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
        <p class="text-gray-500 text-sm">Total Raised</p>
        <h2 class="text-2xl font-bold text-indigo-600 mt-2">
            ₹{{ number_format($totalRaised, 2) }}
        </h2>
        <p class="text-xs text-green-500 mt-1">+12% this month</p>
    </div>

    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
        <p class="text-gray-500 text-sm">Total Goal</p>
        <h2 class="text-2xl font-bold mt-2">
            ₹{{ number_format($totalGoal, 2) }}
        </h2>
    </div>

    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
        <p class="text-gray-500 text-sm">Active Campaigns</p>
        <h2 class="text-2xl font-bold mt-2">
            {{ $activeCampaigns }}
        </h2>
    </div>

    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
        <p class="text-gray-500 text-sm">Total Campaigns</p>
        <h2 class="text-2xl font-bold mt-2">
            {{ $campaigns->count() }}
        </h2>
    </div>

</div>

<!-- Add Performance Chart Section -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 mb-12">
    <div class="flex justify-between items-center mb-6">
        <h3 class="text-lg font-semibold text-gray-800">
            Fundraising Overview
        </h3>
        <select class="border border-gray-200 rounded-lg text-sm px-3 py-2">
            <option>This Month</option>
            <option>Last 3 Months</option>
            <option>Last 6 Months</option>
            <option>Last 9 Months</option>
            <option>This Year</option>
        </select>
    </div>

<div class="h-64">
    <canvas id="fundChart"></canvas>
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
                                @if(strtolower($campaign->status) == 'pending')
                                    <span class="px-3 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-700">
                                        Pending
                                    </span>
                                @elseif(strtolower($campaign->status) == 'approved')
                                    <span class="px-3 py-1 text-xs font-medium rounded-full bg-green-100 text-green-700">
                                        Approved
                                    </span>
                                @elseif(strtolower($campaign->status) == 'rejected')
                                    <span class="px-3 py-1 text-xs font-medium rounded-full bg-red-100 text-red-700">
                                        Rejected
                                    </span>
                                @else
                                    <span class="px-3 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-600">
                                        {{ ucfirst($campaign->status) }}
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
                            
<div class="mt-auto flex gap-3 pt-4 border-t">

    <a href="{{ route('campaign.show', $campaign->id) }}"
       class="flex-1 text-center bg-indigo-600 text-white py-2 rounded-lg hover:bg-indigo-700 transition text-sm">
        View
    </a>

    <a href="{{ route('campaign.edit', $campaign->id) }}"
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
                    Start your first fundraiser 🚀
                </h3>
                <p class="text-gray-500 mb-6">
                    You haven’t created any campaigns yet.
                </p>
                <a href="{{ route('campaign.create') }}"
                   class="inline-block bg-indigo-600 text-white px-6 py-3 rounded-xl hover:bg-indigo-700 transition">
                    Create Campaign
                </a>
            </div>

        @endif

    </div>

</div>



@endsection


<!-- <script>
document.addEventListener("DOMContentLoaded", function () {

    const ctx = document.getElementById('fundChart');

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Jan','Feb','Mar','Apr','May','Jun'],
            datasets: [{
                label: 'Amount Raised',
                data: [12000,19000,30000,25000,40000,50000,100000,200000],
                borderColor: '#6366f1',
                backgroundColor: 'rgba(99,102,241,0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
        }
    });

});
</script> -->

<!-- dynamic data scripts for performance insigths -->
<!--  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> -->

<script>
document.addEventListener("DOMContentLoaded", function () {

    const monthlyData = @json($monthlyData);

    const labels = Object.keys(monthlyData);
    const values = Object.values(monthlyData);

    const ctx = document.getElementById('fundChart');

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Amount Raised',
                data: values,
                borderColor: '#6366f1',
                backgroundColor: 'rgba(99,102,241,0.1)',
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });

});
</script>

