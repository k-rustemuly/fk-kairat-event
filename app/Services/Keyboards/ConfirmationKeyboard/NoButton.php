<?php

namespace App\Services\Keyboards\ConfirmationKeyboard;

use App\Services\Entities\TelegramButton;
use Longman\TelegramBot\Conversation;
use Longman\TelegramBot\Entities\CallbackQuery;
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
        $user    = $message->getFrom();
        $chat_id = $chat->getId();
        $user_id = $user->getId();
        $conversation = new Conversation($user_id, $chat_id, 'start');

        $notes = &$conversation->notes;
        $notes['is_active'] = false;
        $notes['state'] = 5;
        $conversation->update();
        return $this->telegram->executeCommand('start');
    }

}
