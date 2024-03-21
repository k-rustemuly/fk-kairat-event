<?php

namespace App\Services\Keyboards\MenuKeyboard;

use App\Services\Keyboards\TelegramKeyboard;
use Longman\TelegramBot\Entities\InlineKeyboard;
use Longman\TelegramBot\Entities\InlineKeyboardButton;
use Longman\TelegramBot\Entities\Keyboard;

class MenuKeyboard extends TelegramKeyboard
{
    public function buildKeyboard(string $value = ''): Keyboard
    {
        return new InlineKeyboard(
            [$this->inlineButton(ScheduleButton::make()->setText(__('panel.telegram.schedule')))],
            [$this->inlineButton(SchemeButton::make()->setText(__('panel.telegram.scheme')))],
            [new InlineKeyboardButton(['text' => __('panel.telegram.support'), 'url' => 'https://t.me/sembayevaamina'])],
            [$this->inlineButton(SupportButton::make()->setText(__('panel.telegram.my_qr')))],
            [$this->inlineButton(WifiButton::make())],
        );
    }
}
