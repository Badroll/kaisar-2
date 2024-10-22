<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PracticeTalent extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'practice_talent';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'practice_id',
        'user_id',
        'session_time',
    ];

    public function practice()
    {
        return $this->belongsTo(Practice::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
