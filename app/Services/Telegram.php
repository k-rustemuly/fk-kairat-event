<?php

namespace App\Services;

use Longman\TelegramBot\Entities\Update;
use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Telegram as ParentTelegram;

abstract class Telegram extends ParentTelegram
{

    public function processUpdate(Update $update): ServerResponse
    {
        logger()->debug($update->getMessage()->getChat()->getId());
        return parent::processUpdate($update);
    }
}
