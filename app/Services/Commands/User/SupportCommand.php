<?php

namespace App\Services\Commands\User;

use App\Models\Participant;
use App\Models\Support;
use App\Services\Keyboards\MenuKeyboard\MenuKeyboard;
use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Conversation;
use Longman\TelegramBot\Entities\Keyboard;
use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Exception\TelegramException;
use Longman\TelegramBot\Request;
use Throwable;


/**
 * Support command
 */
class SupportCommand extends UserCommand
{

    /**
     * @var string
     */
    protected $name = 'support';

    /**
     * @var string
     */
    protected $description = 'Support command';

    /**
     * @var string
     */
    protected $usage = '/support';

    /**
     * @var string
     */
    protected $version = '1.2.0';

    /**
     * @var bool
     */
    protected $need_mysql = true;

    /**
     * @var bool
     */
    protected $private_only = true;

    /**
     * Conversation Object
     *
     * @var Conversation
     */
    protected $conversation;

    /**
     * @return ServerResponse
     * @throws TelegramException
     * @throws Throwable
     */
    public function execute(): ServerResponse
    {
        $message = $this->getMessage();
        $chat    = $message->getChat();
        $text    = str($message->getText(true))->squish()->value();
        $chat_id = $chat->getId();
        $data = [
            'chat_id'      => $chat_id,
        ];

        if($participant = Participant::where('telegram_id', $chat_id)->first()) {
            Support::create([
                'participant_id' => $participant,
                'question' => $text
            ]);
        }
        $data['text'] = __('panel.telegram.question_accepted');
        $data['reply_markup'] = MenuKeyboard::make()->getKeyboard();
        return Request::sendMessage($data);
    }
}
