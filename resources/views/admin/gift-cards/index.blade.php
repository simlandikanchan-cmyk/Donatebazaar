@extends('layouts.admin')

@section('content')
<div class="body">

    {{-- Stats --}}
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(140px,1fr));gap:12px;margin-bottom:24px;">
        @foreach([
            ['label'=>'Total',    'val'=>$stats['total'],    'color'=>'#6366f1'],
            ['label'=>'Pending',  'val'=>$stats['pending'],  'color'=>'#f59e0b'],
            ['label'=>'Sent',     'val'=>$stats['sent'],     'color'=>'#3b82f6'],
            ['label'=>'Redeemed', 'val'=>$stats['redeemed'], 'color'=>'#10b981'],
            ['label'=>'Expired',  'val'=>$stats['expired'],  'color'=>'#9ca3af'],
            ['label'=>'Revenue',  'val'=>'₹'.number_format($stats['revenue'],0), 'color'=>'#10b981'],
        ] as $s)
        <div style="background:var(--surface);border:1px solid var(--border);border-radius:14px;padding:16px 18px;">
            <div style="font-size:10px;color:var(--text3);text-transform:uppercase;letter-spacing:.08em;font-family:var(--font-mono);">{{ $s['label'] }}</div>
            <div style="font-size:22px;font-weight:700;color:{{ $s['color'] }};font-family:var(--font-mono);margin-top:4px;">{{ $s['val'] }}</div>
        </div>
        @endforeach
    </div>

    {{-- Filters --}}
    <form method="GET" style="display:flex;gap:10px;margin-bottom:18px;flex-wrap:wrap;">
        <input type="text" name="search" value="{{ $search }}" placeholder="Search code, name, email…"
               style="flex:1;min-width:200px;height:36px;border-radius:9px;border:1px solid var(--border2);background:var(--surface2);padding:0 12px;font-size:12.5px;color:var(--text);outline:none;">
        <select name="status" onchange="this.form.submit()"
                style="height:36px;border-radius:9px;border:1px solid var(--border2);background:var(--surface2);padding:0 12px;font-size:12.5px;color:var(--text);outline:none;">
            @foreach(['all','pending','sent','redeemed','expired','cancelled'] as $s)
            <option value="{{ $s }}" @selected($status === $s)>{{ ucfirst($s) }}</option>
            @endforeach
        </select>
        <button type="submit" style="height:36px;padding:0 18px;background:#6366f1;color:#fff;border:none;border-radius:9px;font-size:12.5px;font-weight:600;cursor:pointer;">Search</button>
    </form>

    {{-- Flash --}}
    @if(session('success'))
    <div style="background:rgba(16,185,129,0.09);border:1px solid rgba(16,185,129,0.25);color:#065f46;padding:10px 14px;border-radius:10px;font-size:13px;margin-bottom:16px;">
        {{ session('success') }}
    </div>
    @endif

    {{-- Table --}}
    <div style="background:var(--surface);border:1px solid var(--border);border-radius:14px;overflow:hidden;">
        <div style="overflow-x:auto;">
            <table style="width:100%;border-collapse:collapse;min-width:900px;">
                <thead>
                    <tr style="border-bottom:1px solid var(--border);background:var(--surface2);">
                        @foreach(['Code','Amount','Sender','Recipient','Theme','Status','Payment','Send Date','Actions'] as $h)
                        <th style="padding:10px 14px;font-size:10px;font-weight:700;color:var(--text3);text-transform:uppercase;letter-spacing:.08em;text-align:left;white-space:nowrap;">{{ $h }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @forelse($giftCards as $gc)
                    @php
                    $statusColors = [
                        'pending'   => ['bg'=>'rgba(245,158,11,0.15)','color'=>'#b45309'],
                        'sent'      => ['bg'=>'rgba(59,130,246,0.15)', 'color'=>'#1d4ed8'],
                        'redeemed'  => ['bg'=>'rgba(16,185,129,0.15)', 'color'=>'#065f46'],
                        'expired'   => ['bg'=>'rgba(156,163,175,0.15)','color'=>'#6b7280'],
                        'cancelled' => ['bg'=>'rgba(239,68,68,0.15)',  'color'=>'#991b1b'],
                    ];
                    $sc = $statusColors[$gc->status] ?? $statusColors['pending'];
                    @endphp
                    <tr style="border-bottom:1px solid var(--border);">
                        <td style="padding:12px 14px;font-family:monospace;font-size:12px;color:var(--text);font-weight:600;">{{ $gc->code }}</td>
                        <td style="padding:12px 14px;font-weight:600;color:#10b981;">₹{{ number_format($gc->amount, 0) }}</td>
                        <td style="padding:12px 14px;">
                            <div style="font-size:12.5px;font-weight:500;color:var(--text);">{{ $gc->sender_name }}</div>
                            <div style="font-size:10.5px;color:var(--text3);">{{ $gc->sender_email }}</div>
                        </td>
                        <td style="padding:12px 14px;">
                            <div style="font-size:12.5px;font-weight:500;color:var(--text);">{{ $gc->recipient_name }}</div>
                            <div style="font-size:10.5px;color:var(--text3);">{{ $gc->recipient_email }}</div>
                        </td>
                        <td style="padding:12px 14px;">
                            @php $themeColors=['purple'=>'#6366f1','teal'=>'#10b981','coral'=>'#ef4444','blue'=>'#3b82f6']; @endphp
                            <span style="display:inline-block;width:10px;height:10px;border-radius:50%;background:{{ $themeColors[$gc->theme] ?? '#6366f1' }};margin-right:5px;vertical-align:middle;"></span>
                            <span style="font-size:12px;color:var(--text2);">{{ ucfirst($gc->theme) }}</span>
                        </td>
                        <td style="padding:12px 14px;">
                            <span style="background:{{ $sc['bg'] }};color:{{ $sc['color'] }};font-size:10px;font-weight:700;padding:3px 8px;border-radius:100px;text-transform:uppercase;font-family:monospace;">
                                {{ $gc->status }}
                            </span>
                        </td>
                        <td style="padding:12px 14px;">
                            <span style="background:{{ $gc->payment_status==='completed' ? 'rgba(16,185,129,0.15)' : 'rgba(239,68,68,0.12)' }};color:{{ $gc->payment_status==='completed' ? '#065f46' : '#991b1b' }};font-size:10px;font-weight:700;padding:3px 8px;border-radius:100px;text-transform:uppercase;font-family:monospace;">
                                {{ $gc->payment_status }}
                            </span>
                        </td>
                        <td style="padding:12px 14px;font-size:11.5px;color:var(--text3);font-family:monospace;white-space:nowrap;">
                            {{ $gc->send_at->format('d M Y') }}
                        </td>
                        <td style="padding:12px 14px;">
                            <div style="display:flex;gap:5px;">
                                <a href="{{ route('admin.gift-cards.show', $gc->id) }}"
                                   style="padding:5px 10px;border-radius:7px;font-size:11px;font-weight:600;background:rgba(99,102,241,0.10);color:#6366f1;border:1px solid rgba(99,102,241,0.2);text-decoration:none;">
                                    View
                                </a>
                                @if($gc->isPaid() && !$gc->isRedeemed())
                                <form method="POST" action="{{ route('admin.gift-cards.resend', $gc->id) }}" style="display:inline;">
                                    @csrf
                                    <button type="submit" style="padding:5px 10px;border-radius:7px;font-size:11px;font-weight:600;background:rgba(59,130,246,0.10);color:#1d4ed8;border:1px solid rgba(59,130,246,0.2);cursor:pointer;">
                                        Resend
                                    </button>
                                </form>
                                @endif
                                @if(!$gc->isRedeemed())
                                <form method="POST" action="{{ route('admin.gift-cards.destroy', $gc->id) }}"
                                      onsubmit="return confirm('Cancel this gift card?')" style="display:inline;">
                                    @csrf @method('DELETE')
                                    <button type="submit" style="padding:5px 10px;border-radius:7px;font-size:11px;font-weight:600;background:rgba(239,68,68,0.10);color:#991b1b;border:1px solid rgba(239,68,68,0.2);cursor:pointer;">
                                        Cancel
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="9" style="padding:48px;text-align:center;color:var(--text3);font-size:13px;">No gift cards found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div style="padding:12px 16px;border-top:1px solid var(--border);">
            {{ $giftCards->appends(request()->query())->links() }}
        </div>
    </div>
</div>
@endsection