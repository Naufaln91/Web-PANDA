@extends('layouts.app')

@section('title', 'Belajar Angka - PANDA TK')

@section('content')
    <div class="space-y-6">
        <div class="flex justify-between items-center">
            <h1 class="text-3xl font-bold text-gray-800">
                🔢 Belajar Angka
            </h1>
            <a href="{{ route('materi.index') }}"
                class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded-lg transition">
                <i class="fas fa-arrow-left mr-2"></i> Kembali
            </a>
        </div>

        {{-- Daftar angka --}}
        <div class="card bg-gradient-to-r from-blue-100 to-indigo-100 py-12 px-6 rounded-2xl shadow-md">
            <div id="angka-container" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-4 p-4"></div>


            {{-- Kartu display utama --}}
            <div class="card bg-gradient-to-r from-blue-200 to-purple-200 mt-6 rounded-2xl">
                <div class="text-center p-8">

                    {{-- Kontainer untuk Angka --}}
                    <div class="flex justify-center items-center gap-8 mb-6">
                        <div id="selected-number" class="text-9xl font-bold text-blue-600"></div>
                    </div>

                    <p id="number-name" class="text-2xl text-gray-700 mb-4 font-bold"></p>
                    <p class="text-2xl text-gray-700 mb-4">Klik angka untuk mendengar cara pengucapannya!</p>
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
            const numbers = [{
                    name: 'Satu',
                    value: 1
                },
                {
                    name: 'Dua',
                    value: 2
                },
                {
                    name: 'Tiga',
                    value: 3
                },
                {
                    name: 'Empat',
                    value: 4
                },
                {
                    name: 'Lima',
                    value: 5
                },
                {
                    name: 'Enam',
                    value: 6
                },
                {
                    name: 'Tujuh',
                    value: 7
                },
                {
                    name: 'Delapan',
                    value: 8
                },
                {
                    name: 'Sembilan',
                    value: 9
                },
                {
                    name: 'Sepuluh',
                    value: 10
                },
            ];

            let current = numbers[0];
            const container = document.getElementById('angka-container');

            numbers.forEach(n => {
                const div = document.createElement('div');
                div.className =
                    "w-full h-20 rounded-xl shadow-lg cursor-pointer hover:scale-105 transition border-2 border-white flex items-center justify-center text-3xl font-bold text-white";
                div.style.backgroundColor = '#4da6ff'; // Warna biru untuk angka
                div.textContent = n.value;
                div.onclick = () => selectNumber(n);
                container.appendChild(div);
            });

            function selectNumber(n) {
                current = n;
                const box = document.getElementById('selected-number');
                const name = document.getElementById('number-name');
                box.textContent = n.value;
                name.textContent = n.name;
                playCurrentSound();
            }

            function playCurrentSound() {
                if ('speechSynthesis' in window) {
                    const utterance = new SpeechSynthesisUtterance(current.name);
                    utterance.lang = 'id-ID';
                    utterance.rate = 0.9;
                    speechSynthesis.cancel();
                    speechSynthesis.speak(utterance);

                    const box = document.getElementById('selected-number');
                    box.classList.add('animate-bounce');
                    setTimeout(() => box.classList.remove('animate-bounce'), 800);
                }
            }
        </script>
    @endpush
@endsection
