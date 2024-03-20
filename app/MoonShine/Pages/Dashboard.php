<?php

declare(strict_types=1);

namespace App\MoonShine\Pages;

use App\Models\Conversation;
use App\Models\Participant;
use App\Models\Question;
use MoonShine\ActionButtons\ActionButton;
use MoonShine\Components\FormBuilder;
use MoonShine\Decorations\Heading;
use MoonShine\Fields\Hidden;
use MoonShine\Fields\Text;
use MoonShine\Metrics\DonutChartMetric;
use MoonShine\MoonShineAuth;
use MoonShine\MoonShineRequest;
use MoonShine\MoonShineUI;
use MoonShine\Pages\Page;

class Dashboard extends Page
{

    public $isUser = false;
    protected function booted(): void
    {
        $this->isUser = MoonShineAuth::guard()->user()?->moonshine_user_role_id == 2;
    }

    public function breadcrumbs(): array
    {
        return [
            '#' => $this->title()
        ];
    }

    public function title(): string
    {
        return $this->isUser ? __('panel.menu.questions') : 'Dashboard';
    }

    public function components(): array
	{
        if($this->isUser) {
            if($question = Question::whereNotNull('start')->whereNull('end')->first()) {
                $telegram_id = MoonShineAuth::guard()->user()->email;
                if(Participant::where('telegram_id', $telegram_id)->whereNull('q'.$question->id)->first()) {
                    $variants = $question->variants;
                    return [
                        FormBuilder::make(
                            route('moonshine.async.method', ['pageUri' => $this->uriKey(), 'method' => 'answer'])
                        )
                            ->fields([
                                Heading::make($question->title),
                            ])
                            ->when($variants->isEmpty(),
                                fn($form) => $form->fields([
                                    Heading::make($question->title),
                                    Hidden::make('q_id')->setValue($question->id),
                                    Text::make('', 'answer')
                                        ->required()
                                        ->mask('999')
                                ])->async()
                            )->when(!$variants->isEmpty(),
                                fn($form) => $form->hideSubmit()
                            )
                            ->buttons($variants->map(function($variant) use($question){
                                return ActionButton::make($variant->title)
                                    ->primary()
                                    ->method(
                                        'answer',
                                        [
                                            'q_id' => $question->id,
                                            'answer' => $variant->id
                                        ],
                                        page: $this);
                            })->toArray())
                    ];
                }
            }
            return [
                ActionButton::make(__('panel.buttons.refresh'), route('moonshine.index'))->icon('heroicons.arrow-path')
                    ->success(),
                Heading::make(__('panel.messages.refresh')),
            ];
        }

		return [
            DonutChartMetric::make(__('panel.messages.participants'))
                ->values([
                    __('panel.messages.registered') => Participant::count(),
                    __('panel.messages.not_registered') => Conversation::notRegistered()->count()
                ])
        ];

	}

    public function answer(MoonShineRequest $request)
    {
        if($question = Question::find($request->q_id)) {
            if($question->start <= now() && is_null($question->end)) {
                $telegram_id = MoonShineAuth::guard()->user()->email;
                if($participant = Participant::where('telegram_id', $telegram_id)->first()) {
                    if(is_null($participant->{"q$question->id"})) {
                        $participant->{"q$question->id"} = $request->answer;
                        $participant->{"q$question->id"."_t"} = now();
                        $participant->save();
                        MoonShineUI::toast(__('panel.messages.answered_success'), 'success');
                        return back();
                    }
                }
            }
        }
        MoonShineUI::toast(__('panel.messages.answered_error'), 'error');
        return back();
    }
}
