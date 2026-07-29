@extends('layouts.app')

@push('styles')
    @vite(['resources/css/public/home.css'])
@endpush

@push('scripts')
    @vite(['resources/js/public/home.js'])
@endpush

@section('content')

@include('home.sections.hero')
@include('home.sections.marquee')
@include('home.sections.categories')
@include('home.sections.campaigns')
@include('home.sections.story')
@include('home.sections.how')
@include('home.sections.testimonials')
@include('home.sections.why')
@include('home.sections.cta')
@include('home.sections.blogs')
@include('home.sections.impact')

@endsection