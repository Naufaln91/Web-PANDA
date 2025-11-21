@extends('layouts.app')

@section('title', 'Belajar Warna - PANDA TK')

@section('content')
    <div class="space-y-6">
        <div class="flex justify-between items-center gap-2">
            <h1 class="text-xl sm:text-2xl md:text-3xl font-bold text-gray-800">
                🎨 Belajar Warna
            </h1>
            <a href="{{ route('materi.index') }}"
                class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-3 sm:px-4 rounded-lg transition flex items-center justify-center">
                <i class="fas fa-arrow-left "></i><span class="ml-2 hidden sm:inline">Kembali</span>
            </a>
        </div>

        {{-- Daftar warna --}}
        <div class="card bg-gradient-to-r from-blue-100 to-indigo-100 py-12 px-6 rounded-2xl shadow-md">
            <div id="warna-container"
                class="grid grid-cols-4 sm:grid-cols-5 md:grid-cols-6 lg:grid-cols-7 gap-2 sm:gap-3 md:gap-4 justify-items-center">
            </div>


            {{-- Kartu display utama --}}
            <div class="card bg-gradient-to-r from-blue-200 to-purple-200 mt-6 rounded-2xl">
                <div class="text-center p-8">

                    {{-- Kontainer untuk Warna --}}
                    <div class="flex justify-center items-center gap-8 mb-6">
                        <div id="selected-color"
                            class="w-40 h-40 rounded-2xl shadow-lg border-2 border-gray-300 transition-transform duration-300"
                            style="background-color: #ff4d4d;"></div>
                    </div>

                    <p id="color-name" class="text-2xl text-gray-700 mb-4 font-bold">Merah</p>
                    <button id="play-sound-btn" onclick="playCurrentSound()"
                        class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-3 px-8 rounded-full text-xl transition">
                        <i class="fas fa-volume-up mr-2"></i> Dengarkan
                    </button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            const colors = [{
                    name: 'Merah',
                    hex: '#ff4d4d'
                },
                {
                    name: 'Jingga',
                    hex: '#ff9933'
                },
                {
                    name: 'Kuning',
                    hex: '#ffeb3b'
                },
                {
                    name: 'Hijau',
                    hex: '#4caf50'
                },
                {
                    name: 'Biru',
                    hex: '#4da6ff'
                },
                {
                    name: 'Nila',
                    hex: '#3f51b5'
                },
                {
                    name: 'Ungu',
                    hex: '#b366ff'
                },
                {
                    name: 'Hitam',
                    hex: '#000000'
                },
                {
                    name: 'Putih',
                    hex: '#ffffff'
                },
                {
                    name: 'Abu-abu',
                    hex: '#9e9e9e'
                },
                {
                    name: 'Cokelat',
                    hex: '#795548'
                },
                {
                    name: 'Merah Muda',
                    hex: '#ffb6c1'
                },
                {
                    name: 'Emas',
                    hex: '#FFD700'
                },
                {
                    name: 'Lavender',
                    hex: '#b5b5f6ff'
                },
            ];

            let current = colors[0];
            const container = document.getElementById('warna-container');

            // Inisialisasi tampilan awal
            document.getElementById('selected-color').style.backgroundColor = colors[0].hex;
            document.getElementById('color-name').textContent = colors[0].name;

            colors.forEach(c => {
                const div = document.createElement('div');
                div.className =
                    "w-[70px] h-[70px] sm:w-20 sm:h-20 md:w-24 md:h-24 rounded-lg sm:rounded-xl md:rounded-2xl shadow-lg cursor-pointer hover:scale-105 transition border-2 border-white";
                div.style.backgroundColor = c.hex;
                div.onclick = () => selectColor(c);
                container.appendChild(div);
            });

            function selectColor(c) {
                current = c;
                const box = document.getElementById('selected-color');
                const name = document.getElementById('color-name');
                box.style.backgroundColor = c.hex;
                name.textContent = c.name;
                playCurrentSound();
            }

            function playCurrentSound() {
                if ('speechSynthesis' in window) {
                    const utterance = new SpeechSynthesisUtterance(current.name);
                    utterance.lang = 'id-ID';
                    utterance.rate = 0.9;
                    speechSynthesis.cancel();
                    speechSynthesis.speak(utterance);

                    const box = document.getElementById('selected-color');
                    box.classList.add('scale-50');
                    setTimeout(() => box.classList.remove('scale-50'), 300);
                }
            }
        </script>
    @endpush
@endsection
