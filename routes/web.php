<?php

use App\Http\Controllers\TelegrammController;
use App\Models\Participant;
use App\Models\QrCode as ModelsQrCode;
use App\Models\UserChat;
use App\Models\UserLanguage;
use Illuminate\Support\Facades\Route;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Intervention\Image\ImageManager;
use MoonShine\MoonShineAuth;
use Illuminate\Http\Request;
use MoonShine\Models\MoonshineUser;
use TgWebValid\Exceptions\BotException;
use TgWebValid\Exceptions\ValidationException;
use TgWebValid\TgWebValid;

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
        ->place($fileName, 'bottom-left', 220, 134)
        ->toPng()
        ->save($fileName);
    return response()->file($fileName)->deleteFileAfterSend();
})->name('qrCode');

Route::group(['excluded_middleware' => ['web']], function () {
    Route::post('/webhook/telegram', [TelegrammController::class, 'webhook'])->name('webhook.telegram');
});

Route::get('/set/webhook/telegram', [TelegrammController::class, 'setWebhook']);
Route::get('/delete/webhook/telegram', [TelegrammController::class, 'deleteWebhook']);
Route::get('/info/webhook/telegram', [TelegrammController::class, 'infoWebhook']);

Route::get('/tg', function () {
    if(MoonShineAuth::guard()->check()) {
        $telegram_id = MoonShineAuth::guard()->user()->email;
        $language = UserLanguage::where('telegram_id', $telegram_id)->first()?->language??'ru';
        return redirect()->route('moonshine.index', ['change-moonshine-locale' => $language]);
    }
    return view('telegram');
})->name('telegram');

Route::post('/telegram/user/exists', function (Request $request) {
    $auth = $request->all()['data'];
    try {
        $tgWebValid = new TgWebValid(config('telegram.bot_api_key'), true);
        $initData = $tgWebValid->bot()->validateInitData($auth);
        $telegram_id = $initData->user->id;
        logger()->debug($telegram_id.'');
        if($participant = Participant::where('telegram_id', $telegram_id)->first()) {
            $user = MoonshineUser::firstOrCreate(
                ['email' => $telegram_id],
                ['moonshine_user_role_id' => 2, 'password' => 'no', 'name' => $participant->name]
            );
            $language = UserLanguage::where('telegram_id', $telegram_id)->first()?->language??'ru';
            MoonShineAuth::guard()->loginUsingId($user->id, true);
            session()->regenerate();
            return redirect()->route('moonshine.index', ['change-moonshine-locale' => $language]);
        }
    } catch (ValidationException|BotException|Exception $e) {
        logger()->error($e->getMessage());
    }
    return redirect()->back()->with('error', 'error');
})->name('telegram.user.exists');
