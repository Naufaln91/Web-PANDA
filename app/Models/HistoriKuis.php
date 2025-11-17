<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class HistoriKuis extends Model
{
    use HasFactory;

    protected $table = 'histori_kuis';

    protected $fillable = [
        'user_id',
        'kuis_id',
        'jumlah_soal_dijawab',
        'jumlah_benar',
        'nilai',
        'detail_jawaban',
        'waktu_selesai',
    ];

    protected $casts = [
        'detail_jawaban' => 'array',
        'waktu_selesai' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function kuis()
    {
        return $this->belongsTo(Kuis::class);
    }
}
