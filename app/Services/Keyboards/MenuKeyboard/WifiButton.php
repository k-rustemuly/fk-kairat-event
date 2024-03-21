<?php

namespace App\Services\Keyboards\MenuKeyboard;

use App\Models\Participant;
use App\Services\Entities\TelegramButton;
use Longman\TelegramBot\Entities\CallbackQuery;
use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Request;

class WifiButton extends TelegramButton
{
    protected string $buttonKey = 'wifi';

    protected string $buttonText = 'Wi-Fi';

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
            'reply_markup' => MenuKeyboard::make()->getKeyboard(),
        ];
        $data['text'] = 'Wi-Fi: Super_skating_rink Password: Ice2023!';
        $result = Request::sendMessage($data);
        return $result;
    }

}
