<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teori extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'teori';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'course_id',
        'session_date',
        'session_time',
        'status'
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
