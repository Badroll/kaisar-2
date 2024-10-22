<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    public function dashboard()
    {
        return view('admin.dashboard');
    }

    public function create()
    {
        $user = Auth::user();
        return view('admin.profil', compact('user'));
    }

    public function store(Request $request)
    {
        $user = User::findOrFail(Auth::user()->id);
        // Validasi input
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone_number' => 'required|string|max:15',
            'tgl_lahir' => 'nullable|date',
            'address' => 'nullable|string',
        ]);

        // Update data user
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone_number = $request->phone_number;
        $user->address = $request->address;
        $user->tgl_lahir = $request->tgl_lahir;

        $user->save();

        return redirect()->route('admin.profil')->with('success', 'Yeay! Profil berhasil diperbarui!');
    }

    public function photoUpdate(Request $request)
    {
        $user = User::findOrFail(Auth::user()->id);
        // Validasi input
        $request->validate([
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Cek apakah ada foto baru yang diunggah
        if ($request->hasFile('photo')) {
            // Hapus foto lama jika ada
            if ($user->photo_path) {
                Storage::delete($user->photo_path);
            }

            // Simpan foto baru dan update path-nya di database
            $path = $request->file('photo')->store('profile-photos', 'public');
            $user->photo_path = $path;
        }

        $user->save();

        return redirect()->route('admin.profil')->with('success', 'Asik! Foto profil berhasil diperbarui!');
    }

    public function deletePhoto()
    {
        $user = auth()->user();

        if ($user->photo_path) {
            // Hapus file dari storage
            Storage::delete('public/' . $user->photo_path);

            // Set kolom photo_path menjadi null di database
            $user->photo_path = null;
            $user->save();

            return response()->json(['message' => 'Foto berhasil dihapus'], 200);
        }

        return response()->json(['message' => 'Tidak ada foto yang dihapus'], 400);
    }
}
