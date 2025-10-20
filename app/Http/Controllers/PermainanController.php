<?php

// app/Http/Controllers/PermainanController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PermainanController extends Controller
{
    // Halaman daftar permainan
    public function index()
    {
        $permainans = [
            [
                'id' => 'puzzle',
                'title' => 'Puzzle',
                'description' => 'Susun puzzle gambar hewan dan buah-buahan',
                'icon' => '🧩',
                'route' => 'permainan.puzzle',
            ],
        ];

        return view('permainan.index', compact('permainans'));
    }

    // Permainan Puzzle
    public function puzzle()
    {
        return view('permainan.puzzle');
    }
}
