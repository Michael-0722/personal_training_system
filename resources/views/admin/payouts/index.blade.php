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
            <p class="font-heading text-2xl font-bold text-yellow-400 text-right">₱{{ number_format($pendingTotal, 2) }}</p>
        </div>

        <div class="rounded-2xl border border-app-border bg-surface p-6">
            <p class="mb-1 text-xs uppercase text-gray-500">Paid Out</p>
            <p class="font-heading text-2xl font-bold text-brand text-right">₱{{ number_format($paidTotal, 2) }}</p>
        </div>

        <div class="rounded-2xl border border-app-border bg-surface p-6">
            <p class="mb-1 text-xs uppercase text-gray-500">Platform Commission</p>
            <p class="font-heading text-2xl font-bold text-purple-400 text-right">₱{{ number_format($commissions, 2) }}</p>
        </div>
    </div>

    <div id="admin-payouts" class="overflow-hidden rounded-2xl border border-app-border bg-surface">
        <form method="POST" action="{{ route('admin.payouts.processBulk') }}" id="bulk-payout-form">
            @csrf
            <div class="flex items-center justify-between border-b border-app-border bg-surface-light px-6 py-4">
                <label class="inline-flex items-center gap-2 text-xs uppercase text-gray-400">
                    <input id="select-all-payouts" type="checkbox"
                        class="h-4 w-4 rounded border-app-border bg-surface-dark text-brand focus:ring-brand">
                    Select all on this page
                </label>
                <button id="process-selected"
                    class="rounded-lg bg-brand px-3 py-1 text-xs text-white hover:bg-brand-light disabled:cursor-not-allowed disabled:opacity-60"
                    type="submit" disabled>
                    Process Selected
                </button>
            </div>
            <div class="flex items-center justify-between border-b border-app-border bg-surface px-6 py-3 text-xs text-gray-400"
                data-pending-count="{{ $pendingCount }}">
                <span id="selection-hint">Current page selected.</span>
                <div class="flex items-center gap-3">
                    @if ($pendingCount > 0)
                        <button type="button" id="select-all-pages" class="text-brand hover:text-brand-light">
                            Select all {{ $pendingCount }} pending payouts
                        </button>
                    @endif
                    <button type="button" id="clear-selection" class="hidden text-gray-300 hover:text-white">
                        Clear selection
                    </button>
                </div>
            </div>
            <input type="hidden" name="select_all_pending" id="select-all-pending-input" value="0">

            <table class="w-full text-sm">
                <thead class="bg-surface-light text-xs uppercase text-gray-400">
                    <tr>
                        <th class="px-6 py-4 text-left">Select</th>
                        <th class="px-6 py-4 text-left">Trainer</th>
                        <th class="px-6 py-4 text-right">Gross</th>
                        <th class="px-6 py-4 text-right">Commission</th>
                        <th class="px-6 py-4 text-right">Payout</th>
                        <th class="px-6 py-4 text-center">Status</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-app-border">
                    @forelse($transactions as $txn)
                        <tr class="hover:bg-surface-light/50">
                            <td class="px-6 py-4">
                                <input type="checkbox" name="transaction_ids[]" value="{{ $txn->id }}"
                                    class="payout-checkbox h-4 w-4 rounded border-app-border bg-surface-dark text-brand focus:ring-brand"
                                    @disabled($txn->payout_status !== 'pending')>
                            </td>
                            <td class="px-6 py-4">{{ $txn->trainer->full_name }}</td>
                            <td class="px-6 py-4 text-right">₱{{ number_format($txn->gross_amount, 2) }}</td>
                            <td class="px-6 py-4 text-right text-red-400">-₱{{ number_format($txn->commission_amount, 2) }}
                            </td>
                            <td class="px-6 py-4 text-right text-brand">₱{{ number_format($txn->trainer_payout, 2) }}</td>
                            <td class="px-6 py-4 text-center">
                                <span
                                    class="rounded-full px-2 py-1 text-xs {{ $txn->payout_status === 'paid' ? 'bg-brand/20 text-brand' : 'bg-yellow-500/20 text-yellow-400' }}">{{ ucfirst($txn->payout_status) }}</span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                @if ($txn->payout_status === 'pending')
                                    <button type="submit" formaction="{{ route('admin.payouts.process', $txn) }}"
                                        class="rounded-lg bg-brand px-3 py-1 text-xs text-white hover:bg-brand-light">
                                        Process Payout
                                    </button>
                                @else
                                    <span class="text-xs text-gray-500">Paid {{ $txn->paid_at?->format('M d') }}</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-gray-500">No transactions yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </form>
    </div>

    <div class="mt-6">{{ $transactions->fragment('admin-payouts')->links() }}</div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const selectAll = document.getElementById('select-all-payouts');
                const checkboxes = Array.from(document.querySelectorAll('.payout-checkbox'));
                const processBtn = document.getElementById('process-selected');
                const selectAllPages = document.getElementById('select-all-pages');
                const clearSelection = document.getElementById('clear-selection');
                const selectAllInput = document.getElementById('select-all-pending-input');
                const selectionHint = document.getElementById('selection-hint');
                const pendingCount = Number(document.querySelector('[data-pending-count]')?.dataset.pendingCount || 0);

                if (!selectAll || !checkboxes.length || !processBtn || !selectAllInput || !selectionHint) {
                    return;
                }

                const enabledBoxes = () => checkboxes.filter((box) => !box.disabled);

                const setAllPagesSelected = (enabled) => {
                    selectAllInput.value = enabled ? '1' : '0';
                    if (selectAllPages) {
                        selectAllPages.classList.toggle('hidden', enabled);
                    }
                    if (clearSelection) {
                        clearSelection.classList.toggle('hidden', !enabled);
                    }
                    selectionHint.textContent = enabled && pendingCount
                        ? `All ${pendingCount} pending payouts selected.`
                        : 'Current page selected.';
                };

                const updateButton = () => {
                    const anyChecked = checkboxes.some((box) => box.checked);
                    processBtn.disabled = !anyChecked;
                };

                selectAll.addEventListener('change', () => {
                    setAllPagesSelected(false);
                    enabledBoxes().forEach((box) => {
                        box.checked = selectAll.checked;
                    });
                    updateButton();
                });

                checkboxes.forEach((box) => {
                    box.addEventListener('change', () => {
                        setAllPagesSelected(false);
                        const enabled = enabledBoxes();
                        const allChecked = enabled.length > 0 && enabled.every((item) => item.checked);
                        selectAll.checked = allChecked;
                        updateButton();
                    });
                });

                if (selectAllPages) {
                    selectAllPages.addEventListener('click', () => {
                        enabledBoxes().forEach((box) => {
                            box.checked = true;
                        });
                        selectAll.checked = true;
                        setAllPagesSelected(true);
                        updateButton();
                    });
                }

                if (clearSelection) {
                    clearSelection.addEventListener('click', () => {
                        enabledBoxes().forEach((box) => {
                            box.checked = false;
                        });
                        selectAll.checked = false;
                        setAllPagesSelected(false);
                        updateButton();
                    });
                }
            });
        </script>
    @endpush
@endsection
