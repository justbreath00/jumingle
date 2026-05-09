<?php

use App\Http\Controllers\MeetingController;
use Illuminate\Support\Facades\Route;

Route::get('/', [MeetingController::class, 'dashboard'])->name('dashboard');
Route::get('/meeting/create', [MeetingController::class, 'create'])->name('meeting.create');
Route::get('/meeting/join', [MeetingController::class, 'join'])->name('meeting.join');
Route::get('/meeting/room/{roomId}', [MeetingController::class, 'room'])->name('meeting.room');
Route::post('/meeting/signal', [MeetingController::class, 'signal'])->name('meeting.signal');