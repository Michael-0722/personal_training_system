<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class ClientsController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 'client')->with('clientProfile');
        if ($request->filled('search')) {
            $query->where(fn ($q) => $q->where('full_name', 'like', "%{$request->search}%")
                ->orWhere('username', 'like', "%{$request->search}%")
            );
        }
        $clients = $query->latest()->paginate(15);

        return view('admin.clients.index', compact('clients'));
    }

    public function suspend(User $user)
    {
        $newStatus = $user->account_status === 'active' ? 'suspended' : 'active';
        $user->update(['account_status' => $newStatus]);

        return back()->with('success', "Client status changed to {$newStatus}.");
    }
}
