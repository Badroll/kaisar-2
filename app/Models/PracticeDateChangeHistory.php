<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PracticeDateChangeHistory extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'practice_date_change_histories';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'practice_id',
        'date_before',
        'date_after'
    ];

    // Setiap history dimiliki oleh satu praktek
    public function practice()
    {
        return $this->belongsTo(Practice::class);
    }
}
