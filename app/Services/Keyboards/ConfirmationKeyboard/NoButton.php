<?php

namespace App\Services\Keyboards\ConfirmationKeyboard;

use App\Services\Entities\TelegramButton;
use App\Services\InfoBot;
use Longman\TelegramBot\Conversation;
use Longman\TelegramBot\Entities\CallbackQuery;
use Longman\TelegramBot\Entities\ServerResponse;

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
        $conversation = new Conversation($chat_id, $chat_id, 'start');

        $notes = &$conversation->notes;
        $notes['is_active'] = false;
        $notes['state'] = 5;
        $conversation->update();
        $telegram = InfoBot::makeBot()->telegram;
        return $telegram->executeCommand('start');
    }

}
