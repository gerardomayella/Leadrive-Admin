<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function show()
    {
        // Fetch admin data directly from the 'admin' table
        $admin = DB::table('admin')->where('email', 'admin@gmail.com')->first();
        
        return view('admin.profile.show', compact('admin'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $data = [
            'name' => $request->name,
            'updated_at' => now(),
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        DB::table('admin')->where('email', 'admin@gmail.com')->update($data);

        return redirect()->route('admin.profile.show')->with('success', 'Profile updated successfully.');
    }
}
