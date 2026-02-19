@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto p-8 bg-white rounded-xl shadow-lg mt-10">

    <h1 class="text-3xl font-extrabold text-gray-800 mb-8 text-center">Create a New Campaign</h1>

    {{-- Validation Errors --}}
    @if ($errors->any())
        <div class="bg-red-50 border border-red-400 text-red-700 px-6 py-4 rounded-lg mb-6">
            <h2 class="font-semibold mb-2">Please fix the following errors:</h2>
            <ul class="list-disc pl-5 space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('campaign.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        {{-- Title --}}
        <div>
            <label for="title" class="block text-gray-700 font-medium mb-1">Campaign Title <span class="text-red-500">*</span></label>
            <input type="text" name="title" id="title" value="{{ old('title') }}" 
                class="w-full border border-gray-300 px-4 py-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                placeholder="Enter campaign title" required>
        </div>

        {{-- Description --}}
        <div>
            <label for="description" class="block text-gray-700 font-medium mb-1">Description <span class="text-red-500">*</span></label>
            <textarea name="description" id="description" rows="5" 
                class="w-full border border-gray-300 px-4 py-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                placeholder="Describe your campaign" required>{{ old('description') }}</textarea>
        </div>

        {{-- Goal Amount --}}
        <div>
            <label for="goal_amount" class="block text-gray-700 font-medium mb-1">Goal Amount ($) <span class="text-red-500">*</span></label>
            <input type="number" name="goal_amount" id="goal_amount" value="{{ old('goal_amount') }}" 
                class="w-full border border-gray-300 px-4 py-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                placeholder="Enter fundraising goal" required>
        </div>

        {{-- Category --}}
        <div>
            <label for="category_id" class="block text-gray-700 font-medium mb-1">Category <span class="text-red-500">*</span></label>
            <select name="category_id" id="category_id" 
                class="w-full border border-gray-300 px-4 py-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition" required>
                <option value="">-- Select Category --</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Location --}}
        <div>
            <label for="location" class="block text-gray-700 font-medium mb-1">Location</label>
            <input type="text" name="location" id="location" value="{{ old('location') }}"
                class="w-full border border-gray-300 px-4 py-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                placeholder="City, Country">
        </div>

        {{-- Start & End Dates --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label for="start_date" class="block text-gray-700 font-medium mb-1">Start Date</label>
                <input type="date" name="start_date" id="start_date" value="{{ old('start_date') }}"
                    class="w-full border border-gray-300 px-4 py-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
            </div>
            <div>
                <label for="end_date" class="block text-gray-700 font-medium mb-1">End Date</label>
                <input type="date" name="end_date" id="end_date" value="{{ old('end_date') }}"
                    class="w-full border border-gray-300 px-4 py-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
            </div>
        </div>

        {{-- Video URL --}}
        <div>
            <label for="video_url" class="block text-gray-700 font-medium mb-1">Video URL (optional)</label>
            <input type="url" name="video_url" id="video_url" value="{{ old('video_url') }}" 
                class="w-full border border-gray-300 px-4 py-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                placeholder="https://www.youtube.com/...">
        </div>

        {{-- Cover Image --}}
        <div>
            <label for="cover_image" class="block text-gray-700 font-medium mb-1">Cover Image (optional)</label>
            <input type="file" name="cover_image" id="cover_image" accept="image/*"
                class="w-full border border-gray-300 px-4 py-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
        </div>

        {{-- Checkboxes --}}
        <div class="flex gap-6 items-center">
            <label class="flex items-center gap-2 text-gray-700">
                <input type="checkbox" name="is_featured" class="h-5 w-5 text-blue-600" {{ old('is_featured') ? 'checked' : '' }}>
                Featured
            </label>
            <label class="flex items-center gap-2 text-gray-700">
                <input type="checkbox" name="is_urgent" class="h-5 w-5 text-red-600" {{ old('is_urgent') ? 'checked' : '' }}>
                Urgent
            </label>
        </div>

        {{-- Submit Button --}}
        <div>
            <button type="submit" 
                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-lg transition transform hover:scale-105">
                Submit Campaign
            </button>
        </div>

    </form>
</div>
@endsection
