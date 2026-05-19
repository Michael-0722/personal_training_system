<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TrainifyNotification;
use App\Models\Transaction;
use Illuminate\Http\Request;

class PayoutsController extends Controller
{
    public function index()
    {
        $transactions = Transaction::with(['trainer', 'booking.sessionType'])
            ->latest()
            ->paginate(20);
        $pendingTotal = Transaction::where('payout_status', 'pending')->sum('trainer_payout');
        $pendingCount = Transaction::where('payout_status', 'pending')->count();
        $paidTotal = Transaction::where('payout_status', 'paid')->sum('trainer_payout');
        $commissions = Transaction::sum('commission_amount');

        return view('admin.payouts.index', compact(
            'transactions', 'pendingTotal', 'pendingCount', 'paidTotal', 'commissions'
        ));
    }

    public function process(Transaction $transaction)
    {
        $transaction->update([
            'payout_status' => 'paid',
            'paid_at' => now(),
        ]);
        TrainifyNotification::create([
            'user_id' => $transaction->trainer_id,
            'type' => 'payout_processed',
            'title' => 'Payout Processed',
            'message' => "Your payout of ₱{$transaction->trainer_payout} has been processed.",
        ]);

        return back()->with('success', 'Payout processed successfully.');
    }

    public function processBulk(Request $request)
    {
        $data = $request->validate([
            'select_all_pending' => ['nullable', 'boolean'],
            'transaction_ids' => ['array'],
            'transaction_ids.*' => ['integer', 'exists:transactions,id'],
        ]);

        $selectAll = $request->boolean('select_all_pending');
        if (! $selectAll && empty($data['transaction_ids'])) {
            return back()->with('error', 'Select at least one pending payout.');
        }

        $now = now();
        $query = Transaction::where('payout_status', 'pending');
        if (! $selectAll) {
            $query->whereIn('id', $data['transaction_ids']);
        }
        $transactions = $query->get();

        if ($transactions->isEmpty()) {
            return back()->with('error', 'No pending payouts were selected.');
        }

        foreach ($transactions as $transaction) {
            $transaction->update([
                'payout_status' => 'paid',
                'paid_at' => $now,
            ]);
            TrainifyNotification::create([
                'user_id' => $transaction->trainer_id,
                'type' => 'payout_processed',
                'title' => 'Payout Processed',
                'message' => "Your payout of ₱{$transaction->trainer_payout} has been processed.",
            ]);
        }

        return back()->with('success', "Processed {$transactions->count()} payout(s) successfully.");
    }
}
