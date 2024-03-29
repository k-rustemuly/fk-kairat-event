<?php

namespace App\Services\Entities;

use App\Support\Traits\Makeable;
use Longman\TelegramBot\Entities\CallbackQuery;
use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Telegram;

/**
 * Buttons class
 * All buttons are added here
 * @see CallbackqueryCommand::addCallbackHandler()
 */
abstract class TelegramButton implements BotFunctionInterface
{
    use Makeable;

    protected ?Telegram $telegram;

    protected string $buttonKey = '';

    protected string $buttonText = '';

    public function __construct(
        protected string $value = ''
    ) {}

    abstract public function handle(CallbackQuery $query): ServerResponse;

    public function setText(string $buttonText): self
    {
        $this->buttonText = $buttonText;
        return $this;
    }

    public function getValue(CallbackQuery $query): string
    {
        return str_replace($this->buttonKey, '', $query->getData());
    }

    public function data(): array
    {
        return [
            'callback_data' => !empty($this->value)
                ? $this->buttonKey.$this->value
                : $this->buttonKey,

            'text' => $this->buttonText,
        ];
    }

    public function buttonKey(): string
    {
        return $this->buttonKey;
    }

    /**
     * Return the handle() method call function from the TelegramButton class
     *
     * @return callable
     */
    public static function getHandleFunction(): callable
    {
        return function (CallbackQuery $query) {
            $instance = new static();

            if(!str_contains($query->getData(), $instance->buttonKey())) {
                return null;
            }

            return $instance->handle($query);
        };
    }
}
