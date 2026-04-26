@extends('layouts.app')
@section('title', 'Settings')
@section('page-title', 'Platform Settings')
@section('sidebar-nav')
    @include('admin.partials.sidebar-nav')
@endsection

@section('content')
    <div class="max-w-2xl">
        <div class="rounded-2xl border border-app-border bg-surface p-8">
            <h2 class="mb-6 text-lg font-bold font-heading">Commission Settings</h2>

            <form method="POST" action="{{ route('admin.settings.update') }}" class="space-y-6">
                @csrf

                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-300">Commission Rate (%)</label>
                    <input type="number" name="commission_rate" value="{{ old('commission_rate', $commissionRate * 100) }}"
                        step="0.01" min="0" max="100"
                        class="w-full rounded-xl border border-app-border bg-surface px-4 py-3 text-white focus:border-brand focus:outline-none">
                    @error('commission_rate')
                        <p class="mt-2 text-xs text-red-400">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500">Percentage deducted as platform fee. Trainers receive the
                        remainder.</p>
                </div>

                <button type="submit" class="rounded-xl bg-brand px-6 py-3 text-white hover:bg-brand-light">Save
                    Settings</button>
            </form>
        </div>
    </div>
@endsection
