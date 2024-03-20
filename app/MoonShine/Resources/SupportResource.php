<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use Illuminate\Database\Eloquent\Model;
use App\Models\Support;

use MoonShine\Resources\ModelResource;
use MoonShine\Decorations\Block;
use MoonShine\Fields\ID;
use MoonShine\Fields\Relationships\BelongsTo;
use MoonShine\Fields\Text;

/**
 * @extends ModelResource<Support>
 */
class SupportResource extends ModelResource
{
    protected string $model = Support::class;

    protected array $with = ['participant'];

    public function getActiveActions(): array
    {
        return ['view'];
    }

    public function title(): string
    {
        return __('panel.menu.support');
    }

    public function fields(): array
    {
        return [
            Block::make([
                ID::make()->sortable(),
                BelongsTo::make(__('panel.fields.participant'), 'participant', fn($item) => "$item->name $item->surname $item->phone_number", new ParticipantResource),
                Text::make(__('panel.fields.message'), 'question'),
                Text::make(__('panel.fields.created_at'), 'created_at')->sortable()
            ]),
        ];
    }

    public function rules(Model $item): array
    {
        return [];
    }
}
