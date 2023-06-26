<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\UuidTrait;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Lesson extends Model
{
    use HasFactory, UuidTrait;

    protected $fillable = [
        'name_lesson',
        'description_lesson',
        'calendar_id',
        'date_start_lesson',
        'date_end_lesson',
        'duration_lesson'
    ];

    protected $table = 'lesson';

    public function calendar(): BelongsTo{
        return $this->belongsTo(Calendar::class, 'calendar_id');
    }
}
