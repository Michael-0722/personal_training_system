<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sign In — Trainify</title>
    <link rel="icon" type="image/png" href="{{ asset('logo.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="auth-shell font-body text-white">
    <main class="auth-layout">
        <section class="auth-left">
            <div class="auth-brand">
                <div class="brand-mark auth-brand-mark">
                    <a href="{{ route('welcome') }}" class="auth-brand-link">
                        <img src="{{ asset('logo.png') }}" alt="Trainify" class="brand-icon auth-logo"
                            onerror="this.style.display='none'; this.nextElementSibling.style.display='block';" />
                        <svg class="brand-fallback" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M4 12h4l3-9 4 18 2-9h3" />
                        </svg>
                </div>
                <span class="auth-brand-name font-heading">Traini<span class="text-brand">fy</span></span>
            </div>

            <div class="auth-copy">
                <h1 class="auth-title">Welcome Back</h1>
                <p class="auth-subtitle">Sign in to your Trainify account</p>
            </div>

            <form method="POST" action="{{ route('login') }}" class="auth-form">
                @csrf

                <div class="auth-field">
                    <label for="username">Username</label>
                    <input id="username" name="username" type="text" value="{{ old('username') }}" required
                        autofocus class="auth-input" placeholder="Enter your username">
                    @error('username')
                        <p class="auth-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="auth-field">
                    <label for="password">Password</label>
                    <input id="password" name="password" type="password" required class="auth-input"
                        placeholder="Enter your password">
                </div>

                <button type="submit" class="auth-button">Sign In</button>
            </form>

            <p class="auth-footer">
                Don't have an account?
                <a href="{{ route('register') }}">Register here</a>
            </p>
        </section>

        <aside class="auth-right" aria-label="Trainify benefits">
            <div class="auth-right-inner">
                <h2>Join the Trainify Community</h2>

                <div class="auth-benefits">
                    <div class="auth-benefit">
                        <div class="auth-benefit-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3"
                                stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M9 12l2 2 4-4" />
                                <circle cx="12" cy="12" r="9" />
                            </svg>
                        </div>
                        <div>
                            <h3>Expert Trainers</h3>
                            <p>Access certified professionals across multiple disciplines</p>
                        </div>
                    </div>

                    <div class="auth-benefit">
                        <div class="auth-benefit-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3"
                                stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M9 12l2 2 4-4" />
                                <circle cx="12" cy="12" r="9" />
                            </svg>
                        </div>
                        <div>
                            <h3>Flexible Scheduling</h3>
                            <p>Book sessions at times that work for you</p>
                        </div>
                    </div>

                    <div class="auth-benefit">
                        <div class="auth-benefit-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3"
                                stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M9 12l2 2 4-4" />
                                <circle cx="12" cy="12" r="9" />
                            </svg>
                        </div>
                        <div>
                            <h3>Secure Payments</h3>
                            <p>Safe and encrypted transaction processing</p>
                        </div>
                    </div>
                </div>
            </div>
        </aside>
    </main>
</body>

</html>
