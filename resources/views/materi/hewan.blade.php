{{-- resources/views/materi4.blade.php --}}
@extends('layouts.app')

@section('title', 'Belajar Hewan 🐾')

@section('content')
    <div class="space-y-6">
        {{-- Header atas --}}
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-800">
                🐾 Yuk, Kenali Hewan! 🐾
            </h1>
            <a href="{{ route('materi.index') }}"
                class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded-lg d-inline-flex align-items-center">
                <i class="fas fa-arrow-left me-2"></i> Kembali
            </a>
        </div>

        {{-- Deskripsi --}}
        <p class="text-lg text-gray-700 text-center mb-4">
            Klik hewannya dan sebutkan namanya ya! 🐶🐱🐘
        </p>

        {{-- Kotak Hewan --}}
        <div class="hewan-container">
            <div class="hewan" onclick="tampilkan('Kucing 🐱')">🐱</div>
            <div class="hewan" onclick="tampilkan('Anjing 🐶')">🐶</div>
            <div class="hewan" onclick="tampilkan('Burung 🐦')">🐦</div>
            <div class="hewan" onclick="tampilkan('Gajah 🐘')">🐘</div>
            <div class="hewan" onclick="tampilkan('Ikan 🐠')">🐠</div>
            <div class="hewan" onclick="tampilkan('Singa 🦁')">🦁</div>
            <div class="hewan" onclick="tampilkan('Kelinci 🐰')">🐰</div>
            <div class="hewan" onclick="tampilkan('Panda 🐼')">🐼</div>
            <div class="hewan" onclick="tampilkan('Ayam 🐔')">🐔</div>
            <div class="hewan" onclick="tampilkan('Kuda 🐴')">🐴</div>
        </div>

        {{-- Pesan --}}
        <p id="info" class="fw-bold mt-5 fs-4 text-dark text-center"></p>

        {{-- Suara klik --}}
        <audio id="klik"
            src="https://cdn.pixabay.com/download/audio/2022/03/15/audio_7b962af7cb.mp3?filename=click-124467.mp3"></audio>
    </div>
@endsection

@push('styles')
    <style>
        .hewan-container {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-wrap: wrap;
            gap: 25px;
            margin-top: 40px;
        }

        .hewan {
            background: #fff;
            border-radius: 20px;
            width: 120px;
            height: 120px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
            transition: 0.3s;
            cursor: pointer;
            font-size: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            animation: fade 0.8s ease-in;
        }

        .hewan:hover {
            transform: scale(1.15) rotate(3deg);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.3);
        }

        #info {
            opacity: 0;
            transition: opacity 0.5s ease-in;
            font-weight: bold;
            color: #1b5e20;
        }

        .show-message {
            opacity: 1 !important;
            animation: pulse 1.5s infinite;
        }

        @keyframes fade {
            from {
                opacity: 0;
                transform: scale(0.8);
            }

            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        @keyframes pulse {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.05);
                color: #43a047;
            }

            100% {
                transform: scale(1);
            }
        }
    </style>
@endpush

@push('scripts')
    <script>
        function tampilkan(nama) {
            const info = document.getElementById('info');
            const klik = document.getElementById('klik');
            klik.play();
            info.textContent = `Hebat! Itu adalah ${nama}! 🥳`;
            info.classList.add('show-message');
        }
    </script>
@endpush
