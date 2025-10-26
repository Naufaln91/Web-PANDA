@extends('layouts.app')

@section('title', 'Belajar Warna - PANDA TK')

@section('content')
    <div class="space-y-6">
        <div class="flex justify-between items-center">
            <h1 class="text-3xl font-bold text-gray-800">
                🎨 Belajar Warna
            </h1>
            <a href="{{ route('materi.index') }}"
                class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded-lg transition">
                <i class="fas fa-arrow-left mr-2"></i> Kembali
            </a>
        </div>

        {{-- Daftar warna --}}
        <div class="card">
            <div id="warna-container" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-6 place-items-center"></div>
        </div>

        {{-- Kotak warna terpilih --}}
        <div class="card bg-gradient-to-r from-pink-100 to-yellow-100 text-center py-8">
            <div id="selected-color" class="w-32 h-32 rounded-2xl mx-auto mb-4 shadow-lg border-2 border-gray-300"></div>
            <p id="color-name" class="text-3xl font-bold text-gray-700 mb-4">Merah</p>
            <button onclick="playCurrentSound()"
                class="bg-pink-500 hover:bg-pink-600 text-white font-bold py-3 px-8 rounded-full text-xl transition">
                <i class="fas fa-volume-up mr-2"></i> Dengarkan
            </button>
        </div>
    </div>

    @push('scripts')
        <script>
            const colors = [
                { name: 'Merah', hex: '#ff4d4d' },
                { name: 'Jingga', hex: '#ff9933' },
                { name: 'Kuning', hex: '#ffeb3b' },
                { name: 'Hijau', hex: '#4caf50' },
                { name: 'Biru', hex: '#4da6ff' },
                { name: 'Nila', hex: '#3f51b5' },
                { name: 'Ungu', hex: '#b366ff' },
                { name: 'Hitam', hex: '#000000' },
                { name: 'Putih', hex: '#ffffff' },
                { name: 'Abu-abu', hex: '#9e9e9e' },
                { name: 'Cokelat', hex: '#795548' },
                { name: 'Merah Muda', hex: '#ffb6c1' },
                { name: 'Turquoise', hex: '#40E0D0' },
                { name: 'Emas', hex: '#FFD700' },
                { name: 'Lavender', hex: '#b5b5f6ff' },
            ];

            let current = colors[0];
            const container = document.getElementById('warna-container');

            colors.forEach(c => {
                const div = document.createElement('div');
                div.className =
                    "w-24 h-24 md:w-28 md:h-28 rounded-2xl shadow-lg cursor-pointer hover:scale-110 transition border-2 border-white";
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
                    box.classList.add('animate-bounce');
                    setTimeout(() => box.classList.remove('animate-bounce'), 800);
                }
            }

            document.getElementById('selected-color').style.backgroundColor = colors[0].hex;
            setTimeout(() => playCurrentSound(), 600);
        </script>
    @endpush
@endsection
