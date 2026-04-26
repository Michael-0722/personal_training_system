@extends('layouts.app')
@section('title', 'Admin Dashboard')
@section('page-title', 'Dashboard')
@section('sidebar-nav')
    @include('admin.partials.sidebar-nav')
@endsection

@section('content')
    {{-- Stat Cards --}}
    <div class="mb-8 grid grid-cols-1 gap-6 md:grid-cols-2 xl:grid-cols-4">
        @foreach ([['label' => 'Total Trainers', 'value' => $totalTrainers, 'icon' => 'M15.75 6.75a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.5 20.12a7.5 7.5 0 0 1 15 0A17.9 17.9 0 0 1 12 21.75c-2.7 0-5.25-.59-7.5-1.63Z'], ['label' => 'Total Clients', 'value' => $totalClients, 'icon' => 'M15.75 6.75a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.5 20.12a7.5 7.5 0 0 1 15 0A17.9 17.9 0 0 1 12 21.75c-2.7 0-5.25-.59-7.5-1.63Z'], ['label' => 'Total Revenue', 'value' => '₱' . number_format($totalRevenue, 2), 'icon' => 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z'], ['label' => 'Pending Approvals', 'value' => $pendingApprovals, 'icon' => 'M9 5.25a2.25 2.25 0 0 1 2.25-2.25h1.5A2.25 2.25 0 0 1 15 5.25V6h1.5A2.25 2.25 0 0 1 18.75 8.25v10.5A2.25 2.25 0 0 1 16.5 21H7.5a2.25 2.25 0 0 1-2.25-2.25V8.25A2.25 2.25 0 0 1 7.5 6H9v-.75ZM9 12.75l1.5 1.5 3-3']] as $stat)
            <div
                class="rounded-2xl border border-app-border/80 bg-[#0a1a34] p-6 shadow-[inset_0_1px_0_rgba(255,255,255,0.03)]">
                <div class="mb-3 flex items-center gap-3">
                    <div class="rounded-lg bg-[#0c2b43] p-2.5">
                        <svg class="h-5 w-5 text-brand" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"
                            aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="{{ $stat['icon'] }}" />
                        </svg>
                    </div>
                </div>

                <p class="mb-1 text-sm text-gray-400">{{ $stat['label'] }}</p>
                <p class="font-heading text-[2.5rem] font-bold leading-none">{{ $stat['value'] }}</p>
            </div>
        @endforeach
    </div>

    {{-- Revenue Chart --}}
    @php
        $maxValue = max(
            1000,
            (int) ceil(
                collect($monthlyData)->flatMap(fn($row) => [(float) $row->gross, (float) $row->trainer_payout])->max() /
                    1000,
            ) * 1000,
        );
        $yTicks = [$maxValue, (int) ($maxValue * 0.75), (int) ($maxValue * 0.5), (int) ($maxValue * 0.25), 0];
    @endphp
    <div
        class="mb-8 rounded-2xl border border-app-border/80 bg-[#0a1a34] p-6 shadow-[inset_0_1px_0_rgba(255,255,255,0.03)]">
        <h2 class="mb-6 text-xl font-bold font-heading">Monthly Revenue</h2>
        @if ($monthlyData->isEmpty())
            <div class="rounded-xl border border-dashed border-app-border/70 bg-[#09152b] px-6 py-16 text-center">
                <p class="text-base font-medium text-gray-300">No revenue data yet.</p>
                <p class="mt-2 text-sm text-gray-500">Monthly bars will appear after completed transactions are recorded.
                </p>
            </div>
        @else
            <div class="overflow-x-auto">
                <div class="min-w-[720px]">
                    <div class="mb-2 grid grid-cols-[72px_1fr]">
                        <div></div>
                        <div class="flex items-center justify-end gap-4 pr-2 text-xs">
                            <span class="inline-flex items-center gap-2 text-gray-400"><span
                                    class="h-2.5 w-2.5 rounded-full bg-[#5bb574]"></span>Gross</span>
                            <span class="inline-flex items-center gap-2 text-gray-400"><span
                                    class="h-2.5 w-2.5 rounded-full bg-[#4a81e6]"></span>Payout</span>
                        </div>
                    </div>

                    <div class="grid grid-cols-[72px_1fr] gap-2">
                        <div class="relative h-64">
                            @foreach ($yTicks as $tick)
                                <div class="absolute left-0 right-0 -translate-y-1/2 text-right text-xs text-gray-500"
                                    style="top: {{ $maxValue > 0 ? (($maxValue - $tick) / $maxValue) * 100 : 0 }}%">
                                    ₱{{ number_format($tick) }}
                                </div>
                            @endforeach
                        </div>

                        <div
                            class="relative h-64 overflow-hidden rounded-xl border border-app-border/70 bg-[#09152b] px-4 pb-6 pt-3">
                            @foreach ($yTicks as $tick)
                                <div class="absolute left-0 right-0 border-t border-dashed border-app-border/50"
                                    style="top: {{ $maxValue > 0 ? (($maxValue - $tick) / $maxValue) * 100 : 0 }}%"></div>
                            @endforeach

                            <div class="relative z-10 flex h-full items-end justify-between gap-4">
                                @foreach ($monthlyData as $row)
                                    @php
                                        $grossHeight =
                                            $maxValue > 0 ? max(6, ((float) $row->gross / $maxValue) * 100) : 0;
                                        $payoutHeight =
                                            $maxValue > 0
                                                ? max(6, ((float) $row->trainer_payout / $maxValue) * 100)
                                                : 0;
                                    @endphp
                                    <div class="flex flex-1 flex-col items-center justify-end gap-2">
                                        <div class="flex h-48 items-end gap-1.5">
                                            <div class="w-7 rounded-t-md bg-[#5bb574]" style="height: {{ $grossHeight }}%">
                                            </div>
                                            <div class="w-7 rounded-t-md bg-[#4a81e6]" style="height: {{ $payoutHeight }}%">
                                            </div>
                                        </div>
                                        <span class="text-xs text-gray-500">{{ $row->month }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

    {{-- Recent Bookings --}}
    <div class="rounded-2xl border border-app-border bg-surface p-6">
        <h2 class="mb-6 text-xl font-bold font-heading">Recent Bookings</h2>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-app-border text-gray-400">
                        <th class="pb-3 text-left">Client</th>
                        <th class="pb-3 text-left">Trainer</th>
                        <th class="pb-3 text-left">Session</th>
                        <th class="pb-3 text-left">Date</th>
                        <th class="pb-3 text-right">Amount</th>
                        <th class="pb-3 text-left">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-app-border">
                    @foreach ($recentBookings as $booking)
                        <tr>
                            <td class="py-3 font-medium">{{ $booking->client->full_name }}</td>
                            <td class="py-3 text-gray-300">{{ $booking->trainer->full_name }}</td>
                            <td class="py-3 text-gray-300">{{ $booking->sessionType->title }}</td>
                            <td class="py-3 text-gray-400">{{ $booking->booking_date->format('M d, Y') }}</td>
                            <td class="py-3 text-right">₱{{ number_format($booking->amount, 2) }}</td>
                            <td class="py-3">
                                @php
                                    $statusClasses = [
                                        'pending' => 'border-amber-500/20 bg-amber-500/10 text-amber-400',
                                        'confirmed' => 'border-brand/20 bg-brand/10 text-brand',
                                        'completed' => 'border-blue-500/20 bg-blue-500/10 text-blue-400',
                                        'cancelled' => 'border-red-500/20 bg-red-500/10 text-red-400',
                                    ];
                                    $badgeClass =
                                        $statusClasses[$booking->status] ??
                                        'border-gray-500/20 bg-gray-500/10 text-gray-400';
                                @endphp
                                <span class="rounded-full border px-2 py-0.5 text-xs capitalize {{ $badgeClass }}">
                                    {{ $booking->status }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
