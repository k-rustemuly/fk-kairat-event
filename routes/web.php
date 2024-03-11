<?php

use App\Http\Controllers\TelegrammController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::group(['excluded_middleware' => ['web']], function () {
    Route::post('/webhook/telegram', [TelegrammController::class, 'webhook'])->name('webhook.telegram');
});

Route::get('/set/webhook/telegram', [TelegrammController::class, 'setWebhook']);
