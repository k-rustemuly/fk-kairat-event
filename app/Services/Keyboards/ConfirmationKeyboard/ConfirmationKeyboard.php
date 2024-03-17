<?php

namespace App\Services\Keyboards\ConfirmationKeyboard;

use App\Models\Participant;
use App\Models\QrCode;
use App\Services\Keyboards\TelegramKeyboard;
use Longman\TelegramBot\Entities\InlineKeyboard;
use Longman\TelegramBot\Entities\Keyboard;
use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Request;

class ConfirmationKeyboard extends TelegramKeyboard
{
    public function buildKeyboard(string $value = ''): Keyboard
    {
        return new InlineKeyboard(
            [$this->inlineButton(YesButton::make()->setText(__('panel.telegram.yes')))],
            [$this->inlineButton(NoButton::make()->setText(__('panel.telegram.no')))],
        );
    }

    public static function finish(array $data, array $notes): ServerResponse
    {
        unset($notes['state']);
        $participant = [
            'telegram_id' => $data['chat_id'],
        ];
        $lastQrCode = QrCode::whereNull('participant_id')
            ->orderBy('created_at', 'asc')
            ->first();
        if($lastQrCode) {
            $participant = Participant::create(array_merge($participant, $notes));
            $lastQrCode->participant_id = $participant->id;
            $lastQrCode->save();
            $result = Request::sendPhoto(
                array_merge($data, [
                    'photo' => route('qrCode', ['qrCode' => $lastQrCode->id, 'lang' => app()->getLocale()])
                ])
            );
        } else {
            $data['text'] = __('panel.telegram.registration_closed');
            $result = Request::sendMessage($data);
        }
        return $result;
    }
}
