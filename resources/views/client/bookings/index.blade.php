@extends('layouts.app')
@section('title', 'My Bookings')
@section('page-title', 'My Bookings')
@section('sidebar-nav')
    @include('client.partials.sidebar-nav')
@endsection

@section('content')
    <div class="mb-6">
        <form method="GET" class="flex gap-3">
            <select name="status" class="rounded-xl border border-app-border bg-surface px-4 py-2 text-white">
                <option value="">All</option>
                <option value="pending" @selected(request('status') === 'pending')>Pending</option>
                <option value="confirmed" @selected(request('status') === 'confirmed')>Confirmed</option>
                <option value="completed" @selected(request('status') === 'completed')>Completed</option>
                <option value="cancelled" @selected(request('status') === 'cancelled')>Cancelled</option>
            </select>

            <button type="submit" class="rounded-xl bg-brand px-4 py-2 text-white hover:bg-brand-light">Filter</button>
        </form>
    </div>

    <div class="space-y-4">
        @forelse($bookings as $booking)
            <div class="rounded-2xl border border-app-border bg-surface p-6">
                <div class="flex items-center justify-between">
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

                    <div class="flex items-center gap-4">
                        <span class="text-lg font-bold">₱{{ number_format($booking->amount, 2) }}</span>
                        @php
                            $s = $booking->status;
                            $isRefunded =
                                $s === 'cancelled' &&
                                str_contains((string) $booking->cancellation_reason, 'Refunded amount');
                        @endphp
                        <span
                            class="rounded-full px-2 py-1 text-xs {{ $s === 'completed' ? 'bg-blue-500/20 text-blue-400' : ($s === 'confirmed' ? 'bg-brand/20 text-brand' : ($s === 'pending' ? 'bg-yellow-500/20 text-yellow-400' : 'bg-red-500/20 text-red-400')) }}">{{ ucfirst($s) }}</span>
                        @if ($isRefunded)
                            <span class="rounded-full bg-emerald-500/20 px-2 py-1 text-xs text-emerald-400">Refunded</span>
                        @endif
                    </div>
                </div>

                <div class="mt-4 flex items-center gap-3">
                    @if ($booking->isPending() || $booking->isConfirmed())
                        <form method="POST" action="{{ route('client.bookings.cancel', $booking) }}">
                            @csrf
                            <button class="rounded-lg bg-red-600/20 px-3 py-1 text-xs text-red-400">Cancel</button>
                        </form>
                    @endif

                    @if ($booking->isCompleted() && !$booking->review)
                        <button onclick="document.getElementById('review-{{ $booking->id }}').classList.toggle('hidden')"
                            class="rounded-lg bg-brand/20 px-3 py-1 text-xs text-brand">Leave Review</button>
                    @endif

                    @if ($booking->review)
                        <span class="text-xs text-yellow-400">{{ str_repeat('★', $booking->review->rating) }}</span>
                    @endif
                </div>

                @if ($booking->isCompleted() && !$booking->review)
                    <div id="review-{{ $booking->id }}" class="mt-4 hidden rounded-xl bg-surface-light p-4">
                        <form method="POST" action="{{ route('client.bookings.review', $booking) }}" class="space-y-3">
                            @csrf
                            <div>
                                <label class="text-sm text-gray-300">Rating</label>
                                <select name="rating" class="ml-2 rounded-lg bg-surface px-3 py-1 text-white">
                                    @for ($i = 5; $i >= 1; $i--)
                                        <option value="{{ $i }}">{{ str_repeat('★', $i) }}</option>
                                    @endfor
                                </select>
                            </div>

                            <div>
                                <textarea name="comment" rows="2" class="w-full rounded-lg bg-surface px-3 py-2 text-white"
                                    placeholder="Your review..."></textarea>
                            </div>

                            <button type="submit" class="rounded-lg bg-brand px-4 py-2 text-sm text-white">Submit</button>
                        </form>
                    </div>
                @endif
            </div>
        @empty
            <div class="py-16 text-center text-gray-500">No bookings yet. <a href="{{ route('client.browse') }}"
                    class="text-brand">Browse trainers</a>!</div>
        @endforelse
    </div>

    <div class="mt-6">{{ $bookings->links() }}</div>
@endsection
