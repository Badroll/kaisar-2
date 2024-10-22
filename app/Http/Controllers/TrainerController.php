<?php

namespace App\Http\Controllers;

use App\Models\Practice;
use App\Models\Teori;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class TrainerController extends Controller
{
    public function dashboard()
    {
        $today_theories = Teori::where('status', '<>', 'canceled')->where('session_date', now()->toDateString())->get();
        $tomorrow_theories = Teori::where('status', '<>', 'canceled')->where('session_date', now()->addDay()->toDateString())->get();
        $today_practices = Practice::where('status', '<>', 'canceled')->where('session_date', now()->toDateString())->get();
        $tomorrow_practices = Practice::where('status', '<>', 'canceled')->where('session_date', now()->addDay()->toDateString())->get();

        return view('trainer.home', compact('today_theories', 'tomorrow_theories', 'today_practices', 'tomorrow_practices'));
    }

    public function searchSchedule(Request $request)
    {
        $date = $request->input('date');

        // Ambil data teori berdasarkan tanggal
        $theories = Teori::where('status', '<>', 'canceled')->whereDate('session_date', $date)->get()->map(function ($theory) {
            return [
                'name' => $theory->course->user->name,
                'class_name' => $theory->course->class->name,
                'photo_path' => $theory->course->user->photo_path,
            ];
        });

        // Ambil data praktek berdasarkan tanggal
        $practices = Practice::where('status', '<>', 'canceled')->whereDate('session_date', $date)->get()->map(function ($practice) {
            return [
                'name' => $practice->course->user->name,
                'session_time' => $practice->session_time,
                'photo_path' => $practice->course->user->photo_path,
                'talents' => $practice->talents->map(function ($talent) {
                    return [
                        'name' => $talent->name,
                        'session_time' => $talent->pivot->session_time, // Misalnya ada session_time di pivot table
                    ];
                }),
            ];
        });

        // Return data dalam format JSON
        return response()->json([
            'theories' => $theories,
            'practices' => $practices,
        ]);
    }
}
