@extends('layouts.app')
@section('title', 'Trainer Dashboard')
@section('page-title', 'Dashboard')
@section('sidebar-nav')
    @include('trainer.partials.sidebar-nav')
@endsection
@section('content')
    {{-- Stats --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        @foreach ([
            ['label' => 'This Month Earnings', 'value' => '₱' . number_format($thisMonthEarnings, 2), 'icon' => 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
            ['label' => 'Pending Requests', 'value' => $pendingRequests, 'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2'],
            ['label' => 'Sessions Completed', 'value' => $totalSessions, 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
            ['label' => 'Avg Rating', 'value' => number_format($avgRating, 1) . ' ★', 'icon' => 'M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z'],
        ] as $stat)
            <div class="bg-surface border border-app-border rounded-2xl p-6">
                <div class="flex items-center gap-3 mb-2">
                    <div class="bg-brand/10 p-2.5 rounded-lg">
                        <svg class="w-5 h-5 text-brand" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="{{ $stat['icon'] }}" />
                        </svg>
                    </div>
                </div>
                <p class="text-sm text-gray-400 mb-1">{{ $stat['label'] }}</p>
                <p class="text-3xl font-bold font-heading">{{ $stat['value'] }}</p>
            </div>
        @endforeach
    </div>
    {{-- Upcoming Bookings --}}
    <div class="bg-surface border border-app-border rounded-2xl p-6">
        <h2 class="text-xl font-bold font-heading mb-6">Upcoming Sessions</h2>
        @forelse($upcomingBookings as $booking)
            <div class="flex items-center gap-4 p-4 rounded-xl bg-surface-light border border-app-border mb-3">
                <div
                    class="w-10 h-10 rounded-full bg-brand/10 flex items-center justify-center text-sm font-semibold text-brand">
                    {{ strtoupper(substr($booking->client->full_name, 0, 2)) }}
                </div>
                <div class="flex-1">
                    <p class="font-medium">{{ $booking->client->full_name }}</p>
                    <p class="text-sm text-gray-400">{{ $booking->sessionType->title }}</p>
                </div>
                <div class="text-right">
                    <p class="text-sm font-medium">{{ $booking->booking_date->format('M d, Y') }}</p>
                    <p class="text-sm text-gray-400">{{ $booking->booking_time }}</p>
                </div>
                <span class="px-3 py-1 rounded-full text-xs bg-brand/10 text-brand border border-brand/20">
                    Confirmed
                </span>
            </div>
        @empty
            <p class="text-gray-500 text-sm text-center py-8">No upcoming sessions.</p>
        @endforelse
    </div>
@endsection
