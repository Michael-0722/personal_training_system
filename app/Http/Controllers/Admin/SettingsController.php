<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index()
    {
        $commissionRate = config('trainify.commission_rate', 0.20);

        return view('admin.settings', compact('commissionRate'));
    }

    public function update(Request $request)
    {
        $data = $request->validate(['commission_rate' => 'required|numeric|min:0|max:100']);

        $commissionRate = ((float) $data['commission_rate']) / 100;

        return back()->with('success', 'Commission rate updated to '.number_format($commissionRate * 100, 2).'%.');
    }
}
