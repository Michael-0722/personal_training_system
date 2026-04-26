<?php

namespace App\Http\Controllers\Trainer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        return view('trainer.settings', [
            'user' => $user,
            'profile' => $user->trainerProfile,
        ]);
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        $profile = $user->trainerProfile;

        $data = $request->validate([
            'full_name' => 'required|string|max:255',
            'bio' => 'nullable|string|max:1500',
            'tags' => 'nullable|string|max:500',
            'password' => 'nullable|confirmed|min:8',
            'avatar' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('avatar')) {
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }

            $user->avatar = $request->file('avatar')->store('avatars', 'public');
        }

        $user->full_name = $data['full_name'];

        if (! empty($data['password'])) {
            $user->password = Hash::make($data['password']);
        }

        $user->save();

        $tags = collect(explode(',', (string) ($data['tags'] ?? '')))
            ->map(fn ($tag) => trim($tag))
            ->filter()
            ->values()
            ->all();

        if ($tags === []) {
            $tags = $profile->tags ?? [];
        }

        $profile->update([
            'bio' => $data['bio'] ?? $profile->bio,
            'tags' => $tags,
        ]);

        return back()->with('success', 'Settings updated.');
    }
}
