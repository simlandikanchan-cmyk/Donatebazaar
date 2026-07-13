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

@push('styles')
<style>
.faq-page{--font:'DM Sans',sans-serif;--mono:'DM Mono',monospace;--accent:#6366f1;}
.faq-hero{position:relative;overflow:hidden;background:linear-gradient(160deg,#0d0e1a,#0f172a 50%,#042f2e);padding:80px 24px 48px;text-align:center;}
.faq-hero-bg{position:absolute;inset:0;background-image:linear-gradient(rgba(99,102,241,0.04) 1px,transparent 1px),linear-gradient(90deg,rgba(99,102,241,0.04) 1px,transparent 1px);background-size:36px 36px;pointer-events:none;}
.faq-hero-inner{position:relative;z-index:1;}
.faq-hero-inner h1{font-family:var(--mono);font-size:clamp(26px,4vw,36px);font-weight:500;color:#fff;letter-spacing:-0.03em;}
.faq-hero-inner p{font-size:14px;color:rgba(255,255,255,.45);margin-top:4px;}
.faq-body{max-width:760px;margin:0 auto;padding:40px 24px 80px;}
.faq-section{margin-bottom:36px;}
.faq-section-title{font-size:15px;font-weight:700;color:#0f1117;letter-spacing:-0.01em;padding-bottom:10px;border-bottom:1px solid #e5e7eb;margin-bottom:14px;}
.faq-list{display:flex;flex-direction:column;gap:8px;}
.faq-item{border:1px solid rgba(0,0,0,.06);border-radius:12px;overflow:hidden;transition:border-color .2s,box-shadow .2s;background:#fff;}
.faq-item:hover{border-color:rgba(99,102,241,.15);}
.faq-question{display:flex;align-items:center;justify-content:space-between;width:100%;padding:16px 18px;background:none;border:none;cursor:pointer;font-family:var(--font);font-size:14px;font-weight:600;color:#0f1117;text-align:left;gap:12px;transition:background .2s;}
.faq-question:hover{background:rgba(99,102,241,.04);}
.faq-question svg{width:16px;height:16px;flex-shrink:0;color:#9ca3af;transition:transform .3s;}
.faq-open .faq-question svg{transform:rotate(180deg);}
.faq-answer{max-height:0;overflow:hidden;padding:0 18px;opacity:0;transition:max-height .35s ease,opacity .3s,padding .3s;color:#4b5563;font-size:14px;line-height:1.7;}
.faq-answer p{margin:0;}
@media(max-width:520px){.faq-hero{padding:60px 16px 40px;}.faq-body{padding:32px 16px 60px;}}
</style>
@endpush
