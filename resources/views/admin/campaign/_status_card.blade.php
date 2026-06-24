{{--
    Add this block to your admin campaign show view (resources/views/admin/campaigns/show.blade.php)
    Replace or extend the existing STATUS card where "Approve Campaign" button is shown.
--}}

{{-- STATUS CARD --}}
<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-3">Status</p>

    {{-- Status badges --}}
    <div class="flex gap-2 mb-4">
        <span class="px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-700">
            {{ strtoupper($campaign->status) }}
        </span>
        @if($campaign->is_active ?? false)
            <span class="px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700">● ACTIVE</span>
        @endif
    </div>

    {{-- Flash messages --}}
    @foreach(['success' => 'green', 'error' => 'red', 'warning' => 'yellow'] as $type => $color)
        @if(session($type))
            <div class="mb-4 p-3 rounded-lg bg-{{ $color }}-50 border border-{{ $color }}-200 text-{{ $color }}-700 text-sm">
                {{ session($type) }}
            </div>
        @endif
    @endforeach

    @php
        $ownerKyc = \App\Models\KycVerification::where('user_id', $campaign->user_id)->latest()->first();
        $kycStatus = $ownerKyc?->status;
    @endphp

    {{-- KYC Status indicator --}}
    <div class="mb-4 p-3 rounded-lg border text-sm
        {{ $kycStatus === 'approved' ? 'bg-green-50 border-green-200 text-green-700' :
           ($kycStatus === 'pending'  ? 'bg-yellow-50 border-yellow-200 text-yellow-700' :
                                        'bg-red-50 border-red-200 text-red-700') }}">
        <span class="font-medium">KYC Status: </span>
        {{ $kycStatus ? ucfirst($kycStatus) : 'Not Submitted' }}
    </div>

    {{-- Action buttons based on KYC state --}}
    @if($kycStatus === 'approved')
        {{-- KYC approved → allow campaign approval --}}
        <form action="{{ route('admin.campaigns.approve', $campaign->id) }}" method="POST" class="mb-2">
            @csrf
            <button type="submit"
                    class="w-full bg-green-600 hover:bg-green-700 text-white font-medium py-2.5 px-4 rounded-lg flex items-center justify-center gap-2 transition-colors">
                ✓ Approve Campaign
            </button>
        </form>
    @else
        {{-- KYC missing or rejected → show Request KYC button --}}
        <button onclick="document.getElementById('kyc-request-modal').classList.remove('hidden')"
                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2.5 px-4 rounded-lg flex items-center justify-center gap-2 transition-colors mb-2">
            📋 Request KYC from User
        </button>

        {{-- Approve button disabled --}}
        <button disabled
                title="KYC must be approved before campaign can be activated"
                class="w-full bg-gray-200 text-gray-400 font-medium py-2.5 px-4 rounded-lg flex items-center justify-center gap-2 cursor-not-allowed mb-2">
            ✓ Approve Campaign
        </button>
    @endif

    {{-- Reject always available --}}
    <form action="{{ route('admin.campaigns.reject', $campaign->id) }}" method="POST" class="mb-2">
        @csrf
        <button type="submit"
                onclick="return confirm('Are you sure you want to reject this campaign?')"
                class="w-full bg-red-600 hover:bg-red-700 text-white font-medium py-2.5 px-4 rounded-lg flex items-center justify-center gap-2 transition-colors">
            ✗ Reject Campaign
        </button>
    </form>

    <a href="{{ route('admin.dashboard') }}"
       class="block text-center text-sm text-gray-500 hover:text-gray-700 mt-2">
        ← Back to Dashboard
    </a>
</div>

{{-- Request KYC Modal --}}
<div id="kyc-request-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-xl max-w-md w-full p-6">
        <h3 class="text-lg font-bold text-gray-900 mb-1">Request KYC Documents</h3>
        <p class="text-sm text-gray-500 mb-4">
            An email and in-app notification will be sent to
            <span class="font-medium text-gray-700">{{ $campaign->user->name }}</span>.
        </p>

        <form action="{{ route('admin.campaigns.request-kyc', $campaign->id) }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Message to user <span class="text-gray-400">(optional)</span>
                </label>
                <textarea name="admin_message"
                          rows="3"
                          maxlength="500"
                          placeholder="e.g. Please upload a clear photo of your Aadhaar card..."
                          class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 resize-none"></textarea>
            </div>

            <div class="flex gap-3">
                <button type="submit"
                        class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-medium py-2.5 rounded-lg transition-colors">
                    Send Request
                </button>
                <button type="button"
                        onclick="document.getElementById('kyc-request-modal').classList.add('hidden')"
                        class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium py-2.5 rounded-lg transition-colors">
                    Cancel
                </button>
            </div>
        </form>
    </div>
</div>