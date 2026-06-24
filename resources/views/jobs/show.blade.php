@extends('layouts.app')
@section('content')
<div class="max-w-3xl mx-auto py-10">
    <h1 class="text-3xl font-bold text-green-700">{{ $job->title }}</h1>
    <p class="text-gray-500 mb-4">{{ $job->type }} • {{ $job->location }} • {{ $job->salary }}</p>
    <div class="prose mb-8">{{ $job->description }}</div>

    @if(session('success'))
        <div class="bg-green-100 text-green-700 p-4 rounded mb-6">{{ session('success') }}</div>
    @endif

    <h2 class="text-2xl font-bold mb-4">Apply Now</h2>
    <form action="{{ route('jobs.apply', $job) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
        @csrf
        <input name="name" placeholder="Full Name" class="border w-full px-4 py-2 rounded" required>
        <input name="email" type="email" placeholder="Email" class="border w-full px-4 py-2 rounded" required>
        <input name="phone" placeholder="Phone (optional)" class="border w-full px-4 py-2 rounded">
        <textarea name="cover_letter" placeholder="Cover Letter" rows="5" class="border w-full px-4 py-2 rounded"></textarea>
        <div>
            <label class="block mb-1 font-medium">Upload CV (PDF/DOC)</label>
            <input name="cv" type="file" accept=".pdf,.doc,.docx" required>
        </div>
        <button class="bg-green-600 text-white px-6 py-2 rounded">Submit Application</button>
    </form>
</div>
@endsection