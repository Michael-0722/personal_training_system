<?php

namespace App\Http\Controllers\Trainer;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;

class EarningsController extends Controller
{
    public function index()
    {
        $transactions = Transaction::where('trainer_id', Auth::id())
            ->with('booking.sessionType')->latest()->paginate(20);
        $totalEarnings = Transaction::where('trainer_id', Auth::id())->sum('trainer_payout');
        $pendingPayouts = Transaction::where('trainer_id', Auth::id())->where('payout_status', 'pending')->sum('trainer_payout');
        $paidPayouts = Transaction::where('trainer_id', Auth::id())->where('payout_status', 'paid')->sum('trainer_payout');
        $totalCommission = Transaction::where('trainer_id', Auth::id())->sum('commission_amount');

        return view('trainer.earnings.index', compact(
            'transactions', 'totalEarnings', 'pendingPayouts', 'paidPayouts', 'totalCommission'
        ));
    }
}
