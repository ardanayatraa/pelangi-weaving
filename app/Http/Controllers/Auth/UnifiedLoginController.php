<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Pelanggan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UnifiedLoginController extends Controller
{
    public function showLoginForm()
    {
        // Jika sudah login sebagai admin, redirect ke admin dashboard
        if (Auth::guard('admin')->check()) {
            return redirect()->route('admin.dashboard');
        }
        
        // Jika sudah login sebagai pelanggan, redirect ke home
        if (Auth::guard('pelanggan')->check()) {
            return redirect()->route('home');
        }
        
        return view('auth.unified-login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Cek apakah email ada di tabel admin
        $admin = Admin::where('email', $credentials['email'])->first();
        
        if ($admin && Hash::check($credentials['password'], $admin->password)) {
            // Login sebagai admin
            Auth::guard('admin')->login($admin, $request->filled('remember'));
            $request->session()->regenerate();
            
            // Update last login
            $admin->update(['last_login' => now()]);
            
            return redirect()->intended(route('admin.dashboard'))
                ->with('success', 'Selamat datang, ' . $admin->nama);
        }
        
        // Cek apakah email ada di tabel pelanggan
        $pelanggan = Pelanggan::where('email', $credentials['email'])->first();
        
        if ($pelanggan && Hash::check($credentials['password'], $pelanggan->password)) {
            // Login sebagai pelanggan
            Auth::guard('pelanggan')->login($pelanggan, $request->filled('remember'));
            $request->session()->regenerate();
            
            return redirect()->intended(route('home'))
                ->with('success', 'Selamat datang, ' . $pelanggan->nama);
        }

        // Jika tidak ditemukan di kedua tabel
        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        // Logout dari admin guard
        if (Auth::guard('admin')->check()) {
            Auth::guard('admin')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            
            return redirect()->route('login')
                ->with('success', 'Berhasil logout!');
        }
        
        // Logout dari pelanggan guard
        if (Auth::guard('pelanggan')->check()) {
            Auth::guard('pelanggan')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            
            return redirect()->route('login')
                ->with('success', 'Berhasil logout!');
        }
        
        return redirect()->route('login');
    }
}
