@extends('layouts.app')
@section('title', 'Book Session')
@section('page-title', 'Book a Session')
@section('sidebar-nav')
    @include('client.partials.sidebar-nav')
@endsection

@section('content')
    <a href="{{ route('client.trainer.profile', $trainer) }}"
        class="mb-4 inline-flex items-center gap-2 text-brand hover:text-brand-light">
        ← Back to Trainer Profile
    </a>
    <div class="max-w-2xl mx-auto">
        <div class="mb-8 rounded-2xl border border-app-border bg-surface p-6">
            <div class="mb-3 flex items-center gap-2">
                <span
                    class="rounded-full px-2 py-0.5 text-xs {{ $session->format === '1-on-1' ? 'bg-brand/20 text-brand' : 'bg-purple-500/20 text-purple-400' }}">{{ $session->format }}</span>
                <span
                    class="rounded-full px-2 py-0.5 text-xs {{ $session->delivery_mode === 'Online' ? 'bg-blue-500/20 text-blue-400' : 'bg-orange-500/20 text-orange-400' }}">{{ $session->delivery_mode }}</span>
            </div>

            <h2 class="mb-1 text-xl font-bold font-heading">{{ $session->title }}</h2>
            <p class="mb-2 text-gray-400">with {{ $trainer->full_name }}</p>
            <p class="mb-4 text-sm text-gray-400">{{ $session->description }}</p>

            <div class="flex items-center gap-6">
                <span class="text-2xl font-bold text-brand">₱{{ number_format($session->price, 2) }}</span>
                <span class="text-gray-400">{{ $session->duration_minutes }} min</span>
            </div>
        </div>

        <div class="mb-8 rounded-2xl border border-app-border bg-surface p-6">
            <h3 class="mb-4 font-bold">Trainer Availability</h3>
            <div class="grid grid-cols-2 gap-3">
                @forelse($availabilities as $slot)
                    <div class="rounded-xl bg-surface-light p-3 text-sm">
                        <span class="font-medium">{{ $slot->day_name }}</span>
                        <span class="ml-2 text-gray-400">{{ $slot->start_time }} — {{ $slot->end_time }}</span>
                    </div>
                @empty
                    <p class="col-span-2 text-sm text-gray-500">No specific availability listed.</p>
                @endforelse
            </div>
        </div>

        <div class="rounded-2xl border border-app-border bg-surface p-6">
            <h3 class="mb-4 font-bold">Select Date & Time</h3>

            <form method="POST" action="{{ route('client.book.store', [$trainer, $session]) }}" class="space-y-4">
                @csrf

                <div>
                    <label class="mb-1 block text-sm text-gray-300">Date</label>
                    <input type="date" name="booking_date" required min="{{ now()->addDay()->format('Y-m-d') }}"
                        class="w-full rounded-xl border border-app-border bg-surface-dark px-4 py-3 text-white focus:border-brand">
                    @error('booking_date')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="mb-1 block text-sm text-gray-300">Time</label>
                    <input type="time" name="booking_time" required
                        class="w-full rounded-xl border border-app-border bg-surface-dark px-4 py-3 text-white focus:border-brand">
                    @error('booking_time')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-between pt-4">
                    <div>
                        <p class="text-sm text-gray-400">Total</p>
                        <p class="text-2xl font-bold text-brand">₱{{ number_format($session->price, 2) }}</p>
                    </div>

                    <button type="submit" class="rounded-xl bg-brand px-6 py-3 text-white hover:bg-brand-light">Confirm
                        Booking</button>
                </div>
            </form>
        </div>
    </div>
@endsection
