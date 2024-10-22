<?php

namespace App\Http\Controllers;

use App\Models\Practice;
use App\Models\PracticeTalent;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Silvanix\Wablas\Message;

class TalentController extends Controller
{
    public function dashboard()
    {
        $user = auth()->user();

        $practices_soon = PracticeTalent::where('user_id', $user->id)
            ->whereHas('practice', function ($query) {
                $query->where('session_date', '>=', Carbon::today());
            })
            ->get();

        $practices_history = PracticeTalent::where('user_id', $user->id)
            ->join('practices', 'practice_talent.practice_id', '=', 'practices.id') // Menggabungkan dengan tabel practices
            ->where('practices.session_date', '<', Carbon::today())
            ->orderBy('practices.session_date', 'desc') // Urutkan berdasarkan session_date dari tabel practices
            ->limit(3)
            ->get();

        $lastPotong = PracticeTalent::where('user_id', $user->id)
            ->join('practices', 'practice_talent.practice_id', '=', 'practices.id') // Menggabungkan dengan tabel practices
            ->orderBy('practices.session_date', 'desc') // Mengurutkan berdasarkan session_date dari tabel practices
            ->first();

        return view('talent.home', compact('user', 'practices_soon', 'practices_history', 'lastPotong'));
    }

    public function riwayatPotong()
    {
        $user = auth()->user();

        $practices_history = PracticeTalent::where('user_id', $user->id)
            ->whereHas('practice', function ($query) {
                $query->where('session_date', '<', Carbon::today());
            })
            ->with(['practice' => function ($query) {
                $query->orderBy('session_date', 'desc');
            }])
            ->paginate(10);

        return view('talent.riwayat-potong', compact('practices_history'));
    }

    public function cariJadwal()
    {
        $practices_available = Practice::where('session_date', '>=', Carbon::today())
            ->where('session_date', '>', Carbon::today()) // Untuk mendapatkan praktek dengan tanggal lebih dari hari ini
            ->whereHas('talents', function ($query) {
                $query->havingRaw('COUNT(*) < 3'); // Menghitung jumlah talent dan hanya mengambil praktek yang jumlahnya kurang dari 3
            }, '<', 3)
            ->withCount('talents') // Untuk menghitung jumlah talents yang terhubung ke setiap practice
            ->orderBy('session_date', 'asc')
            ->paginate(6); // Pagination 6 per halaman

        return view('talent.cari-jadwal', compact('practices_available'));
    }

    public function pilihJadwal($id)
    {
        $practice = Practice::findOrFail($id);

        return view('talent.pilih-jadwal', compact('practice'));
    }

    public function storeJadwal(Request $request, string $id)
    {
        $request->validate([
            'session_time' => 'required',
        ]);

        $practice = Practice::findOrFail($id);

        switch ($request->session_time) {
            case 'talent_1':
                if (is_null($practice->talent_1)) {
                    $practice->talent_1 = auth()->user()->id;
                } else {
                    return back()->withErrors(['session_time' => 'Maaf, sesi baru saja diambil orang lain, mohon pilih sesi yang lainnya.']);
                }
                break;
            case 'talent_2':
                if (is_null($practice->talent_2)) {
                    $practice->talent_2 = auth()->user()->id; // Mengisi talent_2 dengan ID user yang login
                } else {
                    return back()->withErrors(['session_time' => 'Maaf, sesi baru saja diambil orang lain, mohon pilih sesi yang lainnya.']);
                }
                break;
            case 'talent_3':
                if (is_null($practice->talent_3)) {
                    $practice->talent_3 = auth()->user()->id; // Mengisi talent_3 dengan ID user yang login
                } else {
                    return back()->withErrors(['session_time' => 'Maaf, sesi baru saja diambil orang lain, mohon pilih sesi yang lainnya.']);
                }
                break;
            default:
                return back()->with('error', 'Sesi tidak valid');
        }

        $practice->save();
        $sessionNumber = $request->session_time === 'talent_1' ? 1 : ($request->session_time === 'talent_2' ? 2 : 3);
        $practice->talents()->attach(auth()->user()->id, ['session_time' => $sessionNumber]);

        // Kirim pesan WhatsApp ke user
        $user = auth()->user();
        $phone = $user->phone_number;
        $dayOfWeek = Carbon::parse($practice->session_date)->locale('id')->isoFormat('dddd');
        $formattedDate = Carbon::parse($practice->session_date)->format('d/m/Y');

        function getWaktuSesi($sessionTime, $sessionNumber)
        {
            $waktu = '';

            if ($sessionTime == 'siang') {
                switch ($sessionNumber) {
                    case 1:
                        $waktu = '14.00 - 15.00';
                        break;
                    case 2:
                        $waktu = '15.00 - 16.00';
                        break;
                    case 3:
                        $waktu = '16.00 - 17.00';
                        break;
                }
            } elseif ($sessionTime == 'malam') {
                switch ($sessionNumber) {
                    case 1:
                        $waktu = '18.30 - 19.30';
                        break;
                    case 2:
                        $waktu = '19.30 - 20.30';
                        break;
                    case 3:
                        $waktu = '20.30 - 21.30';
                        break;
                }
            }

            return $waktu;
        }

        $message = 'Halo, Kak ' . ucwords($user->name) . "\n\n";
        $message .= "Terima kasih sudah memilih jadwal potong rambut.\n\n";
        $message .= "Berikut merupakan rincian jadwal yang telah dibuat:\n";
        $message .= 'Hari : ' . $dayOfWeek . "\n";
        $message .= 'Tanggal : ' . $formattedDate . "\n";

        $waktu = getWaktuSesi($practice->session_time, $sessionNumber);
        $message .= 'Waktu : ' . $waktu . "\n";

        $message .= "\nJangan lupa untuk hadir sesuai jadwal yang sudah dipilih. Terima kasih!";

        // Kirim pesan
        $send = new Message();
        $send_text = $send->single_text($phone, $message);

        return redirect()->route('talent.home')->with('success', 'Jadwal berhasil dibuat 😎');
    }
}
