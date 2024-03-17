<?php

namespace App\Services\Keyboards\ConfirmationKeyboard;

use App\Services\Keyboards\TelegramKeyboard;
use Longman\TelegramBot\Entities\InlineKeyboard;
use Longman\TelegramBot\Entities\Keyboard;

class ConfirmationKeyboard extends TelegramKeyboard
{
    public function buildKeyboard(string $value = ''): Keyboard
    {
        return new InlineKeyboard(
            [$this->inlineButton(YesButton::make()->setText(__('panel.telegram.yes')))],
            [$this->inlineButton(NoButton::make()->setText(__('panel.telegram.no')))],
        );
    }
}
