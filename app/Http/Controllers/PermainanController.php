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
        'description' => 'Susun potongan gambar hewan dan buah menjadi satu gambar utuh.',
        'icon' => '🧩',
        'route' => 'permainan.puzzle',
    ],

    [
        'id' => 'hitung',
        'title' => 'Hitung Jumlah Gambar',
        'description' => 'Hitung jumlah gambar hewan dan buah untuk melatih kemampuan berhitung.',
        'icon' => '➕',
        'route' => 'permainan.hitung',
    ],

    [
        'id' => 'cocokkan_pasangan',
        'title' => 'Mencocokkan Pasangan Buah-Buahan',
        'description' => 'Cocokkan dua gambar buah yang sama untuk melatih daya ingat anak.',
        'icon' => '🍇🍇',
        'route' => 'permainan.cocokkan_pasangan',
    ],

    [
        'id' => 'urutkan_angka',
        'title' => 'Urutkan Angka',
        'description' => 'Urutkan angka dari 1 sampai 20 untuk belajar berhitung dengan menyenangkan.',
        'icon' => '1️⃣5️⃣3️⃣',
        'route' => 'permainan.urutkan_angka',
    ],

    [
        'id' => 'menyusun_kata',
        'title' => 'Susun Kata',
        'description' => 'Susun huruf menjadi kata tentang hewan, buah, dan transportasi.',
        'icon' => '✏️',
        'route' => 'permainan.menyusun_kata',
    ],

    [
        'id' => 'labirin',
        'title' => 'Labirin',
        'description' => 'Bantu hewan lucu menemukan jalan menuju garis finish sambil melatih logika dan konsentrasi.',
        'icon' => '🌀',
        'route' => 'permainan.labirin',
    ],
];


        return view('permainan.index', compact('permainans'));
    }

    // Permainan Puzzle
    public function puzzle()
    {
        return view('permainan.puzzle');
    }
    
    // Permainan Hitung Jumlah Gambar
    public function hitung()
    {
        return view('permainan.hitung');
    }

    // Permainan Cocokkan Pasangan
    public function cocokkan_pasangan()
    {
        return view('permainan.cocokkan_pasangan');
    }

    // Permainan Urutkan Angka
    public function urutkan_angka()
    {
        return view('permainan.urutkan_angka');
    }

    // Permainan Menyusun Kata
    public function menyusun_kata()
    {
        return view('permainan.menyusun_kata');
    }

    // Permainan Labirin
    public function labirin()
    {
        return view('permainan.labirin');
    }
}
