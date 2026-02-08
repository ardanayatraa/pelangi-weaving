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
        $pelanggan = Auth::user();
        return view('customer.profile.index', compact('pelanggan'));
    }

    public function update(Request $request)
    {
        $pelanggan = Auth::user();

        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'email' => 'required|email|unique:pelanggan,email,' . $pelanggan->id_pelanggan . ',id_pelanggan',
            'telepon' => 'required|string|max:20',
            'whatsapp' => 'nullable|string|max:20',
            'kode_pos' => 'nullable|string|max:10',
        ]);

        $pelanggan->update($validated);

        return back()->with('success', 'Profile berhasil diperbarui!');
    }

    public function updatePassword(Request $request)
    {
        $pelanggan = Auth::user();

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

    // Alamat Management (JSON)
    public function storeAlamat(Request $request)
    {
        $pelanggan = Auth::user();

        $validated = $request->validate([
            'label' => 'required|in:rumah,kantor,lainnya',
            'nama_penerima' => 'required|string|max:100',
            'telepon' => 'required|string|max:20',
            'alamat_lengkap' => 'required|string',
            'kota' => 'required|string|max:100',
            'provinsi' => 'required|string|max:100',
            'kode_pos' => 'required|string|max:10',
        ]);

        $pelanggan->addAlamat($validated);

        return back()->with('success', 'Alamat berhasil ditambahkan!');
    }

    public function updateAlamat(Request $request, $index)
    {
        $pelanggan = Auth::user();

        $validated = $request->validate([
            'label' => 'required|in:rumah,kantor,lainnya',
            'nama_penerima' => 'required|string|max:100',
            'telepon' => 'required|string|max:20',
            'alamat_lengkap' => 'required|string',
            'kota' => 'required|string|max:100',
            'provinsi' => 'required|string|max:100',
            'kode_pos' => 'required|string|max:10',
        ]);

        $pelanggan->updateAlamat($index, $validated);

        return back()->with('success', 'Alamat berhasil diperbarui!');
    }

    public function deleteAlamat($index)
    {
        $pelanggan = Auth::user();
        $pelanggan->deleteAlamat($index);

        return back()->with('success', 'Alamat berhasil dihapus!');
    }

    public function setDefaultAlamat($index)
    {
        $pelanggan = Auth::user();
        $pelanggan->setDefaultAlamat($index);

        return back()->with('success', 'Alamat default berhasil diubah!');
    }
}
