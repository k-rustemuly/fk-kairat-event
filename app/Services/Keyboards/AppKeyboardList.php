<?php

namespace App\Services\Keyboards;

use App\Services\Keyboards\ConfirmationKeyboard\ConfirmationKeyboard;

class AppKeyboardList
{
    private array $keyboards = [
        StartKeyboard::class,
        ConfirmationKeyboard::class
    ];

    /**
     * Get all buttons from keyboards
     *
     * @return array
     */
    public static function getAllButtons(): array
    {
        $keyboardObj = new static();
        $buttons = [];
        foreach ($keyboardObj->keyboards as $item) {

            $keyboard = new $item();

            if(!$keyboard instanceof TelegramKeyboard) {
                continue;
            }

            $buttons = array_merge($buttons, $keyboard->getButtons());
        }

        return $buttons;
    }
}
