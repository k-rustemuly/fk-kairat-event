<?php

declare(strict_types=1);

namespace App\MoonShine\Pages;

use App\Models\Conversation;
use App\Models\Participant;
use App\Models\Question;
use MoonShine\ActionButtons\ActionButton;
use MoonShine\Components\FormBuilder;
use MoonShine\Decorations\Heading;
use MoonShine\Metrics\DonutChartMetric;
use MoonShine\MoonShineAuth;
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
                $variants = $question->variants;
                return [
                    FormBuilder::make()
                        ->fields([
                            Heading::make($question->title),
                        ])
                        ->buttons($variants->map(function($variant){
                            return ActionButton::make($variant->title)
                                ->primary();
                        })->toArray())
                ];
            }
            return [];
        }

		return [
            DonutChartMetric::make(__('panel.messages.participants'))
                ->values([
                    __('panel.messages.registered') => Participant::count(),
                    __('panel.messages.not_registered') => Conversation::notRegistered()->count()
                ])
        ];
	}
}
