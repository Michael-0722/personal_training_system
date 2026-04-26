@extends('layouts.app')
@section('title', 'My Sessions')
@section('page-title', 'My Sessions')
@section('sidebar-nav')
    @include('trainer.partials.sidebar-nav')
@endsection
@section('header-actions')
@endsection
@section('content')
    <div class="flex justify-end">
        <button onclick="document.getElementById('create-modal').classList.remove('hidden')"
            class="px-4 py-2 bg-brand rounded-xl text-white text-sm hover:bg-brand-light">
            + New Session
        </button>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($sessions as $session)
            <div class="bg-surface rounded-2xl border border-app-border p-6">
                <div class="flex items-center gap-2 mb-3">
                    <span
                        class="px-2 py-0.5 text-xs rounded-full {{ $session->format === '1-on-1' ? 'bg-brand/20 text-brand' : 'bg-purple-500/20 text-purple-400' }}">{{ $session->format }}</span>
                    <span
                        class="px-2 py-0.5 text-xs rounded-full {{ $session->delivery_mode === 'Online' ? 'bg-blue-500/20 text-blue-400' : 'bg-orange-500/20 text-orange-400' }}">{{ $session->delivery_mode }}</span>
                    @unless ($session->is_active)
                        <span class="px-2 py-0.5 text-xs rounded-full bg-red-500/20 text-red-400">Inactive</span>
                    @endunless
                </div>
                <h3 class="text-lg font-bold font-heading mb-1">{{ $session->title }}</h3>
                <p class="text-sm text-gray-400 mb-4">{{ $session->description }}</p>
                <div class="flex items-center justify-between">
                    <span class="text-xl font-bold text-brand">₱{{ number_format($session->price, 2) }}</span>
                    <span class="text-sm text-gray-500">{{ $session->duration_minutes }} min</span>
                </div>
            </div>
        @empty
            <div class="col-span-full text-center py-12 text-gray-500">No sessions yet. Create your first one!</div>
        @endforelse
    </div>
    <div id="create-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/60">
        <div class="bg-surface rounded-2xl border border-app-border p-8 w-full max-w-lg">
            <h2 class="text-xl font-bold font-heading mb-6">Create New Session</h2>
            <form method="POST" action="{{ route('trainer.sessions.store') }}" class="space-y-4">
                @csrf
                <div><label class="block text-sm text-gray-300 mb-1">Title</label><input type="text" name="title"
                        required
                        class="w-full px-4 py-2 rounded-xl bg-surface-dark border border-app-border text-white focus:border-brand">
                </div>
                <div><label class="block text-sm text-gray-300 mb-1">Description</label>
                    <textarea name="description" rows="3"
                        class="w-full px-4 py-2 rounded-xl bg-surface-dark border border-app-border text-white focus:border-brand"></textarea>
                </div>
                <div class="grid grid-cols-2 gap-4">
                        <div><label class="block text-sm text-gray-300 mb-1">Format</label><select id="session-format" name="format"
                            class="w-full px-4 py-2 rounded-xl bg-surface-dark border border-app-border text-white">
                            <option value="1-on-1">1-on-1</option>
                            <option value="Group">Group</option>
                        </select></div>
                    <div><label class="block text-sm text-gray-300 mb-1">Delivery</label><select name="delivery_mode"
                            class="w-full px-4 py-2 rounded-xl bg-surface-dark border border-app-border text-white">
                            <option value="Online">Online</option>
                            <option value="In-Person">In-Person</option>
                        </select></div>
                </div>
                <div class="grid grid-cols-3 gap-4">
                    <div><label class="block text-sm text-gray-300 mb-1">Duration (min)</label><input type="number"
                            name="duration_minutes" value="60"
                            class="w-full px-4 py-2 rounded-xl bg-surface-dark border border-app-border text-white"></div>
                    <div><label class="block text-sm text-gray-300 mb-1">Price (₱)</label><input type="number"
                            name="price" step="0.01" required
                            class="w-full px-4 py-2 rounded-xl bg-surface-dark border border-app-border text-white"></div>
                        <div><label class="block text-sm text-gray-300 mb-1">Max Capacity</label><input id="max-participants" type="number"
                            name="max_participants" min="2"
                            class="w-full px-4 py-2 rounded-xl bg-surface-dark border border-app-border text-white"></div>
                </div>
                <div class="flex justify-end gap-3 pt-4">
                    <button type="button" onclick="document.getElementById('create-modal').classList.add('hidden')"
                        class="px-4 py-2 bg-surface-light rounded-xl text-white">Cancel</button>
                    <button type="submit"
                        class="px-4 py-2 bg-brand rounded-xl text-white hover:bg-brand-light">Create</button>
                </div>
            </form>

            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    const fmt = document.getElementById('session-format');
                    const max = document.getElementById('max-participants');
                    if (!fmt || !max) return;

                    function updateMax() {
                        if (fmt.value === '1-on-1') {
                            max.disabled = true;
                            max.value = 1;
                            max.setAttribute('min', '1');
                            max.classList.add('opacity-60', 'cursor-not-allowed');
                        } else {
                            max.disabled = false;
                            if (!max.value || Number(max.value) < 2) max.value = 2;
                            max.setAttribute('min', '2');
                            max.classList.remove('opacity-60', 'cursor-not-allowed');
                        }
                    }

                    fmt.addEventListener('change', updateMax);
                    updateMax();
                });
            </script>
        </div>
    </div>
@endsection
