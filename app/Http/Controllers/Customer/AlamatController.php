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
            'is_default' => 'nullable',
        ]);

        $pelanggan = Auth::guard('pelanggan')->user();
        $idPelanggan = $pelanggan->id_pelanggan;

        // Jika ini alamat default, set alamat lain jadi non-default
        if ($request->boolean('is_default')) {
            Alamat::where('id_pelanggan', $idPelanggan)->update(['is_default' => false]);
        }

        // Jika ini alamat pertama, otomatis jadi default
        if (Alamat::where('id_pelanggan', $idPelanggan)->count() === 0) {
            $validated['is_default'] = true;
        } else {
            $validated['is_default'] = $request->boolean('is_default');
        }

        $validated['id_pelanggan'] = $idPelanggan;
        Alamat::create($validated);

        return back()->with('success', 'Alamat berhasil ditambahkan!');
    }

    public function update(Request $request, Alamat $alamat)
    {
        $idPelanggan = Auth::guard('pelanggan')->id();
        if ($alamat->id_pelanggan != $idPelanggan) {
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
            'is_default' => 'nullable',
        ]);

        if ($request->boolean('is_default')) {
            Alamat::where('id_pelanggan', $idPelanggan)
                ->where('id', '!=', $alamat->id)
                ->update(['is_default' => false]);
        }

        $alamat->update($validated);

        return back()->with('success', 'Alamat berhasil diperbarui!');
    }

    public function destroy(Alamat $alamat)
    {
        $idPelanggan = Auth::guard('pelanggan')->id();
        if ($alamat->id_pelanggan != $idPelanggan) {
            abort(403);
        }

        if ($alamat->is_default) {
            $firstAlamat = Alamat::where('id_pelanggan', $idPelanggan)
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
        $idPelanggan = Auth::guard('pelanggan')->id();
        if ($alamat->id_pelanggan != $idPelanggan) {
            abort(403);
        }

        Alamat::where('id_pelanggan', $idPelanggan)->update(['is_default' => false]);
        $alamat->update(['is_default' => true]);

        return back()->with('success', 'Alamat default berhasil diubah!');
    }
}
