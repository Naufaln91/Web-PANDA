@extends('layouts.app')

@section('title', 'Belajar Alfabet - PANDA TK')

@section('content')
    <div class="space-y-4 sm:space-y-6">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
            <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold text-gray-800">
                🔤 Belajar Alfabet
            </h1>
            <a href="{{ route('materi.index') }}"
                class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded-lg text-sm sm:text-base w-full sm:w-auto text-center">
                <i class="fas fa-arrow-left mr-2"></i> Kembali
            </a>
        </div>

        {{-- Grid untuk pilihan huruf --}}
        <div class="card bg-gradient-to-r from-blue-100 to-indigo-100 py-6 sm:py-8 lg:py-12 px-3 sm:px-6 rounded-xl sm:rounded-2xl shadow-md">
            <div id="alfabet-container" class="grid grid-cols-5 sm:grid-cols-7 lg:grid-cols-9 gap-2 sm:gap-3 lg:gap-4">
            </div>


            {{-- Kartu display utama --}}
            <div class="card bg-gradient-to-r from-blue-200 to-purple-200 mt-4 sm:mt-6 rounded-xl sm:rounded-2xl">
                <div class="text-center p-4 sm:p-6 lg:p-8">

                    {{-- Kontainer untuk Huruf --}}
                    <div class="flex justify-center items-center gap-4 sm:gap-8 mb-4 sm:mb-6">
                        <div id="selected-letter" class="text-5xl sm:text-7xl lg:text-9xl font-bold text-blue-600">A</div>
                    </div>

                    <p class="text-sm sm:text-lg lg:text-2xl text-gray-700 mb-3 sm:mb-4">Klik huruf untuk mendengar cara pengucapannya!</p>
                    <button id="play-sound-btn" onclick="playCurrentSound()"
                        class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2.5 sm:py-3 px-6 sm:px-8 rounded-full text-base sm:text-lg lg:text-xl transition w-full sm:w-auto">
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
                    `${colorClass} hover:scale-110 transition transform cursor-pointer rounded-lg sm:rounded-xl lg:rounded-2xl p-3 sm:p-4 lg:p-6 text-center shadow-lg`;
                letterDiv.innerHTML = `<span class="text-xl sm:text-2xl lg:text-4xl font-bold text-white">${letter}</span>`;

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
                    letterEl.classList.add('animate-bounce');

                    setTimeout(() => {
                        letterEl.classList.remove('animate-bounce');
                    }, 1000);
                } else {
                    alert('Browser Anda tidak mendukung text-to-speech');
                }
            }
        </script>
    @endpush
@endsection
