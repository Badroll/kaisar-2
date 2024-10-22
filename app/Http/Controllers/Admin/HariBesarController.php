<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Holiday;
use App\Models\Practice;
use App\Models\Teori;
use Illuminate\Http\Request;

class HariBesarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.hari-besar.index');
    }

    public function getData()
    {
        $holidays = Holiday::all();

        return datatables()->of($holidays)
            ->addColumn('action', function ($holiday) {
                $editRoute = route('hari-besar.edit', $holiday->id);
                $deleteRoute = route('hari-besar.destroy', $holiday->id);
                return '<a href="' . $editRoute . '" class="btn btn-warning" data-bs-toggle="tooltip" data-bs-original-title="Edit Kelas">
                        <i class="fas fa-user-edit text-white"></i>
                    </a>
                    <form action="' . $deleteRoute . '" method="post" class="d-inline">
                          ' . csrf_field() . '
                          ' . method_field('delete') . '
                          <button class="btn btn-danger" data-toggle="tooltip" data-original-title="Delete Kelas" onclick="return confirm(\'Apakah kamu yakin ingin menghapus hari besar ini?\')">
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
        return view('admin.hari-besar.create');
    }

    /**
     * Store a newly created resour
     * ce in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'date' => 'required|date',
        ]);

        $hasPractice = Practice::where('session_date', $request->date)->exists();

        $hasTeori = Teori::where('session_date', $request->date)->exists();

        if ($hasPractice || $hasTeori) {
            return redirect()->back()->withErrors(['message' => 'Terdapat teori atau praktek pada tanggal tersebut, mohon pindah terlebih dahulu.'])->withInput();
        }

        $holiday = Holiday::create($validatedData);

        return redirect()->route('hari-besar.index')->with('success', 'Hari Besar berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $holiday = Holiday::findOrFail($id);
        return view('admin.hari-besar.edit', compact('holiday'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $holiday = Holiday::findOrFail($id);

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'date' => 'required|date',
        ]);

        $holiday->name = $request->name;
        $holiday->date = $request->date;
        $holiday->save();

        return redirect()->route('hari-besar.index')->with('success', 'Hari Besar berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $holiday = Holiday::findOrFail($id);

        $holiday->delete();

        return redirect()->route('hari-besar.index')->with('success', 'Hari Besar berhasil dihapus.');
    }
}
