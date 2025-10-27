@extends('layouts.app')

@section('title', 'Urutkan Angka - PANDA TK')

@section('content')
    <div class="space-y-6">
        <div class="flex justify-between items-center">
            <h1 class="text-3xl font-bold text-gray-800">1️⃣5️⃣3️⃣ Urutkan Angka</h1>
            <a href="{{ route('permainan.index') }}"
                class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded-lg">
                <i class="fas fa-arrow-left mr-2"></i> Kembali
            </a>
        </div>

        <div class="card bg-gradient-to-r from-blue-50 to-indigo-100 py-12 px-6 rounded-2xl shadow-md">
            <div class="card text-center">
                <h2 class="text-xl font-bold text-gray-700 mb-4">Susun angka dari kecil ke besar!</h2>

                <div class="text-lg mb-3 text-gray-600 font-semibold">
                    Level: <span id="level-text" class="text-blue-600 font-bold">1 (5 Angka)</span>
                </div>

                <div id="angka-container"
                    class="flex flex-wrap justify-center gap-4 p-4 rounded-xl min-h-[150px] transition-all duration-300">
                </div>

                <div class="mt-4 space-x-2">
                    <button onclick="cekUrutan()"
                        class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-6 rounded-lg transition transform hover:scale-105">
                        ✅ Cek Urutan
                    </button>
                    <button onclick="resetLevel()"
                        class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-6 rounded-lg transition transform hover:scale-105">
                        🔁 Ulang Level
                    </button>
                </div>

                <p id="pesan" class="text-xl font-bold text-gray-800 mt-4 min-h-[2rem]"></p>
            </div>
        </div>

        @push('styles')
            <style>
                .draggable {
                    width: 100px;
                    height: 100px;
                    font-size: 2.5rem;
                    font-weight: bold;
                    color: white;
                    background-color: #60a5fa;
                    border-radius: 20px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    cursor: grab;
                    transition: transform 0.2s, box-shadow 0.2s;
                    user-select: none;
                }

                .draggable:active {
                    cursor: grabbing;
                }

                .draggable:hover {
                    transform: scale(1.05);
                    box-shadow: 0 0 10px rgba(59, 130, 246, 0.5);
                }

                .dragging {
                    opacity: 0.5;
                    transform: scale(1.1);
                }

                .drag-over {
                    outline: 4px dashed #2563eb;
                    background-color: #bfdbfe;
                }

                @keyframes confetti-fall {
                    to {
                        transform: translateY(100vh) rotate(360deg);
                    }
                }
            </style>
        @endpush

        @push('scripts')
            <script>
                let level = 1;
                const levelConfig = [4, 6, 8, 12, 16, 20];
                const angkaContainer = document.getElementById('angka-container');
                let angka = [];
                let draggedElement = null;
                let audioContext = null;

                function initAudio() {
                    if (!audioContext) {
                        audioContext = new(window.AudioContext || window.webkitAudioContext)();
                    }
                }

                function playSuccessSound() {
                    initAudio();
                    const freqs = [523.25, 659.25, 783.99, 1046.50];
                    freqs.forEach((f, i) => {
                        setTimeout(() => {
                            const o = audioContext.createOscillator();
                            const g = audioContext.createGain();
                            o.connect(g);
                            g.connect(audioContext.destination);
                            o.type = 'sine';
                            o.frequency.value = f;
                            g.gain.setValueAtTime(0.3, audioContext.currentTime);
                            g.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime + 0.3);
                            o.start(audioContext.currentTime);
                            o.stop(audioContext.currentTime + 0.3);
                        }, i * 100);
                    });
                }

                function playFailSound() {
                    initAudio();
                    const freqs = [261.63, 196.00, 130.81];
                    freqs.forEach((f, i) => {
                        setTimeout(() => {
                            const o = audioContext.createOscillator();
                            const g = audioContext.createGain();
                            o.connect(g);
                            g.connect(audioContext.destination);
                            o.type = 'square';
                            o.frequency.value = f;
                            g.gain.setValueAtTime(0.3, audioContext.currentTime);
                            g.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime + 0.3);
                            o.start(audioContext.currentTime);
                            o.stop(audioContext.currentTime + 0.3);
                        }, i * 150);
                    });
                }

                function createConfetti() {
                    const colors = ['#f87171', '#60a5fa', '#34d399', '#fbbf24', '#a78bfa', '#ec4899', '#f97316'];
                    for (let i = 0; i < 80; i++) {
                        setTimeout(() => {
                            const c = document.createElement('div');
                            c.style.position = 'fixed';
                            c.style.left = Math.random() * 100 + '%';
                            c.style.top = '-20px';
                            c.style.width = Math.random() * 10 + 5 + 'px';
                            c.style.height = Math.random() * 10 + 5 + 'px';
                            c.style.background = colors[Math.floor(Math.random() * colors.length)];
                            c.style.borderRadius = Math.random() > 0.5 ? '50%' : '0';
                            c.style.zIndex = '9999';
                            c.style.pointerEvents = 'none';
                            c.style.transform = `rotate(${Math.random() * 360}deg)`;
                            document.body.appendChild(c);

                            let pos = -20;
                            let rotation = Math.random() * 360;
                            const horizontalDrift = (Math.random() - 0.5) * 3;
                            let left = parseFloat(c.style.left);

                            const fall = setInterval(() => {
                                pos += 5;
                                rotation += 5;
                                left += horizontalDrift;
                                c.style.top = pos + 'px';
                                c.style.left = left + '%';
                                c.style.transform = `rotate(${rotation}deg)`;

                                if (pos > window.innerHeight + 50) {
                                    clearInterval(fall);
                                    c.remove();
                                }
                            }, 16);
                        }, i * 20);
                    }
                }

                function acak(arr) {
                    for (let i = arr.length - 1; i > 0; i--) {
                        const j = Math.floor(Math.random() * (i + 1));
                        [arr[i], arr[j]] = [arr[j], arr[i]];
                    }
                    return arr;
                }

                function buatAngka() {
                    angkaContainer.innerHTML = '';
                    angka = Array.from({
                        length: levelConfig[level - 1]
                    }, (_, i) => i + 1);
                    const acakAngka = acak([...angka]);

                    acakAngka.forEach(num => {
                        const div = document.createElement('div');
                        div.textContent = num;
                        div.className = 'draggable';
                        div.draggable = true;
                        div.dataset.number = num;

                        div.addEventListener('dragstart', handleDragStart);
                        div.addEventListener('dragover', handleDragOver);
                        div.addEventListener('drop', handleDrop);
                        div.addEventListener('dragenter', handleDragEnter);
                        div.addEventListener('dragleave', handleDragLeave);
                        div.addEventListener('dragend', handleDragEnd);

                        angkaContainer.appendChild(div);
                    });

                    document.getElementById('level-text').textContent = `${level} (${levelConfig[level - 1]} Angka)`;
                    document.getElementById('pesan').textContent = '';
                }

                function handleDragStart(e) {
                    draggedElement = this;
                    this.classList.add('dragging');
                    e.dataTransfer.effectAllowed = 'move';
                    e.dataTransfer.setData('text/html', this.innerHTML);
                }

                function handleDragOver(e) {
                    if (e.preventDefault) {
                        e.preventDefault();
                    }
                    e.dataTransfer.dropEffect = 'move';
                    return false;
                }

                function handleDragEnter(e) {
                    if (this !== draggedElement) {
                        this.classList.add('drag-over');
                    }
                }

                function handleDragLeave(e) {
                    this.classList.remove('drag-over');
                }

                function handleDrop(e) {
                    if (e.stopPropagation) {
                        e.stopPropagation();
                    }
                    e.preventDefault();

                    if (draggedElement !== this) {
                        // Swap elements
                        const allItems = [...angkaContainer.children];
                        const draggedIndex = allItems.indexOf(draggedElement);
                        const targetIndex = allItems.indexOf(this);

                        if (draggedIndex < targetIndex) {
                            this.parentNode.insertBefore(draggedElement, this.nextSibling);
                        } else {
                            this.parentNode.insertBefore(draggedElement, this);
                        }
                    }

                    this.classList.remove('drag-over');
                    return false;
                }

                function handleDragEnd(e) {
                    this.classList.remove('dragging');
                    document.querySelectorAll('.draggable').forEach(el => {
                        el.classList.remove('drag-over');
                    });
                }

                function cekUrutan() {
                    const items = [...angkaContainer.children].map(d => parseInt(d.textContent));
                    const benar = items.every((num, i) => num === i + 1);
                    const pesan = document.getElementById('pesan');

                    if (benar) {
                        pesan.textContent = "🎉 Hebat! Urutannya benar!";
                        pesan.className = "text-xl font-bold text-green-600 mt-4 min-h-[2rem]";
                        playSuccessSound();
                        createConfetti();
                        setTimeout(() => nextLevel(), 2500);
                    } else {
                        pesan.textContent = "❌ Coba lagi ya!";
                        pesan.className = "text-xl font-bold text-red-600 mt-4 min-h-[2rem]";
                        playFailSound();
                    }
                }

                function nextLevel() {
                    if (level < levelConfig.length) {
                        level++;
                        buatAngka();
                    } else {
                        const pesan = document.getElementById('pesan');
                        pesan.textContent = "🏆 Semua level selesai! Hebat sekali!";
                        pesan.className = "text-xl font-bold text-purple-600 mt-4 min-h-[2rem]";
                        createConfetti();
                        playSuccessSound();
                    }
                }

                function resetLevel() {
                    buatAngka();
                }

                // Initialize game
                buatAngka();
            </script>
        @endpush
    @endsection
