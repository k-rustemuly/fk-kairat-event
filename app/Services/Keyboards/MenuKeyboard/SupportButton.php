<?php

namespace App\Services\Keyboards\MenuKeyboard;

use App\Models\Participant;
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
            'reply_markup' => MenuKeyboard::make()->getKeyboard(),
        ];
        if($participant = Participant::with('settings')->where('telegram_id', $chat_id)->first()) {
            return Request::sendPhoto(
                array_merge($data, [
                    'photo' => route('qrCode', ['qrCode' => $participant->qrCode->id, 'lang' => $participant->settings?->language??'ru'])
                ])
            );
        }
        $data['text'] = '*';
        $result = Request::sendMessage($data);
        return $result;
    }

}
