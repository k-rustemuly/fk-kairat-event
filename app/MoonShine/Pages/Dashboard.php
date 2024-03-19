<?php

declare(strict_types=1);

namespace App\MoonShine\Pages;

use App\Models\Conversation;
use App\Models\Participant;
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
