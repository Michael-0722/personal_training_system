<?php

namespace App\Http\Controllers\Trainer;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Support\Facades\Auth;

class CalendarController extends Controller
{
    public function index()
    {
        $bookings = Booking::where('trainer_id', Auth::id())
            ->whereIn('status', ['confirmed', 'completed'])
            ->with(['client', 'sessionType'])
            ->orderBy('booking_date')
            ->get()
            ->groupBy(fn ($b) => $b->booking_date->format('Y-m-d'));

        return view('trainer.calendar.index', compact('bookings'));
    }
}
