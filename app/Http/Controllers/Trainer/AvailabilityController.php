<?php

namespace App\Http\Controllers\Trainer;

use App\Http\Controllers\Controller;
use App\Models\Availability;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AvailabilityController extends Controller
{
    public function index()
    {
        $slots = Auth::user()->trainerProfile
            ->availabilities()->orderBy('day_of_week')->orderBy('start_time')->get();

        return view('trainer.availability.index', compact('slots'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'day_of_week' => 'required|integer|between:0,6',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ]);
        Auth::user()->trainerProfile->availabilities()->create($data);

        return back()->with('success', 'Availability slot added.');
    }

    public function destroy(Availability $slot)
    {
        $slot->delete();

        return back()->with('success', 'Availability slot removed.');
    }
}
