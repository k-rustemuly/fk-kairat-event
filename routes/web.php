<?php

use App\Http\Controllers\TelegrammController;
use Illuminate\Support\Facades\Route;
use Barryvdh\DomPDF\Facade\Pdf;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Intervention\Image\ImageManager;

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
    $qrCode = QrCode::format('png')->size(200)->generate('Your QR Code Data Here');
    $manager = new ImageManager(
        new Intervention\Image\Drivers\Gd\Driver()
    );
    $image = $manager->read(public_path('images/ru/invite.png'));
    $image->place($qrCode, 'bottom-left', 10, 20);
    return response()->file($image->toPng());
});

Route::group(['excluded_middleware' => ['web']], function () {
    Route::post('/webhook/telegram', [TelegrammController::class, 'webhook'])->name('webhook.telegram');
});

Route::get('/set/webhook/telegram', [TelegrammController::class, 'setWebhook']);
