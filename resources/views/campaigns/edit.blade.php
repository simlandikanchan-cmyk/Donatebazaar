@extends('layouts.app')

@section('content')

<div class="min-h-screen bg-gradient-to-br from-indigo-50 via-white to-purple-50 py-14 px-6">

    <div class="max-w-5xl mx-auto">

        {{-- HEADER --}}
        <div class="mb-10">
            <h1 class="text-4xl font-bold text-gray-900">
                 Edit Your Campaign
            </h1>
            <p class="text-gray-500 mt-2">
                Update your campaign details and make it more impactful.
            </p>
        </div>

        {{-- CARD --}}
        <div class="bg-white shadow-2xl rounded-3xl p-10">

            <form action="{{ route('campaign.update', $campaign->id) }}"
                  method="POST"
                  enctype="multipart/form-data"
                  class="space-y-8">

                @csrf
                @method('PUT')

                {{-- TITLE --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Campaign Title
                    </label>
                    <input type="text"
                           name="title"
                           value="{{ old('title', $campaign->title) }}"
                           class="w-full border border-gray-200 rounded-2xl px-5 py-4 focus:ring-2 focus:ring-indigo-500 focus:outline-none transition shadow-sm">
                </div>

                {{-- GOAL --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Goal Amount (₹)
                    </label>
                    <input type="number"
                           name="goal_amount"
                           value="{{ old('goal_amount', $campaign->goal_amount) }}"
                           class="w-full border border-gray-200 rounded-2xl px-5 py-4 focus:ring-2 focus:ring-indigo-500 focus:outline-none transition shadow-sm">
                </div>

                {{-- DESCRIPTION --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Description
                    </label>
                    <textarea name="description"
                              rows="6"
                              class="w-full border border-gray-200 rounded-2xl px-5 py-4 focus:ring-2 focus:ring-indigo-500 focus:outline-none transition shadow-sm">{{ old('description', $campaign->description) }}</textarea>
                </div>

                {{-- CURRENT IMAGE --}}
                @if($campaign->cover_image)
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-3">
                            Current Cover Image
                        </label>

                        <div class="relative">
                            <img src="{{ asset('storage/' . $campaign->cover_image) }}"
                                 class="w-full h-72 object-cover rounded-2xl shadow-lg">
                        </div>
                    </div>
                @endif

                {{-- CHANGE IMAGE --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Change Cover Image
                    </label>

                    <input type="file"
                           name="cover_image"
                           accept="image/*"
                           onchange="previewImage(event)"
                           class="w-full border border-gray-200 rounded-2xl px-5 py-4 bg-gray-50 shadow-sm">
                    
                    {{-- Live Preview --}}
                    <img id="preview"
                         class="hidden mt-4 w-full h-72 object-cover rounded-2xl shadow-lg"/>
                </div>

                {{-- BUTTONS --}}
                <div class="flex justify-between items-center pt-8">

                    <a href="{{ route('dashboard') }}"
                       class="text-gray-500 hover:text-gray-700 font-medium transition">
                        ← Back to Dashboard
                    </a>

                    <button type="submit"
                            class="bg-indigo-600 hover:bg-indigo-700 text-white px-10 py-4 rounded-2xl shadow-lg transition font-semibold">
                        Update Campaign
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

{{-- Image Preview Script --}}
<script>
function previewImage(event) {
    const preview = document.getElementById('preview');
    preview.src = URL.createObjectURL(event.target.files[0]);
    preview.classList.remove('hidden');
}
</script>

@endsection
