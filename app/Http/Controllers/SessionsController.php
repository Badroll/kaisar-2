<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\session;
use Illuminate\Validation\Rule;

class SessionsController extends Controller
{
    public function create()
    {
        return view('session.user-choose');
    }

    public function showLoginAdminForm()
    {
        if (Auth::check() && Auth::user()->hasRole('admin')) {
            // Jika sudah login dan memiliki role admin, arahkan langsung ke dashboard admin
            return redirect()->route('admin.dashboard');
        }
    
        // Jika belum login atau tidak memiliki role admin, tampilkan halaman login
        return view('session.admin-login');
    }

    public function showLoginTrainerForm()
    {
        if (Auth::check() && Auth::user()->hasRole('trainer')) {
            // Jika sudah login dan memiliki role admin, arahkan langsung ke dashboard admin
            return redirect()->route('trainer.home');
        }

        return view('session.trainer-login');
    }

    public function showLoginStudentForm()
    {
        if (Auth::check() && Auth::user()->hasRole('student')) {
            // Jika sudah login dan memiliki role admin, arahkan langsung ke dashboard admin
            return redirect()->route('student.home');
        }

        return view('session.student-login');
    }

    public function showLoginTalentForm()
    {
        if (Auth::check() && Auth::user()->hasRole('talent')) {
            return redirect()->route('talent.home');
        }
        return view('session.talent-login');
    }

    public function showRegisterTalentForm()
    {
        if (Auth::check() && Auth::user()->hasRole('talent')) {
            return redirect()->route('talent.home');
        }

        return view('session.talent-register');
    }

    public function store(Request $request)
    {
        $attributes = $request->validate([
            'email'=>'required|email',
            'password'=>'required'
        ]);

        $remember = $request->has('remember');

        if(Auth::attempt($attributes, $remember))
        {
            session()->regenerate();
            $user = Auth::user();
            $role = $request->input('role');

            if ($user->hasRole($role)) {
                switch ($role) {
                    case 'admin':
                        return redirect()->route('admin.dashboard');
                    case 'trainer':
                        return redirect()->route('trainer.home');
                    case 'student':
                        return redirect()->route('student.home');
                    case 'talent':
                        return redirect()->route('talent.home');
                    default:
                        Auth::logout();
                        return back()->withErrors(['message' => 'Role tidak valid']);
                }
            } else {
                Auth::logout();
                return back()->withErrors(['message' => 'Akses tidak memiliki akses ke role ini']);
            }
        }
        else{
            return back()->withErrors(['message'=>'Email atau password salah. Mohon coba lagi']);
        }
    }

    public function register(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone_number' => 'required|string|max:15|unique:users,phone_number',
            'tgl_lahir' => 'nullable|date',
            'password' => 'required|string|min:5',
            'address' => 'nullable|string',
        ], [
            'email.unique' => 'Email telah digunakan, mohon gunakan email lainnya.',
            'phone_number.unique' => 'Nomor HP telah dipakai, mohon gunakan nomor lainnya',
        ]);

        $user = User::create($validatedData);
        $roleTalent = Role::where('name', 'talent')->first();
        $user->roles()->sync([$roleTalent->id]);

        return redirect()->route('talent.login')->with('success', 'Yeay, register berhasil dilakukan.');
    }
    
    public function destroy()
    {

        Auth::logout();

        // Menghapus session
        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect('/')->with(['success'=>'You\'ve been logged out.']);
    }
}
