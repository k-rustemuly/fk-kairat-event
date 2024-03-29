<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use Illuminate\Database\Eloquent\Model;
use App\Models\Participant;
use App\Models\Question;
use Illuminate\Notifications\Action;
use MoonShine\ActionButtons\ActionButton;
use MoonShine\Resources\ModelResource;
use MoonShine\Decorations\Block;
use MoonShine\Enums\PageType;
use MoonShine\Fields\Date;
use MoonShine\Fields\ID;
use MoonShine\Fields\Relationships\HasMany;
use MoonShine\Fields\Select;
use MoonShine\Fields\Switcher;
use MoonShine\Fields\Text;
use MoonShine\Handlers\ExportHandler;
use MoonShine\Metrics\ValueMetric;

/**
 * @extends ModelResource<Participant>
 */
class ParticipantResource extends ModelResource
{
    protected string $model = Participant::class;

    protected string $sortDirection = 'ASC';

    protected ?PageType $redirectAfterSave = PageType::INDEX;

    protected array $with = [
        'q1b',
        'q2b',
        'q3b',
        'q4b',
        'q5b',
        'q6b',
        'q7b',
        'q8b',
        'q9b',
        'q10b',
        'q11b',
        'settings',
        'qrCode'
    ];

    public function getActiveActions(): array
    {
        return ['view', 'update'];
    }

    protected bool $saveFilterState = true;

    public function title(): string
    {
        return __('panel.menu.participants');
    }

    public function fields(): array
    {

        $questionFields = Question::with('variants')->get()->flatMap(function($question) {
            return [
                Text::make(
                    __('panel.fields.question', ['q' => $question->id]),
                    'q'.$question->id,
                    fn($item) => $item->{"q$question->id".'b'}?->title??$item->{"q$question->id".'b'}
                )
                ->showOnExport()
                ->hideOnForm(),
                Date::make(__('panel.fields.question_time', ['q' => $question->id]), 'q'.$question->id.'_t')
                    ->sortable()
                    ->withTime()
                    ->format('H:i:s')
                    ->showOnExport()
                    ->hideOnForm()
            ];
        })->toArray();
        return [
            Block::make([
                ID::make()->sortable()->showOnExport(),
                Text::make(__('panel.fields.name'), 'name')->showOnExport(),
                Text::make(__('panel.fields.surname'), 'surname')->showOnExport(),
                Text::make(__('panel.fields.birth_year'), 'birth_year')->showOnExport(),
                Text::make(__('panel.fields.phone_number'), 'phone_number')->showOnExport()->hideOnForm(),
                Switcher::make(__('panel.fields.is_active'), 'is_active')->showOnExport(),
                ...$questionFields,
                HasMany::make(__('panel.fields.supports'), 'supports', resource: new SupportResource)
                    ->hideOnForm()
                    ->hideOnIndex()
                    ->fields([
                        Text::make(__('panel.fields.message'), 'question'),
                        Text::make(__('panel.fields.created_at'), 'created_at')
                    ])
            ]),
        ];
    }

    public function rules(Model $item): array
    {
        return [];
    }

    public function filters(): array
    {
        $questionFields = Question::with('variants')->get()->map(function($question) {
            return
                $question->variants->isEmpty()
                ? Text::make(__('panel.fields.question', ['q' => $question->id]), 'q'.$question->id)
                    ->hint($question->title)
                : Select::make(
                        __('panel.fields.question', ['q' => $question->id]),
                        'q'.$question->id
                    )
                    ->hint($question->title)
                    ->nullable()
                    ->options($question->variants->reduce(function($carry, $var) {
                        $carry[$var->id] = $var->title;
                        return $carry;
                    }, []));
        })->toArray();
        return [
            Text::make(__('panel.fields.name'), 'name'),
            Text::make(__('panel.fields.surname'), 'surname'),
            Text::make(__('panel.fields.birth_year'), 'birth_year'),
            Text::make(__('panel.fields.phone_number'), 'phone_number'),
            Select::make(__('panel.fields.is_active'), 'is_active')
                ->nullable()
                ->options([
                    1 => __('panel.telegram.yes'),
                ]),
            ...$questionFields,
        ];
    }

    public function getIndexPageComponents(): array
    {
        return [
            ValueMetric::make(__('panel.menu.participants'))
                ->value($this->items()->count())
        ];
    }

    public function export(): ?ExportHandler
    {
        return ExportHandler::make(__('moonshine::ui.export'));
    }

    /**
     * @return array<ActionButton>
     */
    public function buttons(): array
    {
        return [
            ActionButton::make('QR', function($item) {
                return  route('qrCode', ['qrCode' => $item->qrCode?->id??'0', 'lang' => $item->settings?->language??'ru']);
            })
        ];
    }

}
