<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Question extends LocalizableModel
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title_kk',
        'title_ru',
        'answer',
        'start',
        'end',
    ];

    /**
     * Localized attributes.
     *
     * @var array
     */
    protected $localizable = [
        'title'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'start' => 'datetime',
        'end' => 'datetime',
    ];

    public function variants(): HasMany
    {
        return $this->hasMany(Variant::class);
    }
}
