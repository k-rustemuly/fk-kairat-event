<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Participant extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'telegram_id',
        'name',
        'surname',
        'birth_year',
        'phone_number',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'q1_t' => 'datetime',
        'q2_t' => 'datetime',
        'q3_t' => 'datetime',
        'q4_t' => 'datetime',
        'q5_t' => 'datetime',
        'q6_t' => 'datetime',
        'q7_t' => 'datetime',
        'q8_t' => 'datetime',
        'q9_t' => 'datetime',
        'q10_t' => 'datetime',
        'q11_t' => 'datetime',
    ];

    public function qrCode()
    {
        return $this->hasOne(QrCode::class);
    }

    public function settings()
    {
        return $this->hasOne(UserLanguage::class, 'telegram_id', 'telegram_id');
    }

    public function supports(): HasMany
    {
        return $this->hasMany(Support::class, 'participant_id');
    }

    public function q1b(): BelongsTo
    {
        return $this->belongsTo(Variant::class, 'q1');
    }

    public function q2b(): BelongsTo
    {
        return $this->belongsTo(Variant::class, 'q2');
    }

    public function q3b(): BelongsTo
    {
        return $this->belongsTo(Variant::class, 'q3');
    }

    public function q4b(): BelongsTo
    {
        return $this->belongsTo(Variant::class, 'q4');
    }

    public function q5b(): BelongsTo
    {
        return $this->belongsTo(Variant::class, 'q5');
    }

    public function q6b(): BelongsTo
    {
        return $this->belongsTo(Variant::class, 'q6');
    }

    public function q7b(): BelongsTo
    {
        return $this->belongsTo(Variant::class, 'q7');
    }

    public function q8b(): BelongsTo
    {
        return $this->belongsTo(Variant::class, 'q8');
    }

    public function q9b(): BelongsTo
    {
        return $this->belongsTo(Variant::class, 'q9');
    }

    public function q10b(): BelongsTo
    {
        return $this->belongsTo(Variant::class, 'q10');
    }

    public function q11b(): BelongsTo
    {
        return $this->belongsTo(Variant::class, 'q11');
    }
}
