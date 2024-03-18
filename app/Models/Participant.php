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

    public function qrCode()
    {
        return $this->hasOne(QrCode::class);
    }
}
