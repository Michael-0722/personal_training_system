@php
    $navItems = [
        [
            'label' => 'Dashboard',
            'route' => 'client.dashboard',
            'matches' => ['client.dashboard'],
            'icon' =>
                '<path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z" />',
        ],
        [
            'label' => 'Browse Trainers',
            'route' => 'client.browse',
            'matches' => ['client.browse', 'client.trainer.profile', 'client.book.show', 'client.book.store'],
            'icon' =>
                '<path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0012 15.75a7.488 7.488 0 00-5.982 2.975m11.964 0a9 9 0 10-11.964 0m11.964 0A8.966 8.966 0 0112 21a8.966 8.966 0 01-5.982-2.275M15 9.75a3 3 0 11-6 0 3 3 0 016 0z" />',
        ],
        [
            'label' => 'My Bookings',
            'route' => 'client.bookings.index',
            'matches' => ['client.bookings.*'],
            'icon' =>
                '<path stroke-linecap="round" stroke-linejoin="round" d="M8.25 6.75h7.5M8.25 12h7.5m-7.5 5.25h7.5M3.75 5.25h.008v.008H3.75V5.25zm0 5.25h.008v.008H3.75V10.5zm0 5.25h.008v.008H3.75v-.008z" />',
        ],
        [
            'label' => 'Notifications',
            'route' => 'client.notifications.index',
            'matches' => ['client.notifications.*'],
            'icon' =>
                '<path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />',
        ],
        [
            'label' => 'Settings',
            'route' => 'client.settings.index',
            'matches' => ['client.settings.*'],
            'icon' =>
                '<path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />',
        ],
    ];
@endphp

@foreach ($navItems as $item)
    @php
        $isActive = collect($item['matches'])->contains(fn($pattern) => request()->routeIs($pattern));
    @endphp
    <a href="{{ route($item['route']) }}"
        class="flex items-center gap-3 rounded-lg py-3 text-sm transition-colors {{ $isActive ? 'border-l-4 border-brand bg-brand/10 pl-3 pr-4 text-brand' : 'px-4 text-gray-400 hover:bg-brand/5 hover:text-white' }}"
        @if ($isActive) aria-current="page" @endif>
        <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"
            aria-hidden="true">
            {!! $item['icon'] !!}
        </svg>
        <span class="font-medium">{{ $item['label'] }}</span>
    </a>
@endforeach
