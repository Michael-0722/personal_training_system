<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TrainifyNotification;
use App\Models\Transaction;

class PayoutsController extends Controller
{
    public function index()
    {
        $transactions = Transaction::with(['trainer', 'booking.sessionType'])
            ->latest()
            ->paginate(20);
        $pendingTotal = Transaction::where('payout_status', 'pending')->sum('trainer_payout');
        $paidTotal = Transaction::where('payout_status', 'paid')->sum('trainer_payout');
        $commissions = Transaction::sum('commission_amount');

        return view('admin.payouts.index', compact(
            'transactions', 'pendingTotal', 'paidTotal', 'commissions'
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
}
