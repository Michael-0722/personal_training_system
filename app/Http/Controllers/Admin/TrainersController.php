<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TrainifyNotification;
use App\Models\User;
use Illuminate\Http\Request;

class TrainersController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 'trainer')->with('trainerProfile');

        if (! $request->filled('status')) {
            $query->whereHas('trainerProfile', fn ($q) => $q->where('approval_status', '!=', 'rejected'));
        }

        if ($request->filled('status')) {
            $query->whereHas('trainerProfile', fn ($q) => $q->where('approval_status', $request->status)
            );
        }
        if ($request->filled('search')) {
            $query->where(fn ($q) => $q->where('full_name', 'like', "%{$request->search}%")
                ->orWhere('username', 'like', "%{$request->search}%")
            );
        }
        $trainers = $query->latest()->paginate(15);

        return view('admin.trainers.index', compact('trainers'));
    }

    public function approve(User $trainer)
    {
        $trainer->trainerProfile->update(['approval_status' => 'approved']);
        TrainifyNotification::create([
            'user_id' => $trainer->id,
            'type' => 'account_approved',
            'title' => 'Application Approved',
            'message' => 'Congratulations! Your trainer application has been approved. You can now create sessions and accept bookings.',
        ]);

        return back()->with('success', "{$trainer->full_name} has been approved.");
    }

    public function reject(Request $request, User $trainer)
    {
        $request->validate(['reason' => 'nullable|string|max:500']);
        $trainer->trainerProfile->update([
            'approval_status' => 'rejected',
            'rejection_reason' => $request->reason,
        ]);
        TrainifyNotification::create([
            'user_id' => $trainer->id,
            'type' => 'account_rejected',
            'title' => 'Application Rejected',
            'message' => 'Your trainer application was not approved. Reason: '.($request->reason ?? 'No reason provided.'),
        ]);

        return back()->with('success', "{$trainer->full_name} has been rejected.");
    }

    public function suspend(User $trainer)
    {
        $newStatus = $trainer->account_status === 'active' ? 'suspended' : 'active';
        $trainer->update(['account_status' => $newStatus]);

        return back()->with('success', "Account status changed to {$newStatus}.");
    }
}
