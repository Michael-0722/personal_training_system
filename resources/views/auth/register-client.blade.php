<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Client Register — Trainify</title>
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

            <div class="wizard-crumbs" data-crumbs="client">
                <div class="crumb is-done" data-crumb="role"><span>✓</span>
                    <p>Role</p>
                </div>
                <div class="crumb-line"></div>
                <div class="crumb is-active" data-crumb="info"><span>2</span>
                    <p>Info</p>
                </div>
                <div class="crumb-line"></div>
                <div class="crumb" data-crumb="done"><span>3</span>
                    <p>Done</p>
                </div>
            </div>

            <form method="POST" action="{{ route('register') }}" class="register-form" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="role" value="client">

                <div class="register-form-head">
                    <h3>Client Information</h3>
                    <p>Create your account and start booking sessions</p>
                </div>

                <div class="auth-field">
                    <label for="client_full_name">Full Name</label>
                    <input id="client_full_name" name="full_name" type="text" value="{{ old('full_name') }}" required
                        class="auth-input" placeholder="Enter your full name">
                    @error('full_name')
                        <p class="auth-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="auth-field">
                    <label for="client_username">Username</label>
                    <input id="client_username" name="username" type="text" value="{{ old('username') }}" required
                        class="auth-input" placeholder="Choose a username">
                    @error('username')
                        <p class="auth-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="auth-field">
                    <label for="client_avatar">Profile Photo (optional)</label>
                    <input id="client_avatar" name="avatar" type="file" accept="image/*" class="auth-input">
                    @error('avatar')
                        <p class="auth-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="register-grid-fields">
                    <div class="auth-field">
                        <label for="client_password">Password</label>
                        <input id="client_password" name="password" type="password" required class="auth-input"
                            placeholder="Create a password">
                        @error('password')
                            <p class="auth-error">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="auth-field">
                        <label for="client_password_confirmation">Confirm Password</label>
                        <input id="client_password_confirmation" name="password_confirmation" type="password" required
                            class="auth-input" placeholder="Confirm your password">
                    </div>
                </div>

                <div class="wizard-actions">
                    <a href="{{ route('register') }}" class="btn btn-secondary">Back</a>
                    <button type="submit" class="auth-button">Create Client Account</button>
                </div>
            </form>

            <p class="register-footer">
                Already have an account?
                <a href="{{ route('login') }}">Sign In</a>
            </p>
        </section>
    </main>
</body>

</html>
