<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DraftkingsController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/draftkings/sport/{sport}/type/{type}', [DraftkingsController::class, 'getPlayers']);
Route::get('/draftkings/sport/{sport}/type/{type}/generate', [DraftkingsController::class, 'generateRoster']);