@extends('layouts.app')
@section('title', 'Manage Trainers')
@section('page-title', 'Manage Trainers')
@section('sidebar-nav')
    @include('admin.partials.sidebar-nav')
@endsection

@section('content')
    <div class="mb-6 flex items-center gap-4">
        <form method="GET" class="flex flex-1 flex-wrap items-center gap-2">
            <div class="flex min-w-0 flex-1 items-center gap-2">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search trainers..."
                    class="max-w-sm flex-1 rounded-xl border border-app-border bg-surface px-4 py-2 text-white placeholder-gray-500">

                <button type="submit"
                    class="rounded-xl bg-brand px-4 py-2 text-white hover:bg-brand-light">Search</button>

                <select name="status" class="rounded-xl border border-app-border bg-surface px-4 py-2 text-white">
                    <option value="">All Status</option>
                    <option value="pending" @selected(request('status') === 'pending')>Pending</option>
                    <option value="approved" @selected(request('status') === 'approved')>Approved</option>
                    <option value="rejected" @selected(request('status') === 'rejected')>Rejected</option>
                </select>

                <button type="submit"
                    class="rounded-xl bg-surface-light px-4 py-2 text-white hover:bg-gray-600">Filter</button>
            </div>
        </form>
    </div>

    <div class="overflow-hidden rounded-2xl border border-app-border bg-surface">
        <table class="w-full text-sm">
            <thead class="bg-surface-light text-xs uppercase text-gray-400">
                <tr>
                    <th class="px-6 py-4 text-left">Trainer</th>
                    <th class="px-6 py-4 text-left">Specializations</th>
                    <th class="px-6 py-4 text-center">Rating</th>
                    <th class="px-6 py-4 text-center">Status</th>
                    <th class="px-6 py-4 text-center">Account</th>
                    <th class="px-6 py-4 text-right">Actions</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-app-border">
                @forelse($trainers as $trainer)
                    <tr class="hover:bg-surface-light/50">
                        <td class="px-6 py-4">
                            <div class="font-medium">{{ $trainer->full_name }}</div>
                            <div class="text-xs text-gray-500">{{ '@' . $trainer->username }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-wrap gap-1">
                                @foreach ($trainer->trainerProfile->specializations ?? [] as $spec)
                                    <span
                                        class="rounded-full bg-brand/20 px-2 py-0.5 text-xs text-brand">{{ $spec }}</span>
                                @endforeach
                            </div>
                        </td>
                        <td class="px-6 py-4 text-center"><span class="text-yellow-400">★</span>
                            {{ number_format($trainer->trainerProfile->rating ?? 0, 1) }}</td>
                        <td class="px-6 py-4 text-center">
                            @php $status = $trainer->trainerProfile->approval_status ?? 'pending'; @endphp
                            <span
                                class="rounded-full px-2 py-1 text-xs {{ $status === 'approved' ? 'bg-brand/20 text-brand' : ($status === 'pending' ? 'bg-yellow-500/20 text-yellow-400' : 'bg-red-500/20 text-red-400') }}">{{ ucfirst($status) }}</span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span
                                class="rounded-full px-2 py-1 text-xs {{ $trainer->account_status === 'active' ? 'bg-brand/20 text-brand' : 'bg-red-500/20 text-red-400' }}">{{ ucfirst($trainer->account_status) }}</span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                @if (optional($trainer->trainerProfile)->isPending())
                                    <form method="POST" action="{{ route('admin.trainers.approve', $trainer) }}">
                                        @csrf
                                        @method('PATCH')
                                        <button
                                            class="rounded-lg bg-brand px-3 py-1 text-xs text-white hover:bg-brand-light">Approve</button>
                                    </form>

                                    <form method="POST" action="{{ route('admin.trainers.reject', $trainer) }}">
                                        @csrf
                                        @method('PATCH')
                                        <button
                                            class="rounded-lg bg-red-600 px-3 py-1 text-xs text-white hover:bg-red-700">Reject</button>
                                    </form>
                                @endif

                                <form method="POST" action="{{ route('admin.trainers.suspend', $trainer) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button
                                        class="rounded-lg bg-surface-light px-3 py-1 text-xs text-white hover:bg-gray-600">{{ $trainer->account_status === 'active' ? 'Suspend' : 'Reactivate' }}</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-500">No trainers found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">{{ $trainers->links() }}</div>
@endsection
