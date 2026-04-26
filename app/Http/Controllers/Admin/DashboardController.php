<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\TrainerProfile;
use App\Models\Transaction;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $totalTrainers = User::where('role', 'trainer')->count();
        $totalClients = User::where('role', 'client')->count();
        $pendingApprovals = TrainerProfile::where('approval_status', 'pending')->count();
        $totalRevenue = Transaction::sum('commission_amount');
        $trainerPayout = Transaction::sum('trainer_payout');
        // Monthly revenue for chart (last 6 months)
        $monthlyData = Transaction::selectRaw(
            "DATE_FORMAT(created_at, '%Y-%m') as month_key,
DATE_FORMAT(created_at, '%b') as month,
SUM(gross_amount) as gross,
SUM(trainer_payout) as trainer_payout,
SUM(commission_amount) as commission"
        )
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupByRaw("DATE_FORMAT(created_at, '%Y-%m'), DATE_FORMAT(created_at, '%b')")
            ->orderByRaw("DATE_FORMAT(created_at, '%Y-%m')")
            ->get();
        $recentBookings = Booking::with(['client', 'trainer', 'sessionType'])
            ->latest()
            ->take(10)
            ->get();

        return view('admin.dashboard', compact(
            'totalTrainers', 'totalClients',
            'pendingApprovals', 'totalRevenue',
            'trainerPayout', 'monthlyData', 'recentBookings'
        ));
    }
}
