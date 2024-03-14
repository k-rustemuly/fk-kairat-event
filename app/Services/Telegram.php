<?php

namespace App\Services;

use App\Models\UserLanguage;
use Longman\TelegramBot\Entities\Update;
use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Telegram as ParentTelegram;

class Telegram extends ParentTelegram
{

    public function processUpdate(Update $update): ServerResponse
    {
        if($chat_id = $update->getMessage()?->getChat()?->getId()) {
            $appLocale = app()->getLocale();
            $userLanguage = UserLanguage::firstOrCreate(
                ['telegram_id' => $chat_id],
                ['language' => $appLocale]
            );
            if($appLocale != $userLanguage->language) {
                app()->setLocale($userLanguage->language);
            }
        }
        return parent::processUpdate($update);
    }
}
