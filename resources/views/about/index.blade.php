@extends('layouts.app')

@section('title', 'About Us')

@push('styles')
    @vite('resources/css/about.css')
@endpush

@section('content')

    @include('about.sections.hero')
    @include('about.sections.marquee')
    @include('about.sections.journey')
    @include('about.sections.how_it_work')
    @include('about.sections.mission')
    @include('about.sections.stats')
    @include('about.sections.team')
    @include('about.sections.testimonial')
    @include('about.sections.trust')
    @include('about.sections.faq')
    @include('about.sections.cta')

@endsection

@push('scripts')
    @vite('resources/js/about.js')
@endpush