<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    public function index()
    {
        $pelanggan = Auth::guard('pelanggan')->user();
        return view('customer.profile.index', compact('pelanggan'));
    }

    public function update(Request $request)
    {
        $pelanggan = Auth::guard('pelanggan')->user();

        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'email' => 'required|email|unique:pelanggan,email,' . $pelanggan->id_pelanggan . ',id_pelanggan',
            'telepon' => 'required|string|max:15',
            'alamat' => 'required|string',
            'kode_pos' => 'nullable|string|max:10',
        ]);

        $pelanggan->update($validated);

        return back()->with('success', 'Profile berhasil diperbarui!');
    }

    public function updatePassword(Request $request)
    {
        $pelanggan = Auth::guard('pelanggan')->user();

        $validated = $request->validate([
            'current_password' => 'required',
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        if (!Hash::check($request->current_password, $pelanggan->password)) {
            return back()->withErrors(['current_password' => 'Password saat ini tidak sesuai.']);
        }

        $pelanggan->update([
            'password' => Hash::make($validated['password'])
        ]);

        return back()->with('success', 'Password berhasil diubah!');
    }
}
