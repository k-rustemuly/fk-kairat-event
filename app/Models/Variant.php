<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Variant extends LocalizableModel
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'question_id',
        'title_kk',
        'title_ru',
    ];

    /**
     * Localized attributes.
     *
     * @var array
     */
    protected $localizable = [
        'title'
    ];

    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }
}
