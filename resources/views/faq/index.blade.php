@extends('layouts.app')

@section('title', 'Frequently Asked Questions')
@section('meta_description', 'Find answers to commonly asked questions about DonateBazaar campaigns, donations, and account management.')

@section('content')
<div class="faq-page">
    <div class="faq-hero">
        <div class="faq-hero-bg"></div>
        <div class="faq-hero-inner">
            <h1>FAQ</h1>
            <p>Frequently asked questions</p>
        </div>
    </div>

    <div class="faq-body">
        @foreach($faqs as $section => $items)
        <div class="faq-section">
            <div class="faq-section-title">{{ $section }}</div>
            <div class="faq-list">
                @foreach($items as $i => $item)
                <div class="faq-item">
                    <button class="faq-question" onclick="toggleFaq(this)">
                        <span>{{ $item['q'] }}</span>
                        <svg viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 8l5 5 5-5"/></svg>
                    </button>
                    <div class="faq-answer" @if($loop->parent->first && $i === 0) style="max-height:600px;padding-bottom:20px;opacity:1;" @endif>
                        <p>{{ $item['a'] }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection

@push('scripts')
<script>
function toggleFaq(btn) {
    var item = btn.parentElement;
    var answer = item.querySelector('.faq-answer');
    var isOpen = answer.style.maxHeight && answer.style.maxHeight !== '0px';
    answer.style.maxHeight = isOpen ? '0px' : answer.scrollHeight + 'px';
    answer.style.paddingBottom = isOpen ? '0px' : '20px';
    answer.style.opacity = isOpen ? '0' : '1';
    item.classList.toggle('faq-open');
}
</script>
@endpush

@push('styles') @vite(['resources/css/public/faq.css']) @endpush
