<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\UuidTrait;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Calendar extends Model
{
    use HasFactory, UuidTrait;

    protected $fillable = [
        'name_calendar',
        'description_calendar',
        'user_id'
    ];

    protected $table = 'calendar';

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function lesson(): HasMany
    {
        return $this->hasMany(Lesson::class, 'calendar_id');
    }
}
