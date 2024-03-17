<?php

namespace App\Services\Keyboards\ConfirmationKeyboard;

use App\Models\Participant;
use App\Services\Entities\TelegramButton;
use Longman\TelegramBot\Conversation;
use Longman\TelegramBot\Entities\CallbackQuery;
use Longman\TelegramBot\Entities\Keyboard;
use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Request;

class NoButton extends TelegramButton
{
    protected string $buttonKey = 'no-button';

    protected string $buttonText = 'No';

    /**
     * @throws TelegramException
     */
    public function handle(CallbackQuery $query): ServerResponse
    {
        $message = $query->getMessage();
        $chat    = $message->getChat();
        $chat_id = $chat->getId();
        if(Participant::where('telegram_id', $chat_id)->first()) {
            $data['text'] = __('panel.telegram.already_exists');
            return Request::sendMessage($data);
        }
        $conversation = new Conversation($chat_id, $chat_id, 'start');

        $notes = &$conversation->notes;
        $notes['is_active'] = false;
        $notes['state'] = 5;
        $conversation->update();
        $data = [
            'chat_id'      => $chat_id,
            'reply_markup' => Keyboard::remove(['selective' => true]),
        ];
        $result = ConfirmationKeyboard::finish($data, $notes);
        $conversation->stop();
        return $result;
    }

}
