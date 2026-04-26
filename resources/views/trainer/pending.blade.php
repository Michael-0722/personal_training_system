<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pending — Trainify</title>@vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-surface-dark text-white min-h-screen flex items-center justify-center p-8 font-body">
    <div class="text-center max-w-md">
        <div class="w-20 h-20 rounded-full bg-yellow-500/20 flex items-center justify-center mx-auto mb-6">
            <svg class="w-10 h-10 text-yellow-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>
        <h1 class="text-3xl font-bold font-heading mb-3">Application Pending</h1>
        <p class="text-gray-400 mb-8">Your trainer application is being reviewed. You'll be notified once approved.</p>
        <form method="POST" action="{{ route('logout') }}">@csrf<button type="submit"
                class="px-6 py-3 bg-surface rounded-xl text-white hover:bg-surface-light">Sign Out</button></form>
    </div>
</body>

</html>
