@extends('layouts.app')

@section('title', 'Belajar Alfabet - PANDA TK')

@section('content')
    <div class="space-y-6">
        <div class="flex justify-between items-center">
            <h1 class="text-3xl font-bold text-gray-800">
                🔤 Belajar Alfabet
            </h1>
            <a href="{{ route('materi.index') }}"
                class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded-lg">
                <i class="fas fa-arrow-left mr-2"></i> Kembali
            </a>
        </div>

        {{-- Grid untuk pilihan huruf --}}
        <div class="card card bg-gradient-to-r from-blue-100 to-indigo-100 py-12 px-6 rounded-2xl shadow-md">
            <div id="alfabet-container" class="grid grid-cols-4 md:grid-cols-7 gap-4">
            </div>


            {{-- Kartu display utama --}}
            <div class="card bg-gradient-to-r from-blue-200 to-purple-200 mt-6 rounded-2xl">
                <div class="text-center p-8">

                    {{-- Kontainer untuk Huruf dan Gambar (Emoji) --}}
                    <div class="flex justify-center items-center gap-8 mb-6">
                        <div id="selected-letter" class="text-9xl font-bold text-blue-600">A</div>
                        <div id="selected-image" class="text-9xl">🍎</div>
                    </div>

                    {{-- Nama Benda --}}
                    <h2 id="selected-name" class="text-5xl font-bold text-gray-800 mb-4">Apel</h2>

                    <p class="text-2xl text-gray-700 mb-4">Klik huruf untuk mendengar cara pengucapannya!</p>
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

            // --- BARU: Database nama benda dan gambar (emoji) ---
            const objekData = {
                'A': {
                    nama: 'Apel',
                    gambar: '🍎'
                },
                'B': {
                    nama: 'Bola',
                    gambar: '⚽'
                },
                'C': {
                    nama: 'Cicak',
                    gambar: '🦎'
                },
                'D': {
                    nama: 'Dasi',
                    gambar: '👔'
                },
                'E': {
                    nama: 'Elang',
                    gambar: '🦅'
                },
                'F': {
                    nama: 'Foto',
                    gambar: '🖼️'
                },
                'G': {
                    nama: 'Gajah',
                    gambar: '🐘'
                },
                'H': {
                    nama: 'Harimau',
                    gambar: '🐅'
                },
                'I': {
                    nama: 'Ikan',
                    gambar: '🐟'
                },
                'J': {
                    nama: 'Jeruk',
                    gambar: '🍊'
                },
                'K': {
                    nama: 'Kucing',
                    gambar: '🐈'
                },
                'L': {
                    nama: 'Lampu',
                    gambar: '💡'
                },
                'M': {
                    nama: 'Mobil',
                    gambar: '🚗'
                },
                'N': {
                    nama: 'Nanas',
                    gambar: '🍍'
                },
                'O': {
                    nama: 'Obor',
                    gambar: '🔥'
                },
                'P': {
                    nama: 'Pohon',
                    gambar: '🌳'
                },
                'Q': {
                    nama: 'Quran',
                    gambar: '📖'
                },
                'R': {
                    nama: 'Roti',
                    gambar: '🍞'
                },
                'S': {
                    nama: 'Sapi',
                    gambar: '🐄'
                },
                'T': {
                    nama: 'Topi',
                    gambar: '👒'
                },
                'U': {
                    nama: 'Ubur-ubur',
                    gambar: '🐙'
                },
                'V': {
                    nama: 'Vas',
                    gambar: '🏺'
                },
                'W': {
                    nama: 'Wortel',
                    gambar: '🥕'
                },
                'X': {
                    nama: 'Xilofon',
                    gambar: '🎶'
                }, // Emoji xilofon tidak ada, diganti not musik
                'Y': {
                    nama: 'Yoyo',
                    gambar: '🪀'
                },
                'Z': {
                    nama: 'Zebra',
                    gambar: '🦓'
                }
            };

            // Generate tombol huruf
            const container = document.getElementById('alfabet-container');
            alfabet.forEach((letter, index) => {
                const colorClass = colors[index % colors.length];
                const letterDiv = document.createElement('div');
                letterDiv.className =
                    `${colorClass} hover:scale-110 transition transform cursor-pointer rounded-2xl p-6 text-center shadow-lg`;
                letterDiv.innerHTML = `<span class="text-4xl font-bold text-white">${letter}</span>`;

                // --- DIMODIFIKASI: Arahkan ke selectLetter ---
                letterDiv.onclick = () => selectLetter(letter);
                container.appendChild(letterDiv);
            });

            // --- DIMODIFIKASI: Fungsi untuk memilih huruf ---
            function selectLetter(letter) {
                currentLetter = letter;
                const data = objekData[letter]; // Ambil data benda dari database

                // Update tampilan di kartu display
                document.getElementById('selected-letter').textContent = letter;
                document.getElementById('selected-image').textContent = data.gambar;
                document.getElementById('selected-name').textContent = data.nama;

                // Putar suara
                playCurrentSound();
            }

            // --- DIMODIFIKASI: Fungsi untuk memutar suara ---
            function playCurrentSound() {
                // Ambil data untuk huruf saat ini
                const data = objekData[currentLetter];
                // Buat teks yang akan diucapkan (misal: "A. Apel.")
                const textToSpeak = `${currentLetter}. ${data.nama}`;

                if ('speechSynthesis' in window) {
                    const utterance = new SpeechSynthesisUtterance(textToSpeak);

                    // --- REVISI 1: Ganti bahasa ke Indonesia ---
                    utterance.lang = 'id-ID';

                    utterance.rate = 0.8;
                    utterance.pitch = 1.1; // Sedikit disesuaikan agar lebih jelas
                    speechSynthesis.speak(utterance);

                    // Animasi
                    const letterEl = document.getElementById('selected-letter');
                    const imageEl = document.getElementById('selected-image');
                    letterEl.classList.add('animate-bounce');
                    imageEl.classList.add('animate-bounce');

                    setTimeout(() => {
                        letterEl.classList.remove('animate-bounce');
                        imageEl.classList.remove('animate-bounce');
                    }, 1000);
                } else {
                    alert('Browser Anda tidak mendukung text-to-speech');
                }
            }

            // Auto play huruf pertama ("A") saat halaman dimuat
            setTimeout(() => playCurrentSound(), 500);
        </script>
    @endpush
@endsection
