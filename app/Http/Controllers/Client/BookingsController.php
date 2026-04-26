<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Review;
use App\Models\TrainifyNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class BookingsController extends Controller
{
    public function index(Request $request)
    {
        $query = Booking::where('client_id', Auth::id())->with(['trainer', 'sessionType', 'review']);
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        $bookings = $query->latest()->paginate(15);

        return view('client.bookings.index', compact('bookings'));
    }

    public function cancel(Booking $booking)
    {
        abort_unless($booking->client_id === Auth::id(), 403);
        abort_unless($booking->isPending() || $booking->isConfirmed(), 403);

        $refundAmount = 0.0;

        DB::transaction(function () use ($booking, &$refundAmount) {
            $transaction = $booking->transaction()->lockForUpdate()->first();

            if ($transaction && $transaction->payout_status === 'paid') {
                throw ValidationException::withMessages([
                    'booking' => 'This booking payout was already processed. Please contact support for manual refund.',
                ]);
            }

            $refundAmount = (float) ($transaction?->gross_amount ?? $booking->amount);

            if ($transaction) {
                // Keep the transaction as an audit trail but zero out financial values after refund.
                $transaction->update([
                    'gross_amount' => 0,
                    'commission_amount' => 0,
                    'trainer_payout' => 0,
                ]);
            }

            $booking->update([
                'status' => 'cancelled',
                'cancellation_reason' => 'Cancelled by client. Refunded amount: ₱'.number_format($refundAmount, 2),
            ]);

            TrainifyNotification::create([
                'user_id' => $booking->trainer_id,
                'type' => 'booking_cancelled',
                'title' => 'Booking Cancelled',
                'message' => Auth::user()->full_name." cancelled their booking for {$booking->sessionType->title}.",
            ]);

            TrainifyNotification::create([
                'user_id' => $booking->client_id,
                'type' => 'booking_refunded',
                'title' => 'Refund Processed',
                'message' => 'Your booking was cancelled and refunded ₱'.number_format($refundAmount, 2).'.',
            ]);
        });

        return back()->with('success', 'Booking cancelled. Refund processed: ₱'.number_format($refundAmount, 2).'.');
    }

    public function review(Request $request, Booking $booking)
    {
        abort_unless($booking->client_id === Auth::id(), 403);
        abort_unless($booking->isCompleted(), 403);
        abort_if($booking->review()->exists(), 403);
        $data = $request->validate(['rating' => 'required|integer|between:1,5', 'comment' => 'nullable|string|max:1000']);
        Review::create([
            'booking_id' => $booking->id, 'client_id' => Auth::id(),
            'trainer_id' => $booking->trainer_id, 'rating' => $data['rating'], 'comment' => $data['comment'],
        ]);
        $trainer = $booking->trainer->trainerProfile;
        $avgRating = Review::where('trainer_id', $booking->trainer_id)->avg('rating');
        $reviewCount = Review::where('trainer_id', $booking->trainer_id)->count();
        $trainer->update(['rating' => round($avgRating, 2), 'review_count' => $reviewCount]);

        return back()->with('success', 'Review submitted!');
    }
}
