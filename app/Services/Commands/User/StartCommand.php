<?php

namespace App\Services\Commands\User;

use App\Models\Participant;
use App\Models\QrCode;
use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Conversation;
use Longman\TelegramBot\Entities\Keyboard;
use Longman\TelegramBot\Entities\ServerResponse;
use Barryvdh\DomPDF\Facade\Pdf;
use Longman\TelegramBot\Exception\TelegramException;
use Longman\TelegramBot\Request;
use Throwable;
use Illuminate\Support\Facades\Validator;
use Longman\TelegramBot\Entities\KeyboardButton;
use SimpleSoftwareIO\QrCode\Facades\QrCode as FacadesQrCode;

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
                    if(Participant::where('telegram_id', $chat_id)->exists()) {
                        $data['text'] = __('panel.telegram.already_exists');
                        $result = Request::sendMessage($data);
                    } else {
                        Request::sendMessage([
                            'chat_id' => $chat_id,
                            'text'    => __('panel.telegram.start_text')
                        ]);
                        $notes['state'] = 0;
                        $this->conversation->update();
                        $data['text'] = __('panel.telegram.name');
                        $result = Request::sendMessage($data);
                    }
                    break;
                }

                $notes['name'] = $text;
                $text          = '';
            case 1:
                if ($text === '') {
                    $notes['state'] = 1;
                    $this->conversation->update();
                    $data['text'] = __('panel.telegram.surname');
                    $result = Request::sendMessage($data);
                    break;
                }

                $notes['surname'] = $text;
                $text             = '';
            case 2:
                $validator = Validator::make(['year' => $text], [
                    'year' => 'date_format:Y|before:today'
                ]);

                if ($text === '' || $validator->fails()) {
                    $notes['state'] = 2;
                    $this->conversation->update();
                    $data['text'] = __('panel.telegram.birth_year');
                    $result = Request::sendMessage($data);
                    break;
                }

                $notes['birth_year'] = $text;
                $text             = '';
            case 3:
                if ($message->getContact() === null) {
                    $notes['state'] = 3;
                    $this->conversation->update();

                    $data['reply_markup'] = (new Keyboard(
                        (new KeyboardButton(__('panel.telegram.share_contact')))->setRequestContact(true)
                    ))
                        ->setOneTimeKeyboard(true)
                        ->setResizeKeyboard(true)
                        ->setSelective(true);

                    $data['text'] = __('panel.telegram.phone_number');
                    if ($text !== '') {
                        $data['text'] = __('panel.telegram.share_contact_please');
                    }
                    $result = Request::sendMessage($data);
                    break;
                }

                $notes['phone_number'] = $message->getContact()->getPhoneNumber();
                $text             = '';

            case 4:
                if ($text === '' || !in_array($text, ['✔', '✖'], true)) {
                    $notes['state'] = 4;
                    $this->conversation->update();

                    $data['reply_markup'] = (new Keyboard(['✔', '✖']))
                        ->setResizeKeyboard(true)
                        ->setOneTimeKeyboard(true)
                        ->setSelective(true);

                    $data['text'] = __('panel.telegram.active_confirm');
                    if ($text !== '') {
                        $data['text'] = __('panel.telegram.choose');
                    }
                    $result = Request::sendMessage($data);
                    break;
                }

                $notes['is_active'] = $text === '✔';
                $text          = '';
            case 5:
                $this->conversation->update();
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

                    $qrCode =  base64_encode(
                        FacadesQrCode::size(500)
                            ->color(255, 255, 255)
                            ->backgroundColor(0, 46, 94)
                            ->generate($lastQrCode->code)
                    );
                    $pdf = Pdf::loadView('invitation', compact('qrCode'))->setPaper('A4');
                    $data['document'] = [
                        'file' => $pdf->output(),
                        'filename' => 'invitation.pdf'
                    ];
                    $result = Request::sendDocument($data);
                } else {
                    $data['text'] = __('panel.telegram.register_finish');
                    $result = Request::sendMessage($data);
                }
                $this->conversation->stop();
                break;
        }
        return $result;
    }

}
