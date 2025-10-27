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
        <div
            class="card bg-gradient-to-r from-blue-50 to-indigo-100 py-12 px-6 rounded-2xl shadow-md relative overflow-hidden">
            <div id="emoji" class="text-8xl md:text-9xl mb-10 leading-snug"></div>

            <div id="choices" class="flex flex-wrap justify-center gap-6 mb-8"></div>

            <p id="result" class="text-2xl font-bold min-h-[2rem]"></p>

            <!-- Canvas untuk confetti -->
            <canvas id="confetti-canvas" class="absolute top-0 left-0 w-full h-full pointer-events-none"></canvas>
        </div>
    </div>

    @push('scripts')
        <script>
            const data = ['🐱', '🐶', '🐰', '🍎', '🍌', '🚗', '🚲', '🐻', '🍓', '🐘'];
            let current = {};
            let audioContext;

            function initAudio() {
                if (!audioContext) {
                    audioContext = new(window.AudioContext || window.webkitAudioContext)();
                }
            }

            // ✅ Sound effect sukses (not naik)
            function playSuccessSound() {
                initAudio();
                const frequencies = [523.25, 659.25, 783.99, 1046.50]; // C5, E5, G5, C6
                frequencies.forEach((freq, i) => {
                    setTimeout(() => {
                        const oscillator = audioContext.createOscillator();
                        const gainNode = audioContext.createGain();
                        oscillator.connect(gainNode);
                        gainNode.connect(audioContext.destination);

                        oscillator.frequency.value = freq;
                        oscillator.type = 'sine';

                        gainNode.gain.setValueAtTime(0.3, audioContext.currentTime);
                        gainNode.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime + 0.25);

                        oscillator.start(audioContext.currentTime);
                        oscillator.stop(audioContext.currentTime + 0.25);
                    }, i * 120);
                });
            }

            // ❌ Sound effect salah (not turun + sedikit buzz)
            function playErrorSound() {
                initAudio();
                const frequencies = [440, 330, 220]; // A4 → E4 → A3
                frequencies.forEach((freq, i) => {
                    setTimeout(() => {
                        const oscillator = audioContext.createOscillator();
                        const gainNode = audioContext.createGain();
                        oscillator.connect(gainNode);
                        gainNode.connect(audioContext.destination);

                        oscillator.frequency.value = freq;
                        oscillator.type = 'square'; // sedikit "kasar"

                        gainNode.gain.setValueAtTime(0.3, audioContext.currentTime);
                        gainNode.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime + 0.3);

                        oscillator.start(audioContext.currentTime);
                        oscillator.stop(audioContext.currentTime + 0.3);
                    }, i * 150);
                });
            }

            // 🎊 Efek confetti sederhana
            function createConfetti() {
                const canvas = document.getElementById('confetti-canvas');
                const ctx = canvas.getContext('2d');
                const particles = [];

                const colors = ['#60a5fa', '#34d399', '#facc15', '#f87171', '#a78bfa'];

                const W = canvas.width = canvas.offsetWidth;
                const H = canvas.height = canvas.offsetHeight;

                for (let i = 0; i < 60; i++) {
                    particles.push({
                        x: Math.random() * W,
                        y: Math.random() * -H / 2,
                        r: Math.random() * 6 + 3,
                        color: colors[Math.floor(Math.random() * colors.length)],
                        speed: Math.random() * 3 + 2,
                        tilt: Math.random() * 10 - 5
                    });
                }

                function draw() {
                    ctx.clearRect(0, 0, W, H);
                    particles.forEach(p => {
                        ctx.beginPath();
                        ctx.fillStyle = p.color;
                        ctx.fillRect(p.x, p.y, p.r, p.r);
                    });
                }

                function update() {
                    particles.forEach(p => {
                        p.y += p.speed;
                        p.x += Math.sin(p.tilt);
                    });
                }

                function loop() {
                    draw();
                    update();
                    if (particles.some(p => p.y < H)) {
                        requestAnimationFrame(loop);
                    }
                }
                loop();
            }

            // 🔢 Soal baru
            function newQuestion() {
                const item = data[Math.floor(Math.random() * data.length)];
                const count = Math.floor(Math.random() * 9) + 1;
                current = {
                    emoji: item,
                    count
                };

                document.getElementById('emoji').textContent = item.repeat(count);

                const choices = document.getElementById('choices');
                choices.innerHTML = '';

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

            // 🧩 Cek jawaban
            function checkAnswer(num) {
                const result = document.getElementById('result');
                const emojiBox = document.getElementById('emoji');

                if (num === current.count) {
                    result.textContent = "🎉 Benar sekali!";
                    result.className = "text-green-600 font-bold text-2xl";
                    emojiBox.classList.add('animate-bounce');

                    playSuccessSound();
                    setTimeout(() => createConfetti(), 200);

                    setTimeout(() => {
                        emojiBox.classList.remove('animate-bounce');
                        newQuestion();
                    }, 2000);
                } else {
                    result.textContent = "❌ Salah, coba lagi!";
                    result.className = "text-red-600 font-bold text-2xl";
                    playErrorSound();
                }
            }

            // 🚀 Mulai permainan pertama kali
            newQuestion();
        </script>
    @endpush
@endsection
