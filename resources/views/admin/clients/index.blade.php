@extends('layouts.app')
@section('title', 'Manage Clients')
@section('page-title', 'Manage Clients')
@section('sidebar-nav')
    @include('admin.partials.sidebar-nav')
@endsection

@section('content')
    <div class="mb-6">
        <form method="GET" class="flex gap-3">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search clients..."
                class="max-w-sm flex-1 rounded-xl border border-app-border bg-surface px-4 py-2 text-white placeholder-gray-500">
            <button type="submit" class="rounded-xl bg-brand px-4 py-2 text-white hover:bg-brand-light">Search</button>
        </form>
    </div>

    <div class="overflow-hidden rounded-2xl border border-app-border bg-surface">
        <table class="w-full text-sm">
            <thead class="bg-surface-light text-xs uppercase text-gray-400">
                <tr>
                    <th class="px-6 py-4 text-left">Client</th>
                    <th class="px-6 py-4 text-center">Total Spent</th>
                    <th class="px-6 py-4 text-center">Status</th>
                    <th class="px-6 py-4 text-center">Joined</th>
                    <th class="px-6 py-4 text-right">Actions</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-app-border">
                @forelse($clients as $client)
                    <tr class="hover:bg-surface-light/50">
                        <td class="px-6 py-4">
                            <div class="font-medium">{{ $client->full_name }}</div>
                            <div class="text-xs text-gray-500">{{ '@' . $client->username }}</div>
                        </td>
                        <td class="px-6 py-4 text-center">₱{{ number_format($client->clientProfile->total_spent ?? 0, 2) }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span
                                class="rounded-full px-2 py-1 text-xs {{ $client->account_status === 'active' ? 'bg-brand/20 text-brand' : 'bg-red-500/20 text-red-400' }}">{{ ucfirst($client->account_status) }}</span>
                        </td>
                        <td class="px-6 py-4 text-center text-gray-400">{{ $client->created_at->format('M d, Y') }}</td>
                        <td class="px-6 py-4 text-right">
                            <form method="POST" action="{{ route('admin.clients.suspend', $client) }}">
                                @csrf
                                @method('PATCH')
                                <button
                                    class="rounded-lg bg-surface-light px-3 py-1 text-xs text-white hover:bg-gray-600">{{ $client->account_status === 'active' ? 'Suspend' : 'Reactivate' }}</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-500">No clients found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">{{ $clients->links() }}</div>
@endsection
