<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Pelanggan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class PelangganRegisterController extends Controller
{
    public function showRegistrationForm()
    {
        if (Auth::guard('pelanggan')->check()) {
            return redirect()->route('home');
        }
        
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'email' => 'required|email|max:100|unique:pelanggan,email',
            'password' => 'required|string|min:8|confirmed',
            'telepon' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
            'id_kota' => 'nullable|integer',
            'id_provinsi' => 'nullable|integer',
            'kode_pos' => 'nullable|string|max:10',
        ]);

        $validated['password'] = Hash::make($validated['password']);

        $pelanggan = Pelanggan::create($validated);

        Auth::guard('pelanggan')->login($pelanggan);

        return redirect()->route('home')
            ->with('success', 'Registrasi berhasil! Selamat datang, ' . $pelanggan->nama);
    }
}
