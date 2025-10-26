@extends('layouts.app')

@section('title', 'Hitung Jumlah Gambar - PANDA TK')

@section('content')
    <div class="space-y-6 text-center">
        {{-- Judul dan tombol kembali --}}
        <div class="flex justify-between items-center">
            <h1 class="text-3xl font-bold text-gray-800">➕ Hitung Jumlah Gambar</h1>
            <a href="{{ route('permainan.index') }}"
                class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded-lg transition">
                <i class="fas fa-arrow-left mr-2"></i> Kembali
            </a>
        </div>

        {{-- Area permainan --}}
        <div class="card bg-gradient-to-r from-blue-50 to-indigo-100 py-12 px-6 rounded-2xl shadow-md">
            <div id="emoji" class="text-8xl md:text-9xl mb-10 leading-snug"></div>

            <div id="choices" class="flex flex-wrap justify-center gap-6 mb-8"></div>

            <p id="result" class="text-2xl font-bold min-h-[2rem]"></p>
        </div>
    </div>

    @push('scripts')
        <script>
            const data = ['🐱', '🐶', '🐰', '🍎', '🍌', '🚗', '🚲', '🐻', '🍓', '🐘'];
            let current = {};

            function newQuestion() {
                const item = data[Math.floor(Math.random() * data.length)];
                const count = Math.floor(Math.random() * 9) + 1; // 1–9
                current = { emoji: item, count };

                document.getElementById('emoji').textContent = item.repeat(count);

                const choices = document.getElementById('choices');
                choices.innerHTML = '';

                // Buat 3 pilihan acak
                const options = [count, count + 1, count - 1]
                    .filter(n => n > 0)
                    .sort(() => Math.random() - 0.5);

                options.forEach(num => {
                    const btn = document.createElement('button');
                    btn.textContent = num;
                    btn.className =
                        "bg-blue-500 hover:bg-blue-600 text-white font-bold py-4 px-7 rounded-xl text-2xl shadow-md transition transform hover:scale-110";
                    btn.onclick = () => checkAnswer(num);
                    choices.appendChild(btn);
                });

                document.getElementById('result').textContent = '';
            }

            function checkAnswer(num) {
                const result = document.getElementById('result');
                const emojiBox = document.getElementById('emoji');

                if (num === current.count) {
                    result.textContent = "🎉 Benar sekali!";
                    result.className = "text-green-600 font-bold text-2xl";
                    emojiBox.classList.add('animate-bounce');

                    if ('speechSynthesis' in window) {
                        const u = new SpeechSynthesisUtterance("Benar, jumlahnya " + num);
                        u.lang = 'id-ID';
                        speechSynthesis.speak(u);
                    }

                    setTimeout(() => {
                        emojiBox.classList.remove('animate-bounce');
                        newQuestion();
                    }, 2000);
                } else {
                    result.textContent = "❌ Salah, coba lagi!";
                    result.className = "text-red-600 font-bold text-2xl";

                    if ('speechSynthesis' in window) {
                        const u = new SpeechSynthesisUtterance("Salah, coba lagi");
                        u.lang = 'id-ID';
                        speechSynthesis.speak(u);
                    }
                }
            }

            // Mulai permainan pertama kali
            newQuestion();
        </script>
    @endpush
@endsection
