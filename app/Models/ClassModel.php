<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassModel extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'ms_classes';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'name',
        'day_duration',
        'num_of_praktek',
    ];

    // Setiap kelas bisa memiliki banyak course
    public function courses()
    {
        return $this->hasMany(Course::class);
    }
}
