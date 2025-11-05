@extends('layouts.app')

@section('title', 'Cocokkan Pasangan - PANDA TK')

@section('content')

    <div class="space-y-6">
        <div class="flex justify-between items-center">
            <h1 class="text-3xl font-bold text-gray-800">🍇🍇 Cocokkan Pasangan</h1>
            <a href="{{ route('permainan.index') }}"
                class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded-lg">
                <i class="fas fa-arrow-left mr-2"></i> Kembali
            </a>
        </div>

        <!-- Area permainan -->
        <div
            class="card bg-gradient-to-r from-blue-100 to-indigo-100 py-12 px-6 rounded-2xl shadow-md relative overflow-hidden">
            <div class="flex flex-col items-center justify-center min-h-[70vh]">
                <div id="game" class="grid gap-4 justify-center mb-3"></div>
                <p id="status" class="text-xl font-bold text-green-600 mt-2"></p>
                <button id="restartBtn"
                    class="hidden bg-gradient-to-r from-purple-500 to-pink-500 hover:from-purple-600 hover:to-pink-600 text-white font-bold py-3 px-8 rounded-lg shadow-lg transform hover:scale-105 transition mt-6">
                    <i class="fas fa-redo mr-2"></i> Main Lagi dari Awal
                </button>
            </div>

            <!-- Canvas untuk confetti -->
            <canvas id="confetti-canvas" class="absolute top-0 left-0 w-full h-full pointer-events-none"></canvas>
        </div>

        @push('scripts')
            <script>
                const emojis = ['🍎', '🍌', '🍇', '🍓', '🍒', '🍊', '🍉', '🍍', '🥝', '🍋'];
                let flipped = [];
                let matched = 0;
                let level = 1;
                let audioContext = null;
                let isChecking = false;
                const levelPairs = [2, 3, 4, 6, 10]; // jumlah pasangan per level

                function initAudio() {
                    if (!audioContext) {
                        audioContext = new(window.AudioContext || window.webkitAudioContext)();
                    }
                }

                function initLevel() {
                    const pairs = levelPairs[level - 1];
                    const selected = emojis.slice(0, pairs);
                    const items = [...selected, ...selected].sort(() => Math.random() - 0.5);
                    const game = document.getElementById('game');
                    flipped = [];
                    matched = 0;
                    isChecking = false;
                    document.getElementById('status').textContent = '';
                    document.getElementById('restartBtn').classList.add('hidden');

                    // Tentukan kolom dengan style inline
                    let gridCols;
                    const totalCards = items.length;

                    if (totalCards <= 4) {
                        gridCols = 2;
                    } else if (totalCards <= 9) {
                        gridCols = 3;
                    } else if (totalCards <= 16) {
                        gridCols = 4;
                    } else {
                        gridCols = 5; // untuk 20 kartu
                    }

                    game.className = 'grid gap-4 justify-center mb-3';
                    game.style.gridTemplateColumns = `repeat(${gridCols}, minmax(0, 1fr))`;
                    game.innerHTML = '';

                    items.forEach((emoji) => {
                        const card = document.createElement('div');
                        card.className =
                            "bg-blue-200 w-[120px] h-[120px] rounded-2xl flex items-center justify-center text-6xl cursor-pointer shadow-lg hover:scale-105 transition";
                        card.dataset.value = emoji;
                        card.onclick = () => flipCard(card);
                        game.appendChild(card);
                    });
                }

                function flipCard(card) {
                    if (isChecking || card.classList.contains('matched') || card.classList.contains('flipped')) {
                        return;
                    }

                    if (flipped.length >= 2) {
                        return;
                    }

                    card.textContent = card.dataset.value;
                    card.classList.add('flipped');
                    flipped.push(card);

                    if (flipped.length === 2) {
                        isChecking = true;
                        setTimeout(checkMatch, 700);
                    }
                }

                function checkMatch() {
                    const [a, b] = flipped;
                    if (a && b && a.dataset.value === b.dataset.value) {
                        matched += 1;
                        a.style.background = '#86efac';
                        b.style.background = '#86efac';
                        a.classList.add('matched');
                        b.classList.add('matched');
                        playSuccessSound();

                        if (matched === levelPairs[level - 1]) {
                            if (level < levelPairs.length) {
                                document.getElementById('status').textContent = "🎉 Hebat! Lanjut ke level berikutnya...";
                                createConfetti(); // Confetti setiap level selesai
                                level++;
                                setTimeout(initLevel, 2000);
                            } else {
                                // Level terakhir selesai
                                document.getElementById('status').textContent = "🏆 Keren banget! Semua pasangan sudah cocok!";
                                createConfetti(); // Confetti level terakhir
                                // Tampilkan tombol restart setelah confetti muncul
                                setTimeout(() => {
                                    document.getElementById('restartBtn').classList.remove('hidden');
                                }, 1000);
                            }
                        }
                    } else {
                        if (a && b) {
                            a.textContent = '';
                            b.textContent = '';
                            a.classList.remove('flipped');
                            b.classList.remove('flipped');
                            playFailSound();
                        }
                    }
                    flipped = [];
                    isChecking = false;
                }

                // --- Efek suara sukses ---
                function playSuccessSound() {
                    initAudio();
                    const frequencies = [523.25, 659.25, 783.99, 1046.50];
                    frequencies.forEach((freq, i) => {
                        setTimeout(() => {
                            const oscillator = audioContext.createOscillator();
                            const gainNode = audioContext.createGain();

                            oscillator.connect(gainNode);
                            gainNode.connect(audioContext.destination);

                            oscillator.frequency.value = freq;
                            oscillator.type = 'sine';

                            gainNode.gain.setValueAtTime(0.3, audioContext.currentTime);
                            gainNode.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime + 0.3);

                            oscillator.start(audioContext.currentTime);
                            oscillator.stop(audioContext.currentTime + 0.3);
                        }, i * 100);
                    });
                }

                // --- Efek suara gagal ---
                function playFailSound() {
                    initAudio();
                    const frequencies = [261.63, 196.00, 130.81];
                    frequencies.forEach((freq, i) => {
                        setTimeout(() => {
                            const oscillator = audioContext.createOscillator();
                            const gainNode = audioContext.createGain();

                            oscillator.connect(gainNode);
                            gainNode.connect(audioContext.destination);

                            oscillator.frequency.value = freq;
                            oscillator.type = 'square';

                            gainNode.gain.setValueAtTime(0.3, audioContext.currentTime);
                            gainNode.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime + 0.3);

                            oscillator.start(audioContext.currentTime);
                            oscillator.stop(audioContext.currentTime + 0.3);
                        }, i * 150);
                    });
                }

                // --- Efek confetti 🎊 (sama seperti permainan hitung) ---
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

                // Fungsi restart game
                function restartGame() {
                    level = 1;
                    matched = 0;
                    flipped = [];
                    isChecking = false;
                    document.getElementById('status').textContent = '';
                    document.getElementById('restartBtn').classList.add('hidden');
                    initLevel();
                }

                // Event listener untuk tombol restart
                document.getElementById('restartBtn').addEventListener('click', restartGame);

                initLevel();
            </script>
        @endpush
    @endsection
