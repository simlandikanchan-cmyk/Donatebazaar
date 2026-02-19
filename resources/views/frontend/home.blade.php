@extends('layouts.frontend')

@section('content')

<section class="relative bg-cover bg-center h-screen"
    style="background-image: url('{{ asset('images/hero.jpg') }}')">

    <div class="bg-black bg-opacity-60 h-full flex items-center">
        <div class="container mx-auto text-center text-white">
            <h1 class="text-5xl font-bold mb-6">
                Change a Life Today
            </h1>

            <p class="text-xl mb-6">
                Donate products or money in just a few clicks.
            </p>

            <div class="flex justify-center gap-4">
                <a href="#money" class="bg-orange-500 px-6 py-3 rounded-lg">
                    Donate Money
                </a>

                <a href="#products" class="bg-green-500 px-6 py-3 rounded-lg">
                    Gift a Product
                </a>
            </div>

            <!-- Stats -->
            <div class="mt-12 flex justify-center gap-12 text-lg">
                <div>
                    <h3 class="text-3xl font-bold">{{ $totalCampaigns }}</h3>
                    <p>Campaigns</p>
                </div>
                <div>
                    <h3 class="text-3xl font-bold">{{ $totalDonors }}</h3>
                    <p>Donors</p>
                </div>
                <div>
                    <h3 class="text-3xl font-bold">{{ $totalItems }}</h3>
                    <p>Items Donated</p>
                </div>
            </div>
        </div>
    </div>
</section>


<section class="py-16 bg-gray-100">
    <div class="container mx-auto">
        <h2 class="text-3xl font-bold mb-10 text-center">
            Featured Campaigns
        </h2>

        <div class="grid md:grid-cols-3 gap-8">
            @foreach($featuredCampaigns as $campaign)
                <div class="bg-white rounded-xl shadow-md overflow-hidden">
                    <img src="{{ asset($campaign->image) }}" class="h-48 w-full object-cover">

                    <div class="p-6">
                        <h3 class="font-bold text-lg mb-2">
                            {{ $campaign->title }}
                        </h3>

                        <div class="w-full bg-gray-200 rounded-full h-3 mb-3">
                            <div class="bg-green-500 h-3 rounded-full"
                                style="width: {{ ($campaign->raised_amount / $campaign->goal_amount) * 100 }}%">
                            </div>
                        </div>

                        <p class="text-sm mb-4">
                            ₹{{ $campaign->raised_amount }} raised of ₹{{ $campaign->goal_amount }}
                        </p>

                        <a href="{{ route('campaign.show', $campaign->id) }}"
                           class="bg-orange-500 text-white px-4 py-2 rounded">
                            Donate
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<section id="products" class="py-16">
    <div class="container mx-auto">
        <h2 class="text-3xl font-bold text-center mb-10">
            Donate Products
        </h2>

        <div class="grid md:grid-cols-4 gap-6">
            @foreach($products as $product)
                <div class="bg-white shadow rounded-lg p-4 text-center">
                    <img src="{{ asset($product->image) }}"
                         class="h-40 w-full object-cover mb-4">

                    <h3 class="font-semibold">
                        {{ $product->name }}
                    </h3>

                    <p class="text-green-600 font-bold mb-2">
                        ₹{{ $product->price }}
                    </p>

                    <a href="{{ route('product.donate', $product->id) }}"
                       class="bg-green-500 text-white px-4 py-2 rounded">
                        Donate This Item
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</section>


<section id="money" class="py-16 bg-gray-50">
    <div class="container mx-auto">
        <h2 class="text-3xl font-bold text-center mb-10">
            Support with Money
        </h2>

        <div class="grid md:grid-cols-3 gap-8">
            @foreach($moneyCampaigns as $campaign)
                <div class="bg-white p-6 rounded shadow">
                    <h3 class="font-bold mb-3">
                        {{ $campaign->title }}
                    </h3>

                    <p class="mb-3">
                        ₹{{ $campaign->raised_amount }} / ₹{{ $campaign->goal_amount }}
                    </p>

                    <a href="{{ route('campaign.show', $campaign->id) }}"
                       class="bg-orange-600 text-white px-4 py-2 rounded">
                        Donate Now
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</section>

<section class="py-16">
    <div class="container mx-auto text-center">
        <h2 class="text-3xl font-bold mb-10">Browse by Causes</h2>

        <div class="grid md:grid-cols-5 gap-6">
            @foreach($categories as $category)
                <div class="bg-white p-6 rounded shadow hover:shadow-lg">
                    <h3 class="font-bold">
                        {{ $category->name }}
                    </h3>
                    <p class="text-sm text-gray-500">
                        {{ $category->campaigns_count }} Campaigns
                    </p>
                </div>
            @endforeach
        </div>
    </div>
</section>


<section class="py-16 bg-gray-100">
    <div class="container mx-auto">
        <h2 class="text-3xl font-bold mb-10 text-center">
            Success Stories
        </h2>

        <div class="grid md:grid-cols-3 gap-8">
            @foreach($posts as $post)
                <div class="bg-white rounded shadow p-6">
                    <h3 class="font-bold mb-3">
                        {{ $post->title }}
                    </h3>

                    <p class="text-sm mb-4">
                        {{ Str::limit($post->content, 100) }}
                    </p>

                    <a href="{{ route('post.show', $post->id) }}"
                       class="text-orange-500 font-semibold">
                        Read More →
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</section>

<a href="#money"
   class="fixed bottom-6 right-6 bg-orange-600 text-white px-6 py-3 rounded-full shadow-lg">
   Donate Now
</a>
