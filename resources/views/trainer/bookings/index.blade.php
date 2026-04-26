@extends('layouts.app')
@section('title', 'Bookings')
@section('page-title', 'My Bookings')
@section('sidebar-nav')
    @include('trainer.partials.sidebar-nav')
@endsection
@section('content')
    <div class="mb-6">
        <form method="GET" class="flex gap-3">
            <select name="status" class="px-4 py-2 rounded-xl bg-surface border border-app-border text-white">
                <option value="">All Status</option>
                <option value="pending" @selected(request('status') === 'pending')>Pending</option>
                <option value="confirmed" @selected(request('status') === 'confirmed')>Confirmed</option>
                <option value="completed" @selected(request('status') === 'completed')>Completed</option>
                <option value="cancelled" @selected(request('status') === 'cancelled')>Cancelled</option>
            </select>
            <button type="submit" class="px-4 py-2 bg-brand rounded-xl text-white hover:bg-brand-light">Filter</button>
        </form>
    </div>
    <div class="bg-surface rounded-2xl border border-app-border overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-surface-light text-gray-400 text-xs uppercase">
                <tr>
                    <th class="px-6 py-4 text-left">Client</th>
                    <th class="px-6 py-4 text-left">Session</th>
                    <th class="px-6 py-4 text-center">Date & Time</th>
                    <th class="px-6 py-4 text-center">Amount</th>
                    <th class="px-6 py-4 text-center">Status</th>
                    <th class="px-6 py-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-app-border">
                @forelse($bookings as $booking)
                    <tr class="hover:bg-surface-light/50">
                        <td class="px-6 py-4 font-medium">{{ $booking->client->full_name }}</td>
                        <td class="px-6 py-4 text-gray-400">{{ $booking->sessionType->title }}</td>
                        <td class="px-6 py-4 text-center">{{ $booking->booking_date->format('M d, Y') }}
                            {{ $booking->booking_time }}</td>
                        <td class="px-6 py-4 text-center">₱{{ number_format($booking->amount, 2) }}</td>
                        <td class="px-6 py-4 text-center">
                            @php $s = $booking->status; @endphp
                            <span
                                class="px-2 py-1 text-xs rounded-full {{ $s === 'completed' ? 'bg-blue-500/20 text-blue-400' : ($s === 'confirmed' ? 'bg-brand/20 text-brand' : ($s === 'pending' ? 'bg-yellow-500/20 text-yellow-400' : 'bg-red-500/20 text-red-400')) }}">{{ ucfirst($s) }}</span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            @if ($booking->isPending())
                                <div class="flex items-center justify-end gap-2">
                                    <form method="POST" action="{{ route('trainer.bookings.confirm', $booking) }}">@csrf
                                        @method('PATCH')<button
                                            class="px-3 py-1 text-xs bg-brand rounded-lg text-white">Confirm</button></form>
                                    <form method="POST" action="{{ route('trainer.bookings.reject', $booking) }}">@csrf
                                        @method('PATCH')<button
                                            class="px-3 py-1 text-xs bg-red-600 rounded-lg text-white">Decline</button>
                                    </form>
                                </div>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-500">No bookings yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-6">{{ $bookings->links() }}</div>
@endsection
