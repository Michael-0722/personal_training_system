<?php

namespace App\Http\Controllers\Trainer;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $trainer = $user->trainerProfile;
        $upcomingBookings = Booking::where('trainer_id', $user->id)
            ->where('status', 'confirmed')
            ->where('booking_date', '>=', today())
            ->with(['client', 'sessionType'])
            ->orderBy('booking_date')
            ->take(5)
            ->get();
        $pendingRequests = Booking::where('trainer_id', $user->id)
            ->where('status', 'pending')
            ->count();
        $thisMonthEarnings = Transaction::where('trainer_id', $user->id)
            ->whereMonth('created_at', now()->month)
            ->sum('trainer_payout');
        $totalSessions = $trainer->sessions_completed;
        $avgRating = $trainer->rating;

        return view('trainer.dashboard', compact(
            'upcomingBookings', 'pendingRequests',
            'thisMonthEarnings', 'totalSessions', 'avgRating'
        ));
    }
}
