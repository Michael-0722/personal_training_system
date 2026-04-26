<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\ClientProfile;
use App\Models\TrainerProfile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;


class AuthController extends Controller
{
    public function showLogin()
    {
        return view ('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);
        if (! Auth::attempt(['username' => $credentials['username'], 'password' => $credentials['password']])) {
            return back()->withErrors(['username' => 'Invalid credentials.'])->onlyInput('username');
        }
        $request->session()->regenerate();

        return $this->redirectByRole(Auth::user()->role);
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function showTrainerRegister()
    {
        return view('auth.register-trainer-info');
    }

    public function storeTrainerRegisterInfo(Request $request)
    {
        $data = $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:60', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $request->session()->put('trainer_register_info', [
            'full_name' => $data['full_name'],
            'username' => $data['username'],
            'password' => $data['password'],
        ]);

        return redirect()->route('register.trainer.profile');
    }

    public function showTrainerRegisterProfile(Request $request)
    {
        if (! $request->session()->has('trainer_register_info')) {
            return redirect()->route('register.trainer');
        }

        return view('auth.register-trainer-profile');
    }

    public function completeTrainerRegister(Request $request)
    {
        $base = $request->session()->get('trainer_register_info');

        if (! is_array($base)) {
            return redirect()->route('register.trainer');
        }

        $data = $request->validate([
            'bio' => ['nullable', 'string', 'max:1500'],
            'specializations' => ['nullable', 'array'],
            'specializations.*' => ['string', 'max:80'],
            'tags' => ['nullable', 'string', 'max:500'],
            'avatar' => ['nullable', 'image', 'max:2048'],
        ]);

        $avatarPath = $this->storeAvatar($request);
        $tags = $this->parseTags($data['tags'] ?? null, $data['specializations'] ?? []);

        $user = User::create([
            'full_name' => $base['full_name'],
            'username' => $base['username'],
            'password' => Hash::make($base['password']),
            'role' => 'trainer',
            'avatar' => $avatarPath,
        ]);

        $this->createTrainerProfile(
            $user,
            $data['bio'] ?? null,
            $data['specializations'] ?? [],
            $tags
        );

        $request->session()->forget('trainer_register_info');

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('trainer.pending');
    }

    public function showClientRegister()
    {
        return view('auth.register-client');
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:60', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'in:trainer,client'],
            'avatar' => ['nullable', 'image', 'max:2048'],
            'bio' => ['nullable', 'string', 'max:1500'],
            'specializations' => ['nullable', 'array'],
            'specializations.*' => ['string', 'max:80'],
            'tags' => ['nullable', 'string', 'max:500'],
        ]);

        $avatarPath = $this->storeAvatar($request);
        $tags = $this->parseTags($data['tags'] ?? null, $data['specializations'] ?? []);

        $user = User::create([
            'full_name' => $data['full_name'],
            'username' => $data['username'],
            'password' => Hash::make($data['password']),
            'role' => $data['role'],
            'avatar' => $avatarPath,
        ]);
        // Create role-specific profile
        if ($user->isTrainer()) {
            $this->createTrainerProfile(
                $user,
                $data['bio'] ?? null,
                $data['specializations'] ?? [],
                $tags
            );
        } else {
            ClientProfile::create(['user_id' => $user->id]);
        }
        Auth::login($user);
        $request->session()->regenerate();
        // Trainers go to pending page; clients go to dashboard
        if ($user->isTrainer()) {
            return redirect()->route('trainer.pending');
        }

        return $this->redirectByRole($user->role);
    }

    public function logout(Request $request)
    {
        $user = $request->user();

        if ($user?->isTrainer() && $user->trainerProfile?->isRejected()) {
            $user->delete();
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    private function redirectByRole(string $role)
    {
        return match ($role) {
            'admin' => redirect()->route('admin.dashboard'),
            'trainer' => redirect()->route('trainer.dashboard'),
            default => redirect()->route('client.dashboard'),
        };
    }

    private function storeAvatar(Request $request): ?string
    {
        return $request->file('avatar')
            ? $request->file('avatar')->store('avatars', 'public')
            : null;
    }

    private function parseTags(?string $rawTags, array $specializations = []): array
    {
        $tags = collect(explode(',', (string) $rawTags))
            ->map(fn ($tag) => trim($tag))
            ->filter()
            ->values()
            ->all();

        return $tags === [] ? $specializations : $tags;
    }

    private function createTrainerProfile(User $user, ?string $bio, array $specializations, array $tags): void
    {
        TrainerProfile::create([
            'user_id' => $user->id,
            'bio' => $bio,
            'approval_status' => 'pending',
            'specializations' => $specializations,
            'tags' => $tags,
        ]);
    }
}
