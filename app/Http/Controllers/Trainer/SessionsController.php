<?php

namespace App\Http\Controllers\Trainer;

use App\Http\Controllers\Controller;
use App\Models\SessionType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SessionsController extends Controller
{
    public function index()
    {
        $sessions = Auth::user()->trainerProfile
            ->sessionTypes()
            ->latest()
            ->get();

        return view('trainer.sessions.index', compact('sessions'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'format' => 'required|in:1-on-1,Group',
            'delivery_mode' => 'required|in:Online,In-Person',
            'duration_minutes' => 'required|integer|min:15|max:480',
            'price' => 'required|numeric|min:0',
            'max_participants' => 'nullable|integer|min:2',
        ]);
        Auth::user()->trainerProfile->sessionTypes()->create($data);

        return redirect()->route('trainer.sessions.index')
            ->with('success', 'Session created successfully.');
    }

    public function update(Request $request, SessionType $session)
    {
        $this->authorize('update', $session);
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'is_active' => 'boolean',
        ]);
        $session->update($data);

        return back()->with('success', 'Session updated.');
    }

    public function destroy(SessionType $session)
    {
        $this->authorize('delete', $session);
        $session->update(['is_active' => false]);

        return back()->with('success', 'Session deactivated.');
    }
}
