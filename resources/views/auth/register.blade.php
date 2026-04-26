<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Register — Trainify</title>
    <link rel="icon" type="image/png" href="{{ asset('logo.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="auth-shell font-body text-white">
    <main class="register-shell">
        <section class="register-card register-wizard">
            <a href="{{ route('welcome') }}" class="auth-brand register-brand">
                <div class="brand-mark auth-brand-mark">
                    <img src="{{ asset('logo.png') }}" alt="Trainify" class="brand-icon auth-logo"
                        onerror="this.style.display='none'; this.nextElementSibling.style.display='block';" />
                    <svg class="brand-fallback" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M4 12h4l3-9 4 18 2-9h3" />
                    </svg>
                </div>
                <span class="auth-brand-name font-heading">Traini<span class="text-brand">fy</span></span>
            </a>

            <div class="register-copy">
                <h1>Join Trainify</h1>
                <p>Choose how you want to get started</p>
            </div>

            <div class="register-grid">
                <a href="{{ route('register.trainer') }}" class="role-card">
                    <div class="role-icon-wrap">
                        <svg class="role-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M12 3l2.5 5 5.5.8-4 3.9.9 5.6-4.9-2.6-4.9 2.6.9-5.6-4-3.9 5.5-.8L12 3z" />
                        </svg>
                    </div>
                    <h2>I’m a Trainer</h2>
                    <p>Share your expertise and earn by training clients</p>
                    <span class="role-button">Continue as Trainer</span>
                </a>

                <a href="{{ route('register.client') }}" class="role-card">
                    <div class="role-icon-wrap">
                        <svg class="role-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                            <circle cx="9" cy="7" r="4" />
                            <path d="M23 21v-2a4 4 0 0 0-3-3.87" />
                            <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                        </svg>
                    </div>
                    <h2>I’m a Client</h2>
                    <p>Find and book sessions with expert trainers</p>
                    <span class="role-button">Continue as Client</span>
                </a>
            </div>

            <p class="register-footer">
                Already have an account?
                <a href="{{ route('login') }}">Sign In</a>
            </p>
        </section>
    </main>
</body>

</html>
