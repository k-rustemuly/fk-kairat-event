<?php

use App\Http\Controllers\TelegrammController;
use App\Models\QrCode as ModelsQrCode;
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

Route::get('/qr/{qrCode}/{lang}', function (ModelsQrCode $qrCode, string $lang) {
    $code = $qrCode->code;
    $fileName = public_path('images/'.$code.'.png');
    QrCode::format('png')->size(922)->generate($code, $fileName);
    $manager = new ImageManager(
        new Intervention\Image\Drivers\Gd\Driver()
    );
    $manager->read(public_path('images/'.$lang.'/invite.png'))
        ->place(public_path('images/test.png'), 'bottom-left', 220, 134)
        ->toPng()
        ->save($fileName);
    return response()->file($fileName)->deleteFileAfterSend();
})->name('qrCode');

Route::group(['excluded_middleware' => ['web']], function () {
    Route::post('/webhook/telegram', [TelegrammController::class, 'webhook'])->name('webhook.telegram');
});

Route::get('/set/webhook/telegram', [TelegrammController::class, 'setWebhook']);
