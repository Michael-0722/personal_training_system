@extends('layouts.app')
@section('title', 'Calendar')
@section('page-title', 'My Calendar')
@section('sidebar-nav')
    @include('trainer.partials.sidebar-nav')
@endsection
@section('content')
    <div class="space-y-6">
        @forelse($bookings as $date => $dayBookings)
            <div class="bg-surface rounded-2xl border border-app-border p-6">
                <h3 class="text-lg font-bold font-heading mb-4">{{ \Carbon\Carbon::parse($date)->format('l, F j, Y') }}</h3>
                <div class="space-y-3">
                    @foreach ($dayBookings as $booking)
                        <div class="flex items-center justify-between p-4 bg-surface-light rounded-xl">
                            <div class="flex items-center gap-4">
                                <div
                                    class="w-12 h-12 rounded-xl bg-brand/20 flex items-center justify-center text-brand font-bold">
                                    {{ $booking->booking_time }}</div>
                                <div>
                                    <p class="font-medium">{{ $booking->sessionType->title }}</p>
                                    <p class="text-sm text-gray-400">with {{ $booking->client->full_name }} ·
                                        {{ $booking->duration_minutes }} min</p>
                                </div>
                            </div>
                            <span
                                class="px-2 py-1 text-xs rounded-full {{ $booking->status === 'completed' ? 'bg-blue-500/20 text-blue-400' : 'bg-brand/20 text-brand' }}">{{ ucfirst($booking->status) }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        @empty
            <div class="text-center py-16 text-gray-500">No scheduled sessions yet.</div>
        @endforelse
    </div>
@endsection
