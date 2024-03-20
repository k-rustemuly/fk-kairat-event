<?php

namespace App\Services\Keyboards\MenuKeyboard;

use App\Services\Entities\TelegramButton;
use Longman\TelegramBot\Entities\CallbackQuery;
use Longman\TelegramBot\Entities\Keyboard;
use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Request;

class SupportButton extends TelegramButton
{
    protected string $buttonKey = 'support';

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
            'text'         => __('panel.telegram.question'),
            'reply_markup' => Keyboard::remove(['selective' => true]),
        ];
        $result = Request::sendMessage($data);
        return $result;
    }

}
