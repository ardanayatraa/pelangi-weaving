<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Alamat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AlamatController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'label' => 'required|string|max:50',
            'nama_penerima' => 'required|string|max:100',
            'telepon' => 'required|string|max:15',
            'alamat_lengkap' => 'required|string',
            'kota' => 'required|string|max:100',
            'provinsi' => 'required|string|max:100',
            'kode_pos' => 'required|string|max:10',
            'is_default' => 'boolean',
        ]);

        $user = Auth::user();

        // Jika ini alamat default, set alamat lain jadi non-default
        if ($request->is_default) {
            Alamat::where('user_id', $user->id)->update(['is_default' => false]);
        }

        // Jika ini alamat pertama, otomatis jadi default
        if (Alamat::where('user_id', $user->id)->count() === 0) {
            $validated['is_default'] = true;
        }

        $validated['user_id'] = $user->id;
        Alamat::create($validated);

        return back()->with('success', 'Alamat berhasil ditambahkan!');
    }

    public function update(Request $request, Alamat $alamat)
    {
        // Pastikan alamat milik user yang login
        if ($alamat->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'label' => 'required|string|max:50',
            'nama_penerima' => 'required|string|max:100',
            'telepon' => 'required|string|max:15',
            'alamat_lengkap' => 'required|string',
            'kota' => 'required|string|max:100',
            'provinsi' => 'required|string|max:100',
            'kode_pos' => 'required|string|max:10',
            'is_default' => 'boolean',
        ]);

        // Jika ini alamat default, set alamat lain jadi non-default
        if ($request->is_default) {
            Alamat::where('user_id', Auth::id())
                ->where('id', '!=', $alamat->id)
                ->update(['is_default' => false]);
        }

        $alamat->update($validated);

        return back()->with('success', 'Alamat berhasil diperbarui!');
    }

    public function destroy(Alamat $alamat)
    {
        // Pastikan alamat milik user yang login
        if ($alamat->user_id !== Auth::id()) {
            abort(403);
        }

        // Jika alamat yang dihapus adalah default, set alamat pertama jadi default
        if ($alamat->is_default) {
            $firstAlamat = Alamat::where('user_id', Auth::id())
                ->where('id', '!=', $alamat->id)
                ->first();
            
            if ($firstAlamat) {
                $firstAlamat->update(['is_default' => true]);
            }
        }

        $alamat->delete();

        return back()->with('success', 'Alamat berhasil dihapus!');
    }

    public function setDefault(Alamat $alamat)
    {
        // Pastikan alamat milik user yang login
        if ($alamat->user_id !== Auth::id()) {
            abort(403);
        }

        // Set semua alamat jadi non-default
        Alamat::where('user_id', Auth::id())->update(['is_default' => false]);

        // Set alamat ini jadi default
        $alamat->update(['is_default' => true]);

        return back()->with('success', 'Alamat default berhasil diubah!');
    }
}
