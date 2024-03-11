<?php

namespace App\Services\Buttons;

use App\Services\Entities\TelegramButton;
use Longman\TelegramBot\Entities\CallbackQuery;
use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Exception\TelegramException;
use Longman\TelegramBot\Request;

class Contact extends TelegramButton
{
    protected string $buttonKey = 'contact';

    protected string $buttonText = '';

    public function __construct(
        protected string $value = ''
    ) {
        parent::__construct($value);
        $this->buttonText = __('panel.messages.share_contact');
    }

    /**
     * @throws TelegramException
     */
    public function handle(CallbackQuery $query): ServerResponse
    {
        $message = $query->getMessage();
        $accountInfo = $message->getChat();
        return Request::sendContact([
            'chat_id' => $accountInfo->getId(),
            'reply_markup' => true
        ]);
    }
}
