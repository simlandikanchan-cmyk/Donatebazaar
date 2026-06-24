@extends('layouts.app')

@section('content')

<div class="relative min-h-screen overflow-hidden bg-gradient-to-br from-indigo-100 via-white to-purple-100 py-12 px-6">

    <!-- Decorative Blur -->
    <div class="absolute -top-40 -left-40 w-96 h-96 bg-purple-300 rounded-full mix-blend-multiply filter blur-3xl opacity-30"></div>
    <div class="absolute top-0 -right-40 w-96 h-96 bg-indigo-300 rounded-full mix-blend-multiply filter blur-3xl opacity-30"></div>
    <div class="absolute -bottom-40 left-40 w-96 h-96 bg-pink-300 rounded-full mix-blend-multiply filter blur-3xl opacity-30"></div>

<div class="relative max-w-7xl mx-auto">

{{-- HEADER --}}
<div class="flex flex-col md:flex-row md:items-center md:justify-between mb-10 gap-6">

<div>
<h1 class="text-3xl font-bold text-gray-900">
Welcome Back, {{ auth()->user()->name }}
</h1>

<p class="text-gray-500 mt-2">
Manage your campaigns and track performance.
</p>
</div>

<a href="{{ route('campaign.create') }}"
class="bg-indigo-600 text-white px-6 py-3 rounded-xl shadow-md hover:bg-indigo-700">
+ Create Campaign
</a>

</div>


{{-- STATS --}}
@php
$totalRaised = $campaigns->sum('raised_amount');
$totalGoal = $campaigns->sum('goal_amount');
$activeCampaigns = $campaigns->where('status','approved')->count();
@endphp


<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">

<div class="bg-white/40 backdrop-blur-xl rounded-3xl p-6 shadow-xl">
<p class="text-gray-500 text-sm">Total Raised</p>
<h2 class="text-2xl font-bold text-indigo-600 mt-2">
₹{{ number_format($totalRaised,2) }}
</h2>
</div>


<div class="bg-white/40 backdrop-blur-xl rounded-3xl p-6 shadow-xl">
<p class="text-gray-500 text-sm">Total Goal</p>
<h2 class="text-2xl font-bold mt-2">
₹{{ number_format($totalGoal,2) }}
</h2>
</div>


<div class="bg-white/40 backdrop-blur-xl rounded-3xl p-6 shadow-xl">
<p class="text-gray-500 text-sm">Active Campaigns</p>
<h2 class="text-2xl font-bold mt-2">
{{ $activeCampaigns }}
</h2>
</div>


<div class="bg-white/40 backdrop-blur-xl rounded-3xl p-6 shadow-xl">
<p class="text-gray-500 text-sm">Total Campaigns</p>
<h2 class="text-2xl font-bold mt-2">
{{ $campaigns->count() }}
</h2>
</div>

</div>



{{-- CHART --}}
<div class="bg-white/40 backdrop-blur-xl rounded-3xl shadow-2xl p-8 mb-12">

<h3 class="text-lg font-semibold mb-6">
Fundraising Overview
</h3>

<div class="h-64">

<canvas id="fundChart"></canvas>

</div>

</div>



{{-- CAMPAIGNS --}}

<h2 class="text-2xl font-semibold text-gray-800 mb-6">

Your Campaigns

</h2>



@if($campaigns->count()>0)


<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-8">


@foreach($campaigns as $campaign)


@php

$raised=$campaign->raised_amount??0;

$goal=$campaign->goal_amount;

$percentage=$goal>0?min(100,($raised/$goal)*100):0;

@endphp



<div class="bg-white/50 backdrop-blur-xl rounded-3xl shadow-xl overflow-hidden flex flex-col">


@if($campaign->cover_image)

<img src="{{ asset('storage/'.$campaign->cover_image) }}"
class="w-full h-48 object-cover">

@endif


<div class="p-6 flex flex-col flex-1">


<h3 class="text-lg font-semibold mb-2">

{{ $campaign->title }}

</h3>



<div class="mb-3">

₹{{ number_format($raised,2) }}

<span class="text-gray-400">

of ₹{{ number_format($goal,2) }}

</span>

</div>



<div class="w-full bg-gray-200 rounded-full h-3 mb-4">

<div class="bg-indigo-600 h-3 rounded-full"

style="width:{{ $percentage }}%">

</div>

</div>



<p class="text-xs mb-6">

{{ number_format($percentage) }}% funded

</p>



<div class="mt-auto flex gap-3">


<a href="{{ route('campaign.show',$campaign->id) }}"

class="flex-1 text-center bg-indigo-600 text-white py-2 rounded-lg">

View

</a>



<a href="{{ route('campaign.edit',$campaign->id) }}"

class="flex-1 text-center bg-gray-200 py-2 rounded-lg">

Edit

</a>


</div>


</div>

</div>


@endforeach


</div>


@else


<div class="bg-white rounded-2xl shadow-md p-16 text-center">

<h3 class="text-xl font-semibold mb-3">

Start your first fundraiser

</h3>


<a href="{{ route('campaign.create') }}"

class="bg-indigo-600 text-white px-6 py-3 rounded-xl">

Create Campaign

</a>


</div>


@endif


</div>

</div>

@endsection



{{-- Chart Script --}}

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


<script>

document.addEventListener("DOMContentLoaded", function () {

const monthlyData = @json($monthlyData ?? []);

const labels = Object.keys(monthlyData);

const values = Object.values(monthlyData);

const ctx = document.getElementById('fundChart');


if(ctx){

new Chart(ctx,{

type:'line',

data:{

labels:labels,

datasets:[{

label:'Amount Raised',

data:values,

borderColor:'#6366f1',

backgroundColor:'rgba(99,102,241,0.1)',

fill:true,

tension:0.4

}]

}

});

}


});

</script>