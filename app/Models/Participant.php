<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
