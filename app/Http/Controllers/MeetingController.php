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

    public function signal(Request $request)
    {
        $data    = $request->all();
        $room    = $data['room'] ?? null;

        if (!$room) {
            return response()->json(['error' => 'No room'], 400);
        }

        $appId      = env('PUSHER_APP_ID');
        $appKey     = env('PUSHER_APP_KEY');
        $appSecret  = env('PUSHER_APP_SECRET');
        $appCluster = env('PUSHER_APP_CLUSTER', 'ap1');

        $channel   = 'room-' . $room;
        $eventName = 'signal';

        // Build the body
        $body = json_encode([
            'name'     => $eventName,
            'channel'  => $channel,
            'data'     => json_encode(['data' => $data]),
        ]);

        $timestamp = time();
        $md5body   = md5($body);
        $path      = "/apps/{$appId}/events";

        // Query params must be sorted alphabetically
        $params = [
            'auth_key'       => $appKey,
            'auth_timestamp' => $timestamp,
            'auth_version'   => '1.0',
            'body_md5'       => $md5body,
        ];
        ksort($params);
        $queryString = http_build_query($params);

        // Build string to sign
        $stringToSign = "POST\n{$path}\n{$queryString}";
        $authSignature = hash_hmac('sha256', $stringToSign, $appSecret);

        $url = "https://api-{$appCluster}.pusher.com{$path}?{$queryString}&auth_signature={$authSignature}";

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        return response()->json(['ok' => true, 'pusher' => $response, 'code' => $httpCode]);
    }
}