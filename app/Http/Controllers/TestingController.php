<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Silvanix\Wablas\Message;

class TestingController extends Controller
{
    public function index()
    {

    }

    public function kirimPesanKeResha()
    {
        $user = User::findOrFail(Auth::user()->id);

        $send = new Message();

        $phone = '085201690011';

        $message = 'Halo, Kak ' . ucwords($user->name) .
        '\nIni merupakan pesan otomatis dari sistem';

        $send_text = $send->single_text($phone,$message);

        dd($send_text);

        return view('universal.testing', compact('send_text'));
    }
}
