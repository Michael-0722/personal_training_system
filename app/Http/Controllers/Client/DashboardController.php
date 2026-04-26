<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $upcomingBookings = Booking::where('client_id', $user->id)
            ->whereIn('status', ['confirmed', 'pending'])
            ->where('booking_date', '>=', today())
            ->with(['trainer', 'sessionType'])->orderBy('booking_date')->take(5)->get();
        $completedCount = Booking::where('client_id', $user->id)->where('status', 'completed')->count();
        $totalSpent = $user->clientProfile->total_spent ?? 0;
        $activeBookings = Booking::where('client_id', $user->id)->whereIn('status', ['confirmed', 'pending'])->count();

        return view('client.dashboard', compact('upcomingBookings', 'completedCount', 'totalSpent', 'activeBookings'));
    }
}
