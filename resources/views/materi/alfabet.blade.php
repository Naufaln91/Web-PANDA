@extends('layouts.app')

@section('title', 'Belajar Alfabet - PANDA TK')

@section('content')
    <div class="space-y-6">
        <div class="flex justify-between items-center">
            <h1 class="text-3xl font-bold text-gray-800">
                🔤 Belajar Alfabet
            </h1>
            <a href="{{ route('materi.index') }}"
                class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded-lg transition">
                <i class="fas fa-arrow-left mr-2"></i> Kembali
            </a>
        </div>

        {{-- Grid untuk pilihan huruf --}}
        <div class="card bg-gradient-to-r from-blue-100 to-indigo-100 py-12 px-6 rounded-2xl shadow-md">
            <div id="alfabet-container" class="grid grid-cols-5 sm:grid-cols-7 lg:grid-cols-9 gap-2 sm:gap-3 lg:gap-4">
            </div>


            {{-- Kartu display utama --}}
            <div class="card bg-gradient-to-r from-blue-200 to-purple-200 mt-6 rounded-2xl">
                <div class="text-center p-8">

                    {{-- Kontainer untuk Huruf --}}
                    <div class="flex justify-center items-center gap-8 mb-6">
                        <div id="selected-letter"
                            class="text-9xl font-bold text-blue-600 transition-transform duration-300">A</div>
                    </div>

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
            const alfabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ'.split('');
            const colors = ['bg-red-400', 'bg-blue-400', 'bg-green-400', 'bg-yellow-400', 'bg-purple-400', 'bg-pink-400',
                'bg-indigo-400'
            ];

            // Variabel global untuk huruf yang sedang aktif
            let currentLetter = 'A';



            // Generate tombol huruf
            const container = document.getElementById('alfabet-container');
            alfabet.forEach((letter, index) => {
                const colorClass = colors[index % colors.length];
                const letterDiv = document.createElement('div');
                letterDiv.className =
                    `${colorClass} hover:scale-110 transition transform cursor-pointer rounded-2xl shadow-lg p-4 sm:p-5 md:p-6 text-center min-h-[60px] sm:min-h-[70px] md:min-h-[80px] flex items-center justify-center`;
                letterDiv.innerHTML =
                    `<span class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-bold text-white">${letter}</span>`;

                // --- DIMODIFIKASI: Arahkan ke selectLetter ---
                letterDiv.onclick = () => selectLetter(letter);
                container.appendChild(letterDiv);
            });

            // --- DIMODIFIKASI: Fungsi untuk memilih huruf ---
            function selectLetter(letter) {
                currentLetter = letter;

                // Update tampilan di kartu display
                document.getElementById('selected-letter').textContent = letter;

                // Putar suara
                playCurrentSound();
            }

            // --- DIMODIFIKASI: Fungsi untuk memutar suara ---
            function playCurrentSound() {
                // Buat teks yang akan diucapkan (hanya huruf)
                const textToSpeak = currentLetter;

                if ('speechSynthesis' in window) {
                    const utterance = new SpeechSynthesisUtterance(textToSpeak);

                    // --- REVISI 1: Ganti bahasa ke Indonesia ---
                    utterance.lang = 'id-ID';

                    utterance.rate = 0.8;
                    utterance.pitch = 1.1; // Sedikit disesuaikan agar lebih jelas
                    speechSynthesis.speak(utterance);

                    // Animasi
                    const letterEl = document.getElementById('selected-letter');
                    letterEl.classList.add('scale-50');

                    setTimeout(() => {
                        letterEl.classList.remove('scale-50');
                    }, 300);
                } else {
                    alert('Browser Anda tidak mendukung text-to-speech');
                }
            }
        </script>
    @endpush
@endsection
