<?php

// app/Http/Controllers/MateriController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MateriController extends Controller
{
    // Halaman daftar materi
    public function index()
    {
        $materis = [
            [
                'id' => 'alfabet',
                'title' => 'Belajar Alfabet',
                'description' => 'Belajar mengenal huruf A-Z dengan cara yang menyenangkan',
                'icon' => '🔤',
                'route' => 'materi.alfabet',
            ],
            [
                'id' => 'warna',
                'title' => 'Belajar Warna',
                'description' => 'Belajar mengenal berbagai macam warna dengan cara yang menyenangkan',
                'icon' => '🎨',
                'route' => 'materi.warna',
            ],
            [
                'id' => 'hewan',
                'title' => 'Belajar Nama Hewan',
                'description' => 'Belajar mengenal berbagai nama hewan dengan cara yang menyenangkan',
                'icon' => '🦁',
                'route' => 'materi.hewan',
            ],
        ];

        return view('materi.index', compact('materis'));
    }

    // Materi Alfabet
    public function alfabet()
    {
        return view('materi.alfabet');
    }
    // Materi Warna
    public function warna()
    {
        return view('materi.warna');
    }
    // Materi Hewan
    public function hewan()
    {
        return view('materi.hewan');
    }
}
