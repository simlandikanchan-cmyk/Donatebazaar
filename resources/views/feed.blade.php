@extends('layouts.app')

@section('content')

<div class="max-w-2xl mx-auto py-10">

    <h2 class="text-2xl font-bold mb-6">Activity Feed</h2>

    @foreach($activities as $activity)
        <div class="bg-white p-4 rounded-xl shadow mb-4">

            <div class="flex items-center justify-between mb-2">
                <h4 class="font-semibold">{{ $activity->title }}</h4>
                <span class="text-sm text-gray-500">
                    {{ $activity->created_at->diffForHumans() }}
                </span>
            </div>

            <p class="text-gray-600">{{ $activity->description }}</p>

            @if($activity->image)
                <img src="{{ asset($activity->image) }}"
                     class="mt-3 rounded-lg">
            @endif

        </div>
    @endforeach

</div>

@endsection