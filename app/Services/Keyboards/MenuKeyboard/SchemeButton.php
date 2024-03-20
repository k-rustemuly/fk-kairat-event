<?php

namespace App\Services\Keyboards\MenuKeyboard;

use App\Services\Entities\TelegramButton;
use Longman\TelegramBot\Entities\CallbackQuery;
use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Request;

class SchemeButton extends TelegramButton
{
    protected string $buttonKey = 'scheme';

    protected string $buttonText = 'No';

    /**
     * @throws TelegramException
     */
    public function handle(CallbackQuery $query): ServerResponse
    {
        $message = $query->getMessage();
        $chat    = $message->getChat();
        $chat_id = $chat->getId();
        $data = [
            'chat_id'      => $chat_id,
            'photo'        => url('scheme.png'),
            'reply_markup' => MenuKeyboard::make()->getKeyboard(),
        ];
        return Request::sendPhoto($data);
    }

}
