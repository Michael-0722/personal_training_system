<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\TrainerProfile;
use App\Models\User;
use Illuminate\Http\Request;

class BrowseController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 'trainer')
            ->where('account_status', 'active')
            ->whereHas('trainerProfile', fn ($q) => $q->where('approval_status', 'approved')
            )
            ->with('trainerProfile');
        // Filter by specialization
        if ($request->filled('spec')) {
            $query->whereHas('trainerProfile', fn ($q) => $q->whereJsonContains('specializations', $request->spec)
            );
        }
        // Filter by delivery mode
        if ($request->filled('mode')) {
            $query->whereHas('trainerProfile.sessionTypes', fn ($q) => $q->where('delivery_mode', $request->mode)->where('is_active', true)
            );
        }
        // Sort
        $sort = $request->get('sort', 'rating');
        if ($sort === 'rating') {
            $query->join('trainer_profiles', 'users.id', '=', 'trainer_profiles.user_id')
                ->orderByDesc('trainer_profiles.rating')
                ->select('users.*');
        } elseif ($sort === 'price_asc') {
            $query->join('trainer_profiles', 'users.id', '=', 'trainer_profiles.user_id')
                ->orderBy('trainer_profiles.hourly_rate')
                ->select('users.*');
        }
        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(fn ($q) => $q->where('full_name', 'like', "%{$search}%")
                ->orWhereHas('trainerProfile', fn ($q2) => $q2->whereJsonContains('tags', $search)
                )
            );
        }
        $trainers = $query->paginate(12)->withQueryString();
        $specializations = TrainerProfile::pluck('specializations')
            ->flatten()->unique()->values();

        return view('client.browse', compact('trainers', 'specializations'));
    }
}
