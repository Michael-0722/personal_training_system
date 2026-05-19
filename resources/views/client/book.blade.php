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

            @if ($hasActiveBooking)
                <div class="mb-4 rounded-xl border border-yellow-500/30 bg-yellow-500/10 p-4 text-sm text-yellow-300">
                    You already have an active booking for this session. Please wait for confirmation or cancel it first.
                </div>
            @endif

            <form method="POST" action="{{ route('client.book.store', [$trainer, $session]) }}" class="space-y-4" id="client-booking-form">
                @csrf

                <div>
                    <label class="mb-1 block text-sm text-gray-300">Date</label>
                    <input type="date" name="booking_date" required min="{{ now()->addDay()->format('Y-m-d') }}" id="booking-date"
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

                <div class="rounded-xl border border-app-border bg-surface-light p-4">
                    <p class="text-sm font-medium text-gray-200">Available slots for selected date</p>
                    <div id="available-slots" class="mt-2 text-sm text-gray-400">
                        Pick a date to see available time ranges.
                    </div>
                </div>

                <div class="flex items-center justify-between pt-4">
                    <div>
                        <p class="text-sm text-gray-400">Total</p>
                        <p class="text-2xl font-bold text-brand">₱{{ number_format($session->price, 2) }}</p>
                    </div>

                    <button type="button" id="open-payment-confirm" @disabled($hasActiveBooking)
                        class="rounded-xl bg-brand px-6 py-3 text-white hover:bg-brand-light disabled:cursor-not-allowed disabled:opacity-60">
                        Confirm Booking
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div id="payment-confirm-modal" class="fixed inset-0 z-50 hidden">
        <div class="absolute inset-0 bg-black/60"></div>
        <div class="absolute inset-0 flex items-center justify-center p-4">
            <div class="w-full max-w-md rounded-2xl border border-app-border bg-surface p-6 shadow-xl">
                <h4 class="text-lg font-bold font-heading">Confirm Payment</h4>
                <p class="mt-2 text-sm text-gray-400">Proceed with payment for this booking?</p>
                <div class="mt-6 flex items-center justify-end gap-3">
                    <button type="button" id="cancel-payment"
                        class="rounded-xl border border-app-border px-4 py-2 text-sm text-gray-300 hover:bg-surface-light">No</button>
                    <button type="button" id="confirm-payment"
                        class="rounded-xl bg-brand px-4 py-2 text-sm text-white hover:bg-brand-light">Yes, Pay</button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const form = document.getElementById('client-booking-form');
                const openBtn = document.getElementById('open-payment-confirm');
                const modal = document.getElementById('payment-confirm-modal');
                const cancelBtn = document.getElementById('cancel-payment');
                const confirmBtn = document.getElementById('confirm-payment');
                const dateInput = document.getElementById('booking-date');
                const slotsEl = document.getElementById('available-slots');
                const availability = @json($availabilitySlots);

                if (!form || !openBtn || !modal || !cancelBtn || !confirmBtn || !dateInput || !slotsEl) {
                    return;
                }

                const renderSlots = (dayIndex) => {
                    const matches = availability.filter((slot) => slot.day === dayIndex);
                    if (!matches.length) {
                        slotsEl.textContent = 'No availability for the selected date.';
                        return;
                    }

                    slotsEl.innerHTML = matches
                        .map((slot) => `<span class="inline-flex items-center rounded-full border border-app-border/60 bg-surface px-2 py-0.5 text-xs text-gray-300">${slot.start} — ${slot.end}</span>`)
                        .join(' ');
                };

                dateInput.addEventListener('change', (event) => {
                    if (!event.target.value) {
                        slotsEl.textContent = 'Pick a date to see available time ranges.';
                        return;
                    }

                    const selected = new Date(`${event.target.value}T00:00:00`);
                    renderSlots(selected.getDay());
                });

                const openModal = () => {
                    if (openBtn.disabled) {
                        return;
                    }
                    modal.classList.remove('hidden');
                    document.body.classList.add('overflow-hidden');
                };

                const closeModal = () => {
                    modal.classList.add('hidden');
                    document.body.classList.remove('overflow-hidden');
                };

                openBtn.addEventListener('click', openModal);
                cancelBtn.addEventListener('click', closeModal);

                modal.addEventListener('click', (event) => {
                    if (event.target === modal || event.target.classList.contains('bg-black/60')) {
                        closeModal();
                    }
                });

                confirmBtn.addEventListener('click', () => {
                    closeModal();
                    form.submit();
                });
            });
        </script>
    @endpush
@endsection
