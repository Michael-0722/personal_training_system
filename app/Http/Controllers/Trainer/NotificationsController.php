<?php

namespace App\Http\Controllers\Trainer;

use App\Http\Controllers\Controller;
use App\Models\TrainifyNotification;
use Illuminate\Support\Facades\Auth;

class NotificationsController extends Controller
{
    public function index()
    {
        $notifications = TrainifyNotification::where('user_id', Auth::id())
            ->latest()
            ->paginate(20);

        return view('trainer.notifications.index', compact('notifications'));
    }

    public function markRead(TrainifyNotification $n)
    {
        abort_unless($n->user_id === Auth::id(), 403);

        $n->update(['is_read' => true]);

        return back()->with('success', 'Notification marked as read.');
    }
}
