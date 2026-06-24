@extends('layouts.app')
@section('content')
<div class="max-w-4xl mx-auto py-10">
    <h1 class="text-3xl font-bold text-green-700 mb-6">Volunteer & Jobs at DonateBazaar</h1>

    <form class="flex gap-3 mb-8">
        <input name="search" value="{{ request('search') }}" placeholder="Search jobs..." class="border px-4 py-2 rounded w-full">
        <select name="type" class="border px-4 py-2 rounded">
            <option value="">All Types</option>
            <option value="full-time">Full-Time</option>
            <option value="part-time">Part-Time</option>
            <option value="volunteer">Volunteer</option>
        </select>
        <button class="bg-green-600 text-white px-5 py-2 rounded">Search</button>
    </form>

    @foreach($jobs as $job)
    <div class="border rounded-lg p-5 mb-4 hover:shadow">
        <h2 class="text-xl font-semibold">{{ $job->title }}</h2>
        <p class="text-sm text-gray-500">{{ $job->type }} • {{ $job->location }} • {{ $job->salary }}</p>
        <p class="mt-2 text-gray-700">{{ Str::limit($job->description, 120) }}</p>
        <a href="{{ route('jobs.show', $job) }}" class="mt-3 inline-block text-green-600 font-medium">View & Apply →</a>
    </div>
    @endforeach

    {{ $jobs->links() }}
</div>
@endsection