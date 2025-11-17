@extends('layouts.app')

@section('title', 'Belajar Hewan - PANDA TK')

@section('content')
    <div class="space-y-6">
        {{-- Header --}}
        <div class="flex justify-between items-center">
            <h1 class="text-3xl font-bold text-gray-800">
                🐾 Belajar Hewan
            </h1>
            <a href="{{ route('materi.index') }}"
                class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded-lg transition">
                <i class="fas fa-arrow-left mr-2"></i> Kembali
            </a>
        </div>

        {{-- Daftar Hewan --}}
        <div class=" card bg-gradient-to-r from-blue-100 to-indigo-100 py-12 px-6 rounded-2xl shadow-md">
            <div id="hewan-container" class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-5 lg:grid-cols-6 gap-4"></div>


            {{-- Kartu display utama --}}
            <div class="card bg-gradient-to-r from-blue-200 to-purple-200 mt-6 rounded-2xl">
                <div class="text-center p-8">

                    {{-- Kontainer untuk Hewan --}}
                    <div class="flex justify-center items-center gap-8 mb-6">
                        <div id="selected-hewan" class="text-9xl">🐱</div>
                    </div>

                    <p id="hewan-name" class="text-2xl text-gray-700 mb-4 font-bold">Kucing</p>
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
            const hewans = [{
                    name: 'Kucing',
                    icon: '🐱'
                },
                {
                    name: 'Anjing',
                    icon: '🐶'
                },
                {
                    name: 'Burung',
                    icon: '🐦'
                },
                {
                    name: 'Gajah',
                    icon: '🐘'
                },
                {
                    name: 'Ikan',
                    icon: '🐠'
                },
                {
                    name: 'Singa',
                    icon: '🦁'
                },
                {
                    name: 'Kelinci',
                    icon: '🐰'
                },
                {
                    name: 'Panda',
                    icon: '🐼'
                },
                {
                    name: 'Ayam',
                    icon: '🐔'
                },
                {
                    name: 'Kuda',
                    icon: '🐴'
                },
                {
                    name: 'Domba',
                    icon: '🐑'
                },
                {
                    name: 'Kambing',
                    icon: '🐐'
                },
                {
                    name: 'Katak',
                    icon: '🐸'
                },
                {
                    name: 'Kupu-kupu',
                    icon: '🦋'
                },
                {
                    name: 'Monyet',
                    icon: '🐵'
                },
                {
                    name: 'Burung Hantu',
                    icon: '🦉'
                },
                {
                    name: 'Iguana',
                    icon: '🦎'
                },
                {
                    name: 'Pinguin',
                    icon: '🐧'
                },
                {
                    name: 'Ular',
                    icon: '🐍'
                },
                {
                    name: 'Kura-kura',
                    icon: '🐢'
                }
            ];

            let current = hewans[0];
            const container = document.getElementById('hewan-container');
            const selectedBox = document.getElementById('selected-hewan');

            // Inisialisasi tampilan awal
            selectedBox.textContent = hewans[0].icon;
            document.getElementById('hewan-name').textContent = hewans[0].name;

            // Buat grid hewan
            hewans.forEach(h => {
                const div = document.createElement('div');
                div.className =
                    "rounded-2xl bg-white shadow-lg p-6 text-center cursor-pointer hover:scale-110 transition flex items-center justify-center text-5xl";
                div.textContent = h.icon;
                div.onclick = () => selectHewan(h);
                container.appendChild(div);
            });

            function selectHewan(h) {
                current = h;
                selectedBox.textContent = h.icon;
                document.getElementById('hewan-name').textContent = h.name;
                playCurrentSound();
            }

            function playCurrentSound() {
                if (!current) return;
                if ('speechSynthesis' in window) {
                    const utterance = new SpeechSynthesisUtterance(current.name);
                    utterance.lang = 'id-ID';
                    utterance.rate = 0.9;
                    speechSynthesis.cancel();
                    speechSynthesis.speak(utterance);

                    selectedBox.classList.add('animate-bounce');
                    setTimeout(() => selectedBox.classList.remove('animate-bounce'), 800);
                }
            }
        </script>
    @endpush
@endsection
