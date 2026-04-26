@extends('layouts.app')
@section('title', 'Browse Trainers')
@section('page-title', 'Browse Trainers')
@section('sidebar-nav')
    @include('client.partials.sidebar-nav')
@endsection

@section('content')

    <form method="GET" action="{{ route('client.browse') }}"
        class="browse-filter-form mb-6 flex flex-wrap items-center gap-3 rounded-2xl border border-app-border bg-surface p-4">
        <div class="flex items-center gap-2 flex-1 min-w-[220px]">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search trainers..."
                class="browse-filter-control w-full rounded-xl border border-app-border bg-surface-dark px-4 py-2 text-sm text-white focus:border-brand focus:outline-none">

            <button type="submit" name="action" value="search"
                class="inline-flex h-9 items-center gap-2 rounded-xl bg-brand px-3 py-2 text-sm font-medium text-white transition-colors hover:bg-brand-light">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"
                    aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M21 21l-4.35-4.35M11 19a8 8 0 100-16 8 8 0 000 16z" />
                </svg>
                Search
            </button>
        </div>

        <select name="spec"
            class="browse-filter-control rounded-xl border border-app-border bg-surface-dark px-4 py-2 text-sm text-gray-300 focus:outline-none">
            <option value="">All Specializations</option>
            @foreach ($specializations as $spec)
                <option value="{{ $spec }}" {{ request('spec') == $spec ? 'selected' : '' }}>{{ $spec }}
                </option>
            @endforeach
        </select>

        <select name="mode"
            class="browse-filter-control rounded-xl border border-app-border bg-surface-dark px-4 py-2 text-sm text-gray-300 focus:outline-none">
            <option value="">Any Mode</option>
            <option value="Online" {{ request('mode') == 'Online' ? 'selected' : '' }}>Online</option>
            <option value="In-Person" {{ request('mode') == 'In-Person' ? 'selected' : '' }}>In-Person</option>
        </select>

        <button type="submit" name="action" value="filter"
            class="browse-filter-control rounded-xl bg-brand px-4 py-2 text-sm font-medium text-white transition-colors hover:bg-brand-light">
            Filter
        </button>
    </form>

    <div class="browse-trainer-grid grid gap-6 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
        @forelse($trainers as $trainer)
            <a href="{{ route('client.trainer.profile', $trainer) }}"
                class="browse-trainer-card group block rounded-2xl border border-app-border bg-surface p-6 transition-all hover:border-brand/40">
                @if ($trainer->avatar)
                    <img src="{{ asset('storage/' . $trainer->avatar) }}" alt="{{ $trainer->full_name }}"
                        class="browse-trainer-avatar mb-4 h-14 w-14 rounded-2xl object-cover transition-transform group-hover:scale-105">
                @else
                    <div
                        class="browse-trainer-avatar mb-4 flex h-14 w-14 items-center justify-center rounded-2xl bg-brand/20 text-xl font-semibold text-brand transition-transform group-hover:scale-105">
                        {{ strtoupper(substr($trainer->full_name, 0, 2)) }}
                    </div>
                @endif

                <h3 class="browse-trainer-name mb-1 font-semibold text-white">{{ $trainer->full_name }}</h3>

                <div class="mb-3 flex items-center gap-1">
                    <span class="text-sm text-yellow-400">★</span>
                    <span class="text-sm font-medium">{{ number_format($trainer->trainerProfile->rating, 1) }}</span>
                    <span class="text-sm text-gray-500">({{ $trainer->trainerProfile->review_count }} reviews)</span>
                </div>

                <p class="browse-trainer-bio mb-4 text-sm text-gray-400">{{ $trainer->trainerProfile->bio }}</p>

                <div class="browse-tag-row mb-4 flex flex-wrap gap-1">
                    @foreach (array_slice($trainer->trainerProfile->tags ?? [], 0, 3) as $tag)
                        <span
                            class="rounded-full bg-surface-light px-2 py-0.5 text-xs text-gray-400">{{ $tag }}</span>
                    @endforeach
                </div>

                <div
                    class="browse-trainer-footer flex items-center justify-between border-t border-app-border pt-4 text-sm">
                    <span class="text-gray-500">{{ $trainer->trainerProfile->sessions_completed }} sessions</span>
                    <span class="font-medium text-brand">View Profile →</span>
                </div>
            </a>
        @empty
            <div class="col-span-full py-16 text-center text-gray-500">
                <p class="mb-2 text-lg">No trainers found</p>
                <p class="text-sm">Try adjusting your filters</p>
            </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    <div class="mt-8">
        {{ $trainers->links() }}
    </div>
@endsection
