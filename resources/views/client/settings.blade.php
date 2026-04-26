@extends('layouts.app')
@section('title', 'Settings')
@section('page-title', 'Account Settings')
@section('sidebar-nav')
    @include('client.partials.sidebar-nav')
@endsection

@section('content')
    <div class="max-w-2xl mx-auto">
        <div class="rounded-2xl border border-app-border bg-surface p-8">
            <h2 class="mb-6 text-lg font-bold font-heading">Profile Information</h2>

            <form method="POST" action="{{ route('client.settings.update') }}" class="space-y-5" enctype="multipart/form-data">
                @csrf

                <div class="flex items-center gap-4">
                    @if ($user->avatar)
                        <img src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->full_name }}"
                            class="h-16 w-16 rounded-2xl object-cover">
                    @else
                        <div
                            class="flex h-16 w-16 items-center justify-center rounded-2xl bg-brand/20 text-lg font-semibold text-brand">
                            {{ strtoupper(substr($user->full_name, 0, 2)) }}
                        </div>
                    @endif
                    <div>
                        <p class="font-medium text-white">Profile Photo</p>
                        <p class="text-sm text-gray-400">Optional. Upload a new photo to replace the current one.</p>
                    </div>
                </div>

                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-300">Change Photo</label>
                    <input type="file" name="avatar" accept="image/*"
                        class="w-full rounded-xl border border-app-border bg-surface px-4 py-3 text-white focus:border-brand">
                    @error('avatar')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-300">Full Name</label>
                    <input type="text" name="full_name" value="{{ old('full_name', $user->full_name) }}" required
                        class="w-full rounded-xl border border-app-border bg-surface px-4 py-3 text-white focus:border-brand">
                    @error('full_name')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-300">New Password <span
                            class="text-gray-500">(optional)</span></label>
                    <input type="password" name="password"
                        class="w-full rounded-xl border border-app-border bg-surface px-4 py-3 text-white focus:border-brand">
                </div>

                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-300">Confirm Password</label>
                    <input type="password" name="password_confirmation"
                        class="w-full rounded-xl border border-app-border bg-surface px-4 py-3 text-white focus:border-brand">
                </div>

                <button type="submit" class="rounded-xl bg-brand px-6 py-3 text-white hover:bg-brand-light">Save
                    Changes</button>
            </form>
        </div>
    </div>
@endsection
