<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\SessionType;
use App\Models\TrainifyNotification;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    public function show(User $trainer, SessionType $session)
    {
        abort_unless($session->trainerProfile->user_id === $trainer->id, 404);
        abort_unless($session->is_active, 404);
        $availabilities = $trainer->trainerProfile->availabilities()->get();

        return view('client.book', compact('trainer', 'session', 'availabilities'));
    }

    public function store(Request $request, User $trainer, SessionType $session)
    {
        abort_unless($session->trainerProfile->user_id === $trainer->id, 404);
        abort_unless($session->is_active, 404);

        $data = $request->validate([
            'booking_date' => 'required|date|after:today',
            'booking_time' => 'required|date_format:H:i',
        ]);

        $bookingDate = Carbon::parse($data['booking_date']);
        $requestedStart = Carbon::createFromFormat('Y-m-d H:i', $bookingDate->format('Y-m-d').' '.$data['booking_time']);
        $requestedEnd = $requestedStart->copy()->addMinutes((int) $session->duration_minutes);

        $booking = null;
        $validationError = null;

        DB::transaction(function () use (
            &$booking,
            &$validationError,
            $trainer,
            $session,
            $bookingDate,
            $requestedStart,
            $requestedEnd,
            $data
        ) {
            // Lock trainer row so booking attempts for this trainer are serialized.
            User::query()->whereKey($trainer->id)->lockForUpdate()->first();
            SessionType::query()->whereKey($session->id)->lockForUpdate()->first();

            $isInsideAvailability = $trainer->trainerProfile
                ->availabilities()
                ->where('day_of_week', $bookingDate->dayOfWeek)
                ->get()
                ->contains(function ($slot) use ($requestedStart, $requestedEnd) {
                    $slotStart = Carbon::createFromFormat('H:i:s', $slot->start_time);
                    $slotEnd = Carbon::createFromFormat('H:i:s', $slot->end_time);
                    $reqStartTime = Carbon::createFromFormat('H:i:s', $requestedStart->format('H:i:s'));
                    $reqEndTime = Carbon::createFromFormat('H:i:s', $requestedEnd->format('H:i:s'));

                    return $reqStartTime->greaterThanOrEqualTo($slotStart)
                        && $reqEndTime->lessThanOrEqualTo($slotEnd);
                });

            if (! $isInsideAvailability) {
                $validationError = 'Selected time is outside this trainer\'s availability.';

                return;
            }

            $sameDayBookings = Booking::query()
                ->where('trainer_id', $trainer->id)
                ->where('booking_date', $data['booking_date'])
                ->whereIn('status', ['pending', 'confirmed'])
                ->lockForUpdate()
                ->get(['session_type_id', 'booking_time', 'duration_minutes']);

            $isGroupSession = $session->format === 'Group';
            $maxParticipants = $session->max_participants;

            $hasOverlap = $sameDayBookings->contains(function (Booking $existing) use ($bookingDate, $requestedStart, $requestedEnd, $session, $isGroupSession) {
                $existingStart = Carbon::createFromFormat(
                    'Y-m-d H:i:s',
                    $bookingDate->format('Y-m-d').' '.$existing->booking_time
                );
                $existingEnd = $existingStart->copy()->addMinutes((int) $existing->duration_minutes);

                $hasTimeOverlap = $requestedStart->lt($existingEnd) && $requestedEnd->gt($existingStart);

                if (! $hasTimeOverlap) {
                    return false;
                }

                // Group sessions can share the exact same slot for the same session type.
                if ($isGroupSession && (int) $existing->session_type_id === (int) $session->id) {
                    $isExactSameSlot = $requestedStart->equalTo($existingStart) && $requestedEnd->equalTo($existingEnd);

                    return ! $isExactSameSlot;
                }

                return true;
            });

            if ($hasOverlap) {
                $validationError = 'This time overlaps with another booking. Please choose a different slot.';

                return;
            }

            if ($isGroupSession) {
                $effectiveMaxParticipants = max(1, (int) ($maxParticipants ?? 1));

                $bookingsInRequestedGroupSlot = $sameDayBookings->filter(function (Booking $existing) use ($bookingDate, $requestedStart, $requestedEnd, $session) {
                    if ((int) $existing->session_type_id !== (int) $session->id) {
                        return false;
                    }

                    $existingStart = Carbon::createFromFormat(
                        'Y-m-d H:i:s',
                        $bookingDate->format('Y-m-d').' '.$existing->booking_time
                    );
                    $existingEnd = $existingStart->copy()->addMinutes((int) $existing->duration_minutes);

                    return $requestedStart->equalTo($existingStart) && $requestedEnd->equalTo($existingEnd);
                });

                if ($bookingsInRequestedGroupSlot->count() >= $effectiveMaxParticipants) {
                    $validationError = 'This group slot is already full. Please choose a different time.';

                    return;
                }
            }

            $booking = Booking::create([
                'client_id' => Auth::id(),
                'trainer_id' => $trainer->id,
                'session_type_id' => $session->id,
                'booking_date' => $data['booking_date'],
                'booking_time' => $data['booking_time'],
                'duration_minutes' => $session->duration_minutes,
                'amount' => $session->price,
                'status' => 'pending',
            ]);
        });

        if ($validationError !== null) {
            return back()->withErrors(['booking_time' => $validationError])->withInput();
        }

        // Notify trainer
        TrainifyNotification::create([
            'user_id' => $trainer->id,
            'type' => 'new_booking_request',
            'title' => 'New Booking Request',
            'message' => Auth::user()->full_name." has requested a booking for {$session->title}.",
        ]);

        return redirect()->route('client.bookings.index')
            ->with('success', 'Booking request sent! Awaiting trainer confirmation.');
    }
}
