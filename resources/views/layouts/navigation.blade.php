<nav class="bg-white border-b border-gray-200 p-4">
    <div class="container mx-auto flex justify-between">

        <div>
            <a href="{{ route('campaigns.index') }}" class="font-bold text-lg">
                DonateBazar
        </div>

        <div class="space-x-4">
            <a href="{{ route('campaigns.index') }}">
                Campaigns
            </a>

            @auth
                <a href="{{ route('campaign.create') }}">
                    Create Campaign
                </a>

                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit">
                        Logout
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}">Login</a>
                <a href="{{ route('register') }}">Register</a>
            @endauth
        </div>

    </div>
</nav>
