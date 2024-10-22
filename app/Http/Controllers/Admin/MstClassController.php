<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClassModel;
use Illuminate\Http\Request;

class MstClassController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.kelas.index');
    }

    public function getKelas()
    {
        $classes = ClassModel::all();

        return datatables()->of($classes)
            ->addColumn('action', function ($class) {
                $editRoute = route('kelas.edit', $class->id);
                $deleteRoute = route('kelas.destroy', $class->id);
                return '<a href="' . $editRoute . '" class="btn btn-warning" data-bs-toggle="tooltip" data-bs-original-title="Edit Kelas">
                        <i class="fas fa-user-edit text-white"></i>
                    </a>
                    <form action="' . $deleteRoute . '" method="post" class="d-inline">
                          ' . csrf_field() . '
                          ' . method_field('delete') . '
                          <button class="btn btn-danger" data-toggle="tooltip" data-original-title="Delete Kelas" onclick="return confirm(\'Apakah kamu yakin ingin menghapus kelas ini?\')">
                            <i class="cursor-pointer fas fa-trash text-white"></i>
                          </button>
                    </form>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.kelas.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'day_duration' => 'required|integer',
            'num_of_praktek' => 'required|integer',
        ]);

        $class = ClassModel::create($validatedData);

        return redirect()->route('kelas.index')->with('success', 'Kelas berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $class = ClassModel::findOrFail($id);
        return view('admin.kelas.edit', compact('class'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $class = ClassModel::findOrFail($id);

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'day_duration' => 'required|integer',
            'num_of_praktek' => 'required|integer',
        ]);

        $class->name = $request->name;
        $class->day_duration = $request->day_duration;
        $class->num_of_praktek = $request->num_of_praktek;

        $class->save();

        return redirect()->route('kelas.index')->with('success', 'Kelas berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $class = ClassModel::findOrFail($id);

        $class->delete();

        return redirect()->route('kelas.index')->with('success', 'Kelas berhasil dihapus.');
    }
}
