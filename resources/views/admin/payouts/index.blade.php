@extends('layouts.app')
@section('title', 'Payouts')
@section('page-title', 'Payouts & Commissions')
@section('sidebar-nav')
    @include('admin.partials.sidebar-nav')
@endsection

@section('content')
    <div class="mb-8 grid grid-cols-1 gap-6 md:grid-cols-3">
        <div class="rounded-2xl border border-app-border bg-surface p-6">
            <p class="mb-1 text-xs uppercase text-gray-500">Pending Payouts</p>
            <p class="font-heading text-2xl font-bold text-yellow-400">₱{{ number_format($pendingTotal, 2) }}</p>
        </div>

        <div class="rounded-2xl border border-app-border bg-surface p-6">
            <p class="mb-1 text-xs uppercase text-gray-500">Paid Out</p>
            <p class="font-heading text-2xl font-bold text-brand">₱{{ number_format($paidTotal, 2) }}</p>
        </div>

        <div class="rounded-2xl border border-app-border bg-surface p-6">
            <p class="mb-1 text-xs uppercase text-gray-500">Platform Commission</p>
            <p class="font-heading text-2xl font-bold text-purple-400">₱{{ number_format($commissions, 2) }}</p>
        </div>
    </div>

    <div class="overflow-hidden rounded-2xl border border-app-border bg-surface">
        <table class="w-full text-sm">
            <thead class="bg-surface-light text-xs uppercase text-gray-400">
                <tr>
                    <th class="px-6 py-4 text-left">Trainer</th>
                    <th class="px-6 py-4 text-center">Gross</th>
                    <th class="px-6 py-4 text-center">Commission</th>
                    <th class="px-6 py-4 text-center">Payout</th>
                    <th class="px-6 py-4 text-center">Status</th>
                    <th class="px-6 py-4 text-right">Actions</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-app-border">
                @forelse($transactions as $txn)
                    <tr class="hover:bg-surface-light/50">
                        <td class="px-6 py-4">{{ $txn->trainer->full_name }}</td>
                        <td class="px-6 py-4 text-center">₱{{ number_format($txn->gross_amount, 2) }}</td>
                        <td class="px-6 py-4 text-center text-red-400">-₱{{ number_format($txn->commission_amount, 2) }}
                        </td>
                        <td class="px-6 py-4 text-center text-brand">₱{{ number_format($txn->trainer_payout, 2) }}</td>
                        <td class="px-6 py-4 text-center">
                            <span
                                class="rounded-full px-2 py-1 text-xs {{ $txn->payout_status === 'paid' ? 'bg-brand/20 text-brand' : 'bg-yellow-500/20 text-yellow-400' }}">{{ ucfirst($txn->payout_status) }}</span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            @if ($txn->payout_status === 'pending')
                                <form method="POST" action="{{ route('admin.payouts.process', $txn) }}">
                                    @csrf
                                    <button
                                        class="rounded-lg bg-brand px-3 py-1 text-xs text-white hover:bg-brand-light">Process
                                        Payout</button>
                                </form>
                            @else
                                <span class="text-xs text-gray-500">Paid {{ $txn->paid_at?->format('M d') }}</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-500">No transactions yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">{{ $transactions->links() }}</div>
@endsection
