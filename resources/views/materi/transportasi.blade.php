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

        <div class="card bg-gradient-to-r from-blue-100 to-indigo-100 py-12 px-6 rounded-2xl shadow-md">
            <div id="transport-container" class="grid grid-cols-3 md:grid-cols-6 gap-4"></div>


            {{-- Kartu display utama --}}
            <div class="card bg-gradient-to-r from-blue-200 to-purple-200 mt-6 rounded-2xl">
                <div class="text-center p-8">

                    {{-- Kontainer untuk Transportasi --}}
                    <div class="flex justify-center items-center gap-8 mb-6">
                        <div id="selected-transport" class="text-9xl">🚗</div>
                    </div>

                    <p id="transport-name" class="text-2xl text-gray-700 mb-4 font-bold">Mobil</p>
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
            const transports = [{
                    emoji: '🚗',
                    name: 'Mobil'
                },
                {
                    emoji: '🚌',
                    name: 'Bus'
                },
                {
                    emoji: '🚲',
                    name: 'Sepeda'
                },
                {
                    emoji: '✈️',
                    name: 'Pesawat'
                },
                {
                    emoji: '🚤',
                    name: 'Kapal'
                },
                {
                    emoji: '🚂',
                    name: 'Kereta Api'
                },
                {
                    emoji: '🏍️',
                    name: 'Motor'
                },
                {
                    emoji: '🚚',
                    name: 'Truk'
                },
                {
                    emoji: '🚑',
                    name: 'Ambulans'
                },
                {
                    emoji: '🚁',
                    name: 'Helikopter'
                },
                {
                    emoji: '🚒',
                    name: 'Pemadam Kebakaran'
                },
                {
                    emoji: '🛻',
                    name: 'Pick-up'
                }
            ];

            let current = transports[0];
            const container = document.getElementById('transport-container');

            transports.forEach(t => {
                const div = document.createElement('div');
                div.className =
                    "rounded-2xl bg-white shadow-lg p-6 text-center cursor-pointer hover:scale-110 transition text-5xl";
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
        </script>
    @endpush
@endsection
