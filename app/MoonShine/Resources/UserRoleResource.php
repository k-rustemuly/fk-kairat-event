<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use MoonShine\Resources\MoonShineUserRoleResource;

/**
 * @extends ModelResource<Variant>
 */
class UserRoleResource extends MoonShineUserRoleResource
{
    /**
     * @return array{name: string}
     */
    public function rules($item): array
    {
        return [
            'name' => 'required|min:3',
        ];
    }
}
