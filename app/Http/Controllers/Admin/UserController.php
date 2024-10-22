<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClassModel;
use App\Models\Course;
use App\Models\Practice;
use App\Models\PracticeTalent;
use App\Models\Role;
use App\Models\Teori;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::all();
        return view('admin.users.index', compact('users'));
    }

    public function getUsers()
    {
        $users = User::all();

        return datatables()->of($users)
            ->addColumn('photo', function ($user) {
                return '<img src="' . ($user->photo_path ? asset('storage/' . $user->photo_path) : asset('assets/img/user-1.jpg')) . '" class="avatar avatar-sm" style="object-fit:cover;">';
            })
            ->addColumn('roles', function ($user) {
                return $user->roles->pluck('name')->map(function ($role) {
                    return '<span class="badge badge-sm bg-gradient-success">' . $role . '</span>';
                })->implode(' ');
            })
            ->addColumn('action', function ($user) {
                $showRoute = route('users.show', $user->id);
                $editRoute = route('users.edit', $user->id);
                $deleteRoute = route('users.destroy', $user->id);
                return
                    '<a href="' . $showRoute . '" class="btn btn-primary" title="Lihat Data User" data-bs-toggle="tooltip" data-bs-original-title="Edit user">
                        <i class="fas fa-eye text-white"></i>
                    </a>    
                    <a href="' . $editRoute . '" class="btn btn-warning" title="Edit User" data-bs-toggle="tooltip" data-bs-original-title="Edit user">
                        <i class="fas fa-user-edit text-white"></i>
                    </a>
                    <form action="' . $deleteRoute . '" method="post" class="d-inline">
                          ' . csrf_field() . '
                          ' . method_field('delete') . '
                          <button class="btn btn-danger" title="Hapus User" data-toggle="tooltip" data-original-title="Delete user" onclick="return confirm(\'Apakah kamu yakin ingin menghapus user ini?\')">
                            <i class="cursor-pointer fas fa-trash text-white"></i>
                          </button>
                    </form>';
            })
            ->rawColumns(['photo', 'roles', 'action'])
            ->make(true);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::all();
        return view('admin.users.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone_number' => [
                'required',
                'string',
                'max:15',
                Rule::unique('users', 'phone_number'),
            ],
            'tgl_lahir' => 'nullable|date',
            'password' => 'required|string|min:5',
            'address' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'roles' => 'required|array',
            'roles.*' => 'exists:ms_roles,id'
        ], [
            'phone_number.unique' => 'Nomor HP telah dipakai, mohon gunakan nomor lainnya',
        ]);

        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('profile-photos', 'public');
            $validatedData['photo_path'] = $path;
        }

        $user = User::create($validatedData);
        $user->roles()->sync($request->roles);

        return redirect()->route('users.index')->with('success', 'User berhasil ditambahkan.');
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::findOrFail($id);
        $courses = Course::where('user_id', $id)->get();
        $classes = ClassModel::all();
        $theories = Teori::whereHas('course', function ($query) use ($user) {
            $query->where('user_id', $user->id); 
        })->get();
        $practices = Practice::whereHas('course', function ($query) use ($user) {
            $query->where('user_id', $user->id);  // Asumsinya `user_id` ada di tabel `courses`
        })->get();
        $practiceTalent = PracticeTalent::where('user_id', $id)->get();        

        return view('admin.users.show', compact('classes','user', 'courses', 'theories', 'practices', 'practiceTalent'));
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = User::findOrFail($id);
        $roles = Role::all();

        return view('admin.users.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = User::findOrFail($id);
        // Validasi input
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone_number' => [
                'required',
                'string',
                'max:15',
                Rule::unique('users', 'phone_number')->where('id', '<>', $id),
            ],
            'tgl_lahir' => 'nullable|date',
            'password' => 'nullable|string|min:5',
            'address' => 'nullable|string',
            'roles' => 'required|array',
            'roles.*' => 'exists:ms_roles,id',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'phone_number.unique' => 'Nomor HP telah dipakai, mohon gunakan nomor lainnya',
        ]);

        // Update data user
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone_number = $request->phone_number;
        $user->tgl_lahir = $request->tgl_lahir;
        if ($request->password !== null) {
            $user->password = $request->password;
        }
        $user->address = $request->address;

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

        $user->roles()->sync($request->roles);
        $user->save();

        return redirect()->route('users.index')->with('success', 'User berhasil diperbarui 😎');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);

        // Cek apakah user yang akan dihapus adalah user yang sedang login
        if (auth()->id() === $user->id) {
            return redirect()->route('users.index')->with('error', 'Anda tidak dapat menghapus user yang sedang login.');
        }

        $user->delete();

        return redirect()->route('users.index')->with('success', 'User berhasil dihapus.');
    }

    public function deletePhoto(string $id)
    {
        $user = User::findOrFail($id);

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
