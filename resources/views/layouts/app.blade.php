<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>FundRaise</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    {{-- ✅ This loads Tailwind --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100">

    @include('layouts.navigation')

    <div class="min-h-screen">
        @yield('content')
    </div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</body>
</html>
