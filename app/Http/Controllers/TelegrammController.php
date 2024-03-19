<?php

namespace App\Http\Controllers;

use App\Services\InfoBot;
use Longman\TelegramBot\Exception\TelegramException;

class TelegrammController extends Controller
{
    /**
     * @throws Throwable
     */
    public function webhook()
    {
        try {
            logger()->debug(now()->toString());
            $bot = InfoBot::makeBot();
            $bot->enableMysql();
            $bot->handle();
        } catch (TelegramException $e) {
            report_app($e);
        }
    }

    public function setWebhook()
    {
        $bot = InfoBot::makeBot();
        $response = $bot->setWebHook(route('webhook.telegram'));
        dd($response);
    }

    public function deleteWebhook()
    {
        $bot = InfoBot::makeBot();
        $response = $bot->deleteWebHook();
        dd($response);
    }

    public function infoWebhook()
    {
        $bot = InfoBot::makeBot();
        dd($bot->infoWebhook());
    }

}
