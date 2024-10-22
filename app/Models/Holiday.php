<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Holiday extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'ms_holidays';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'name',
        'date',
    ];
}
