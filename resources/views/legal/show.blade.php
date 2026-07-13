@extends('layouts.app')

@section('title', $page->title)
@section('meta_description', 'Read our ' . strtolower($page->title) . ' for DonateBazaar.')

@section('content')
<div class="legal-page">
    <div class="legal-hero">
        <div class="legal-hero-bg"></div>
        <div class="legal-hero-inner">
            <h1>{{ $page->title }}</h1>
            <p>Last updated: {{ $page->updated_at?->format('F Y') ?? 'July 2026' }}</p>
        </div>
    </div>

    <div class="legal-body">
        <div class="legal-content">
            {!! $page->content !!}
        </div>
    </div>
</div>
@endsection
