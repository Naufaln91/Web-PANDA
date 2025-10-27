@extends('layouts.app')

@section('title', 'Susun Kata - PANDA TK')

@section('content')
    <div class="space-y-6">
        {{-- Header --}}
        <div class="flex justify-between items-center">
            <h1 class="text-3xl font-bold text-gray-800">✏️ Susun Kata</h1>
            <a href="{{ route('permainan.index') }}"
                class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded-lg transition">
                <i class="fas fa-arrow-left mr-2"></i> Kembali
            </a>
        </div>

        {{-- Main Game Area --}}
        <div class="max-w-4xl mx-auto">
            <div
                class="bg-gradient-to-br from-purple-50 via-pink-50 to-blue-50 rounded-3xl shadow-2xl p-8 border-4 border-white">

                {{-- Progress Bar --}}
                <div class="mb-6">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm font-semibold text-gray-600">Pertanyaan <span id="soal-sekarang">1</span> dari
                            <span id="total-soal">12</span></span>
                        <span class="text-sm font-semibold text-purple-600" id="kategori-badge">Mulai</span>
                    </div>
                    <div class="bg-white rounded-full h-3 overflow-hidden shadow-inner">
                        <div id="progress-bar"
                            class="bg-gradient-to-r from-purple-500 via-pink-500 to-blue-500 h-full transition-all duration-500 rounded-full"
                            style="width: 0%"></div>
                    </div>
                </div>

                {{-- Icon Display --}}
                <div class="text-center mb-6">
                    <div id="ikon-item"
                        class="text-9xl mb-4 inline-block transform transition-all duration-300 hover:scale-110">
                        ❓
                    </div>
                    <p class="text-lg font-semibold text-gray-700">Susun huruf-huruf di bawah!</p>
                </div>

                {{-- Word Area --}}
                <div id="kata-area"
                    class="flex flex-wrap justify-center gap-3 mb-8 min-h-[6rem] items-center p-4 bg-white/50 rounded-2xl backdrop-blur-sm">
                </div>

                {{-- Letter Buttons --}}
                <div id="huruf-container"
                    class="flex flex-wrap justify-center gap-3 mb-8 p-4 bg-white/30 rounded-2xl backdrop-blur-sm">
                </div>

                {{-- Result Message --}}
                <div class="text-center mb-6 min-h-[3rem] flex items-center justify-center">
                    <p id="result" class="text-2xl font-bold"></p>
                </div>

                {{-- Action Buttons --}}
                <div class="flex flex-wrap justify-center gap-3">
                    <button onclick="hapusHurufTerakhir()" id="btn-hapus"
                        class="bg-orange-500 hover:bg-orange-600 text-white font-bold py-3 px-6 rounded-full transition transform hover:scale-105 shadow-lg disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:scale-100">
                        <i class="fas fa-backspace mr-2"></i> Hapus
                    </button>
                    <button onclick="resetKata()"
                        class="bg-red-500 hover:bg-red-600 text-white font-bold py-3 px-6 rounded-full transition transform hover:scale-105 shadow-lg">
                        <i class="fas fa-redo mr-2"></i> Reset
                    </button>
                    <button onclick="tampilkanHint()" id="btn-hint"
                        class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-3 px-6 rounded-full transition transform hover:scale-105 shadow-lg disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:scale-100">
                        <i class="fas fa-lightbulb mr-2"></i> Petunjuk
                    </button>
                    <button onclick="lewatiSoal()"
                        class="bg-purple-500 hover:bg-purple-600 text-white font-bold py-3 px-6 rounded-full transition transform hover:scale-105 shadow-lg">
                        <i class="fas fa-forward mr-2"></i> Lewati
                    </button>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
        <style>
            @keyframes bounce-in {
                0% {
                    transform: scale(0);
                    opacity: 0;
                }

                50% {
                    transform: scale(1.1);
                }

                100% {
                    transform: scale(1);
                    opacity: 1;
                }
            }

            @keyframes shake {

                0%,
                100% {
                    transform: translateX(0);
                }

                25% {
                    transform: translateX(-10px);
                }

                75% {
                    transform: translateX(10px);
                }
            }

            .letter-box {
                animation: bounce-in 0.3s ease-out;
            }

            .shake-animation {
                animation: shake 0.5s ease-in-out;
            }

            .icon-success {
                animation: bounce 1s ease-in-out infinite;
            }

            @keyframes bounce {

                0%,
                100% {
                    transform: translateY(0);
                }

                50% {
                    transform: translateY(-20px);
                }
            }
        </style>
    @endpush

    @push('scripts')
        <script>
            const semuaItem = [{
                    kata: 'KUCING',
                    ikon: '🐱',
                    kategori: 'Hewan'
                },
                {
                    kata: 'ANJING',
                    ikon: '🐶',
                    kategori: 'Hewan'
                },
                {
                    kata: 'BURUNG',
                    ikon: '🐦',
                    kategori: 'Hewan'
                },
                {
                    kata: 'SINGA',
                    ikon: '🦁',
                    kategori: 'Hewan'
                },
                {
                    kata: 'GAJAH',
                    ikon: '🐘',
                    kategori: 'Hewan'
                },
                {
                    kata: 'KELINCI',
                    ikon: '🐰',
                    kategori: 'Hewan'
                },
                {
                    kata: 'APEL',
                    ikon: '🍎',
                    kategori: 'Buah'
                },
                {
                    kata: 'PISANG',
                    ikon: '🍌',
                    kategori: 'Buah'
                },
                {
                    kata: 'ANGGUR',
                    ikon: '🍇',
                    kategori: 'Buah'
                },
                {
                    kata: 'JERUK',
                    ikon: '🍊',
                    kategori: 'Buah'
                },
                {
                    kata: 'MOBIL',
                    ikon: '🚗',
                    kategori: 'Transportasi'
                },
                {
                    kata: 'MOTOR',
                    ikon: '🏍️',
                    kategori: 'Transportasi'
                },
                {
                    kata: 'KAPAL',
                    ikon: '⛴️',
                    kategori: 'Transportasi'
                },
                {
                    kata: 'PESAWAT',
                    ikon: '✈️',
                    kategori: 'Transportasi'
                },
                {
                    kata: 'BUS',
                    ikon: '🚌',
                    kategori: 'Transportasi'
                },
                {
                    kata: 'KERETA',
                    ikon: '🚂',
                    kategori: 'Transportasi'
                }
            ];

            let currentData = null;
            let currentWord = [];
            let hurufButtons = [];
            let soalSekarang = 0;
            let hintDigunakan = false;
            let audioContext = null;

            const ikonItem = document.getElementById('ikon-item');
            const hurufContainer = document.getElementById('huruf-container');
            const kataArea = document.getElementById('kata-area');
            const result = document.getElementById('result');
            const btnHapus = document.getElementById('btn-hapus');
            const btnHint = document.getElementById('btn-hint');

            function initAudio() {
                if (!audioContext) {
                    audioContext = new(window.AudioContext || window.webkitAudioContext)();
                }
            }

            function playSuccessSound() {
                initAudio();
                const notes = [523.25, 659.25, 783.99, 1046.50];
                notes.forEach((freq, i) => {
                    setTimeout(() => {
                        const osc = audioContext.createOscillator();
                        const gain = audioContext.createGain();
                        osc.connect(gain);
                        gain.connect(audioContext.destination);
                        osc.type = 'sine';
                        osc.frequency.value = freq;
                        gain.gain.setValueAtTime(0.3, audioContext.currentTime);
                        gain.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime + 0.4);
                        osc.start(audioContext.currentTime);
                        osc.stop(audioContext.currentTime + 0.4);
                    }, i * 120);
                });
            }

            function playFailSound() {
                initAudio();
                const notes = [293.66, 246.94, 196.00];
                notes.forEach((freq, i) => {
                    setTimeout(() => {
                        const osc = audioContext.createOscillator();
                        const gain = audioContext.createGain();
                        osc.connect(gain);
                        gain.connect(audioContext.destination);
                        osc.type = 'sawtooth';
                        osc.frequency.value = freq;
                        gain.gain.setValueAtTime(0.3, audioContext.currentTime);
                        gain.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime + 0.3);
                        osc.start(audioContext.currentTime);
                        osc.stop(audioContext.currentTime + 0.3);
                    }, i * 150);
                });
            }

            function createConfetti() {
                const colors = ['#f87171', '#60a5fa', '#34d399', '#fbbf24', '#a78bfa', '#ec4899', '#f97316'];
                for (let i = 0; i < 100; i++) {
                    setTimeout(() => {
                        const confetti = document.createElement('div');
                        confetti.style.position = 'fixed';
                        confetti.style.left = Math.random() * 100 + '%';
                        confetti.style.top = '-20px';
                        confetti.style.width = Math.random() * 12 + 6 + 'px';
                        confetti.style.height = Math.random() * 12 + 6 + 'px';
                        confetti.style.background = colors[Math.floor(Math.random() * colors.length)];
                        confetti.style.borderRadius = Math.random() > 0.5 ? '50%' : '0';
                        confetti.style.zIndex = '9999';
                        confetti.style.pointerEvents = 'none';
                        confetti.style.transform = `rotate(${Math.random() * 360}deg)`;
                        confetti.style.opacity = '0.9';
                        document.body.appendChild(confetti);

                        let pos = -20;
                        let rotation = Math.random() * 360;
                        const drift = (Math.random() - 0.5) * 4;
                        let left = parseFloat(confetti.style.left);

                        const fall = setInterval(() => {
                            pos += 6;
                            rotation += 8;
                            left += drift;
                            confetti.style.top = pos + 'px';
                            confetti.style.left = left + '%';
                            confetti.style.transform = `rotate(${rotation}deg)`;

                            if (pos > window.innerHeight + 50) {
                                clearInterval(fall);
                                confetti.remove();
                            }
                        }, 16);
                    }, i * 15);
                }
            }

            function shuffle(array) {
                const arr = [...array];
                for (let i = arr.length - 1; i > 0; i--) {
                    const j = Math.floor(Math.random() * (i + 1));
                    [arr[i], arr[j]] = [arr[j], arr[i]];
                }
                return arr;
            }

            function newQuestion() {
                currentData = semuaItem[Math.floor(Math.random() * semuaItem.length)];
                currentWord = [];
                hurufButtons = [];
                hintDigunakan = false;
                soalSekarang++;

                updateKataArea();
                result.textContent = '';
                ikonItem.textContent = currentData.ikon;
                ikonItem.classList.remove('icon-success', 'shake-animation');
                btnHapus.disabled = true;
                btnHint.disabled = false;

                // Update kategori badge dengan warna
                const kategoriBadge = document.getElementById('kategori-badge');
                kategoriBadge.textContent = currentData.kategori;
                if (currentData.kategori === 'Hewan') {
                    kategoriBadge.className = 'text-sm font-semibold text-green-600 bg-green-100 px-3 py-1 rounded-full';
                } else if (currentData.kategori === 'Buah') {
                    kategoriBadge.className = 'text-sm font-semibold text-orange-600 bg-orange-100 px-3 py-1 rounded-full';
                } else {
                    kategoriBadge.className = 'text-sm font-semibold text-blue-600 bg-blue-100 px-3 py-1 rounded-full';
                }

                // Update progress
                document.getElementById('soal-sekarang').textContent = soalSekarang;
                document.getElementById('total-soal').textContent = semuaItem.length;
                const progress = (soalSekarang / semuaItem.length) * 100;
                document.getElementById('progress-bar').style.width = progress + '%';

                // Create letter buttons
                hurufContainer.innerHTML = '';
                const hurufArray = shuffle(currentData.kata.split(''));
                hurufArray.forEach((huruf, index) => {
                    const btn = document.createElement('button');
                    btn.textContent = huruf;
                    btn.className =
                        'bg-gradient-to-br from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-bold py-4 px-6 rounded-xl text-2xl shadow-lg transition transform hover:scale-110 hover:-rotate-3';
                    btn.dataset.huruf = huruf;
                    btn.dataset.index = index;
                    btn.onclick = () => pilihHuruf(huruf, btn);
                    hurufContainer.appendChild(btn);
                    hurufButtons.push(btn);
                });
            }

            function updateKataArea() {
                kataArea.innerHTML = '';

                if (currentWord.length === 0) {
                    const placeholder = document.createElement('div');
                    placeholder.textContent = 'Pilih huruf...';
                    placeholder.className = 'text-3xl text-gray-400 font-semibold italic';
                    kataArea.appendChild(placeholder);
                } else {
                    currentWord.forEach((huruf, idx) => {
                        const box = document.createElement('div');
                        box.textContent = huruf;
                        box.className =
                            'letter-box bg-white border-4 border-purple-400 text-purple-600 font-bold py-4 px-5 rounded-xl text-3xl shadow-lg';
                        kataArea.appendChild(box);
                    });
                }

                btnHapus.disabled = currentWord.length === 0;
            }

            function pilihHuruf(huruf, btn) {
                currentWord.push(huruf);
                updateKataArea();
                btn.disabled = true;
                btn.classList.add('opacity-30', 'cursor-not-allowed', 'scale-90');
                btn.classList.remove('hover:scale-110');

                if (currentWord.length === currentData.kata.length) {
                    setTimeout(() => checkAnswer(), 300);
                }
            }

            function hapusHurufTerakhir() {
                if (currentWord.length === 0) return;

                const hurufTerakhir = currentWord.pop();
                updateKataArea();

                const btn = Array.from(hurufContainer.children).find(b =>
                    b.textContent === hurufTerakhir && b.disabled
                );
                if (btn) {
                    btn.disabled = false;
                    btn.classList.remove('opacity-30', 'cursor-not-allowed', 'scale-90');
                    btn.classList.add('hover:scale-110');
                }
            }

            function checkAnswer() {
                const jawabanUser = currentWord.join('');

                if (jawabanUser === currentData.kata) {
                    result.textContent = `🎉 Benar Sekali! Ini ${currentData.kata}!`;
                    result.className = "text-green-600 font-bold text-2xl";
                    ikonItem.classList.add('icon-success');

                    playSuccessSound();
                    createConfetti();

                    setTimeout(() => {
                        if (soalSekarang >= semuaItem.length) {
                            result.textContent = "🏆 Semua Soal Selesai! Hebat!";
                            result.className = "text-purple-600 font-bold text-2xl";
                            createConfetti();
                        } else {
                            newQuestion();
                        }
                    }, 3000);
                } else {
                    result.textContent = "❌ Ups, Salah! Coba Lagi!";
                    result.className = "text-red-600 font-bold text-2xl";
                    ikonItem.classList.add('shake-animation');

                    playFailSound();

                    setTimeout(() => {
                        ikonItem.classList.remove('shake-animation');
                        resetJawaban();
                    }, 1500);
                }
            }

            function resetJawaban() {
                currentWord = [];
                updateKataArea();
                result.textContent = '';

                hurufButtons.forEach(btn => {
                    btn.disabled = false;
                    btn.classList.remove('opacity-30', 'cursor-not-allowed', 'scale-90');
                    btn.classList.add('hover:scale-110');
                });
            }

            function resetKata() {
                resetJawaban();
            }

            function lewatiSoal() {
                if (confirm('Yakin ingin melewati soal ini?')) {
                    if (soalSekarang >= semuaItem.length) {
                        result.textContent = "🏆 Semua Soal Sudah Selesai!";
                        result.className = "text-purple-600 font-bold text-2xl";
                    } else {
                        newQuestion();
                    }
                }
            }

            function tampilkanHint() {
                if (hintDigunakan) return;

                hintDigunakan = true;
                btnHint.disabled = true;

                const hurufPertama = currentData.kata[0];
                const btn = Array.from(hurufContainer.children).find(b =>
                    b.textContent === hurufPertama && !b.disabled
                );

                if (btn) {
                    btn.classList.add('ring-4', 'ring-yellow-400', 'ring-offset-2');
                    setTimeout(() => {
                        btn.classList.remove('ring-4', 'ring-yellow-400', 'ring-offset-2');
                        pilihHuruf(hurufPertama, btn);
                    }, 1000);
                }

                result.textContent = `💡 Huruf pertama adalah "${hurufPertama}"`;
                result.className = "text-yellow-600 font-bold text-xl";

                setTimeout(() => {
                    if (currentWord.length < currentData.kata.length) {
                        result.textContent = '';
                    }
                }, 3000);
            }

            // Keyboard support
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Backspace' || e.key === 'Delete') {
                    e.preventDefault();
                    hapusHurufTerakhir();
                } else if (e.key === 'Escape') {
                    resetKata();
                }
            });

            // Start game
            newQuestion();
        </script>
    @endpush
@endsection
