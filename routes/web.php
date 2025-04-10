<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FootballMatchController;

Route::get('/', [FootballMatchController::class, 'index']);
Route::get('/api/matches', [FootballMatchController::class, 'getUpdates']);
