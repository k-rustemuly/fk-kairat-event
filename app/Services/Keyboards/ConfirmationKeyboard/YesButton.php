<?php

namespace App\Services\Keyboards\ConfirmationKeyboard;

use App\Models\Participant;
use App\Models\QrCode;
use App\Services\Entities\TelegramButton;
use App\Services\InfoBot;
use Longman\TelegramBot\Conversation;
use Longman\TelegramBot\Entities\CallbackQuery;
use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Request;

class YesButton extends TelegramButton
{
    protected string $buttonKey = 'yes-button';

    protected string $buttonText = 'yes';

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
        $notes['is_active'] = true;
        $notes['state'] = 5;
        $conversation->update();
        unset($notes['state']);
        $participant = [
            'telegram_id' => $chat_id,
        ];
        $lastQrCode = QrCode::whereNull('participant_id')
            ->orderBy('created_at', 'asc')
            ->first();
        if($lastQrCode) {
            $participant = Participant::create(array_merge($participant, $notes));
            $lastQrCode->participant_id = $participant->id;
            $lastQrCode->save();
            $result = Request::sendPhoto([
                'chat_id' => $chat_id,
                'photo' => route('qrCode', ['qrCode' => $lastQrCode->id, 'lang' => app()->getLocale()])
            ]);
        } else {
            $data['text'] = __('panel.telegram.registration_closed');
            $result = Request::sendMessage($data);
        }
        $conversation->stop();
        return $result;
    }

}
