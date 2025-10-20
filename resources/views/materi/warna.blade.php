{{-- resources/views/materi1.blade.php --}}
@extends('layouts.app')

@section('title', 'Belajar Warna Pelangi 🌈')

@section('content')
    <div class="space-y-6">
        {{-- Header atas --}}
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-800">
                🌈 Yuk, Belajar Warna Pelangi!
            </h1>
            <a href="{{ route('materi.index') }}"
                class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded-lg d-inline-flex align-items-center">
                <i class="fas fa-arrow-left me-2"></i> Kembali
            </a>
        </div>

        {{-- Deskripsi --}}
        <p class="text-lg text-gray-700 text-center mb-4">
            Klik warna yang kamu suka, lalu sebutkan namanya dengan lantang! 🎵
        </p>

        {{-- Kotak warna --}}
        <div class="warna-container mt-4">
            <div class="warna merah" onclick="tampilkan('Merah ❤️')" role="button" aria-label="Merah"></div>
            <div class="warna jingga" onclick="tampilkan('Jingga 🧡')" role="button" aria-label="Jingga"></div>
            <div class="warna kuning" onclick="tampilkan('Kuning 💛')" role="button" aria-label="Kuning"></div>
            <div class="warna hijau" onclick="tampilkan('Hijau 💚')" role="button" aria-label="Hijau"></div>
            <div class="warna biru" onclick="tampilkan('Biru 💙')" role="button" aria-label="Biru"></div>
            <div class="warna nila" onclick="tampilkan('Nila 💜')" role="button" aria-label="Nila"></div>
            <div class="warna ungu" onclick="tampilkan('Ungu 💟')" role="button" aria-label="Ungu"></div>
        </div>

        <p id="pesan" class="fw-bold mt-5 fs-4 text-dark text-center" aria-live="polite"></p>
    </div>
@endsection

@push('styles')
    <style>
        .warna-container {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-wrap: wrap;
            gap: 25px;
            margin-top: 40px;
        }

        .warna {
            width: 120px;
            height: 120px;
            border-radius: 20px;
            cursor: pointer;
            transition: transform 0.3s, box-shadow 0.3s;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .warna:hover {
            transform: scale(1.1);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.25);
        }

        .merah {
            background-color: #ff4d4d;
        }

        .jingga {
            background-color: #ff9933;
        }

        .kuning {
            background-color: #ffeb3b;
        }

        .hijau {
            background-color: #4caf50;
        }

        .biru {
            background-color: #4da6ff;
        }

        .nila {
            background-color: #3f51b5;
        }

        .ungu {
            background-color: #b366ff;
        }

        #pesan {
            opacity: 0;
            transition: opacity 0.8s ease-in;
        }

        .show-message {
            opacity: 1 !important;
            animation: pulse 1.5s infinite;
        }

        @keyframes pulse {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.1);
                color: #ff6f61;
            }

            100% {
                transform: scale(1);
            }
        }
    </style>
@endpush

@push('scripts')
    <script>
        function tampilkan(warna) {
            const pesan = document.getElementById('pesan');
            pesan.textContent = `Bagus sekali! Ini warna ${warna}! 🌟`;
            pesan.classList.add('show-message');
        }
    </script>
@endpush
