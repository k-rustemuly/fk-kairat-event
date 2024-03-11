<?php

namespace App\Services\Commands\User;

use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Conversation;
use Longman\TelegramBot\Entities\Keyboard;
use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Exception\TelegramException;
use Longman\TelegramBot\Request;
use Throwable;

/**
 * Start command
 */
class StartCommand extends UserCommand
{

    /**
     * @var string
     */
    protected $name = 'start';

    /**
     * @var string
     */
    protected $description = 'Start command';

    /**
     * @var string
     */
    protected $usage = '/start';

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
        $user    = $message->getFrom();
        $text    = str($message->getText(true))->squish()->value();
        $chat_id = $chat->getId();
        $user_id = $user->getId();

        $data = [
            'chat_id'      => $chat_id,
            'reply_markup' => Keyboard::remove(['selective' => true]),
        ];

        $this->conversation = new Conversation($user_id, $chat_id, $this->getName());

        $notes = &$this->conversation->notes;
        !is_array($notes) && $notes = [];

        $state = $notes['state'] ?? 0;

        $result = Request::emptyResponse();
        switch ($state) {
            case 0:
                if ($text === '') {
                    $notes['state'] = 0;
                    $this->conversation->update();
                    $data['text'] = 'Имя:';
                    $result = Request::sendMessage($data);
                    break;
                }

                $notes['name'] = $text;
                $text          = '';
        }
        return $result;
    }

}
