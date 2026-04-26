@extends('layouts.app')
@section('title', 'Notifications')
@section('page-title', 'Notifications')
@section('sidebar-nav')
    @include('trainer.partials.sidebar-nav')
@endsection

@section('content')
    <div class="space-y-4">
        @forelse($notifications as $n)
            <div class="rounded-2xl border border-app-border bg-surface p-6 {{ $n->is_read ? 'opacity-60' : '' }}">
                <div class="flex items-start justify-between gap-4">
                    <div class="flex items-start gap-4">
                        <div
                            class="flex h-10 w-10 items-center justify-center rounded-xl {{ str_contains($n->type, 'rejected') || str_contains($n->type, 'cancelled') ? 'bg-red-500/20 text-red-400' : 'bg-brand/20 text-brand' }}">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>
                        </div>

                        <div>
                            <p class="font-medium">{{ $n->title }}</p>
                            <p class="mt-1 text-sm text-gray-400">{{ $n->message }}</p>
                            <p class="mt-2 text-xs text-gray-500">{{ $n->created_at->diffForHumans() }}</p>
                        </div>
                    </div>

                    @unless ($n->is_read)
                        <form method="POST" action="{{ route('trainer.notifications.read', $n) }}">
                            @csrf
                            @method('PATCH')
                            <button class="text-xs text-brand hover:text-brand-light">Mark Read</button>
                        </form>
                    @endunless
                </div>
            </div>
        @empty
            <div class="py-16 text-center text-gray-500">No notifications yet.</div>
        @endforelse
    </div>

    <div class="mt-6">{{ $notifications->links() }}</div>
@endsection
