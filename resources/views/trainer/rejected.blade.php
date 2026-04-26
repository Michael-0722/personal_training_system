<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Rejected — Trainify</title>@vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-surface-dark text-white min-h-screen flex items-center justify-center p-8 font-body">
    <div class="text-center max-w-md">
        <div class="w-20 h-20 rounded-full bg-red-500/20 flex items-center justify-center mx-auto mb-6">
            <svg class="w-10 h-10 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </div>
        <h1 class="text-3xl font-bold font-heading mb-3">Application Rejected</h1>
        <p class="text-gray-400 mb-4">Unfortunately, your trainer application was not approved.</p>
        @if (session('error'))
            <p class="text-red-400 text-sm mb-6">{{ session('error') }}</p>
        @endif
        <form method="POST" action="{{ route('logout') }}">@csrf<button type="submit"
                class="px-6 py-3 bg-surface rounded-xl text-white hover:bg-surface-light">Sign Out</button></form>
    </div>
</body>

</html>
