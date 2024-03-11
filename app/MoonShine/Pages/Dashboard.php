<?php

declare(strict_types=1);

namespace App\MoonShine\Pages;

use App\Models\Conversation;
use MoonShine\Metrics\DonutChartMetric;
use MoonShine\Pages\Page;

class Dashboard extends Page
{
    public function breadcrumbs(): array
    {
        return [
            '#' => $this->title()
        ];
    }

    public function title(): string
    {
        return $this->title ?: 'Dashboard';
    }

    public function components(): array
	{
		return [
            DonutChartMetric::make(__('panel.messages.participants'))
                ->values([
                    __('panel.messages.registered') => Conversation::registered()->count(),
                    __('panel.messages.not_registered') => Conversation::notRegistered()->count()
                ])
        ];
	}
}
