<?php

namespace App\Services\Commands\User;

use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Entities\Keyboard;
use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Exception\TelegramException;
use Throwable;
use Longman\TelegramBot\Request;

/**
 * Start command
 */
class StartCommand extends UserCommand
{

    /**
     * @var string
     */
    protected $name = 'poll';

    /**
     * @var string
     */
    protected $description = 'Poll command';

    /**
     * @var string
     */
    protected $usage = '/poll';

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

        $sender = '@' . $message->getFrom()->getUsername();

        if ($poll_answer = $this->getUpdate()->getPollAnswer())
        {
            $poll_options_id = $poll_answer->option_ids();

            if ($poll_options_id[0] == 0){
                return $this->replyToChat('
               **Correct Answer!**
                ', ['parse_mode' => 'markdown',]);
            } else {
                return $this->replyToChat('
               **Wrong Answer!**
                ', ['parse_mode' => 'markdown',]);
            }
        }
        else{

            $options =  array("Mission Impossible 5","Oblivion", "Top Gun 2");

            return Request::sendPoll([
                'chat_id' => $message->getFrom()->getId(),
                'question' => "What is the latest movie from Tom Cruise?",
                'options'   => json_encode($options),
                'type' => 'quiz',
                'correct_option_id' => "0",
                'open_period' => 2
            ]);
        }
    }

}
