<?php

/**
 * This file is part of the PHP Telegram Bot example-bot package.
 * https://github.com/php-telegram-bot/example-bot/
 *
 * (c) PHP Telegram Bot Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Generic message command
 *
 * Gets executed when any type of message is sent.
 *
 * In this conversation-related context, we must ensure that active conversations get executed correctly.
 */

namespace App\Services\TelegramBots\InfoBot\Commands\System;

use App\Models\Log;
use App\Models\User;
use Longman\TelegramBot\Commands\SystemCommand;
use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Exception\TelegramException;
use Longman\TelegramBot\Request;
use Smalot\PdfParser\Parser;
use Illuminate\Support\Str;
use Longman\TelegramBot\ChatAction;
use Illuminate\Support\Facades\File;

class GenericmessageCommand extends SystemCommand
{
    /**
     * @var string
     */
    protected $name = 'genericmessage';

    /**
     * @var string
     */
    protected $description = 'Handle generic message';

    /**
     * @var string
     */
    protected $version = '1.0.0';

    /**
     * Command execute method if MySQL is required but not available
     *
     * @return ServerResponse
     */
    public function executeNoDb(): ServerResponse
    {
        // Do nothing
        return Request::emptyResponse();
    }

    /**
     * Main command execution
     *
     * @return ServerResponse
     * @throws TelegramException
     */
    public function execute(): ServerResponse
    {
        $message = $this->getMessage();
        $chat    = $message->getChat();
        $chat_id = $chat->getId();
        $message_type = $message->getType();
        $data = [
            'chat_id' => $chat_id,
            'text'    => $message_type
        ];

        if($message_type == 'contact') {
            $contact = $message->getContact();
            $userFullName = str($chat->getFirstName().' '.$chat->getLastName())
                ->squish()
                ->value();
            $phoneNumber = preg_replace('/[^0-9]/', '', $contact->getPhoneNumber());
            $formattedPhoneNumber = preg_replace('/(\d{1})(\d{3})(\d{3})(\d{2})(\d{2})/', '+$1 ($2) $3-$4-$5', $phoneNumber);

            $user = User::firstOrNew(['phone_number' => $formattedPhoneNumber]);
            if ($user->exists) {
                $user->telegram_id = $chat_id;
                $user->save();
            } else {
                $user->fill([
                    'name' => $userFullName,
                    'telegram_id' => $chat_id,
                    'password' => Str::random(8)
                ])->save();
            }

            $data = [
                'chat_id' => $chat_id,
                'text'    => '👇 Нажмите сюда (кнопка слева)',
                'reply_markup' => json_encode(['remove_keyboard' => true]),
            ];
        }
        return Request::sendMessage($data);
    }
}
