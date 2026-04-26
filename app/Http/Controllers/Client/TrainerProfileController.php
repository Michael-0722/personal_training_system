<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\User;

class TrainerProfileController extends Controller
{
    public function show(User $trainer)
    {
        abort_unless($trainer->isTrainer() && $trainer->trainerProfile->isApproved(), 404);
        $profile = $trainer->trainerProfile;
        $sessions = $profile->sessionTypes()->where('is_active', true)->get();
        $reviews = Review::where('trainer_id', $trainer->id)->with('client')->latest()->take(10)->get();

        return view('client.trainer_profile', compact('trainer', 'profile', 'sessions', 'reviews'));
    }
}
