@extends('layouts.app')
@section('title', 'Dashboard')
@section('page-title', 'My Dashboard')
@section('sidebar-nav')
    @include('client.partials.sidebar-nav')
@endsection

@section('content')
    {{-- Welcome --}}
    <div class="mb-8">
        <h2 class="mb-2 text-3xl font-bold font-heading">Welcome back, {{ explode(' ', auth()->user()->full_name)[0] }}!</h2>
        <p class="text-gray-400">Here's what's happening with your training sessions</p>
    </div>

    <div class="mb-8 grid grid-cols-1 gap-6 md:grid-cols-3">
        <div class="rounded-2xl border border-app-border bg-surface p-6">
            <div class="mb-2 w-fit rounded-lg bg-brand/10 p-2.5">
                <svg class="h-5 w-5 text-brand" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"
                    aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
            </div>
            <p class="mb-1 text-sm text-gray-400">Active Bookings</p>
            <p class="font-heading text-3xl font-bold">{{ $activeBookings }}</p>
        </div>

        <div class="rounded-2xl border border-app-border bg-surface p-6">
            <div class="mb-2 w-fit rounded-lg bg-brand/10 p-2.5">
                <svg class="h-5 w-5 text-brand" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"
                    aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <p class="mb-1 text-sm text-gray-400">Completed</p>
            <p class="font-heading text-3xl font-bold">{{ $completedCount }}</p>
        </div>

        <div class="rounded-2xl border border-app-border bg-surface p-6">
            <div class="mb-2 w-fit rounded-lg bg-brand/10 p-2.5">
                <svg class="h-5 w-5 text-brand" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"
                    aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <p class="mb-1 text-sm text-gray-400">Total Spent</p>
            <p class="font-heading text-3xl font-bold">₱{{ number_format($totalSpent, 2) }}</p>
        </div>
    </div>

    <div class="rounded-2xl border border-app-border bg-surface p-6">
        <div class="mb-6 flex items-center justify-between">
            <h2 class="text-lg font-bold font-heading">Upcoming Sessions</h2>
            <a href="{{ route('client.browse') }}" class="text-sm text-brand">Browse Trainers →</a>
        </div>

        <div class="space-y-4">
            @forelse($upcomingBookings as $booking)
                <div class="flex items-center justify-between rounded-xl bg-surface-light p-4">
                    <div class="flex items-center gap-4">
                        <div
                            class="flex h-12 w-12 items-center justify-center rounded-xl bg-brand/20 text-xs font-bold text-brand">
                            {{ $booking->booking_date->format('M d') }}</div>
                        <div>
                            <p class="font-medium">{{ $booking->sessionType->title }}</p>
                            <p class="text-sm text-gray-400">with {{ $booking->trainer->full_name }} ·
                                {{ $booking->booking_time }}</p>
                        </div>
                    </div>

                    <span
                        class="rounded-full px-2 py-1 text-xs {{ $booking->status === 'confirmed' ? 'bg-brand/20 text-brand' : 'bg-yellow-500/20 text-yellow-400' }}">{{ ucfirst($booking->status) }}</span>
                </div>
            @empty
                <p class="py-8 text-center text-gray-500">No upcoming sessions. <a href="{{ route('client.browse') }}"
                        class="text-brand">Browse trainers</a> to book!</p>
            @endforelse
        </div>
    </div>
@endsection
