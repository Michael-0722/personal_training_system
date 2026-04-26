@extends('layouts.app')
@section('title', 'Earnings')
@section('page-title', 'My Earnings')
@section('sidebar-nav')
    @include('trainer.partials.sidebar-nav')
@endsection
@section('content')
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-surface rounded-2xl border border-app-border p-6">
            <p class="text-xs text-gray-500 uppercase mb-1">Total Earnings</p>
            <p class="text-2xl font-bold font-heading text-brand">₱{{ number_format($totalEarnings, 2) }}</p>
        </div>
        <div class="bg-surface rounded-2xl border border-app-border p-6">
            <p class="text-xs text-gray-500 uppercase mb-1">Pending</p>
            <p class="text-2xl font-bold font-heading text-yellow-400">₱{{ number_format($pendingPayouts, 2) }}</p>
        </div>
        <div class="bg-surface rounded-2xl border border-app-border p-6">
            <p class="text-xs text-gray-500 uppercase mb-1">Paid Out</p>
            <p class="text-2xl font-bold font-heading text-blue-400">₱{{ number_format($paidPayouts, 2) }}</p>
        </div>
        <div class="bg-surface rounded-2xl border border-app-border p-6">
            <p class="text-xs text-gray-500 uppercase mb-1">Commission</p>
            <p class="text-2xl font-bold font-heading text-red-400">₱{{ number_format($totalCommission, 2) }}</p>
        </div>
    </div>
    <div class="bg-surface rounded-2xl border border-app-border overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-surface-light text-gray-400 text-xs uppercase">
                <tr>
                    <th class="px-6 py-4 text-left">Session</th>
                    <th class="px-6 py-4 text-center">Gross</th>
                    <th class="px-6 py-4 text-center">Commission</th>
                    <th class="px-6 py-4 text-center">Payout</th>
                    <th class="px-6 py-4 text-center">Status</th>
                    <th class="px-6 py-4 text-center">Date</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-app-border">
                @forelse($transactions as $txn)
                    <tr class="hover:bg-surface-light/50">
                        <td class="px-6 py-4">{{ $txn->booking->sessionType->title ?? 'N/A' }}</td>
                        <td class="px-6 py-4 text-center">₱{{ number_format($txn->gross_amount, 2) }}</td>
                        <td class="px-6 py-4 text-center text-red-400">-₱{{ number_format($txn->commission_amount, 2) }}
                        </td>
                        <td class="px-6 py-4 text-center text-brand">₱{{ number_format($txn->trainer_payout, 2) }}</td>
                        <td class="px-6 py-4 text-center"><span
                                class="px-2 py-1 text-xs rounded-full {{ $txn->payout_status === 'paid' ? 'bg-brand/20 text-brand' : 'bg-yellow-500/20 text-yellow-400' }}">{{ ucfirst($txn->payout_status) }}</span>
                        </td>
                        <td class="px-6 py-4 text-center text-gray-400">{{ $txn->created_at->format('M d, Y') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-500">No earnings yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-6">{{ $transactions->links() }}</div>
@endsection
