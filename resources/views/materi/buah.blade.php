@extends('layouts.app')

@section('title', 'Belajar Buah - PANDA TK')

@section('content')
    <div class="space-y-6">
        <div class="flex justify-between items-center">
            <h1 class="text-3xl font-bold text-gray-800">
                🍎 Belajar Buah
            </h1>
            <a href="{{ route('materi.index') }}"
                class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded-lg transition">
                <i class="fas fa-arrow-left mr-2"></i> Kembali
            </a>
        </div>

        <div class="card bg-gradient-to-r from-blue-100 to-indigo-100 py-12 px-6 rounded-2xl shadow-md">
            <div id="fruit-container" class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-5 lg:grid-cols-6 gap-4"></div>


            {{-- Kartu display utama --}}
            <div class="card bg-gradient-to-r from-blue-200 to-purple-200 mt-6 rounded-2xl">
                <div class="text-center p-8">

                    {{-- Kontainer untuk Buah --}}
                    <div class="flex justify-center items-center gap-8 mb-6">
                        <div id="selected-fruit" class="text-9xl transition-transform duration-300">🍎</div>
                    </div>

                    <p id="fruit-name" class="text-2xl text-gray-700 mb-4 font-bold">Apel</p>
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
            const fruits = [{
                    emoji: '🍎',
                    name: 'Apel'
                },
                {
                    emoji: '🍌',
                    name: 'Pisang'
                },
                {
                    emoji: '🍊',
                    name: 'Jeruk'
                },
                {
                    emoji: '🍉',
                    name: 'Semangka'
                },
                {
                    emoji: '🍇',
                    name: 'Anggur'
                },
                {
                    emoji: '🍓',
                    name: 'Stroberi'
                },
                {
                    emoji: '🍍',
                    name: 'Nanas'
                },
                {
                    emoji: '🥭',
                    name: 'Mangga'
                },
                {
                    emoji: '🍒',
                    name: 'Ceri'
                },
                {
                    emoji: '🥥',
                    name: 'Kelapa'
                },
                {
                    emoji: '🍐',
                    name: 'Pir'
                },
                {
                    emoji: '🍈',
                    name: 'Melon'
                }
            ];

            let currentFruit = fruits[0];

            const container = document.getElementById('fruit-container');
            fruits.forEach(fruit => {
                const div = document.createElement('div');
                div.className =
                    "rounded-2xl bg-white shadow-lg p-6 text-center cursor-pointer hover:scale-110 transition flex items-center justify-center text-5xl";
                div.innerHTML =
                    `<span class='text-5xl'>${fruit.emoji}</span>`;
                div.onclick = () => selectFruit(fruit);
                container.appendChild(div);
            });

            function selectFruit(fruit) {
                currentFruit = fruit;
                document.getElementById('selected-fruit').textContent = fruit.emoji;
                document.getElementById('fruit-name').textContent = fruit.name;
                playCurrentSound();
            }

            function playCurrentSound() {
                if ('speechSynthesis' in window) {
                    const utterance = new SpeechSynthesisUtterance(currentFruit.name);
                    utterance.lang = 'id-ID';
                    utterance.rate = 0.9;
                    utterance.pitch = 1.1;
                    speechSynthesis.speak(utterance);

                    const el = document.getElementById('selected-fruit');
                    el.classList.add('scale-50');
                    setTimeout(() => el.classList.remove('scale-50'), 300);
                } else {
                    alert('Browser Anda tidak mendukung text-to-speech');
                }
            }
        </script>
    @endpush
@endsection
