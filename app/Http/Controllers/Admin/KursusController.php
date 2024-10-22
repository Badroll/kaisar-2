<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClassModel;
use App\Models\Course;
use App\Models\Holiday;
use App\Models\Practice;
use App\Models\Role;
use App\Models\Teori;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KursusController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.kursus.index');
    }

    public function getActiveCourses()
    {
        $courses = Course::whereNotIn('status', ['canceled', 'completed'])
            ->with(['user', 'class'])
            ->get();

        return datatables()->of($courses)
            ->addColumn('alert', function ($course) {
                if($course->jumlah_teori < 1){
                    return '<span title="Teori Belum Dijadwalkan" class="p-2 bg-danger rounded-2"><i class="text-white fas fa-chalkboard-teacher"></i></span>';
                } elseif($course->sisa_praktek > 0 && $course->jumlah_teori >= 1){
                    return '<span title="Praktek Belum Dijadwalkan" class="p-2 bg-danger rounded-2"><i class="text-white fas fa-cut"></i></span>';
                } else {
                    return '';
                }
            })
            ->addColumn('student_name', function ($course) {
                return $course->user ? ucfirst($course->user->name) : 'N/A';
            })
            ->addColumn('class_name', function ($course) {
                return $course->class ? $course->class->name : 'N/A';
            })
            ->addColumn('status', function ($course) {
                if (in_array($course->status, ['created', 'scheduled'])) {
                    return '<span class="badge badge-sm bg-gradient-success">' . ucfirst($course->status) . '</span>';
                } elseif ($course->status === 'need_reschedule') {
                    return '<span class="badge badge-sm bg-gradient-warning">' . ucfirst($course->status) . '</span>';
                }
            })
            ->addColumn('action', function ($course) {
                $showRoute = route('kursus.show', $course->id);
                $editRoute = route('kursus.edit', $course->id);
                $deleteRoute = route('kursus.destroy', $course->id);
                return '
                    <a href="' . $showRoute . '" class="btn btn-primary" data-bs-toggle="tooltip" data-bs-original-title="View Kursus">
                        <i class="fas fa-eye text-white"></i>
                    </a>  
                    <a href="' . $editRoute . '" class="btn btn-warning" data-bs-toggle="tooltip" data-bs-original-title="Edit user">
                        <i class="fas fa-user-edit text-white"></i>
                    </a>
                    <form action="' . $deleteRoute . '" method="post" class="d-inline">
                          ' . csrf_field() . '
                          ' . method_field('delete') . '
                          <button class="btn btn-danger" data-toggle="tooltip" data-original-title="Delete user">
                            <i class="cursor-pointer fas fa-trash text-white"></i>
                          </button>
                    </form>';
            })
            ->rawColumns(['alert','status', 'action'])
            ->make(true);
    }

    public function getCompletedCourses()
    {
        $courses = Course::whereIn('status', ['canceled', 'completed'])
            ->with(['user', 'class']) // Eager load user dan class
            ->get();

        return datatables()->of($courses)
            ->addColumn('student_name', function ($course) {
                return $course->user ? ucfirst($course->user->name) : 'N/A';
            })
            ->addColumn('class_name', function ($course) {
                return $course->class ? $course->class->name : 'N/A';
            })
            ->addColumn('status', function ($course) {
                if ($course->status === 'canceled') {
                    return '<span class="badge badge-sm bg-gradient-danger">' . ucfirst($course->status) . '</span>';
                } elseif ($course->status === 'completed') {
                    return '<span class="badge badge-sm bg-gradient-success">' . ucfirst($course->status) . '</span>';
                }
            })
            ->addColumn('action', function ($course) {
                $showRoute = route('kursus.show', $course->id);
                $editRoute = route('kursus.edit', $course->id);
                $deleteRoute = route('kursus.destroy', $course->id);
                return '
                    <a href="' . $showRoute . '" class="btn btn-primary" title="Lihat Detail Kursus" data-bs-toggle="tooltip" data-bs-original-title="View Kursus">
                        <i class="fas fa-eye text-white"></i>
                    </a>    
                    <a href="' . $editRoute . '" class="btn btn-warning" title="Hapus Edit Kursus" data-bs-toggle="tooltip" data-bs-original-title="Edit Kursus">
                        <i class="fas fa-user-edit text-white"></i>
                    </a>
                    <form action="' . $deleteRoute . '" method="post" class="d-inline">
                          ' . csrf_field() . '
                          ' . method_field('delete') . '
                          <button class="btn btn-danger" title="Hapus Kursus" data-toggle="tooltip" data-original-title="Delete user" onclick="return confirm(\'Apakah kamu yakin ingin menghapus kursus ini?\')">
                            <i class="cursor-pointer fas fa-trash text-white"></i>
                          </button>
                    </form>';
            })
            ->rawColumns(['status', 'action'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $studentRole = Role::where('name', 'student')->first();

        $students = User::whereHas('roles', function ($query) use ($studentRole) {
            $query->where('role_id', $studentRole->id);
        })->get();

        $classes = ClassModel::all();

        return view('admin.kursus.create', compact('students', 'classes'));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi input
        $validatedData = $request->validate([
            'user_id' => 'required|string',
            'class_id' => 'required|string',
            'start_date' => 'required|date',
        ]);

        $class = ClassModel::findOrFail($validatedData['class_id']);

        $startDate = Carbon::parse($validatedData['start_date']);
        $endDate = $startDate->addDays($class->day_duration);

        // Membuat kursus baru
        $course = new Course($validatedData);
        $course->end_date = $endDate;
        $course->status = 'created';
        $course->save();

        return redirect()->route('kursus.index')->with('success', 'Kursus berhasil dibuat.');
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $course = Course::findOrFail($id);
        $user = User::findOrFail($course->user->id);
        $theories = Teori::where('course_id', $course->id)->orderBy('session_date', 'asc')
        ->get();
        $practices = Practice::where('course_id', $course->id)
        ->orderBy('session_date', 'asc')
        ->get();

        return view('admin.kursus.show', compact('course', 'user', 'theories', 'practices'));
    }

    public function view_jadwal(string $id)
    {
        $course = Course::with('class')->findOrFail($id);
        $startDate = Carbon::parse($course->start_date);
        $endDate = Carbon::parse($course->end_date);

        $holidays = Holiday::pluck('date')->toArray();

        $student_practices = Practice::where('course_id', $id)
            ->whereBetween('session_date', [$startDate, $endDate])
            ->where('status', '<>', 'canceled')
            ->get()
            ->groupBy('session_date');

        $other_practices = Practice::whereBetween('session_date', [$startDate, $endDate])->where('status', '<>', 'canceled')->get()->groupBy('session_date');

        return view('admin.kursus.jadwal', compact('course', 'startDate', 'endDate', 'holidays', 'student_practices', 'other_practices'));
    }

    public function store_jadwal(Request $request, string $course_id)
    {
        $course = Course::findOrFail($course_id);

        $validated = $request->validate([
            'sessions' => 'required|array',
            'sessions.*.date' => 'required|date',
            'sessions.*.time' => 'required|in:siang,malam',
        ]);

        DB::beginTransaction();
        try {
            // Ambil sesi yang sudah dipilih oleh user
            $selectedSessions = $validated['sessions'];

            foreach ($selectedSessions as $session) {
                // Periksa apakah sesi ini sudah ada di database
                $existingPractice = Practice::where('session_date', $session['date'])
                    ->where('session_time', $session['time'])
                    ->count();

                if ($existingPractice >= 2) {
                    // Batalkan transaksi dan return dengan status 409 (Conflict)
                    DB::rollBack();
                    return response()->json([
                        'message' => 'Maaf, sesi ' . $session['time'] . ' pada ' . $session['date'] . ' sudah penuh. Silakan pilih sesi lain.'
                    ], 409); // Conflict
                }
            }

            // Simpan setiap sesi ke database jika tidak ada yang bertabrakan
            foreach ($selectedSessions as $session) {
                Practice::create([
                    'course_id' => $course_id,
                    'session_date' => $session['date'],
                    'session_time' => $session['time'],
                    'status' => 'scheduled',
                ]);
            }

            DB::commit();
            return response()->json([
                'message' => 'Uhuyy, Jadwal berhasil disimpan!',
            ], 201); // Created
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Yahh, terjadi kesalahan saat menyimpan jadwal.',
                'error' => $e->getMessage(),
            ], 500); // Internal Server Error
        }
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $course = Course::findOrFail($id);
        $studentRole = Role::where('name', 'student')->first();

        $students = User::whereHas('roles', function ($query) use ($studentRole) {
            $query->where('role_id', $studentRole->id);
        })->get();

        $classes = ClassModel::all();
        return view('admin.kursus.edit', compact('course', 'students', 'classes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $course = Course::findOrFail($id);

        $request->validate([
            'user_id' => 'required|string',
            'class_id' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'status' => 'required|string',
        ]);

        $course->user_id = $request->user_id;
        $course->class_id = $request->class_id;
        $course->start_date = $request->start_date;
        $course->end_date = $request->end_date;
        $course->status = $request->status;
        $course->save();

        $previousUrl = url()->previous();

        if (str_contains($previousUrl, 'users')) {
            return redirect()->back()->with('success', 'Kursus berhasil diperbarui 😎');
        }

        return redirect()->route('kursus.index')->with('success', 'Kursus berhasil diperbarui 😎');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $course = Course::findOrFail($id);

        $course->delete();
        // Ambil URL referer (halaman sebelumnya)
        $previousUrl = url()->previous();

        if (str_contains($previousUrl, 'users')) {
            return redirect()->back()->with('success', 'Kursus berhasil dihapus');
        }

        return redirect()->route('kursus.index')->with('success', 'Kursus berhasil dihapus 👌');
    }
}
