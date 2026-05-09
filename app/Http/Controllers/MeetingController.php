<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MeetingController extends Controller
{
    public function dashboard()
    {
        return view('meeting.dashboard');
    }

    public function create()
    {
        $roomId = Str::upper(Str::random(3)) . '-' . Str::upper(Str::random(4)) . '-' . Str::upper(Str::random(3));
        return view('meeting.create', compact('roomId'));
    }

    public function join()
    {
        return view('meeting.join');
    }

    public function room(Request $request, string $roomId)
    {
        $nickname = $request->query('nickname', 'Guest');
        return view('meeting.room', compact('roomId', 'nickname'));
    }
}