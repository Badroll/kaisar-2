<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Holiday;
use App\Models\Practice;
use App\Models\Teori;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Silvanix\Wablas\Message;

class TeoriController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $today_theories = Teori::where('status', '<>', 'canceled')->where('session_date', now()->toDateString())->get();
        $tomorrow_theories = Teori::where('status', '<>', 'canceled')->where('session_date', now()->addDay()->toDateString())->get();
        return view('admin.teori.index', compact('today_theories', 'tomorrow_theories'));
    }

    public function getScheduledTeori()
    {
        $theories = Teori::where('status', 'scheduled')
            ->whereDate('session_date', '>=', now())
            ->with('course')
            ->get();

        return datatables()->of($theories)
            ->addColumn('student_name', function ($theory) {
                return $theory->course_id ? ucfirst($theory->course->user->name) : 'N/A';
            })
            ->addColumn('class_name', function ($theory) {
                return $theory->course_id ? $theory->course->class->name : 'N/A';
            })
            ->addColumn('status', function ($theory) {
                if ($theory->status === 'scheduled') {
                    return '<span class="badge badge-sm bg-gradient-warning">' . ucfirst($theory->status) . '</span>';
                } elseif ($theory->status === 'completed') {
                    return '<span class="badge badge-sm bg-gradient-success">' . ucfirst($theory->status) . '</span>';
                } elseif ($theory->status === 'canceled') {
                    return '<span class="badge badge-sm bg-gradient-danger">' . ucfirst($theory->status) . '</span>';
                }
            })
            ->addColumn('action', function ($theory) {
                $showRoute = route('kursus.show', $theory->course->id);
                $editRoute = route('teori.edit', $theory->id);
                $deleteRoute = route('teori.cancel', $theory->id);
                return '
                    <a href="' . $showRoute . '" title="Lihat Detail Kursus" class="btn btn-primary" data-bs-toggle="tooltip" data-bs-original-title="View Kursus">
                        <i class="fas fa-search text-white"></i>
                    </a>
                    <a href="' . $editRoute . '" title="Edit Teori" class="btn btn-warning" data-bs-toggle="tooltip" data-bs-original-title="Edit user">
                        <i class="fas fa-user-edit text-white"></i>
                    </a>
                    <form action="' . $deleteRoute . '" method="post" class="d-inline">
                          ' . csrf_field() . '
                          <button class="btn btn-danger" title="Hapus Teori" data-toggle="tooltip" data-original-title="Cancel Praktek" onclick="return confirm(\'Apakah kamu yakin ingin membatalkan teori ini?\')">
                            <i class="cursor-pointer fas fa-window-close text-white"></i>
                          </button>
                    </form>';
            })
            ->rawColumns(['status', 'talents', 'action'])
            ->make(true);
    }

    public function getCompletedTeori()
    {
        $theories = Teori::whereIn('status', ['completed', 'canceled'])
            ->orWhereDate('session_date', '<', now())
            ->with('course')
            ->get();

        return datatables()->of($theories)
            ->addColumn('student_name', function ($theory) {
                return $theory->course_id ? ucfirst($theory->course->user->name) : 'N/A';
            })
            ->addColumn('class_name', function ($theory) {
                return $theory->course_id ? $theory->course->class->name : 'N/A';
            })
            ->addColumn('status', function ($theory) {
                if ($theory->status === 'scheduled') {
                    return '<span class="badge badge-sm bg-gradient-warning">' . ucfirst($theory->status) . '</span>';
                } elseif ($theory->status === 'completed') {
                    return '<span class="badge badge-sm bg-gradient-success">' . ucfirst($theory->status) . '</span>';
                } elseif ($theory->status === 'canceled') {
                    return '<span class="badge badge-sm bg-gradient-danger">' . ucfirst($theory->status) . '</span>';
                }
            })
            ->addColumn('action', function ($theory) {
                $showRoute = route('kursus.show', $theory->course->id);
                $editRoute = route('teori.edit', $theory->id);
                $deleteRoute = route('teori.cancel', $theory->id);
                return '
                    <a href="' . $showRoute . '" class="btn btn-primary" title="Lihat Detail Kursus" data-bs-toggle="tooltip" data-bs-original-title="View Kursus">
                        <i class="fas fa-search text-white"></i>
                    </a>
                    <a href="' . $editRoute . '" class="btn btn-warning" title="Edit Teori" data-bs-toggle="tooltip" data-bs-original-title="Edit user">
                        <i class="fas fa-user-edit text-white"></i>
                    </a>
                    <form action="' . $deleteRoute . '" method="post" class="d-inline">
                          ' . csrf_field() . '
                          <button class="btn btn-danger" title="Hapus Teori" data-toggle="tooltip" data-original-title="Cancel Praktek" onclick="return confirm(\'Apakah kamu yakin ingin menghapus teori ini?\')">
                            <i class="cursor-pointer fas fa-window-close text-white"></i>
                          </button>
                    </form>';
            })
            ->rawColumns(['status', 'talents', 'action'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $courses = Course::with('user')
            ->whereNotIn('status', ['canceled', 'completed'])
            ->whereDoesntHave('theories', function ($query) {
                $query->where('status', '<>', 'canceled'); // Memeriksa teori yang statusnya bukan 'canceled'
            })
            ->get();

        $holidays = Holiday::pluck('date')->toArray();

        $datesWithFullSessions = Teori::select('session_date')
            ->groupBy('session_date')
            ->havingRaw('COUNT(*) >= 2') // 2 sesi, masing-masing 2 slot
            ->pluck('session_date')
            ->toArray();

        return view('admin.teori.create', compact('courses', 'holidays', 'datesWithFullSessions'));
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


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'course_id' => 'required|exists:courses,id',
            'status' => 'required|string',
            'session_date' => 'required|date'
        ]);

        $teori = new Teori($validatedData);
        $teori->save();

        return redirect()->route('teori.index')->with('success', 'Teori berhasil dibuat.');
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
        $theory = Teori::findOrFail($id);

        $holidays = Holiday::pluck('date')->toArray();

        $datesWithFullSessions = Teori::select('session_date')
            ->where('id', '<>', $id)
            ->groupBy('session_date')
            ->havingRaw('COUNT(*) >= 2')
            ->pluck('session_date')
            ->toArray();

        return view('admin.teori.edit', compact('theory', 'holidays', 'datesWithFullSessions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $teori = Teori::findOrFail($id);

        $request->validate([
            'status' => 'required|string',
            'session_date' => 'required|date',
        ]);

        $statusLama = $teori->status;
        $teori->status = $request->status;
        $teori->session_date = $request->session_date;

        $teori->save();

        // Ambil URL referer (halaman sebelumnya)
        $previousUrl = url()->previous();

        if (str_contains($previousUrl, 'kursus')) {
            return redirect()->back()->with('success', 'Teori berhasil diperbarui bos 😎');
        } elseif (str_contains($previousUrl, 'users')) {
            return redirect()->back()->with('success', 'Teori berhasil diperbarui bos 😎');
        }

        if ($teori->status == 'canceled' && $statusLama != 'canceled') {
            // Kirim pesan WhatsApp ke user
            $user = $teori->course->user;
            $phone = $user->phone_number;
            $dayOfWeek = Carbon::parse($teori->session_date)->locale('id')->isoFormat('dddd');
            $formattedDate = Carbon::parse($teori->session_date)->format('d/m/Y');

            $message = 'Halo, Kak ' . ucwords($user->name) . "\n\n";
            $message .= "Kami ingin menyampaikan bahwa teori pada tanggal berikut:\n";
            $message .= 'Hari : ' . $dayOfWeek . "\n";
            $message .= 'Tanggal : ' . $formattedDate . "\n";

            $message .= "\nTelah dibatalkan oleh Admin. Mohon hubungi admin untuk informasi lebih lanjut";

            // Kirim pesan
            $send = new Message();
            $send_text = $send->single_text($phone, $message);
        }

        return redirect()->route('teori.index')->with('success', 'Teori berhasil diperbarui bos 😎');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $teori = Teori::findOrFail($id);
        $teori->delete();

        $previousUrl = url()->previous();

        if (str_contains($previousUrl, 'kursus')) {
            return redirect()->back()->with('success', 'Teori berhasil dihapus 🗑️');
        } elseif (str_contains($previousUrl, 'users')) {
            return redirect()->back()->with('success', 'Teori berhasil dihapus 🗑️');
        }

        return redirect()->route('teori.index')->with('success', 'Teori berhasil dihapus 🗑️');
    }

    public function cancel($id)
    {
        $teori = Teori::findOrFail($id);

        // Ubah status menjadi 'canceled' atau logika lainnya
        $teori->status = 'canceled';
        $teori->save();

        // Kirim pesan WhatsApp ke user
        $user = $teori->course->user;
        $phone = $user->phone_number;
        $dayOfWeek = Carbon::parse($teori->session_date)->locale('id')->isoFormat('dddd');
        $formattedDate = Carbon::parse($teori->session_date)->format('d/m/Y');

        $message = 'Halo, Kak ' . ucwords($user->name) . "\n\n";
        $message .= "Kami ingin menyampaikan bahwa teori pada tanggal berikut:\n";
        $message .= 'Hari : ' . $dayOfWeek . "\n";
        $message .= 'Tanggal : ' . $formattedDate . "\n";

        $message .= "\nTelah dibatalkan oleh Admin. Mohon hubungi admin untuk informasi lebih lanjut";

        // Kirim pesan
        $send = new Message();
        $send_text = $send->single_text($phone, $message);

        return redirect()->route('teori.index')->with('success', 'Praktek berhasil dibatalkan ❌');
    }
}
