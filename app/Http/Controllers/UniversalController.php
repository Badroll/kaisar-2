<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UniversalController extends Controller
{
    public function showProfil()
    {
        $user = User::findOrFail(Auth::user()->id);

        return view('universal.profil', compact('user'));
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

    public function updatePhoto(Request $request)
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

        $userRole = $request->segment(1);

        switch ($userRole) {
            case 'trainer':
                $route = 'trainer.profil';
                break;
            case 'student':
                $route = 'student.profil';
                break;
            case 'talent':
                $route = 'talent.profil';
                break;
            default:
                $route = 'choose'; // Pengalihan default jika segmen tidak sesuai
                break;
        }

        return redirect()->route($route)->with('success', 'Asik! Foto profil berhasil diperbarui!');
    }

    public function updateProfil(Request $request)
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

        $userRole = $request->segment(1);
        
        switch ($userRole) {
            case 'trainer':
                $route = 'trainer.profil';
                break;
            case 'student':
                $route = 'student.profil';
                break;
            case 'talent':
                $route = 'talent.profil';
                break;
            default:
                $route = 'choose'; // Pengalihan default jika segmen tidak sesuai
                break;
        }

        return redirect()->route($route)->with('success', 'Yeay! Profil berhasil diperbarui!');
    }

    public function showGantiPassword()
    {
        return view('universal.ganti-password');
    }

    public function savePassword(Request $request)
    {
        $user = User::findOrFail(Auth::user()->id);

        $request->validate([
            'old_password' => 'required|string|min:5',
            'new_password' => 'required|string|min:5',
        ]);

        if (!Hash::check($request->old_password, $user->password)) {
            // Jika password lama salah, kembalikan error
            return back()->withErrors(['old_password' => 'Password lama salah'])->withInput();
        }

        $user->password = $request->new_password;
        $user->save();

        $userRole = $request->segment(1);
        
        switch ($userRole) {
            case 'trainer':
                $route = 'trainer.profil';
                break;
            case 'student':
                $route = 'student.profil';
                break;
            case 'talent':
                $route = 'talent.profil';
                break;
            default:
                $route = 'choose'; // Pengalihan default jika segmen tidak sesuai
                break;
        }

        return redirect()->route($route)->with('success', 'Yeay! Password berhasil diubah!');
    }
}
