<?php

namespace App\Services\Keyboards\MenuKeyboard;

use App\Services\Keyboards\TelegramKeyboard;
use Longman\TelegramBot\Entities\InlineKeyboard;
use Longman\TelegramBot\Entities\Keyboard;

class MenuKeyboard extends TelegramKeyboard
{
    public function buildKeyboard(string $value = ''): Keyboard
    {
        return new InlineKeyboard(
            [$this->inlineButton(SchemeButton::make()->setText(__('panel.telegram.scheme')))],
            [$this->inlineButton(SupportButton::make()->setText(__('panel.telegram.support')))],
        );
    }
}
