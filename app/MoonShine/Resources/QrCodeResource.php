<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use Illuminate\Database\Eloquent\Model;
use App\Models\QrCode;

use MoonShine\Resources\ModelResource;
use MoonShine\Decorations\Block;
use MoonShine\Enums\PageType;
use MoonShine\Fields\ID;
use MoonShine\Fields\Text;
use MoonShine\Handlers\ImportHandler;

/**
 * @extends ModelResource<QrCode>
 */
class QrCodeResource extends ModelResource
{
    protected string $model = QrCode::class;

    protected ?PageType $redirectAfterSave = PageType::INDEX;

    public function title(): string
    {
        return __('panel.menu.qr_codes');
    }

    public function fields(): array
    {
        return [
            Block::make([
                ID::make()->sortable(),
                Text::make('QR code', 'code')
                    ->required()
                    ->useOnImport()
            ]),
        ];
    }

    public function rules(Model $item): array
    {
        return [
            'code' => 'required|unique:qr_codes'
        ];
    }

    public function import(): ?ImportHandler
    {
        return ImportHandler::make(__('moonshine::ui.import'))->delimiter(';');
    }

}
