<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Trainer Profile — Trainify</title>
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

            <div class="wizard-crumbs" data-crumbs="trainer">
                <div class="crumb is-done" data-crumb="role"><span>✓</span>
                    <p>Role</p>
                </div>
                <div class="crumb-line"></div>
                <div class="crumb is-done" data-crumb="info"><span>✓</span>
                    <p>Info</p>
                </div>
                <div class="crumb-line"></div>
                <div class="crumb is-active" data-crumb="profile"><span>3</span>
                    <p>Profile</p>
                </div>
                <div class="crumb-line"></div>
                <div class="crumb" data-crumb="done"><span>4</span>
                    <p>Done</p>
                </div>
            </div>

            <form method="POST" action="{{ route('register.trainer.complete') }}" class="register-form"
                enctype="multipart/form-data">
                @csrf

                <div class="register-form-head">
                    <h3>Trainer Profile</h3>
                    <p>Help clients find you</p>
                </div>

                <div class="auth-field">
                    <label for="trainer_bio">Bio</label>
                    <textarea id="trainer_bio" name="bio" rows="4" class="auth-input"
                        placeholder="Tell clients about your experience and expertise">{{ old('bio') }}</textarea>
                    @error('bio')
                        <p class="auth-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="auth-field">
                    <label for="trainer_avatar">Profile Photo (optional)</label>
                    <input id="trainer_avatar" name="avatar" type="file" accept="image/*" class="auth-input">
                    @error('avatar')
                        <p class="auth-error">{{ $message }}</p>
                    @enderror
                </div>

                <fieldset class="register-specializations">
                    <legend>Specializations</legend>
                    @php $selectedSpecs = old('specializations', []); @endphp
                    @foreach (['Fitness & Gym', 'Sports Coaching', 'Knowledge/Academic', 'Wellness'] as $spec)
                        <label>
                            <input type="checkbox" name="specializations[]" value="{{ $spec }}"
                                {{ in_array($spec, $selectedSpecs, true) ? 'checked' : '' }}>
                            <span>{{ $spec }}</span>
                        </label>
                    @endforeach
                    @error('specializations')
                        <p class="auth-error">{{ $message }}</p>
                    @enderror
                    @error('specializations.*')
                        <p class="auth-error">{{ $message }}</p>
                    @enderror
                </fieldset>

                <div class="auth-field">
                    <label for="trainer_tags">Tags</label>
                    <input id="trainer_tags" name="tags" type="text" value="{{ old('tags') }}"
                        class="auth-input" placeholder="Example: strength, weight loss, mobility">
                    <p class="mt-2 text-sm text-gray-400">Optional. Separate tags with commas. If left blank, your
                        specializations will be used.</p>
                    @error('tags')
                        <p class="auth-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="wizard-actions">
                    <a href="{{ route('register.trainer') }}" class="btn btn-secondary">Back</a>
                    <button type="submit" class="auth-button">Submit Application</button>
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
