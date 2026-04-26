@extends('layouts.app')
@section('title', 'Availability')
@section('page-title', 'My Availability')
@section('sidebar-nav')
    @include('trainer.partials.sidebar-nav')
@endsection
@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <div class="bg-surface rounded-2xl border border-app-border p-6">
            <h2 class="text-lg font-bold font-heading mb-4">Add Availability Slot</h2>
            <form method="POST" action="{{ route('trainer.availability.store') }}" class="space-y-4">
                @csrf
                <div><label class="block text-sm text-gray-300 mb-1">Day of Week</label>
                    <select name="day_of_week"
                        class="w-full px-4 py-2 rounded-xl bg-surface-dark border border-app-border text-white">
                        @foreach (['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'] as $i => $day)
                            <option value="{{ $i }}">{{ $day }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div><label class="block text-sm text-gray-300 mb-1">Start</label><input type="time"
                            name="start_time" required
                            class="w-full px-4 py-2 rounded-xl bg-surface-dark border border-app-border text-white"></div>
                    <div><label class="block text-sm text-gray-300 mb-1">End</label><input type="time" name="end_time"
                            required
                            class="w-full px-4 py-2 rounded-xl bg-surface-dark border border-app-border text-white"></div>
                </div>
                <button type="submit" class="px-4 py-2 bg-brand rounded-xl text-white hover:bg-brand-light">Add
                    Slot</button>
            </form>
        </div>
        <div class="bg-surface rounded-2xl border border-app-border p-6">
            <h2 class="text-lg font-bold font-heading mb-4">Current Schedule</h2>
            <div class="space-y-3">
                @forelse($slots as $slot)
                    <div class="flex items-center justify-between p-3 bg-surface-light rounded-xl">
                        <div><span class="font-medium">{{ $slot->day_name }}</span><span
                                class="text-gray-400 ml-2">{{ $slot->start_time }} — {{ $slot->end_time }}</span></div>
                        <form method="POST" action="{{ route('trainer.availability.destroy', $slot) }}">@csrf
                            @method('DELETE')<button class="text-red-400 hover:text-red-300 text-sm">Remove</button></form>
                    </div>
                @empty
                    <p class="text-gray-500 text-sm">No availability slots set.</p>
                @endforelse
            </div>
        </div>
    </div>
@endsection
