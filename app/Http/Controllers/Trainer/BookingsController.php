<?php

namespace App\Http\Controllers\Trainer;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\TrainifyNotification;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingsController extends Controller
{
    public function index(Request $request)
    {
        $query = Booking::where('trainer_id', Auth::id())
            ->with(['client', 'sessionType']);
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        $bookings = $query->latest()->paginate(15);

        return view('trainer.bookings.index', compact('bookings'));
    }

    public function confirm(Booking $booking)
    {
        abort_unless($booking->trainer_id === Auth::id(), 403);
        $booking->update(['status' => 'confirmed']);
        // Create transaction record
        $commissionRate = config('trainify.commission_rate', 0.20);
        $commission = $booking->amount * $commissionRate;
        Transaction::create([
            'booking_id' => $booking->id,
            'trainer_id' => Auth::id(),
            'gross_amount' => $booking->amount,
            'commission_rate' => $commissionRate,
            'commission_amount' => $commission,
            'trainer_payout' => $booking->amount - $commission,
        ]);
        TrainifyNotification::create([
            'user_id' => $booking->client_id,
            'type' => 'booking_confirmed',
            'title' => 'Booking Confirmed',
            'message' => "Your booking for {$booking->sessionType->title} on ".
            $booking->booking_date->format('M d, Y').' has been confirmed.',
        ]);

        return back()->with('success', 'Booking confirmed.');
    }

    public function reject(Request $request, Booking $booking)
    {
        abort_unless($booking->trainer_id === Auth::id(), 403);
        $booking->update(['status' => 'cancelled']);
        TrainifyNotification::create([
            'user_id' => $booking->client_id,
            'type' => 'booking_rejected',
            'title' => 'Booking Declined',
            'message' => "Your booking request for {$booking->sessionType->title} was declined.",
        ]);

        return back()->with('success', 'Booking declined.');
    }
}
