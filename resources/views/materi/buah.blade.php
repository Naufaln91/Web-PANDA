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

        <div class="card">
            <div id="fruit-container" class="grid grid-cols-3 md:grid-cols-6 gap-4">
                <!-- Buah akan di-generate oleh JavaScript -->
            </div>
        </div>

        <div class="card bg-gradient-to-r from-red-100 to-orange-100">
            <div class="text-center">
                <div id="selected-fruit" class="text-8xl mb-4">🍎</div>
                <p id="fruit-name" class="text-2xl text-gray-700 mb-4 font-bold">Apel</p>
                <button onclick="playCurrentSound()"
                    class="bg-red-500 hover:bg-red-600 text-white font-bold py-3 px-8 rounded-full text-xl transition">
                    <i class="fas fa-volume-up mr-2"></i> Dengarkan
                </button>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            const fruits = [
                { emoji: '🍎', name: 'Apel' },
                { emoji: '🍌', name: 'Pisang' },
                { emoji: '🍊', name: 'Jeruk' },
                { emoji: '🍉', name: 'Semangka' },
                { emoji: '🍇', name: 'Anggur' },
                { emoji: '🍓', name: 'Stroberi' },
                { emoji: '🍍', name: 'Nanas' },
                { emoji: '🥭', name: 'Mangga' },
                { emoji: '🍒', name: 'Ceri' },
                { emoji: '🥥', name: 'Kelapa' },
                { emoji: '🍐', name: 'Pir' },
                { emoji: '🍈', name: 'Melon' }
            ];

            let currentFruit = fruits[0];

            const container = document.getElementById('fruit-container');
            fruits.forEach(fruit => {
                const div = document.createElement('div');
                div.className =
                    "rounded-2xl bg-white shadow-lg p-6 text-center cursor-pointer hover:scale-110 transition";
                div.innerHTML = `<span class='text-5xl'>${fruit.emoji}</span><p class='mt-2 font-bold text-gray-700'>${fruit.name}</p>`;
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
                    el.classList.add('animate-bounce');
                    setTimeout(() => el.classList.remove('animate-bounce'), 800);
                } else {
                    alert('Browser Anda tidak mendukung text-to-speech');
                }
            }

            // Auto play first fruit
            setTimeout(() => playCurrentSound(), 800);
        </script>
    @endpush
@endsection
