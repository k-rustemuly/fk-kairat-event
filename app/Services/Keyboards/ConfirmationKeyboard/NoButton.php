<?php

namespace App\Services\Keyboards\ConfirmationKeyboard;

use App\Services\Entities\TelegramButton;
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
        return Request::sendMessage([
            'chat_id' => $query->getMessage()->getChat()->getId(),
            'text' => 'no',
        ]);
    }

}
