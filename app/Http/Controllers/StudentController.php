<?php

namespace App\Http\Controllers;

use App\Models\ClassModel;
use App\Models\Course;
use App\Models\Holiday;
use App\Models\Practice;
use App\Models\Teori;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Silvanix\Wablas\Message;

class StudentController extends Controller
{
    public function dashboard()
    {
        $user = auth()->user();

        $courses = Course::where('user_id', $user->id)
            ->whereNotIn('status', ['canceled', 'completed'])
            ->get();
        $classes = ClassModel::all();
        $theories_soon = Teori::whereHas('course', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })
            ->where('session_date', '>=', Carbon::today())
            ->get();
        $theories_history = Teori::whereHas('course', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })
            ->where('session_date', '<', Carbon::today())
            ->get();
        $practices_soon = Practice::whereHas('course', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })
            ->where('session_date', '>=', Carbon::today())
            ->get();
        $practices_history = Practice::whereHas('course', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })
            ->where('session_date', '<', Carbon::today())
            ->get();

        return view('student.home', compact('classes', 'user', 'courses', 'theories_soon', 'theories_history', 'practices_soon', 'practices_history'));
    }

    public function showCourse()
    {
        $user = auth()->user();

        $courses = Course::where('user_id', $user->id)
            ->whereNotIn('status', ['canceled', 'completed'])
            ->get();

        return view('student.kursus', compact('courses'));
    }

    public function courseDetail(string $id)
    {
        $user = auth()->user();

        $course = Course::findOrFail($id);
        $theories = Teori::where('course_id', $course->id)->orderBy('session_date', 'asc')
            ->get();
        $practices = Practice::where('course_id', $course->id)
            ->orderBy('session_date', 'asc')
            ->get();

        return view('student.detail-kursus', compact('course', 'theories', 'practices'));
    }

    public function getDates()
    {
        // Ambil data hari libur dari tabel Holidays
        $holidays = Holiday::pluck('date')->toArray();

        // Ambil data tanggal yang sudah penuh dari tabel Practices
        $datesWithFullSessions = Teori::select('session_date')
            ->groupBy('session_date')
            ->havingRaw('COUNT(*) >= 2') // 2 sesi, masing-masing 2 slot
            ->pluck('session_date')
            ->toArray();

        return response()->json([
            'holidays' => $holidays,
            'datesWithFullSessions' => $datesWithFullSessions
        ]);
    }

    public function storeTeori(Request $request)
    {
        $validatedData = $request->validate([
            'course_id' => 'required|exists:courses,id',
            'status' => 'required|string',
            'session_date' => 'required|date'
        ]);

        $teori = new Teori($validatedData);
        $teori->save();

        return redirect()->route('student.home')->with('success', 'Teori berhasil dibuat.');
    }

    public function showJadwalPraktek(string $id)
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

        $other_practices = Practice::whereBetween('session_date', [$startDate, $endDate])->get()->groupBy('session_date');

        return view('student.jadwal-praktek', compact('course', 'startDate', 'endDate', 'holidays', 'student_practices', 'other_practices'));
    }

    public function storeJadwalPraktek(Request $request, string $course_id)
    {
        $course = Course::findOrFail($course_id);

        $validated = $request->validate([
            'sessions' => 'required|array',
            'sessions.*.date' => 'required|date',
            'sessions.*.time' => 'required|in:siang,malam',
        ]);

        DB::beginTransaction();
        try {
            $selectedSessions = $validated['sessions'];

            foreach ($selectedSessions as $session) {
                // Periksa apakah sesi ini sudah ada di database
                $existingPractice = Practice::where('session_date', $session['date'])
                    ->where('session_time', $session['time'])
                    ->count();

                if ($existingPractice >= 2) {
                    DB::rollBack();
                    return response()->json([
                        'message' => 'Maaf, sesi ' . $session['time'] . ' pada ' . $session['date'] . ' sudah penuh. Silakan pilih sesi lain.'
                    ], 409);
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

            // Kirim pesan WhatsApp ke user
            $user = auth()->user();
            $phone = $user->phone_number;

            // Template pesan
            $message = 'Halo, Kak ' . ucwords($user->name) . "\n\n";
            $message .= "Terima kasih sudah melakukan pemilihan jadwal praktek untuk kursus.\n\n";
            $message .= "Berikut merupakan rincian jadwal yang telah dibuat:\n";

            foreach ($selectedSessions as $index => $session) {
                $sessionDate = $session['date'];
                $sessionTime = ucwords($session['time']);
                $dayOfWeek = Carbon::parse($sessionDate)->locale('id')->isoFormat('dddd');
                $formattedDate = Carbon::parse($sessionDate)->format('d/m/Y');
            
                $message .= ($index + 1) . ". Hari: $dayOfWeek, Tanggal: $formattedDate, Sesi: $sessionTime\n";
            }

            $message .= "\nJangan lupa untuk hadir sesuai jadwal yang sudah dipilih. Terima kasih!";

            $send = new Message();
            $send_text = $send->single_text($phone,$message);

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

    public function showKursus(string $id)
    {
        $course = Course::findOrFail($id);
        $user = User::findOrFail($course->user->id);
        $theories = Teori::where('course_id', $course->id)->orderBy('session_date', 'asc')
            ->get();
        $practices = Practice::where('course_id', $course->id)
            ->orderBy('session_date', 'asc')
            ->get();

        return view('student.detail-kursus', compact('course', 'user', 'theories', 'practices'));
    }

    public function allKursus()
    {
        $user = auth()->user();

        $courses = Course::where('user_id', $user->id)
            ->get();

        return view('student.list-kursus', compact('course', 'user'));
    }
}
