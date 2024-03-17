<?php

namespace App\Services\Keyboards\ConfirmationKeyboard;

use App\Services\Entities\TelegramButton;
use App\Support\Traits\Makeable;
use Longman\TelegramBot\Entities\CallbackQuery;
use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Request;

class YesButton extends TelegramButton
{
    use Makeable;

    protected string $buttonKey = 'yes-button';

    protected string $buttonText = 'Девушка';

    /**
     * @throws TelegramException
     */
    public function handle(CallbackQuery $query): ServerResponse
    {
        // $this->telegram
        return Request::sendMessage([
            'chat_id' => $query->getMessage()->getChat()->getId(),
            'text' => 'ttt',
        ]);
    }

    public function setText(string $buttonText): self
    {
        $this->buttonText = $buttonText;
        return $this;
    }
}
