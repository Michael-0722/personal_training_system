<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Review;
use App\Models\TrainerProfile;
use App\Models\User;

class LandingController extends Controller
{
    public function index()
    {
        $approvedActiveTrainers = User::query()
            ->where('role', 'trainer')
            ->where('account_status', 'active')
            ->whereHas('trainerProfile', fn ($q) => $q->where('approval_status', 'approved'));

        $activeTrainerCount = (clone $approvedActiveTrainers)->count();
        $totalClientCount = User::query()->where('role', 'client')->where('account_status', 'active')->count();
        $totalBookingCount = Booking::query()->count();

        $averageRating = (float) Review::query()->avg('rating');
        $satisfactionPercent = $averageRating > 0
            ? max(0, min(100, (int) round(($averageRating / 5) * 100)))
            : 0;

        $categoryBuckets = TrainerProfile::query()
            ->where('approval_status', 'approved')
            ->whereHas('user', fn ($q) => $q->where('account_status', 'active'))
            ->pluck('specializations')
            ->flatten()
            ->filter()
            ->countBy();

        $tones = ['tone-green', 'tone-blue', 'tone-amber', 'tone-violet'];
        $categories = $categoryBuckets
            ->sortDesc()
            ->take(4)
            ->map(function ($count, $name) use ($tones) {
                static $toneIndex = 0;
                $tone = $tones[$toneIndex % count($tones)];
                $toneIndex++;

                return [
                    'name' => (string) $name,
                    'count' => (int) $count,
                    'tone' => $tone,
                ];
            })
            ->values();

        $featuredTrainers = $approvedActiveTrainers
            ->with('trainerProfile')
            ->orderByDesc(
                TrainerProfile::query()
                    ->select('rating')
                    ->whereColumn('trainer_profiles.user_id', 'users.id')
                    ->limit(1)
            )
            ->take(3)
            ->get();

        $testimonials = Review::query()
            ->with(['client', 'trainer'])
            ->latest()
            ->take(2)
            ->get();

        return view('welcome', [
            'heroTrustedUsers' => $activeTrainerCount + $totalClientCount,
            'stats' => [
                ['value' => number_format($activeTrainerCount), 'label' => 'Active Trainers'],
                ['value' => number_format($totalBookingCount), 'label' => 'Total Bookings'],
                ['value' => $satisfactionPercent > 0 ? $satisfactionPercent.'%' : 'N/A', 'label' => 'Satisfaction'],
                ['value' => number_format($categoryBuckets->keys()->count()), 'label' => 'Categories'],
            ],
            'categories' => $categories,
            'featuredTrainers' => $featuredTrainers,
            'testimonials' => $testimonials,
            'ctaClientCount' => $totalClientCount,
            'ctaTrainerCount' => $activeTrainerCount,
        ]);
    }
}
