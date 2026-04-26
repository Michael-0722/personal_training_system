@extends('layouts.app')
@section('title', $trainer->full_name)
@section('page-title', 'Trainer Profile')
@section('sidebar-nav')
    @include('client.partials.sidebar-nav')
@endsection

@section('content')
    <a href="{{ route('client.browse') }}" class="mb-4 inline-flex items-center gap-2 text-brand hover:text-brand-light">
        ← Back to Browse Trainers
    </a>
    <div class="max-w-4xl mx-auto">
        <div class="mb-8 rounded-2xl border border-app-border bg-surface p-8">
            <div class="flex items-start gap-6">
                @if ($trainer->avatar)
                    <img src="{{ asset('storage/' . $trainer->avatar) }}" alt="{{ $trainer->full_name }}"
                        class="h-20 w-20 rounded-2xl object-cover">
                @else
                    <div
                        class="flex h-20 w-20 items-center justify-center rounded-2xl bg-brand/20 text-2xl font-bold text-brand">
                        {{ strtoupper(substr($trainer->full_name, 0, 2)) }}</div>
                @endif

                <div class="flex-1">
                    <h2 class="text-2xl font-bold font-heading">{{ $trainer->full_name }}</h2>

                    <div class="mt-2 flex items-center gap-4 text-sm text-gray-400">
                        <span class="text-yellow-400">★ {{ number_format($profile->rating, 1) }}</span>
                        <span>{{ $profile->review_count }} reviews</span>
                        <span>{{ $profile->sessions_completed }} sessions</span>
                        <span>₱{{ number_format($profile->hourly_rate, 0) }}/hr</span>
                    </div>

                    <p class="mt-4 text-gray-300">{{ $profile->bio }}</p>

                    <div class="mt-4 flex flex-wrap gap-2">
                        @foreach ($profile->specializations as $spec)
                            <span class="rounded-full bg-brand/20 px-3 py-1 text-xs text-brand">{{ $spec }}</span>
                        @endforeach

                        @foreach ($profile->tags as $tag)
                            <span
                                class="rounded-full bg-surface-light px-3 py-1 text-xs text-gray-300">{{ $tag }}</span>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <h3 class="mb-4 text-lg font-bold font-heading">Available Sessions</h3>

        <div class="mb-8 grid grid-cols-1 gap-6 md:grid-cols-2">
            @foreach ($sessions as $session)
                <div class="rounded-2xl border border-app-border bg-surface p-6">
                    <div class="mb-3 flex items-center gap-2">
                        <span
                            class="rounded-full px-2 py-0.5 text-xs {{ $session->format === '1-on-1' ? 'bg-brand/20 text-brand' : 'bg-purple-500/20 text-purple-400' }}">{{ $session->format }}</span>
                        <span
                            class="rounded-full px-2 py-0.5 text-xs {{ $session->delivery_mode === 'Online' ? 'bg-blue-500/20 text-blue-400' : 'bg-orange-500/20 text-orange-400' }}">{{ $session->delivery_mode }}</span>
                    </div>

                    <h4 class="mb-1 text-lg font-bold font-heading">{{ $session->title }}</h4>
                    <p class="mb-4 text-sm text-gray-400">{{ $session->description }}</p>

                    <div class="flex items-center justify-between">
                        <span class="text-xl font-bold text-brand">₱{{ number_format($session->price, 2) }}</span>
                        <a href="{{ route('client.book.show', [$trainer, $session]) }}"
                            class="rounded-xl bg-brand px-4 py-2 text-sm text-white hover:bg-brand-light">Book Now</a>
                    </div>
                </div>
            @endforeach
        </div>

        <h3 class="mb-4 text-lg font-bold font-heading">Reviews</h3>

        <div class="space-y-4">
            @forelse($reviews as $review)
                <div class="rounded-2xl border border-app-border bg-surface p-6">
                    <div class="mb-3 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div
                                class="flex h-8 w-8 items-center justify-center rounded-full bg-brand/10 text-xs font-bold text-brand">
                                {{ strtoupper(substr($review->client->full_name, 0, 2)) }}</div>
                            <span class="font-medium">{{ $review->client->full_name }}</span>
                        </div>

                        <span class="text-sm text-yellow-400">
                            @for ($i = 1; $i <= 5; $i++)
                                {{ $i <= $review->rating ? '★' : '☆' }}
                            @endfor
                        </span>
                    </div>

                    <p class="text-gray-300">{{ $review->comment }}</p>
                    <p class="mt-2 text-xs text-gray-500">{{ $review->created_at->diffForHumans() }}</p>
                </div>
            @empty
                <p class="py-8 text-center text-gray-500">No reviews yet.</p>
            @endforelse
        </div>
    </div>
@endsection
