@extends('layouts.app')

@section('content')

<style>

/* PREMIUM BACKGROUND */

.page-bg{

background:
linear-gradient(120deg,#eef2ff,#f8fafc,#ffffff);

min-height:100vh;

}


/* GLASS EFFECT */

.glass{

background:rgba(255,255,255,0.6);

backdrop-filter:blur(20px);

border:1px solid rgba(255,255,255,0.7);

box-shadow:0 10px 40px rgba(0,0,0,0.05);

transition:.4s;

}


.glass:hover{

transform:translateY(-6px);

box-shadow:0 20px 50px rgba(0,0,0,0.1);

}



/* FLOAT CARD */

.float-card{

animation:float 6s ease-in-out infinite;

}

@keyframes float{

0%,100%{transform:translateY(0)}

50%{transform:translateY(-10px)}

}



/* DONATE BUTTON */

.btn-donate{

background:linear-gradient(45deg,#6366f1,#9333ea);

color:white;

transition:.4s;

}


.btn-donate:hover{

transform:scale(1.05);

box-shadow:0 10px 25px rgba(0,0,0,0.2);

}



/* AMOUNT BUTTON */

.amount-btn{

transition:.3s;

background:white;

}


.amount-btn:hover,

.amount-btn.active{

background:#4f46e5;

color:white;

transform:translateY(-4px);

}



/* IMAGE ZOOM */

.img-zoom{

overflow:hidden;

}


.img-zoom img{

transition:.6s;

}


.img-zoom:hover img{

transform:scale(1.1);

}



/* FAQ */

.faq-card{

transition:.4s;

}


.faq-card:hover{

box-shadow:0 10px 30px rgba(0,0,0,0.08);

}

</style>


@php

$goal = $campaign->goal_amount ?? 0;
$raised = $campaign->raised_amount ?? 0;
$percent = $goal > 0 ? ($raised / $goal) * 100 : 0;

@endphp



<div class="page-bg">


<!-- HERO -->

<section class="relative h-[500px]">

<img
src="{{ $campaign->cover_image ? asset('storage/'.$campaign->cover_image) : '/images/default.jpg' }}"
class="absolute w-full h-full object-cover">


<div class="absolute inset-0 bg-black/60"></div>


<div class="relative h-full flex items-center">

<div class="max-w-7xl mx-auto px-6 text-white">


<h1 class="text-5xl font-bold">

{{ $campaign->title }}

</h1>


<p class="mt-4 text-lg text-white/90 max-w-2xl">
Help us reach ₹{{ number_format($goal) }} to support this cause. Every donation creates real impact.
</p>

<!-- <a href="#donate"
class="inline-block mt-6 bg-indigo-600 hover:bg-indigo-700 px-6 py-3 rounded-lg text-white font-semibold shadow-lg">
Donate Now →
</a> -->

<!-- <div class="flex gap-6 mt-4 text-sm"> -->

<!-- <span>

📅 {{ $campaign->created_at->format('d M Y') }}

</span>


<span>

👤 {{ $campaign->user->name ?? 'Admin' }}

</span>


<span>

🏷 {{ $campaign->category->name ?? 'General' }}

</span> -->



    <!-- Date -->
<div class="flex items-center gap-6 mt-4 text-white/90 text-sm">

    <!-- Date -->
    <div class="flex items-center gap-2 bg-white/10 px-3 py-1 rounded-full backdrop-blur">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
        </svg>
        <span>{{ $campaign->created_at->format('d M Y') }}</span>
    </div>

    <!-- User -->
    <div class="flex items-center gap-2 bg-white/10 px-3 py-1 rounded-full backdrop-blur">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M5.121 17.804A9 9 0 1118.879 6.196M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
        </svg>
        <span>{{ $campaign->user->name ?? 'Admin' }}</span>
    </div>

    <!-- Category -->
    <div class="flex items-center gap-2 bg-white/10 px-3 py-1 rounded-full backdrop-blur">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M7 7h.01M7 3h5l9 9-5 5-9-9V3z" />
        </svg>
        <span>{{ $campaign->category->name ?? 'General' }}</span>
    </div>

</div>


<!-- </div> -->


</div>

</div>

</section>




<!-- Mission Section -->

<div class="bg-gray-50 py-16">

<div class="max-w-6xl mx-auto px-6 text-center">


<h2 class="text-3xl font-bold mb-4">

Join Our Monthly Mission for Disaster Relief in India

</h2>

<p class="text-gray-600 mb-12">

With the support of thousands of donors, we reach families with urgent relief and long-term support.

</p>



<!-- Cards -->

<div class="grid md:grid-cols-3 gap-8 mb-10">


<!-- Card 1 -->

<div class="bg-white shadow rounded-xl p-6">

<i class="fa-solid fa-bowl-food text-orange-500 text-4xl mb-4"></i>

<p class="font-semibold">

25,000+ food and relief kits distributed

</p>

</div>



<!-- Card 2 -->

<div class="bg-white shadow rounded-xl p-6">

<i class="fa-solid fa-people-group text-blue-500 text-4xl mb-4"></i>

<p class="font-semibold">

1,00,000+ people supported

</p>

</div>



<!-- Card 3 -->

<div class="bg-white shadow rounded-xl p-6">

<i class="fa-solid fa-hand-holding-medical text-red-500 text-4xl mb-4"></i>

<p class="font-semibold">

Emergency shelter & medical aid

</p>

</div>
</div>



<!-- Buttons -->

<div class="flex justify-center gap-6">


            <a href="{{ route('contact',['campaign'=>$campaign->title]) }}"
class="btn-donate px-6 py-3 rounded-lg shadow-lg inline-block">

About Us

</a>



<button
onclick="navigator.share({
title:'{{ $campaign->title }}',
url:window.location.href
})"
class="border border-blue-600 text-blue-600 px-10 py-3 rounded-lg">

Share this mission

</button>


</div>



</div>

</div>



















<!-- CONTENT -->

<div class="max-w-7xl mx-auto px-6 py-16">

<div class="grid lg:grid-cols-3 gap-12">



<!-- LEFT -->

<div class="lg:col-span-2 space-y-10">


<div class="glass p-8 rounded-3xl">


<h2 class="text-2xl font-bold mb-4">

About Campaign

</h2>


<p class="text-gray-700 leading-8">

{{ $campaign->description }}

</p>

</div>




<!-- STATS -->

<div class="grid md:grid-cols-3 gap-6">


<div class="glass p-6 rounded-2xl text-center">

<h3 class="text-3xl font-bold">

₹{{ number_format($raised) }}

</h3>

<p>Raised</p>

</div>



<div class="glass p-6 rounded-2xl text-center">

<h3 class="text-3xl font-bold">

₹{{ number_format($goal) }}

</h3>

<p>Goal</p>

</div>



<div class="glass p-6 rounded-2xl text-center">

<h3 class="text-3xl font-bold">

{{ round($percent) }}%

</h3>

<p>Funded</p>

</div>


</div>


</div>



<!-- RIGHT DONATE CARD -->

<div>


<div class="glass p-8 rounded-3xl sticky top-24 float-card">


<h3 class="text-2xl font-bold mb-6">

Support This Cause

</h3>



<!-- PROGRESS -->

<div class="w-full bg-gray-200 rounded-full h-4 mb-4">

<div
class="bg-indigo-600 h-4 rounded-full"
style="width:{{ $percent }}%">

</div>

</div>


<p class="text-center mb-6">

₹{{ number_format($raised) }}

of

₹{{ number_format($goal) }}

</p>




<!-- AMOUNT -->

<div class="grid grid-cols-3 gap-3 mb-4">


@foreach([100,500,1000,2000,5000,10000,20000,30000,40000] as $amt)


<button
type="button"
class="amount-btn border rounded-xl py-2"
onclick="selectAmount({{ $amt }},this)">

₹{{ $amt }}

</button>


@endforeach


</div>



<form
action="{{ route('donate.redirect',$campaign->id) }}"
method="POST">

@csrf


<input
type="number"
id="customAmount"
name="amount"
placeholder="Enter amount"
required
class="w-full border p-3 rounded-lg mb-4">


<button
class="btn-donate w-full py-3 rounded-lg">

Donate Now

</button>


</form>


</div>


</div>



</div>

</div>






<!-- IMPACT -->

<div class="py-20">


<div class="max-w-7xl mx-auto px-6">


<h2 class="text-4xl font-bold text-center mb-12">

Your Impact

</h2>



<div class="grid md:grid-cols-3 gap-8">



<div class="glass rounded-3xl overflow-hidden img-zoom">


<img
src="/images/donation1 (3).jpg"
class="w-full h-60 object-cover">


<div class="p-6">

<h3 class="font-bold mb-2">

Relief Kits

</h3>


<p class="text-gray-600">

Provide essential relief kits to families facing crisis and hardship. Each kit delivers food, hygiene supplies, and hope—helping vulnerable communities recover faster and rebuild safer, healthier lives together.

</p>


</div>

</div>



<div class="glass rounded-3xl overflow-hidden img-zoom">


<img
src="/images/donation1 (2).jpg"
class="w-full h-60 object-cover">


<div class="p-6">

<h3 class="font-bold mb-2">

Medical Care

</h3>


<p class="text-gray-600">

Support lifesaving medical care for those who cannot afford treatment. Your help provides medicines, checkups, and urgent care—bringing healing, dignity, and hope to individuals and families in need.

</p>


</div>

</div>



<div class="glass rounded-3xl overflow-hidden img-zoom">


<img
src="/images/donation1 (1).jpg"
class="w-full h-60 object-cover">



<div class="p-6">

<h3 class="font-bold mb-2">

Shelter

</h3>


<p class="text-gray-600">

Help provide safe shelter for families without a home. Your support offers protection, warmth, and stability—giving vulnerable people a secure place to live and rebuild their future with dignity.

</p>


</div>

</div>



</div>


</div>


</div>




<!-- IMPACT CAROUSEL -->

<div class="py-20">

<div class="max-w-7xl mx-auto px-6">

<h2 class="text-4xl font-bold text-center mb-12">
Your Impact
</h2>


<div class="relative overflow-hidden">

<!-- Slider -->

<div id="impactCarousel"
class="flex transition duration-700 ease-in-out">



<!-- Card 1 -->
<div class="w-1/3 p-4 flex-shrink-0">

<div class="glass rounded-3xl overflow-hidden img-zoom">

<img src="/images/donation1 (3).jpg"
class="w-full h-60 object-cover">

<div class="p-6">

<h3 class="font-bold mb-2">
Relief Kits
</h3>

<p class="text-gray-600">
Provide essential relief kits to families facing crisis.
</p>

</div>

</div>

</div>



<!-- Card 2 -->
<div class="w-1/3 p-4 flex-shrink-0">

<div class="glass rounded-3xl overflow-hidden img-zoom">

<img src="/images/donation1 (2).jpg"
class="w-full h-60 object-cover">

<div class="p-6">

<h3 class="font-bold mb-2">
Medical Care
</h3>

<p class="text-gray-600">
Support lifesaving medical care.
</p>

</div>

</div>

</div>



<!-- Card 3 -->
<div class="w-1/3 p-4 flex-shrink-0">

<div class="glass rounded-3xl overflow-hidden img-zoom">

<img src="/images/donation1 (1).jpg"
class="w-full h-60 object-cover">

<div class="p-6">

<h3 class="font-bold mb-2">
Shelter
</h3>

<p class="text-gray-600">
Help provide safe shelter.
</p>

</div>

</div>

</div>







<!-- Card 4 -->
<div class="w-1/3 p-4 flex-shrink-0">

<div class="glass rounded-3xl overflow-hidden img-zoom">

<img src="/images/donation1 (3).jpg"
class="w-full h-60 object-cover">

<div class="p-6">

<h3 class="font-bold mb-2">
Relief Kits
</h3>

<p class="text-gray-600">
Help families recover.
</p>

</div>

</div>

</div>



<!-- Card 5 -->
<div class="w-1/3 p-4 flex-shrink-0">

<div class="glass rounded-3xl overflow-hidden img-zoom">

<img src="/images/donation1 (2).jpg"
class="w-full h-60 object-cover">

<div class="p-6">

<h3 class="font-bold mb-2">
Medical Care
</h3>

<p class="text-gray-600">
Provide treatment support.
</p>

</div>

</div>

</div>



<!-- Card 6 -->
<div class="w-1/3 p-4 flex-shrink-0">

<div class="glass rounded-3xl overflow-hidden img-zoom">

<img src="/images/donation1 (1).jpg"
class="w-full h-60 object-cover">

<div class="p-6">

<h3 class="font-bold mb-2">
Shelter
</h3>

<p class="text-gray-600">
Safe living support.
</p>

</div>

</div>

</div>



</div>



<!-- Buttons -->

<button onclick="prevImpact()"
class="absolute left-0 top-1/2 -translate-y-1/2 bg-white shadow px-4 py-2 rounded-full">

◀

</button>



<button onclick="nextImpact()"
class="absolute right-0 top-1/2 -translate-y-1/2 bg-white shadow px-4 py-2 rounded-full">

▶

</button>



</div>

</div>

</div>





<!-- FAQ -->

<div class="max-w-5xl mx-auto py-20 px-6">


<h2 class="text-4xl font-bold text-center mb-10">

Frequently Asked Questions

</h2>



<div class="space-y-4">



<div class="faq-card glass rounded-2xl">


<button
onclick="openFAQ(this)"
class="w-full flex justify-between p-6 font-semibold">


1. How will my donation be used?


<span class="arrow">

+

</span>


</button>


<div class="faq-content hidden p-6 text-gray-600">


Donations qualify for tax deductions.Your donation directly supports relief activities such as providing food, medical care, shelter, and essential supplies to people affected by disasters and emergencies

</div>


</div>


<div class="space-y-4">



<div class="faq-card glass rounded-2xl">


<button
onclick="openFAQ(this)"
class="w-full flex justify-between p-6 font-semibold">


2. Is my donation secure?


<span class="arrow">

+

</span>


</button>


<div class="faq-content hidden p-6 text-gray-600">


Yes, all donations are processed through secure payment systems to ensure your personal and financial information remains safe and protected.


</div>


</div>


<div class="space-y-4">



<div class="faq-card glass rounded-2xl">


<button
onclick="openFAQ(this)"
class="w-full flex justify-between p-6 font-semibold">


3. Can I track how my donation is used?


<span class="arrow">

+

</span>


</button>


<div class="faq-content hidden p-6 text-gray-600">


Yes, we provide regular updates and reports to show how donations are helping communities and making a positive impact.


</div>


</div>


<div class="space-y-4">



<div class="faq-card glass rounded-2xl">


<button
onclick="openFAQ(this)"
class="w-full flex justify-between p-6 font-semibold">


4. Can I make a monthly donation?


<span class="arrow">

+

</span>


</button>


<div class="faq-content hidden p-6 text-gray-600">


Yes, you can choose to donate monthly to provide continuous support for ongoing relief and humanitarian programs.


</div>


</div>






<div class="faq-card glass rounded-2xl">


<button
onclick="openFAQ(this)"
class="w-full flex justify-between p-6 font-semibold">


5. How else can I help besides donating?


<span class="arrow">

+

</span>


</button>


<div class="faq-content hidden p-6 text-gray-600">


You can support our mission by volunteering, sharing campaigns with others, or organizing fundraising events to help reach more people in need.


</div>


</div>




</div>


</div>



<!-- Why Donatekart -->
<section class="bg-gray-50 py-20">

<div class="max-w-6xl mx-auto px-6">

<!-- Heading -->

<div class="text-center mb-12">

<p class="text-gray-500">
6 Reasons of Assurance
</p>

<h2 class="text-4xl font-bold mt-2">
Why DonateBazaar?
</h2>

<p class="text-gray-500 mt-3">
Trusted platform for transparent and secure donations
</p>

</div>



<!-- Cards -->

<div class="grid md:grid-cols-3 gap-8">



<!-- Card 1 -->

<div class="bg-white p-6 rounded-2xl shadow-sm border
hover:shadow-xl hover:-translate-y-2
transition duration-300 flex gap-4">

<div class="w-16 h-16
bg-gradient-to-br from-orange-100 to-orange-200
rounded-xl flex items-center justify-center">

<img src="{{ asset('images/loyalty-program.png') }}"
class="w-8">

</div>

<div>

<h3 class="font-semibold text-lg">
Product Giving
</h3>

<p class="text-gray-600 text-sm mt-2">
Make your impact tangible and transparent by donating products.
</p>

</div>

</div>




<!-- Card 2 -->

<div class="bg-white p-6 rounded-2xl shadow-sm border
hover:shadow-xl hover:-translate-y-2
transition duration-300 flex gap-4">

<div class="w-16 h-16
bg-gradient-to-br from-green-100 to-green-200
rounded-xl flex items-center justify-center">

<img src="{{ asset('images/verify.png') }}"
class="w-8">

</div>

<div>

<h3 class="font-semibold text-lg">
Verified & Trusted
</h3>

<p class="text-gray-600 text-sm mt-2">
Support verified charities through strict verification.
</p>

</div>

</div>




<!-- Card 3 -->

<div class="bg-white p-6 rounded-2xl shadow-sm border
hover:shadow-xl hover:-translate-y-2
transition duration-300 flex gap-4">

<div class="w-16 h-16
bg-gradient-to-br from-blue-100 to-blue-200
rounded-xl flex items-center justify-center">

<img src="{{ asset('images/rotation.png') }}"
class="w-8">

</div>

<div>

<h3 class="font-semibold text-lg">
Guaranteed Updates
</h3>

<p class="text-gray-600 text-sm mt-2">
Stay informed with regular campaign updates.
</p>

</div>

</div>




<!-- Card 4 -->

<div class="bg-white p-6 rounded-2xl shadow-sm border
hover:shadow-xl hover:-translate-y-2
transition duration-300 flex gap-4">

<div class="w-16 h-16
bg-gradient-to-br from-purple-100 to-purple-200
rounded-xl flex items-center justify-center">

<img src="{{ asset('images/easy-return.png') }}"
class="w-8">

</div>

<div>

<h3 class="font-semibold text-lg">
Easy Setup
</h3>

<p class="text-gray-600 text-sm mt-2">
Launch a fundraiser in minutes.
</p>

</div>

</div>




<!-- Card 5 -->

<div class="bg-white p-6 rounded-2xl shadow-sm border
hover:shadow-xl hover:-translate-y-2
transition duration-300 flex gap-4">

<div class="w-16 h-16
bg-gradient-to-br from-red-100 to-red-200
rounded-xl flex items-center justify-center">

<img src="{{ asset('images/lock.png') }}"
class="w-8">

</div>

<div>

<h3 class="font-semibold text-lg">
Secure & Private
</h3>

<p class="text-gray-600 text-sm mt-2">
Encrypted payments and protected data.
</p>

</div>

</div>




<!-- Card 6 -->

<div class="bg-white p-6 rounded-2xl shadow-sm border
hover:shadow-xl hover:-translate-y-2
transition duration-300 flex gap-4">

<div class="w-16 h-16
bg-gradient-to-br from-indigo-100 to-indigo-200
rounded-xl flex items-center justify-center">

<img src="{{ asset('images/support.png') }}"
class="w-8">

</div>

<div>

<h3 class="font-semibold text-lg">
24x7 Support
</h3>

<p class="text-gray-600 text-sm mt-2">
We are always here to help you.
</p>

</div>

</div>



</div>

</div>

</section>



<!-- UPDATES --> <div class="bg-gray-50 py-20"> 
    <div class="max-w-5xl mx-auto px-6"> 
        <h2 class="text-4xl font-bold mb-8"> Updates </h2> 
        <div class="flex justify-between items-center"> 
            <p class="text-gray-600"> Feel free to ask Campaigner for a new update on this fundraiser. </p> 
<!-- button for ask for update -->

            <a href="{{ route('contact',['campaign'=>$campaign->title]) }}"
class="btn-donate px-6 py-3 rounded-lg shadow-lg inline-block">

Ask for update

</a>
        </div> 
    </div> 
</div>















<!-- RECENT DONORS -->

<div class="py-20 bg-gray-50">

<div class="max-w-7xl mx-auto px-6">

<h2 class="text-4xl font-bold text-center mb-12">

Recent Donors ❤️

</h2>


<div class="relative overflow-hidden">


<div id="donorSlider"
class="flex transition duration-700">

</div>
<!-- DONOR -->

@foreach($campaign->donations->take(10) as $donation)

<div class="min-w-[300px] mx-4">

<div class="glass p-6 rounded-2xl text-center">


<div class="w-16 h-16 mx-auto mb-3
bg-indigo-100 rounded-full
flex items-center justify-center
text-xl font-bold">

{{ strtoupper(substr($donation->name,0,1)) }}

</div>


<h3 class="font-semibold">

{{ $donation->name }}

</h3>


<p class="text-gray-500">

Donated ₹{{ number_format($donation->amount) }}

</p>


<p class="text-sm text-gray-400 mt-2">

{{ $donation->created_at->diffForHumans() }}

</p>


</div>

</div>

@endforeach


</div>

</div>
<!-- BUTTONS OFF -->

<!-- <button onclick="prevDonor()"
class="absolute left-0 top-1/2 -translate-y-1/2
bg-white shadow p-3 rounded-full">

◀

</button> -->


<!-- <button onclick="nextDonor()"
class="absolute right-0 top-1/2 -translate-y-1/2
bg-white shadow p-3 rounded-full">

▶

</button> -->


</div>


</div>

</div>



<script>

let donorIndex=0;

function nextDonor(){

let slider=
document.getElementById("donorSlider");

donorIndex++;

slider.style.transform=
"translateX(-"+(donorIndex*320)+"px)";

}


function prevDonor(){

let slider=document.getElementById("donorSlider");



if(donorIndex>0){

donorIndex--;

slider.style.transform=
"translateX(-"+(donorIndex*320)+"px)";

}

}


// AUTO SLIDE for IMPACT SECTION

setInterval(()=>{

nextDonor();

},4000);




function selectAmount(amount,btn){

document.getElementById('customAmount').value=amount;


document.querySelectorAll('.amount-btn')
.forEach(b=>b.classList.remove('active'));


btn.classList.add('active');


}



function openFAQ(btn){


let content=btn.nextElementSibling;


content.classList.toggle('hidden');


}



let index = 0;

function nextImpact(){

const slider =
document.getElementById('impactCarousel');

index++;

if(index>1)
index=0;

slider.style.transform =
`translateX(-${index*100}%)`;

}


function prevImpact(){

const slider =
document.getElementById('impactCarousel');

index--;

if(index<0)
index=1;

slider.style.transform =
`translateX(-${index*100}%)`;

}


</script>

@endsection