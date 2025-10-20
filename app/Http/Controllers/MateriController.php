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
        ];

        return view('materi.index', compact('materis'));
    }

    // Materi Alfabet
    public function alfabet()
    {
        return view('materi.alfabet');
    }
}
