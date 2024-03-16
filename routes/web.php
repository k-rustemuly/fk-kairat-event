<?php

use App\Http\Controllers\TelegrammController;
use Illuminate\Support\Facades\Route;
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
    $fileName = public_path('images/test.png');
    QrCode::format('png')->size(900)->generate('Your QR Code Data Here', $fileName);
    $manager = new ImageManager(
        new Intervention\Image\Drivers\Gd\Driver()
    );
    $manager->read(public_path('images/ru/invite.png'))
        ->place(public_path('images/test.png'), 'bottom-left', 220, 1480)
        ->toPng()
        ->save($fileName);
    return response()->file($fileName)->deleteFileAfterSend();
});

Route::group(['excluded_middleware' => ['web']], function () {
    Route::post('/webhook/telegram', [TelegrammController::class, 'webhook'])->name('webhook.telegram');
});

Route::get('/set/webhook/telegram', [TelegrammController::class, 'setWebhook']);
