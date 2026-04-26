<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class TrainerApproved
{
    public function handle(Request $request, Closure $next)
    {
        if (! Auth::check()) {
            return redirect()->route('login');
        }

        $trainerProfile = Auth::user()->trainerProfile;

        if (! $trainerProfile) {
            return redirect()->route('trainer.pending');
        }

        if ($trainerProfile->approval_status === 'rejected') {
            return redirect()->route('trainer.rejected');
        }

        if ($trainerProfile->approval_status !== 'approved') {
            return redirect()->route('trainer.pending');
        }

        return $next($request);
    }
}
