@extends('layouts.app')

@section('title', 'Belajar Transportasi - PANDA TK')

@section('content')
    <div class="space-y-6">
        <div class="flex justify-between items-center">
            <h1 class="text-3xl font-bold text-gray-800">
                🚗 Belajar Transportasi
            </h1>
            <a href="{{ route('materi.index') }}"
                class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded-lg transition">
                <i class="fas fa-arrow-left mr-2"></i> Kembali
            </a>
        </div>

        <div class="card">
            <div id="transport-container" class="grid grid-cols-3 md:grid-cols-6 gap-4"></div>
        </div>

        <div class="card bg-gradient-to-r from-sky-100 to-gray-100">
            <div class="text-center">
                <div id="selected-transport" class="text-8xl mb-4">🚗</div>
                <p id="transport-name" class="text-2xl text-gray-700 mb-4">Mobil</p>
                <button onclick="playCurrentSound()"
                    class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-3 px-8 rounded-full text-xl transition">
                    <i class="fas fa-volume-up mr-2"></i> Dengarkan
                </button>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            const transports = [
                { emoji: '🚗', name: 'Mobil' },
                { emoji: '🚌', name: 'Bus' },
                { emoji: '🚲', name: 'Sepeda' },
                { emoji: '✈️', name: 'Pesawat' },
                { emoji: '🚤', name: 'Kapal' },
                { emoji: '🚂', name: 'Kereta Api' },
                { emoji: '🏍️', name: 'Motor' },
                { emoji: '🚚', name: 'Truk' },
                { emoji: '🚑', name: 'Ambulans' },
                { emoji: '🚁', name: 'Helikopter' },
                { emoji: '🚒', name: 'Pemadam Kebakaran' },
                { emoji: '🛻', name: 'Pick-up' }
            ];

            let current = transports[0];
            const container = document.getElementById('transport-container');

            transports.forEach(t => {
                const div = document.createElement('div');
                div.className = "rounded-2xl p-6 text-center shadow-lg cursor-pointer hover:scale-110 transition bg-white";
                div.innerHTML = `<span class='text-5xl'>${t.emoji}</span>`;
                div.onclick = () => selectTransport(t);
                container.appendChild(div);
            });

            function selectTransport(t) {
                current = t;
                document.getElementById('selected-transport').textContent = t.emoji;
                document.getElementById('transport-name').textContent = t.name;
                playCurrentSound();
            }

            function playCurrentSound() {
                if ('speechSynthesis' in window) {
                    const utterance = new SpeechSynthesisUtterance(current.name);
                    utterance.lang = 'id-ID';
                    speechSynthesis.cancel(); // hentikan suara sebelumnya
                    speechSynthesis.speak(utterance);

                    const el = document.getElementById('selected-transport');
                    el.classList.add('animate-bounce');
                    setTimeout(() => el.classList.remove('animate-bounce'), 800);
                }
            }

            // Otomatis putar suara pertama
            setTimeout(() => playCurrentSound(), 500);
        </script>
    @endpush
@endsection
