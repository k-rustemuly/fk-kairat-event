<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Conversation extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'conversation';

    public function scopeRegistered(Builder $query): void
    {
        $query->where('status', 'stopped')->where('command', 'start');
    }

    public function scopeNotRegistered(Builder $query): void
    {
        $query->where('status', 'active')->where('command', 'start');
    }
}
