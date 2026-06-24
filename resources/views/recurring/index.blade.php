@extends('layouts.app')

@section('content')

<section class="py-5 recurring-page">

    <div class="container">

        {{-- Header --}}
        <div class="recurring-header mb-5">

            <div>

                <span class="recurring-eyebrow">
                    DONATION MANAGEMENT
                </span>

                <h1 class="recurring-title">
                    My Recurring Donations
                </h1>

                <p class="recurring-subtitle">
                    Track, manage and control all your recurring contribution plans.
                </p>

            </div>

        </div>

        {{-- Success Message --}}
        @if(session('success'))

            <div class="alert alert-success border-0 shadow-sm rounded-4 mb-4">
                {{ session('success') }}
            </div>

        @endif

        {{-- Error Message --}}
        @if(session('error'))

            <div class="alert alert-danger border-0 shadow-sm rounded-4 mb-4">
                {{ session('error') }}
            </div>

        @endif

        {{-- Cards --}}
        @forelse($recurring as $donation)

            <div class="recurring-card mb-4">

                {{-- Left --}}
                <div class="recurring-left">

                    <div class="recurring-icon">

                        <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M12 21s-7-4.35-9-8.5C1 8 3.5 4 7.5 4c2.04 0 3.04 1 4.5 2.5C13.46 5 14.46 4 16.5 4 20.5 4 23 8 21 12.5 19 16.65 12 21 12 21z"/>
                        </svg>

                    </div>

                    <div class="recurring-info">

                        <h3 class="campaign-title">
                            {{ $donation->campaign->title ?? 'Campaign' }}
                        </h3>

                        <div class="recurring-meta">

                            {{-- Amount --}}
                            <div class="meta-box">

                                <span class="meta-label">
                                    Amount
                                </span>

                                <span class="meta-value amount">
                                    ₹{{ number_format($donation->amount, 2) }}
                                </span>

                            </div>

                            {{-- Frequency --}}
                            <div class="meta-box">

                                <span class="meta-label">
                                    Frequency
                                </span>

                                <span class="meta-value">
                                    {{ ucfirst($donation->frequency) }}
                                </span>

                            </div>

                            {{-- Billing Count --}}
                            <div class="meta-box">

                                <span class="meta-label">
                                    Billing Count
                                </span>

                                <span class="meta-value">
                                    {{ $donation->billing_count }}
                                </span>

                            </div>

                            {{-- Next Billing --}}
                            <div class="meta-box">

                                <span class="meta-label">
                                    Next Billing
                                </span>

                                <span class="meta-value">
                                    {{
                                        optional($donation->next_billing_date)
                                            ?->format('d M Y')
                                    }}
                                </span>

                            </div>

                        </div>

                    </div>

                </div>

                {{-- Right --}}
                <div class="recurring-right">

                    {{-- Status --}}
                    @if($donation->status === 'active')

                        <div class="status-pill active">
                            Active
                        </div>

                    @elseif($donation->status === 'paused')

                        <div class="status-pill paused">
                            Paused
                        </div>

                    @else

                        <div class="status-pill cancelled">
                            Cancelled
                        </div>

                    @endif

                    {{-- Action Buttons --}}
                    <div class="action-group">

                        {{-- Pause --}}
                        @if($donation->status === 'active')

                            <form
                                action="{{ route('recurring.pause', $donation->id) }}"
                                method="POST"
                            >
                                @csrf
                                @method('PATCH')

                                <button class="btn-recurring btn-pause">
                                    Pause
                                </button>

                            </form>

                        @endif

                        {{-- Resume --}}
                        @if($donation->status === 'paused')

                            <form
                                action="{{ route('recurring.resume', $donation->id) }}"
                                method="POST"
                            >
                                @csrf
                                @method('PATCH')

                                <button class="btn-recurring btn-resume">
                                    Resume
                                </button>

                            </form>

                        @endif

                        {{-- Cancel --}}
                        @if($donation->status !== 'cancelled')

                            <form
                                action="{{ route('recurring.cancel', $donation->id) }}"
                                method="POST"
                            >
                                @csrf
                                @method('PATCH')

                                <button
                                    class="btn-recurring btn-cancel"
                                    onclick="return confirm('Cancel recurring donation?')"
                                >
                                    Cancel
                                </button>

                            </form>

                        @endif

                    </div>

                </div>

            </div>

        @empty

            {{-- Empty State --}}
            <div class="empty-state">

                <div class="empty-icon">
                    ❤️
                </div>

                <h3>
                    No Recurring Donations Yet
                </h3>

                <p>
                    Start supporting campaigns with recurring contributions.
                </p>

                <a href="/all-campaigns" class="explore-btn">
                    Explore Campaigns
                </a>

            </div>

        @endforelse

    </div>

</section>

<style>

.recurring-page{
    background:#f8fafc;
    min-height:100vh;
}

.recurring-header{
    display:flex;
    justify-content:space-between;
    align-items:center;
}

.recurring-eyebrow{
    display:inline-block;
    padding:6px 14px;
    background:#ede9fe;
    color:#6d28d9;
    border-radius:999px;
    font-size:12px;
    font-weight:700;
    letter-spacing:.08em;
    margin-bottom:14px;
}

.recurring-title{
    font-size:42px;
    font-weight:800;
    line-height:1.1;
    margin-bottom:10px;
    color:#111827;
}

.recurring-subtitle{
    font-size:17px;
    color:#6b7280;
}

.recurring-card{
    background:#fff;
    border-radius:28px;
    padding:30px;
    display:flex;
    justify-content:space-between;
    align-items:center;
    gap:30px;
    box-shadow:0 10px 35px rgba(0,0,0,.06);
    border:1px solid rgba(0,0,0,.04);
    transition:.3s;
}

.recurring-card:hover{
    transform:translateY(-4px);
    box-shadow:0 18px 45px rgba(0,0,0,.08);
}

.recurring-left{
    display:flex;
    gap:22px;
    align-items:flex-start;
}

.recurring-icon{
    width:70px;
    height:70px;
    border-radius:22px;
    background:linear-gradient(135deg,#7c3aed,#4f46e5);
    color:#fff;
    display:flex;
    align-items:center;
    justify-content:center;
    flex-shrink:0;
    box-shadow:0 10px 25px rgba(124,58,237,.25);
}

.campaign-title{
    font-size:24px;
    font-weight:700;
    margin-bottom:18px;
    color:#111827;
}

.recurring-meta{
    display:grid;
    grid-template-columns:repeat(2,minmax(140px,1fr));
    gap:18px;
}

.meta-box{
    background:#f9fafb;
    padding:14px 16px;
    border-radius:16px;
}

.meta-label{
    display:block;
    font-size:12px;
    color:#9ca3af;
    margin-bottom:6px;
    text-transform:uppercase;
    letter-spacing:.05em;
}

.meta-value{
    font-size:16px;
    font-weight:700;
    color:#111827;
}

.meta-value.amount{
    color:#4f46e5;
}

.recurring-right{
    display:flex;
    flex-direction:column;
    align-items:flex-end;
    gap:18px;
}

.status-pill{
    padding:10px 18px;
    border-radius:999px;
    font-size:13px;
    font-weight:700;
}

.status-pill.active{
    background:#dcfce7;
    color:#15803d;
}

.status-pill.paused{
    background:#fef3c7;
    color:#b45309;
}

.status-pill.cancelled{
    background:#fee2e2;
    color:#b91c1c;
}

.action-group{
    display:flex;
    gap:10px;
    flex-wrap:wrap;
    justify-content:flex-end;
}

.btn-recurring{
    border:none;
    border-radius:14px;
    padding:12px 18px;
    font-weight:700;
    transition:.25s;
}

.btn-pause{
    background:#facc15;
    color:#111827;
}

.btn-pause:hover{
    background:#eab308;
}

.btn-resume{
    background:#22c55e;
    color:#fff;
}

.btn-resume:hover{
    background:#16a34a;
}

.btn-cancel{
    background:#ef4444;
    color:#fff;
}

.btn-cancel:hover{
    background:#dc2626;
}

.empty-state{
    background:#fff;
    border-radius:30px;
    padding:90px 40px;
    text-align:center;
    box-shadow:0 10px 40px rgba(0,0,0,.05);
}

.empty-icon{
    font-size:70px;
    margin-bottom:20px;
}

.empty-state h3{
    font-size:34px;
    font-weight:800;
    margin-bottom:10px;
}

.empty-state p{
    color:#6b7280;
    font-size:17px;
    margin-bottom:28px;
}

.explore-btn{
    display:inline-flex;
    align-items:center;
    justify-content:center;
    padding:14px 28px;
    border-radius:16px;
    background:linear-gradient(135deg,#7c3aed,#4f46e5);
    color:#fff;
    text-decoration:none;
    font-weight:700;
    transition:.3s;
}

.explore-btn:hover{
    transform:translateY(-2px);
    color:#fff;
}

@media(max-width:992px){

    .recurring-card{
        flex-direction:column;
        align-items:flex-start;
    }

    .recurring-right{
        width:100%;
        align-items:flex-start;
    }

    .action-group{
        justify-content:flex-start;
    }
}

@media(max-width:768px){

    .recurring-title{
        font-size:32px;
    }

    .recurring-meta{
        grid-template-columns:1fr;
    }

    .recurring-left{
        flex-direction:column;
    }

    .campaign-title{
        font-size:22px;
    }

    .recurring-card{
        padding:22px;
    }
}

</style>

@endsection