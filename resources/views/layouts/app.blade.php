<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Trainify') — Trainify</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>

<body class="bg-surface-dark text-white antialiased font-body">
    @php
        $unreadCount = auth()->check() ? auth()->user()->notifications()->where('is_read', false)->count() : 0;
        $notificationRouteName = auth()->check() ? auth()->user()->role . '.notifications.index' : null;
        $hasNotificationRoute = $notificationRouteName ? Route::has($notificationRouteName) : false;
    @endphp

    {{-- Sidebar + main layout --}}
    <div class="flex h-screen overflow-hidden bg-[#050c18]">
        <div id="sidebar-backdrop" class="fixed inset-0 z-40 hidden bg-black/50 md:hidden" aria-hidden="true"></div>

        {{-- Sidebar --}}
        <aside id="app-sidebar"
            class="fixed inset-y-0 left-0 z-50 flex h-full w-72 max-w-[86vw] -translate-x-full flex-col border-r border-app-border/80 bg-[#030917] transition-transform duration-200 ease-out md:static md:z-auto md:w-64 md:max-w-none md:translate-x-0">
            {{-- Logo --}}
            <div class="shrink-0 border-b border-app-border p-4 md:p-6">
                <div class="flex items-center justify-between gap-3">
                    <a href="{{ route(auth()->user()->role . '.dashboard') }}" class="flex items-center gap-3">
                        <div class="brand-mark h-10 w-10">
                            <img src="{{ asset('logo.png') }}" alt="Trainify" class="brand-icon"
                                onerror="this.style.display='none'; this.nextElementSibling.style.display='block';" />
                            <svg class="brand-fallback h-6 w-6 text-brand" fill="none" stroke="currentColor"
                                stroke-width="2.5" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round"
                                aria-hidden="true">
                                <path d="M4 12h4l3-9 4 18 2-9h3" />
                            </svg>
                        </div>
                        <span class="text-xl font-bold font-heading">Traini<span class="text-brand">fy</span></span>
                    </a>
                    <button id="sidebar-close" type="button"
                        class="ml-auto inline-flex h-9 w-9 items-center justify-center rounded-lg border border-app-border text-gray-300 hover:bg-white/5 md:hidden"
                        aria-label="Close menu">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"
                            aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>

            {{-- Nav --}}
            <nav class="flex-1 space-y-1 overflow-y-auto p-4">
                @yield('sidebar-nav')
            </nav>

            {{-- User info + Sign out --}}
            <div class="shrink-0 border-t border-app-border p-4">
                <div class="mb-3 flex items-center gap-3">
                    @if (auth()->user()->avatar)
                        <img src="{{ asset('storage/' . auth()->user()->avatar) }}" alt="{{ auth()->user()->full_name }}"
                            class="h-10 w-10 rounded-full object-cover">
                    @else
                        <div
                            class="flex h-10 w-10 items-center justify-center rounded-full bg-brand/10 text-sm font-semibold text-brand">
                            {{ strtoupper(substr(auth()->user()->full_name, 0, 2)) }}
                        </div>
                    @endif

                    <div class="min-w-0 flex-1">
                        <p class="truncate text-sm font-semibold">{{ auth()->user()->full_name }}</p>
                        <p class="text-xs capitalize text-gray-500">{{ auth()->user()->role }}</p>
                    </div>
                </div>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="flex w-full items-center gap-2 rounded-lg px-3 py-2 text-left text-sm text-gray-400 transition-colors hover:bg-red-400/10 hover:text-red-400">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"
                            aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        Sign Out
                    </button>
                </form>
            </div>
        </aside>

        {{-- Main Content --}}
        <main class="flex min-w-0 flex-1 flex-col overflow-y-auto">
            {{-- Header --}}
            <header
                class="sticky top-0 z-30 flex items-center justify-between border-b border-app-border/80 bg-[#050c18]/90 px-4 py-3 backdrop-blur-lg md:px-8 md:py-4">
                <div class="flex min-w-0 items-center gap-3">
                    <button id="sidebar-open" type="button"
                        class="inline-flex h-9 w-9 items-center justify-center rounded-lg border border-app-border text-gray-300 hover:bg-white/5 md:hidden"
                        aria-label="Open menu">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"
                            aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                    <h1 class="truncate font-heading text-xl font-bold md:text-2xl">@yield('page-title')</h1>
                </div>

                <div class="flex items-center gap-3">
                    @yield('header-actions')

                    @if ($hasNotificationRoute)
                        <a href="{{ route($notificationRouteName) }}"
                            class="flex items-center gap-2 text-sm text-gray-300 transition-colors hover:text-brand"
                            aria-label="Open notifications">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15 17h5l-1.4-1.4a2 2 0 01-.6-1.4V11a6 6 0 10-12 0v3.2a2 2 0 01-.6 1.4L4 17h5m6 0a3 3 0 11-6 0m6 0H9" />
                            </svg>
                            <span>{{ $unreadCount }}</span>
                        </a>
                    @else
                        <div class="flex items-center gap-2 text-sm text-gray-300">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15 17h5l-1.4-1.4a2 2 0 01-.6-1.4V11a6 6 0 10-12 0v3.2a2 2 0 01-.6 1.4L4 17h5m6 0a3 3 0 11-6 0m6 0H9" />
                            </svg>
                            <span>{{ $unreadCount }}</span>
                        </div>
                    @endif

                    @if (auth()->user()->avatar)
                        <img src="{{ asset('storage/' . auth()->user()->avatar) }}"
                            alt="{{ auth()->user()->full_name }}" class="h-9 w-9 rounded-full object-cover">
                    @else
                        <div
                            class="flex h-9 w-9 items-center justify-center rounded-full bg-brand/10 text-sm font-semibold text-brand">
                            {{ strtoupper(substr(auth()->user()->full_name, 0, 1)) }}
                        </div>
                    @endif
                </div>
            </header>

            {{-- Alerts --}}
            <div class="px-4 pt-4 md:px-8">
                @if (session('success'))
                    <div class="mb-4 rounded-xl border border-brand/30 bg-brand/10 p-4 text-sm text-brand">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="mb-4 rounded-xl border border-red-500/30 bg-red-500/10 p-4 text-sm text-red-400">
                        {{ session('error') }}
                    </div>
                @endif
            </div>

            {{-- Page Content --}}
            <div class="flex-1 px-4 py-5 md:px-8 md:py-6">
                @yield('content')
            </div>
        </main>
    </div>

    <script>
        (() => {
            const sidebar = document.getElementById('app-sidebar');
            const backdrop = document.getElementById('sidebar-backdrop');
            const openBtn = document.getElementById('sidebar-open');
            const closeBtn = document.getElementById('sidebar-close');

            if (!sidebar || !backdrop || !openBtn || !closeBtn) {
                return;
            }

            const openSidebar = () => {
                sidebar.classList.remove('-translate-x-full');
                backdrop.classList.remove('hidden');
                document.body.classList.add('overflow-hidden');
            };

            const closeSidebar = () => {
                sidebar.classList.add('-translate-x-full');
                backdrop.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
            };

            openBtn.addEventListener('click', openSidebar);
            closeBtn.addEventListener('click', closeSidebar);
            backdrop.addEventListener('click', closeSidebar);

            window.addEventListener('resize', () => {
                if (window.innerWidth >= 768) {
                    backdrop.classList.add('hidden');
                    document.body.classList.remove('overflow-hidden');
                    sidebar.classList.remove('-translate-x-full');
                } else {
                    sidebar.classList.add('-translate-x-full');
                }
            });
        })();
    </script>

    @stack('scripts')
</body>

</html>
