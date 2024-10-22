<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Holiday;
use App\Models\Practice;
use App\Models\PracticeTalent;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Silvanix\Wablas\Message;

class PraktekController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $today_practices = Practice::where('status', '<>', 'canceled')->where('session_date', now()->toDateString())->get();
        $tomorrow_practices = Practice::where('status', '<>', 'canceled')->where('session_date', now()->addDay()->toDateString())->get();
        return view('admin.praktek.index', compact('today_practices', 'tomorrow_practices'));
    }

    public function getScheduledPractices()
    {
        $practices = Practice::where('status', 'scheduled')
            ->whereDate('session_date', '>=', now())
            ->with('course')
            ->get();

        return datatables()->of($practices)
            ->addColumn('student_name', function ($practice) {
                return $practice->course_id ? ucfirst($practice->course->user->name) : 'N/A';
            })
            ->addColumn('class_name', function ($practice) {
                return $practice->course_id ? $practice->course->class->name : 'N/A';
            })
            ->addColumn('talents', function ($practice) {
                if ($practice->talents->isEmpty()) {
                    return '<span class="text-danger">Belum ada talent</span>';
                }
                return $practice->talents->pluck('name')->map(function ($talent) {
                    return '<span class="badge badge-sm bg-gradient-secondary">' . $talent . '</span>';
                })->implode(' ');
            })
            ->addColumn('status', function ($practice) {
                if ($practice->status === 'scheduled') {
                    return '<span class="badge badge-sm bg-gradient-warning">' . ucfirst($practice->status) . '</span>';
                } elseif ($practice->status === 'completed') {
                    return '<span class="badge badge-sm bg-gradient-success">' . ucfirst($practice->status) . '</span>';
                } elseif ($practice->status === 'canceled') {
                    return '<span class="badge badge-sm bg-gradient-danger">' . ucfirst($practice->status) . '</span>';
                }
            })
            ->addColumn('action', function ($practice) {
                $showRoute = route('kursus.show', $practice->course->id);
                $editRoute = route('praktek.edit', $practice->id);
                $deleteRoute = route('praktek.cancel', $practice->id);
                return '
                    <a href="' . $showRoute . '" class="btn btn-primary" title="Lihat Detail Kursus" data-bs-toggle="tooltip" data-bs-original-title="View Kursus">
                        <i class="fas fa-search text-white"></i>
                    </a>
                    <a href="' . $editRoute . '" class="btn btn-warning" title="Edit Praktek" data-bs-toggle="tooltip" data-bs-original-title="Edit Praktek">
                        <i class="fas fa-user-edit text-white"></i>
                    </a>
                    <form action="' . $deleteRoute . '" method="post" class="d-inline">
                          ' . csrf_field() . '
                          <button class="btn btn-danger" title="Batalkan Praktek" data-toggle="tooltip" data-original-title="Cancel Praktek" onclick="return confirm(\'Apakah kamu yakin ingin membatalkan praktek ini?\')">
                            <i class="cursor-pointer fas fa-window-close text-white"></i>
                          </button>
                    </form>';
            })
            ->rawColumns(['status', 'talents', 'action'])
            ->make(true);
    }

    public function getCompletedPractices()
    {
        $practices = Practice::whereIn('status', ['completed', 'canceled'])
            ->orWhereDate('session_date', '<', now())
            ->with('course')
            ->get();

        return datatables()->of($practices)
            ->addColumn('student_name', function ($practice) {
                return $practice->course_id ? ucfirst($practice->course->user->name) : 'N/A';
            })
            ->addColumn('class_name', function ($practice) {
                return $practice->course_id ? $practice->course->class->name : 'N/A';
            })
            ->addColumn('talents', function ($practice) {
                if ($practice->talents->isEmpty()) {
                    return '<span class="text-danger">Belum ada talent</span>';
                }
                return $practice->talents->pluck('name')->map(function ($talent) {
                    return '<span class="badge badge-sm bg-gradient-secondary">' . $talent . '</span>';
                })->implode(' ');
            })
            ->addColumn('status', function ($practice) {
                if ($practice->status === 'scheduled') {
                    return '<span class="badge badge-sm bg-gradient-warning">' . ucfirst($practice->status) . '</span>';
                } elseif ($practice->status === 'completed') {
                    return '<span class="badge badge-sm bg-gradient-success">' . ucfirst($practice->status) . '</span>';
                } elseif ($practice->status === 'canceled') {
                    return '<span class="badge badge-sm bg-gradient-danger">' . ucfirst($practice->status) . '</span>';
                }
            })
            ->addColumn('action', function ($practice) {
                $showRoute = route('kursus.show', $practice->course->id);
                $editRoute = route('praktek.edit', $practice->id);
                $deleteRoute = route('praktek.destroy', $practice->id);
                return '
                    <a href="' . $showRoute . '" class="btn btn-primary" title="Lihat Detail Kursus" data-bs-toggle="tooltip" data-bs-original-title="View Kursus">
                        <i class="fas fa-search text-white"></i>
                    </a>
                    <a href="' . $editRoute . '" class="btn btn-warning" title="Edit Praktek" data-bs-toggle="tooltip" data-bs-original-title="Edit user">
                        <i class="fas fa-user-edit text-white"></i>
                    </a>
                    <form action="' . $deleteRoute . '" method="post" class="d-inline">
                          ' . csrf_field() . '
                          ' . method_field('delete') . '
                          <button class="btn btn-danger" title="Hapus Praktek" data-toggle="tooltip" data-original-title="Delete Praktek" onclick="return confirm(\'Apakah kamu yakin ingin menghapus praktek ini?\')">
                            <i class="cursor-pointer fas fa-trash text-white"></i>
                          </button>
                    </form>';
            })
            ->rawColumns(['status', 'talents', 'action'])
            ->make(true);
    }

    public function getAvailableSessions(Request $request)
    {
        $date = $request->query('date');

        // Hitung jumlah slot yang tersedia untuk sesi 1 dan 2
        $session1Count = Practice::where('session_date', $date)
            ->where('session_time', 1)
            ->where('status', '<>', 'canceled')
            ->count();

        $session2Count = Practice::where('session_date', $date)
            ->where('session_time', 2)
            ->where('status', '<>', 'canceled')
            ->count();

        $students = User::whereHas('courses.practices', function ($query) use ($date) {
            $query->where('session_date', $date)
                ->where('status', '<>', 'canceled');
        })->get();

        return response()->json([
            'sesi1_slots' => max(0, 2 - $session1Count), // maksimal 2 slot per sesi
            'sesi2_slots' => max(0, 2 - $session2Count),
            'students' => $students,
        ]);
    }

    public function getAvailableSessionsEdit(Request $request, string $id)
    {
        $date = $request->query('date');

        // Hitung jumlah slot yang tersedia untuk sesi 1 dan 2
        $session1Count = Practice::where('session_date', $date)
            ->where('session_time', 1)
            ->where('id', '!=', $id)
            ->where('status', '<>', 'canceled')
            ->count();

        $session2Count = Practice::where('session_date', $date)
            ->where('session_time', 2)
            ->where('id', '!=', $id)
            ->where('status', '<>', 'canceled')
            ->count();

        $students = User::whereHas('courses.practices', function ($query) use ($date, $id) {
            $query->where('session_date', $date)
                ->where('id', '!=', $id)
                ->where('status', '<>', 'canceled');
        })->get();

        return response()->json([
            'sesi1_slots' => max(0, 2 - $session1Count), // maksimal 2 slot per sesi
            'sesi2_slots' => max(0, 2 - $session2Count),
            'students' => $students,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $courses = Course::with('user')
            ->whereNotIn('status', ['canceled', 'completed'])
            ->whereHas('theories', function ($query) {
                $query->where('status', '<>', 'canceled');  // Teori yang sudah dijadwalkan
            })
            ->where(function ($query) {
                $query->whereHas('practices', function ($query) {
                    $query->where('status', '<>', 'canceled')
                        ->select('course_id')  // Mengelompokkan berdasarkan 'course_id'
                        ->groupBy('course_id')
                        ->havingRaw('COUNT(*) < (SELECT num_of_praktek FROM ms_classes WHERE ms_classes.id = courses.class_id)');
                })
                    ->orWhereDoesntHave('practices');  // Kursus yang belum menjadwalkan praktek
            })
            ->get();

        $talents = User::whereHas('roles', function ($query) {
            $query->where('name', 'talent');
        })
            ->whereDoesntHave('practices', function ($query) {
                $query->where('session_date', '>=', now()->subDays(14));
            })
            ->get();

        $holidays = Holiday::pluck('date')->toArray();

        $datesWithFullSessions = Practice::select('session_date')
            ->groupBy('session_date')
            ->havingRaw('COUNT(*) >= 4') // 2 sesi, masing-masing 2 slot
            ->pluck('session_date')
            ->toArray();

        return view('admin.praktek.create', compact('courses', 'talents', 'holidays', 'datesWithFullSessions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'course_id' => 'required|exists:courses,id',
            'status' => 'required|string',
            'session_date' => 'required|date',
            'session_time' => 'required|string',
            'talent_1' => 'nullable|exists:users,id',
            'talent_2' => 'nullable|exists:users,id',
            'talent_3' => 'nullable|exists:users,id',
        ]);

        $existingPractice = Practice::where('course_id', $request->course_id)
            ->where('session_date', $request->session_date)
            ->where('session_time', $request->session_time)
            ->exists();

        if ($existingPractice) {
            return redirect()->back()->withErrors(['sesi' => 'Tidak bisa memilih sesi yang sama pada tanggal dan waktu yang sama untuk kursus ini.'])->withInput();
        }

        $practice = new Practice($validatedData);
        $practice->save();

        if ($request->talent_1) {
            $practice->talents()->attach($request->talent_1, ['session_time' => 1]);
        }

        if ($request->talent_2) {
            $practice->talents()->attach($request->talent_2, ['session_time' => 2]);
        }

        if ($request->talent_3) {
            $practice->talents()->attach($request->talent_3, ['session_time' => 3]);
        }

        return redirect()->route('praktek.index')->with('success', 'Praktek berhasil dibuat.');
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
        $practice = Practice::findOrFail($id);

        $talents = User::whereHas('roles', function ($query) {
            $query->where('name', 'talent');
        })
            ->whereDoesntHave('practices', function ($query) {
                $query->where('session_date', '>=', now()->subDays(14));
            })
            ->get();

        $holidays = Holiday::pluck('date')->toArray();

        $datesWithFullSessions = Practice::select('session_date')
            ->where('id', '<>', $id)
            ->groupBy('session_date')
            ->havingRaw('COUNT(*) >= 4') // 2 sesi, masing-masing 2 slot
            ->pluck('session_date')
            ->toArray();

        return view('admin.praktek.edit', compact('practice', 'talents', 'holidays', 'datesWithFullSessions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $practice = Practice::findOrFail($id);

        $request->validate([
            'status' => 'required|string',
            'session_date' => 'required|date',
            'session_time' => 'required|string',
            'talent_1' => 'nullable|exists:users,id',
            'talent_2' => 'nullable|exists:users,id',
            'talent_3' => 'nullable|exists:users,id',
        ]);

        $statusLama = $practice->status;
        $practice->status = $request->status;
        $practice->session_date = $request->session_date;
        $practice->session_time = $request->session_time;

        $practice->talent_1 = $request->talent_1;
        $practice->talent_2 = $request->talent_2;
        $practice->talent_3 = $request->talent_3;

        $practice->save();

        $practice->talents()->detach();

        // Masukkan talenta baru ke pivot table dengan session_time
        if ($request->talent_1) {
            $practice->talents()->attach($request->talent_1, ['session_time' => 1]);
        }

        if ($request->talent_2) {
            $practice->talents()->attach($request->talent_2, ['session_time' => 2]);
        }

        if ($request->talent_3) {
            $practice->talents()->attach($request->talent_3, ['session_time' => 3]);
        }

        if ($practice->status == 'canceled' && $statusLama != 'canceled') {
            $send = new Message();
            $student = $practice->course->user;
            $talents = $practice->talents;
            $dayOfWeek = Carbon::parse($practice->session_date)->locale('id')->isoFormat('dddd');
            $sessionTime = ucwords($practice->session_time) . ' ' . ($practice->session_time == 'siang' ? '14.00 - 17.00' : '18.30 - 21.30');
            $formattedDate = Carbon::parse($practice->session_date)->format('d/m/Y');

            $payload = [];

            $studentMessage = 'Halo, Kak ' . ucwords($student->name) . "\n\n";
            $studentMessage .= "Kami ingin menyampaikan bahwa praktek pada tanggal berikut:\n";
            $studentMessage .= 'Hari : ' . $dayOfWeek . "\n";
            $studentMessage .= 'Tanggal : ' . $formattedDate . "\n";
            $studentMessage .= 'Sesi : ' . $sessionTime . "\n";
            $studentMessage .= "\nTelah dibatalkan oleh Admin. Mohon hubungi admin untuk informasi lebih lanjut.";

            $payload[] = [
                'phone' => $student->phone_number,
                'message' => $studentMessage,
            ];

            foreach ($talents as $talent) {
                $talentMessage = 'Halo, Kak ' . ucwords($talent->name) . "\n\n";
                $talentMessage .= "Kami ingin menyampaikan bahwa praktek pada tanggal berikut:\n";
                $talentMessage .= 'Hari : ' . $dayOfWeek . "\n";
                $talentMessage .= 'Tanggal : ' . $formattedDate . "\n";
                $talentMessage .= 'Sesi : ' . $sessionTime . "\n";
                $talentMessage .= "\nTelah dibatalkan oleh Admin. Mohon hubungi admin untuk informasi lebih lanjut.";

                $payload[] = [
                    'phone' => $talent->phone_number,
                    'message' => $talentMessage,
                ];
            }

            $send_text = $send->multiple_text($payload);
        }

        $previousUrl = url()->previous();

        if (str_contains($previousUrl, 'kursus')) {
            return redirect()->back()->with('success', 'Praktek berhasil diperbarui bos 😎');
        } elseif (str_contains($previousUrl, 'users')) {
            return redirect()->back()->with('success', 'Praktek berhasil dihapus 🗑️');
        }

        return redirect()->route('praktek.index')->with('success', 'Praktek berhasil diperbarui bos 😎');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $practice = Practice::findOrFail($id);
        $practice->delete();

        // Ambil URL referer (halaman sebelumnya)
        $previousUrl = url()->previous();

        // Jika referer mengandung 'show', redirect kembali ke halaman show
        if (str_contains($previousUrl, 'kursus')) {
            return redirect()->back()->with('success', 'Praktek berhasil dihapus 🗑️');
        } elseif (str_contains($previousUrl, 'users')) {
            return redirect()->back()->with('success', 'Praktek berhasil dihapus 🗑️');
        }

        return redirect()->route('praktek.index')->with('success', 'Praktek berhasil dihapus 🗑️');
    }

    public function cancel($id)
    {
        $practice = Practice::findOrFail($id);

        // Ubah status menjadi 'canceled' atau logika lainnya
        $practice->status = 'canceled';
        $practice->save();

        $send = new Message();
        $student = $practice->course->user;
        $talents = $practice->talents;
        $dayOfWeek = Carbon::parse($practice->session_date)->locale('id')->isoFormat('dddd');
        $sessionTime = ucwords($practice->session_time) . ' ' . ($practice->session_time == 'siang' ? '14.00 - 17.00' : '18.30 - 21.30');
        $formattedDate = Carbon::parse($practice->session_date)->format('d/m/Y');

        $payload = [];

        $studentMessage = 'Halo, Kak ' . ucwords($student->name) . "\n\n";
        $studentMessage .= "Kami ingin menyampaikan bahwa praktek pada tanggal berikut:\n";
        $studentMessage .= 'Hari : ' . $dayOfWeek . "\n";
        $studentMessage .= 'Tanggal : ' . $formattedDate . "\n";
        $studentMessage .= 'Sesi : ' . $sessionTime . "\n";
        $studentMessage .= "\nTelah dibatalkan oleh Admin. Mohon hubungi admin untuk informasi lebih lanjut.";

        $payload[] = [
            'phone' => $student->phone_number,
            'message' => $studentMessage,
        ];

        foreach ($talents as $talent) {
            $talentMessage = 'Halo, Kak ' . ucwords($talent->name) . "\n\n";
            $talentMessage .= "Kami ingin menyampaikan bahwa praktek pada tanggal berikut:\n";
            $talentMessage .= 'Hari : ' . $dayOfWeek . "\n";
            $talentMessage .= 'Tanggal : ' . $formattedDate . "\n";
            $talentMessage .= 'Sesi : ' . $sessionTime . "\n";
            $talentMessage .= "\nTelah dibatalkan oleh Admin. Mohon hubungi admin untuk informasi lebih lanjut.";

            $payload[] = [
                'phone' => $talent->phone_number,
                'message' => $talentMessage,
            ];
        }

        $send_text = $send->multiple_text($payload);

        return redirect()->route('praktek.index')->with('success', 'Praktek berhasil dibatalkan ❌');
    }
}
