<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Soal extends Model
{
    protected $table = 'soal';

    protected $fillable = [
        'kuis_id',
        'urutan',
        'tipe',
        'konten_soal',
        'gambar_soal',
        'jumlah_pilihan',
        'jawaban_benar',
    ];

    public function kuis()
    {
        return $this->belongsTo(Kuis::class);
    }

    public function pilihanJawaban()
    {
        return $this->hasMany(PilihanJawaban::class)->orderBy('urutan');
    }

    public function isPilihanGanda()
    {
        return $this->tipe === 'pilihan_ganda';
    }

    public function isIsianSingkat()
    {
        return $this->tipe === 'isian_singkat';
    }

    public function checkJawaban($jawaban)
    {
        if ($this->isIsianSingkat()) {
            // Case insensitive, trim whitespace
            return strtolower(trim($jawaban)) === strtolower(trim($this->jawaban_benar));
        }

        // Untuk pilihan ganda, jawaban adalah index pilihan
        return $jawaban == $this->jawaban_benar;
    }
}
