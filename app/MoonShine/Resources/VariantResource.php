<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use Illuminate\Database\Eloquent\Model;
use App\Models\Variant;

use MoonShine\Resources\ModelResource;
use MoonShine\Decorations\Block;
use MoonShine\Enums\PageType;
use MoonShine\Fields\ID;
use MoonShine\Fields\Relationships\BelongsTo;
use MoonShine\Fields\Text;

/**
 * @extends ModelResource<Variant>
 */
class VariantResource extends ModelResource
{
    protected string $model = Variant::class;

    protected ?PageType $redirectAfterSave = PageType::INDEX;

    protected string $sortDirection = 'ASC';

    public function getActiveActions(): array
    {
        return ['create', 'view', 'update'];
    }

    public function title(): string
    {
        return __('panel.menu.variants');
    }

    public function fields(): array
    {
        return [
            Block::make([
                ID::make()->sortable(),
                BelongsTo::make(__('panel.fields.question'), 'question', resource: new QuestionResource)
                    ->hideOnForm(),
                Text::make(__('panel.fields.title_kk'), 'title_kk')
                    ->required(),
                Text::make(__('panel.fields.title_ru'), 'title_ru')
                    ->required(),
            ]),
        ];
    }

    public function rules(Model $item): array
    {
        return [
            'title_kk' => 'required',
            'title_ru' => 'required',
        ];
    }
}
