<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trainify — Train Smarter. Grow Faster.</title>
    <link rel="icon" type="image/png" href="{{ asset('logo.png') }}">
    <link rel="alternate icon" href="{{ asset('favicon.ico') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen font-body text-white antialiased bg-page-shell">
    <div class="page-glow page-glow-left"></div>
    <div class="page-glow page-glow-right"></div>

    <nav class="fixed inset-x-0 top-0 z-50 h-[76px] overflow-hidden border-b border-white/6 bg-surface-dark/85 backdrop-blur-xl"
        style="position: fixed; top: 0; left: 0; right: 0;">
        <div class="mx-auto flex h-full max-w-7xl items-center justify-between px-6 py-4 lg:px-8">
            <a href="{{ route('welcome') }}" class="flex items-center gap-3">
                <div class="brand-mark">
                    <img src="{{ asset('logo.png') }}" alt="Trainify" class="brand-icon"
                        onerror="this.style.display='none'; this.nextElementSibling.style.display='block';" />
                    <svg class="brand-fallback" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M4 12h4l3-9 4 18 2-9h3" />
                    </svg>
                </div>
                <span class="text-lg font-semibold tracking-tight font-heading">Traini<span
                        class="text-brand">fy</span></span>
            </a>

            <div class="flex items-center gap-2 sm:gap-3">
                <a href="{{ route('login') }}"
                    class="nav-link border border-white/10 bg-white/5 transition-[background-color,border-color,box-shadow,color,filter] hover:border-brand/70 hover:bg-brand/25 hover:text-white hover:brightness-110 hover:shadow-[0_0_0_2px_rgba(24,169,107,0.35),0_0_26px_rgba(24,169,107,0.30),0_16px_30px_rgba(0,0,0,0.30)]">Sign
                    In</a>
                <a href="{{ route('register') }}" class="btn btn-primary btn-sm">Get Started</a>
            </div>
        </div>
    </nav>

    <main class="pt-[76px]">
        <section class="section-hero">
            <div class="mx-auto max-w-5xl px-6 lg:px-8">
                <div class="hero-panel">
                    <span class="badge mx-auto">
                        <svg class="h-3.5 w-3.5 text-brand" viewBox="0 0 24 24" fill="currentColor">
                            <path
                                d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" />
                        </svg>
                        Trusted by {{ number_format($heroTrustedUsers ?? 0) }}+ active users
                    </span>

                    <h1 class="hero-title">
                        Train Smarter. <span class="block text-brand">Grow Faster.</span>
                    </h1>

                    <p class="hero-copy">
                        The all-in-one platform connecting clients with expert trainers in fitness, sports, academics,
                        and wellness. Book sessions, track progress, and achieve your goals.
                    </p>

                    <div class="hero-actions">
                        <a href="{{ route('register') }}" class="btn btn-primary">
                            Get Started
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24">
                                <path d="M5 12h14M12 5l7 7-7 7" />
                            </svg>
                        </a>
                        <a href="{{ route('login') }}"
                            class="btn btn-secondary border border-white/10 bg-white/5 transition-[background-color,border-color,box-shadow,color,filter] hover:border-brand/70 hover:bg-brand/25 hover:text-white hover:brightness-110 hover:shadow-[0_0_0_2px_rgba(24,169,107,0.35),0_0_28px_rgba(24,169,107,0.30),0_16px_30px_rgba(0,0,0,0.30)]">
                            Sign In
                        </a>
                    </div>
                </div>

                <div class="stats-grid">
                    @foreach ($stats ?? [] as $stat)
                        <div class="metric-card">
                            <div class="metric-value">{{ $stat['value'] }}</div>
                            <div class="metric-label">{{ $stat['label'] }}</div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        <section class="section-panel section-panel-muted">
            <div class="mx-auto max-w-7xl px-6 py-16 lg:px-8 lg:py-20">
                <div class="section-heading">
                    <h2>Browse by Category</h2>
                    <p>Find the perfect trainer for your needs across multiple categories</p>
                </div>

                <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-4">
                    @forelse (($categories ?? []) as $category)
                        <div class="category-card">
                            <div class="category-icon {{ $category['tone'] }}">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2"
                                    viewBox="0 0 24 24">
                                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                                    <circle cx="9" cy="7" r="4" />
                                    <path d="M23 21v-2a4 4 0 0 0-3-3.87" />
                                    <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                                </svg>
                            </div>
                            <h3>{{ $category['name'] }}</h3>
                            <p>{{ $category['count'] }} {{ $category['count'] === 1 ? 'Trainer' : 'Trainers' }}</p>
                        </div>
                    @empty
                        <div class="category-card md:col-span-2 xl:col-span-4">
                            <h3>No categories yet</h3>
                            <p>Trainer categories will appear after approved trainers add specializations.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </section>

        <section class="section-panel">
            <div class="mx-auto max-w-7xl px-6 py-16 lg:px-8 lg:py-20">
                <div class="section-heading">
                    <h2>Featured Trainers</h2>
                    <p>Meet our top-rated professionals ready to help you achieve your goals</p>
                </div>

                <div class="grid gap-6 lg:grid-cols-3">
                    @forelse (($featuredTrainers ?? []) as $trainer)
                        <article class="trainer-card">
                            <div class="trainer-header">
                                <div class="trainer-avatar">
                                    {{ strtoupper(substr($trainer->full_name, 0, 1) . substr(strrchr(' ' . $trainer->full_name, ' '), 1, 1)) }}
                                </div>
                                <div>
                                    <h3>{{ $trainer->full_name }}</h3>
                                    <div class="trainer-rating">
                                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor">
                                            <path
                                                d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" />
                                        </svg>
                                        <span>{{ number_format((float) ($trainer->trainerProfile->rating ?? 0), 1) }}</span>
                                        <span>{{ (int) ($trainer->trainerProfile->review_count ?? 0) }} reviews</span>
                                    </div>
                                </div>
                            </div>

                            <p class="trainer-copy">
                                {{ $trainer->trainerProfile->bio ?: 'Experienced trainer ready to help you hit your goals.' }}
                            </p>

                            <div class="tag-row">
                                @foreach (collect($trainer->trainerProfile->specializations ?? [])->take(3) as $tag)
                                    <span class="tag-pill">{{ $tag }}</span>
                                @endforeach
                            </div>

                            <div class="trainer-footer">
                                <span>{{ (int) ($trainer->trainerProfile->sessions_completed ?? 0) }} sessions</span>
                                <a
                                    href="{{ auth()->check() && auth()->user()->isClient() ? route('client.trainer.profile', $trainer) : route('login') }}">View
                                    Profile <span>→</span></a>
                            </div>
                        </article>
                    @empty
                        <article class="trainer-card lg:col-span-3">
                            <p class="trainer-copy">No featured trainers yet. Approved trainers will appear here
                                automatically.</p>
                        </article>
                    @endforelse
                </div>
            </div>
        </section>

        <section class="section-panel section-panel-muted">
            <div class="mx-auto max-w-7xl px-6 py-16 lg:px-8 lg:py-20">
                <div class="section-heading">
                    <h2>Why Choose Trainify?</h2>
                    <p>Everything you need to succeed in one powerful platform</p>
                </div>

                @php
                    $features = [
                        [
                            'icon' => 'M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2',
                            'title' => 'Expert Trainers',
                            'desc' =>
                                'Connect with certified professionals across fitness, sports, academics, and wellness.',
                        ],
                        [
                            'icon' =>
                                'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z',
                            'title' => 'Easy Scheduling',
                            'desc' => 'Book sessions at your convenience with our intuitive calendar system.',
                        ],
                        [
                            'icon' => 'M23 6l-9.5 9.5-5-5L1 18',
                            'title' => 'Track Progress',
                            'desc' => 'Monitor your growth and achievements with detailed analytics.',
                        ],
                        [
                            'icon' => 'M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z',
                            'title' => 'Secure Platform',
                            'desc' => 'Safe and secure payments with 24/7 customer support.',
                        ],
                        [
                            'icon' => 'M13 10V3L4 14h7v7l9-11h-7z',
                            'title' => 'Instant Bookings',
                            'desc' => 'Get confirmed bookings instantly and start your journey today.',
                        ],
                        [
                            'icon' => 'M12 15l-2-2m0 0l2-2m-2 2h12M4 6h16M4 12h16M4 18h16',
                            'title' => 'Verified Professionals',
                            'desc' => 'All trainers are vetted and approved by our quality assurance team.',
                        ],
                    ];
                @endphp

                <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-3">
                    @foreach ($features as $feature)
                        <article class="feature-card">
                            <div class="feature-icon">
                                <svg class="h-5 w-5 text-brand" fill="none" stroke="currentColor"
                                    stroke-width="2" viewBox="0 0 24 24">
                                    <path d="{{ $feature['icon'] }}" />
                                </svg>
                            </div>
                            <h3>{{ $feature['title'] }}</h3>
                            <p>{{ $feature['desc'] }}</p>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>

        <section class="section-panel">
            <div class="mx-auto max-w-7xl px-6 py-16 lg:px-8 lg:py-20">
                <div class="section-heading">
                    <h2>What Our Users Say</h2>
                    <p>Real reviews from satisfied clients</p>
                </div>

                <div class="grid gap-6 lg:grid-cols-2 lg:max-w-4xl lg:mx-auto">
                    @forelse (($testimonials ?? []) as $review)
                        <article class="testimonial-card">
                            <div class="stars">{{ str_repeat('★', (int) $review->rating) }}</div>
                            <p>"{{ $review->comment ?: 'Great training session and smooth booking experience.' }}"</p>
                            <div class="testimonial-meta">
                                <strong>{{ $review->client?->full_name ?? 'Trainify Client' }}</strong>
                                <span>Trainify Client - Trained with
                                    {{ $review->trainer?->full_name ?? 'Trainify Trainer' }}</span>
                            </div>
                        </article>
                    @empty
                        <article class="testimonial-card lg:col-span-2">
                            <p>No reviews yet. Client testimonials will show here after completed sessions are reviewed.
                            </p>
                        </article>
                    @endforelse
                </div>
            </div>
        </section>

        <section class="cta-section">
            <div class="mx-auto max-w-7xl px-6 py-16 text-center lg:px-8">
                <h2>Ready to Start Your Journey?</h2>
                <p>Join {{ number_format($ctaClientCount ?? 0) }}+ clients and
                    {{ number_format($ctaTrainerCount ?? 0) }} trainers who are achieving their goals with Trainify.
                </p>
                <div class="hero-actions justify-center">
                    <a href="{{ route('register') }}?role=client" class="btn btn-primary">
                        Join as Client
                    </a>
                    <a href="{{ route('register') }}?role=trainer" class="btn btn-secondary">
                        Become a Trainer
                    </a>
                </div>
            </div>
        </section>
    </main>

    <footer class="border-t border-white/6 bg-surface-dark/80">
        <div class="mx-auto max-w-7xl px-6 py-12 lg:px-8">
            <div class="footer-grid">
                <div>
                    <a href="{{ route('welcome') }}" class="flex items-center gap-3">
                        <div class="brand-mark">
                            <img src="{{ asset('logo.png') }}" alt="Trainify" class="brand-icon"
                                onerror="this.style.display='none'; this.nextElementSibling.style.display='block';" />
                            <svg class="brand-fallback" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"
                                aria-hidden="true">
                                <path d="M4 12h4l3-9 4 18 2-9h3" />
                            </svg>
                        </div>
                        <span class="text-lg font-semibold tracking-tight font-heading">Traini<span
                                class="text-brand">fy</span></span>
                    </a>
                    <p class="footer-copy">Connecting clients with expert trainers worldwide.</p>
                </div>

                <div>
                    <h3>Platform</h3>
                    <ul>
                        <li><a href="#">Browse Trainers</a></li>
                        <li><a href="#">Become a Trainer</a></li>
                        <li><a href="#">How It Works</a></li>
                        <li><a href="#">Pricing</a></li>
                    </ul>
                </div>

                <div>
                    <h3>Support</h3>
                    <ul>
                        <li><a href="#">Help Center</a></li>
                        <li><a href="#">Contact Us</a></li>
                        <li><a href="#">FAQ</a></li>
                        <li><a href="#">Safety</a></li>
                    </ul>
                </div>

                <div>
                    <h3>Company</h3>
                    <ul>
                        <li><a href="#">About Us</a></li>
                        <li><a href="#">Careers</a></li>
                        <li><a href="#">Blog</a></li>
                        <li><a href="#">Press</a></li>
                    </ul>
                </div>
            </div>

            <div class="footer-bar">
                <p>&copy; 2026 Trainify. All rights reserved.</p>
            </div>
        </div>
    </footer>
</body>

</html>
