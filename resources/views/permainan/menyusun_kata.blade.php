@extends('layouts.app')

@section('title', 'Susun Kata - PANDA TK')

@section('content')
    <div class="space-y-6 text-center">
        {{-- Judul dan tombol kembali --}}
        <div class="flex justify-between items-center">
            <h1 class="text-3xl font-bold text-gray-800">✏️ Susun Kata</h1>
            <a href="{{ route('permainan.index') }}"
                class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded-lg transition">
                <i class="fas fa-arrow-left mr-2"></i> Kembali
            </a>
        </div>

        {{-- Statistik permainan --}}
        <div class="grid grid-cols-3 gap-4 max-w-2xl mx-auto">
            <div class="bg-blue-100 rounded-lg p-4">
                <div class="text-2xl font-bold text-blue-600" id="skor">0</div>
                <div class="text-sm text-gray-600">Skor</div>
            </div>
            <div class="bg-green-100 rounded-lg p-4">
                <div class="text-2xl font-bold text-green-600" id="benar">0</div>
                <div class="text-sm text-gray-600">Benar</div>
            </div>
            <div class="bg-red-100 rounded-lg p-4">
                <div class="text-2xl font-bold text-red-600" id="salah">0</div>
                <div class="text-sm text-gray-600">Salah</div>
            </div>
        </div>

        {{-- Area permainan --}}
        <div class="card bg-gradient-to-r from-green-50 to-yellow-100 py-12 px-6 rounded-2xl shadow-md">
            <div id="ikon-item" class="text-8xl mb-8">❓</div>

            {{-- Area kata yang sedang disusun --}}
            <div id="kata-area" class="flex flex-wrap justify-center gap-3 mb-6 min-h-[5rem]"></div>

            {{-- Tombol hapus huruf terakhir --}}
            <button onclick="hapusHurufTerakhir()" id="btn-hapus"
                class="bg-orange-500 hover:bg-orange-600 text-white font-bold py-2 px-6 rounded-full mb-4 transition disabled:opacity-50 disabled:cursor-not-allowed">
                <i class="fas fa-backspace mr-2"></i> Hapus Huruf
            </button>

            {{-- Huruf yang bisa dipilih --}}
            <div id="huruf-container" class="flex flex-wrap justify-center gap-4 mb-8"></div>

            <p id="result" class="text-2xl font-bold min-h-[2rem]"></p>

            <div class="flex justify-center gap-4 mt-6">
                <button onclick="resetKata()"
                    class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-6 rounded-full transition">
                    <i class="fas fa-redo mr-2"></i> Reset
                </button>
                <button onclick="lewatiSoal()"
                    class="bg-purple-500 hover:bg-purple-600 text-white font-bold py-2 px-6 rounded-full transition">
                    <i class="fas fa-forward mr-2"></i> Lewati
                </button>
                <button onclick="tampilkanHint()" id="btn-hint"
                    class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-6 rounded-full transition">
                    <i class="fas fa-lightbulb mr-2"></i> Petunjuk
                </button>
            </div>
        </div>

        {{-- Progress Bar --}}
        <div class="max-w-2xl mx-auto">
            <div class="bg-gray-200 rounded-full h-4 overflow-hidden">
                <div id="progress-bar"
                    class="bg-gradient-to-r from-blue-500 to-purple-500 h-full transition-all duration-500"
                    style="width: 0%"></div>
            </div>
            <p class="text-sm text-gray-600 mt-2">Soal <span id="soal-sekarang">1</span> dari <span
                    id="total-soal">12</span></p>
        </div>
    </div>

    @push('scripts')
        <script>
            // Data gabungan: Hewan, Buah, Transportasi
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
                    kata: 'STROBERI',
                    ikon: '🍓',
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
                }
            ];

            let currentData = null;
            let currentWord = [];
            let hurufButtons = [];
            let skor = 0;
            let benar = 0;
            let salah = 0;
            let soalSekarang = 0;
            let hintDigunakan = false;

            const ikonItem = document.getElementById('ikon-item');
            const hurufContainer = document.getElementById('huruf-container');
            const kataArea = document.getElementById('kata-area');
            const result = document.getElementById('result');
            const btnHapus = document.getElementById('btn-hapus');
            const btnHint = document.getElementById('btn-hint');

            function newQuestion() {
                // Pilih kata acak
                currentData = semuaItem[Math.floor(Math.random() * semuaItem.length)];
                currentWord = [];
                hurufButtons = [];
                hintDigunakan = false;
                soalSekarang++;

                updateKataArea();
                result.textContent = '';
                ikonItem.textContent = currentData.ikon;
                btnHapus.disabled = true;
                btnHint.disabled = false;
                btnHint.classList.remove('opacity-50');

                // Update progress
                document.getElementById('soal-sekarang').textContent = soalSekarang;
                const progress = (soalSekarang / semuaItem.length) * 100;
                document.getElementById('progress-bar').style.width = progress + '%';

                // Buat tombol huruf
                hurufContainer.innerHTML = '';
                const hurufArray = shuffle(currentData.kata.split(''));
                hurufArray.forEach((h, index) => {
                    const btn = document.createElement('button');
                    btn.textContent = h;
                    btn.className =
                        'bg-blue-500 hover:bg-blue-600 text-white font-bold py-4 px-6 rounded-xl text-3xl shadow-md transition transform hover:scale-110';
                    btn.dataset.huruf = h;
                    btn.dataset.index = index;
                    btn.onclick = () => pilihHuruf(h, btn);
                    hurufContainer.appendChild(btn);
                    hurufButtons.push(btn);
                });
            }

            function shuffle(array) {
                const arr = [...array];
                for (let i = arr.length - 1; i > 0; i--) {
                    const j = Math.floor(Math.random() * (i + 1));
                    [arr[i], arr[j]] = [arr[j], arr[i]];
                }
                return arr;
            }

            function updateKataArea() {
                kataArea.innerHTML = '';

                if (currentWord.length === 0) {
                    const placeholder = document.createElement('div');
                    placeholder.textContent = '_ _ _';
                    placeholder.className = 'text-4xl text-gray-400 font-bold';
                    kataArea.appendChild(placeholder);
                } else {
                    currentWord.forEach((h, idx) => {
                        const box = document.createElement('div');
                        box.textContent = h;
                        box.className =
                            'bg-white border-4 border-blue-500 text-blue-600 font-bold py-3 px-5 rounded-xl text-3xl shadow-md animate-bounce';
                        box.style.animationDuration = '0.5s';
                        box.style.animationIterationCount = '1';
                        kataArea.appendChild(box);
                    });
                }

                btnHapus.disabled = currentWord.length === 0;
            }

            function pilihHuruf(h, btn) {
                currentWord.push(h);
                updateKataArea();
                btn.disabled = true;
                btn.classList.add('opacity-50', 'cursor-not-allowed');

                // Efek suara klik
                playSound('click');

                if (currentWord.length === currentData.kata.length) {
                    checkAnswer();
                }
            }

            function hapusHurufTerakhir() {
                if (currentWord.length === 0) return;

                const hurufTerakhir = currentWord.pop();
                updateKataArea();

                // Aktifkan kembali tombol huruf yang dihapus
                const btn = Array.from(hurufContainer.children).find(b =>
                    b.textContent === hurufTerakhir && b.disabled
                );
                if (btn) {
                    btn.disabled = false;
                    btn.classList.remove('opacity-50', 'cursor-not-allowed');
                }

                playSound('pop');
            }

            function checkAnswer() {
                const jawabanUser = currentWord.join('');

                if (jawabanUser === currentData.kata) {
                    const poin = hintDigunakan ? 5 : 10;
                    skor += poin;
                    benar++;

                    result.textContent = `🎉 Benar sekali! +${poin} poin`;
                    result.className = "text-green-600 font-bold text-2xl";
                    ikonItem.classList.add('animate-bounce');

                    updateStats();
                    playSound('success');

                    if ('speechSynthesis' in window) {
                        const u = new SpeechSynthesisUtterance(`Benar, kata ini ${currentData.kata}`);
                        u.lang = 'id-ID';
                        speechSynthesis.speak(u);
                    }

                    setTimeout(() => {
                        ikonItem.classList.remove('animate-bounce');
                        newQuestion();
                    }, 2000);
                } else {
                    salah++;
                    result.textContent = "❌ Salah, coba lagi!";
                    result.className = "text-red-600 font-bold text-2xl";

                    updateStats();
                    playSound('error');

                    if ('speechSynthesis' in window) {
                        const u = new SpeechSynthesisUtterance("Salah, coba lagi");
                        u.lang = 'id-ID';
                        speechSynthesis.speak(u);
                    }

                    // Reset jawaban setelah 1 detik
                    setTimeout(() => {
                        resetJawaban();
                    }, 1000);
                }
            }

            function resetJawaban() {
                currentWord = [];
                updateKataArea();
                result.textContent = '';

                // Aktifkan semua tombol huruf
                hurufButtons.forEach(btn => {
                    btn.disabled = false;
                    btn.classList.remove('opacity-50', 'cursor-not-allowed');
                });
            }

            function resetKata() {
                resetJawaban();
            }

            function lewatiSoal() {
                if (confirm('Yakin ingin melewati soal ini?')) {
                    newQuestion();
                }
            }

            function tampilkanHint() {
                if (hintDigunakan) return;

                hintDigunakan = true;
                btnHint.disabled = true;
                btnHint.classList.add('opacity-50');

                // Tampilkan huruf pertama
                const hurufPertama = currentData.kata[0];
                const btn = Array.from(hurufContainer.children).find(b =>
                    b.textContent === hurufPertama && !b.disabled
                );

                if (btn) {
                    pilihHuruf(hurufPertama, btn);
                }

                // Tampilkan kategori
                result.textContent = `💡 Petunjuk: Ini adalah ${currentData.kategori}`;
                result.className = "text-yellow-600 font-bold text-xl";

                setTimeout(() => {
                    result.textContent = '';
                }, 3000);
            }

            function updateStats() {
                document.getElementById('skor').textContent = skor;
                document.getElementById('benar').textContent = benar;
                document.getElementById('salah').textContent = salah;
            }

            function playSound(type) {
                // Simulasi efek suara dengan Web Audio API
                const audioContext = new(window.AudioContext || window.webkitAudioContext)();
                const oscillator = audioContext.createOscillator();
                const gainNode = audioContext.createGain();

                oscillator.connect(gainNode);
                gainNode.connect(audioContext.destination);

                switch (type) {
                    case 'click':
                        oscillator.frequency.value = 800;
                        gainNode.gain.value = 0.1;
                        oscillator.start();
                        oscillator.stop(audioContext.currentTime + 0.1);
                        break;
                    case 'success':
                        oscillator.frequency.value = 1000;
                        gainNode.gain.value = 0.2;
                        oscillator.start();
                        oscillator.stop(audioContext.currentTime + 0.3);
                        break;
                    case 'error':
                        oscillator.frequency.value = 200;
                        gainNode.gain.value = 0.2;
                        oscillator.start();
                        oscillator.stop(audioContext.currentTime + 0.2);
                        break;
                    case 'pop':
                        oscillator.frequency.value = 600;
                        gainNode.gain.value = 0.1;
                        oscillator.start();
                        oscillator.stop(audioContext.currentTime + 0.05);
                        break;
                }
            }

            // Keyboard support
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Backspace' || e.key === 'Delete') {
                    e.preventDefault();
                    hapusHurufTerakhir();
                } else if (e.key === 'Enter') {
                    if (currentWord.length === currentData.kata.length) {
                        checkAnswer();
                    }
                } else if (e.key === 'Escape') {
                    resetKata();
                }
            });

            // Mulai permainan pertama kali
            newQuestion();
        </script>
    @endpush
@endsection
