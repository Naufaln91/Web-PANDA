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

        <div class="card">
            <div id="alfabet-container" class="grid grid-cols-4 md:grid-cols-7 gap-4">
                <!-- Huruf akan di-generate oleh JavaScript -->
            </div>
        </div>

        <div class="card bg-gradient-to-r from-blue-100 to-purple-100">
            <div class="text-center">
                <div id="selected-letter" class="text-9xl font-bold text-blue-600 mb-4">A</div>
                <p class="text-2xl text-gray-700 mb-4">Klik huruf untuk mendengar cara pengucapannya!</p>
                <button id="play-sound-btn" onclick="playCurrentSound()"
                    class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-3 px-8 rounded-full text-xl transition">
                    <i class="fas fa-volume-up mr-2"></i> Dengarkan
                </button>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            const alfabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ'.split('');
            const colors = ['bg-red-400', 'bg-blue-400', 'bg-green-400', 'bg-yellow-400', 'bg-purple-400', 'bg-pink-400',
                'bg-indigo-400'
            ];
            let currentLetter = 'A';

            // Generate huruf
            const container = document.getElementById('alfabet-container');
            alfabet.forEach((letter, index) => {
                const colorClass = colors[index % colors.length];
                const letterDiv = document.createElement('div');
                letterDiv.className =
                    `${colorClass} hover:scale-110 transition transform cursor-pointer rounded-2xl p-6 text-center shadow-lg`;
                letterDiv.innerHTML = `<span class="text-4xl font-bold text-white">${letter}</span>`;
                letterDiv.onclick = () => selectLetter(letter);
                container.appendChild(letterDiv);
            });

            function selectLetter(letter) {
                currentLetter = letter;
                document.getElementById('selected-letter').textContent = letter;
                playCurrentSound();
            }

            function playCurrentSound() {
                // Gunakan Web Speech API untuk text-to-speech
                if ('speechSynthesis' in window) {
                    const utterance = new SpeechSynthesisUtterance(currentLetter);
                    utterance.lang = 'en-US';
                    utterance.rate = 0.8;
                    utterance.pitch = 1.2;
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

            // Auto play first letter
            setTimeout(() => playCurrentSound(), 500);
        </script>
    @endpush
@endsection
