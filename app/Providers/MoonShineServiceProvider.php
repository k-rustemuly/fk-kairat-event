<?php

declare(strict_types=1);

namespace App\Providers;

use App\MoonShine\Resources\QrCodeResource;
use App\MoonShine\Resources\QuestionResource;
use App\MoonShine\Resources\UserRoleResource;
use App\MoonShine\Resources\VariantResource;
use Illuminate\Http\Request;
use MoonShine\Providers\MoonShineApplicationServiceProvider;
use MoonShine\Menu\MenuGroup;
use MoonShine\Menu\MenuItem;
use MoonShine\Resources\MoonShineUserResource;

class MoonShineServiceProvider extends MoonShineApplicationServiceProvider
{
    public function boot(): void
    {
        parent::boot();

        moonshineAssets()->add([
            'https://telegram.org/js/telegram-web-app.js',
            'js/telegram.js',
        ]);
    }

    protected function resources(): array
    {
        return [
            new VariantResource()
        ];
    }

    protected function pages(): array
    {
        return [];
    }

    protected function menu(): array
    {
        return [
            MenuGroup::make(static fn() => __('moonshine::ui.resource.system'), [
                MenuItem::make(
                    static fn() => __('moonshine::ui.resource.admins_title'),
                    new MoonShineUserResource()
                ),
                MenuItem::make(
                    static fn() => __('moonshine::ui.resource.role_title'),
                    new UserRoleResource()
                ),
            ])->canSee(fn(Request $request) => $request->user()->moonshine_user_role_id == 1),

            MenuItem::make(
                static fn() => __('panel.menu.qr_codes'),
                new QrCodeResource(),
                'heroicons.qr-code'
            )->canSee(fn(Request $request) => $request->user()->moonshine_user_role_id == 1),

            MenuItem::make(
                static fn() => __('panel.menu.questions'),
                new QuestionResource(),
            )->canSee(fn(Request $request) => $request->user()->moonshine_user_role_id == 1),
        ];
    }

    /**
     * @return array{css: string, colors: array, darkColors: array}
     */
    protected function theme(): array
    {
        return [];
    }
}
