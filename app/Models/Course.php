<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'courses';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'user_id',
        'class_id',
        'start_date',
        'end_date',
        'status'
    ];

    // Setiap course dimiliki oleh satu user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Setiap course memiliki banyak sesi praktek
    public function practices()
    {
        return $this->hasMany(Practice::class);
    }

    public function theories()
    {
        return $this->hasMany(Teori::class);
    }

    // Setiap course dimiliki oleh satu kelas
    public function class()
    {
        return $this->belongsTo(ClassModel::class, 'class_id');
    }

    // App\Models\Course.php

    public function getSisaPraktekAttribute()
    {
        $num_of_praktek = $this->class->num_of_praktek; // num_of_praktek dari relasi class
        $jumlah_praktek = $this->practices()->where('status', '<>', 'canceled')->count();  // jumlah praktek dari relasi practices

        return $num_of_praktek - $jumlah_praktek; // hitung sisa praktek
    }

    public function getJumlahPraktekAttribute()
    {
        $jumlah_praktek = $this->practices()->where('status', '<>', 'canceled')->count();  // jumlah praktek dari relasi practices

        return $jumlah_praktek; // hitung sisa praktek
    }

    public function getJumlahTeoriAttribute()
    {
        $jumlah_teori = $this->theories()->where('status', '<>', 'canceled')->count();  // jumlah praktek dari relasi practices

        return $jumlah_teori; // hitung sisa praktek
    }
}
