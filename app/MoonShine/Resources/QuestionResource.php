<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use Illuminate\Database\Eloquent\Model;
use App\Models\Question;
use MoonShine\ActionButtons\ActionButton;
use MoonShine\Components\FormBuilder;
use MoonShine\Resources\ModelResource;
use MoonShine\Decorations\Block;
use MoonShine\Decorations\Heading;
use MoonShine\Enums\PageType;
use MoonShine\Fields\Date;
use MoonShine\Fields\ID;
use MoonShine\Fields\Preview;
use MoonShine\Fields\Relationships\HasMany;
use MoonShine\Fields\Text;
use MoonShine\MoonShineRequest;
use MoonShine\MoonShineUI;

/**
 * @extends ModelResource<Question>
 */
class QuestionResource extends ModelResource
{
    protected string $model = Question::class;

    protected ?PageType $redirectAfterSave = PageType::INDEX;

    protected string $sortDirection = 'ASC';

    public function getActiveActions(): array
    {
        return ['create', 'view', 'update'];
    }

    public function title(): string
    {
        return __('panel.menu.questions');
    }

    public function fields(): array
    {
        return [
            Block::make([
                ID::make()->sortable(),
                Text::make(__('panel.fields.title_kk'), 'title_kk')
                    ->required(),
                Text::make(__('panel.fields.title_ru'), 'title_ru')
                    ->required(),
                Date::make(__('panel.fields.start'), 'start')
                    ->withTime()
                    ->hideOnForm(),
                Date::make(__('panel.fields.end'), 'end')
                    ->withTime()
                    ->hideOnForm(),
                HasMany::make(__('panel.menu.variants'), 'variants', resource: new VariantResource)
                    ->hideOnIndex()
                    ->creatable()
                    ->fields([
                        Text::make(__('panel.fields.title_kk'), 'title_kk')
                            ->required(),
                        Text::make(__('panel.fields.title_ru'), 'title_ru')
                            ->required(),
                        Preview::make(__('panel.fields.answers'), formatted: fn($item) => $item->answers()->count()),
                    ])
            ]),
        ];
    }

    public function detailButtons(): array
    {
        return [
            ActionButton::make(__('panel.buttons.start'), item: $this->getItem())
                ->canSee(fn($item) => is_null($item->start))
                ->success()
                ->withConfirm(
                    __('panel.buttons.start'),
                    formBuilder: fn() => FormBuilder::make()
                        ->fields([
                            Heading::make(__('panel.messages.are_you_started'))
                        ])
                        ->buttons([
                            ActionButton::make(__('panel.buttons.start'))
                                ->method('start', ['resourceItem' => $this->getItem()->id])
                        ])
                        ->submit('', [
                            'style' => 'display: none'
                        ])
                ),
            ActionButton::make(__('panel.buttons.end'), item: $this->getItem())
                ->canSee(fn($item) => is_null($item->end) && !is_null($item->start))
                ->error()
                ->withConfirm(
                    __('panel.buttons.end'),
                    formBuilder: fn() => FormBuilder::make()
                        ->fields([
                            Heading::make(__('panel.messages.are_you_ended'))
                        ])
                        ->buttons([
                            ActionButton::make(__('panel.buttons.end'))
                                ->method('end', ['resourceItem' => $this->getItem()->id])
                        ])
                        ->submit('', [
                            'style' => 'display: none'
                        ])
                ),
        ];
    }

    public function rules(Model $item): array
    {
        return [
            'title_kk' => 'required',
            'title_ru' => 'required',
        ];
    }

    public function start(MoonShineRequest $request)
    {
        $question = $request->getResource()->getItem();
        if($question && !Question::whereNotNull('start')->whereNull('end')->where('id', '!=', $question->id)->exists()) {
            $question->start = now();
            $question->save();
            MoonShineUI::toast(__('panel.messages.started'), 'success');
        } else {
            MoonShineUI::toast(__('panel.messages.not_started'), 'error');
        }

        return back();
    }

    public function end(MoonShineRequest $request)
    {
        $question = $request->getResource()->getItem();
        if($question && is_null($question->end) && !is_null($question->start)) {
            $question->end = now();
            $question->save();
            MoonShineUI::toast(__('panel.messages.ended'), 'success');
        } else {
            MoonShineUI::toast(__('panel.messages.not_ended'), 'error');
        }
        return back();
    }
}
