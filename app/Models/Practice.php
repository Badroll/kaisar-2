<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Practice extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'practices';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'course_id',
        'session_date',
        'session_time',
        'status',
        'talent_1',
        'talent_2',
        'talent_3'
    ];

    // Setiap praktek dimiliki oleh satu course
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    // Setiap praktek bisa memiliki banyak talent (praktek-talent many-to-many)
    public function practiceTalents()
    {
        return $this->hasMany(PracticeTalent::class);
    }

    public function talents(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'practice_talent', 'practice_id', 'user_id')
            ->withPivot('session_time')
            ->withTimestamps();
    }

    // Setiap praktek bisa memiliki banyak history perubahan
    public function practiceDateChangeHistories()
    {
        return $this->hasMany(PracticeDateChangeHistory::class);
    }
}
