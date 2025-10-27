@extends('layouts.app')

@section('title', 'Permainan Puzzle - PANDA TK')

@section('content')
    <div class="space-y-6">
        <div class="flex justify-between items-center">
            <h1 class="text-3xl font-bold text-gray-800">
                🧩 Permainan Puzzle
            </h1>
            <a href="{{ route('permainan.index') }}"
                class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded-lg">
                <i class="fas fa-arrow-left mr-2"></i> Kembali
            </a>
        </div>

        <!-- Level Selection -->
        <div class="card bg-gradient-to-r from-blue-50 to-indigo-100 py-12 px-6 rounded-2xl shadow-md">
            <div class="card">
                <h2 class="text-xl font-bold mb-4 text-gray-800">
                    <i class="fas fa-layer-group mr-2 text-purple-500"></i>
                    Pilih Level Kesulitan
                </h2>
                <div class="flex gap-4 flex-wrap mb-4">
                    <button onclick="setDifficulty(2)"
                        class="bg-green-500 hover:bg-green-600 text-white font-bold py-3 px-6 rounded-lg">
                        😊 Mudah (2x2)
                    </button>
                    <button onclick="setDifficulty(3)"
                        class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-3 px-6 rounded-lg">
                        🤔 Sedang (3x3)
                    </button>
                    <button onclick="setDifficulty(4)"
                        class="bg-red-500 hover:bg-red-600 text-white font-bold py-3 px-6 rounded-lg">
                        😤 Sulit (4x4)
                    </button>
                </div>
            </div>

            <div class="grid lg:grid-cols-2 gap-6">
                <!-- Preview Image -->
                <div class="card">
                    <h2 class="text-xl font-bold mb-4 text-gray-800">
                        <i class="fas fa-image mr-2 text-blue-500"></i>
                        Gambar Asli
                    </h2>
                    <div class="bg-gray-100 p-4 rounded-xl">
                        <img id="preview-image" src="" alt="Preview" class="w-full rounded-lg shadow-lg">
                    </div>
                    <div class="mt-4">
                        <button onclick="changeImage()"
                            class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-lg w-full">
                            <i class="fas fa-sync-alt mr-2"></i> Ganti Gambar
                        </button>
                    </div>
                </div>

                <!-- Puzzle Board -->
                <div class="card">
                    <h2 class="text-xl font-bold mb-4 text-gray-800">
                        <i class="fas fa-puzzle-piece mr-2 text-green-500"></i>
                        Papan Puzzle <span id="timer" class="text-sm text-gray-600 ml-2"></span>
                    </h2>
                    <div class="bg-gray-200 p-4 rounded-xl">
                        <div id="puzzle-board" class="grid gap-2 mx-auto" style="width: fit-content;"></div>
                    </div>
                    <div class="mt-4 flex gap-2">
                        <button onclick="shufflePuzzle()"
                            class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-4 rounded-lg flex-1">
                            <i class="fas fa-shuffle mr-2"></i> Acak
                        </button>
                        <button onclick="resetPuzzle()"
                            class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded-lg flex-1">
                            <i class="fas fa-redo mr-2"></i> Reset
                        </button>
                    </div>
                </div>
            </div>

            <!-- Success Message -->
            <div id="success-message"
                class="card bg-gradient-to-r from-green-400 to-blue-400 text-white text-center py-8 hidden">
                <div class="text-6xl mb-4">🎉</div>
                <h2 class="text-3xl font-bold mb-2">Selamat!</h2>
                <p class="text-xl mb-2">Kamu berhasil menyelesaikan puzzle!</p>
                <p class="text-lg">Waktu: <span id="final-time"></span></p>
                <button onclick="resetPuzzle()"
                    class="mt-4 bg-white text-blue-600 font-bold py-3 px-8 rounded-full hover:bg-gray-100 transition">
                    Main Lagi
                </button>
            </div>
        </div>

        @push('styles')
            <style>
                .puzzle-piece {
                    width: 120px;
                    height: 120px;
                    background-size: cover;
                    background-position: center;
                    border-radius: 8px;
                    cursor: grab;
                    transition: all 0.3s;
                    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
                    border: 2px solid white;
                }

                .puzzle-piece:hover {
                    transform: scale(1.05);
                    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
                }

                .puzzle-piece.dragging {
                    opacity: 0.5;
                    cursor: grabbing;
                }

                .puzzle-slot {
                    width: 120px;
                    height: 120px;
                    background: rgba(255, 255, 255, 0.5);
                    border: 3px dashed #ccc;
                    border-radius: 8px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    transition: all 0.3s;
                }

                .puzzle-slot.drag-over {
                    background: rgba(59, 130, 246, 0.3);
                    border-color: #3b82f6;
                    transform: scale(1.05);
                }

                .puzzle-slot.filled {
                    border: 3px solid #10b981;
                    background: transparent;
                }
            </style>
        @endpush

        @push('scripts')
            <script>
                // GANTI PATH GAMBAR INI SESUAI DENGAN LOKASI GAMBAR ANDA
                const puzzleImages = [
                    '/images/puzzle/gambar1.jpg',
                    '/images/puzzle/gambar2.jpg',
                    '/images/puzzle/gambar3.jpg',
                    '/images/puzzle/gambar4.jpg',
                    '/images/puzzle/gambar5.jpg',
                    '/images/puzzle/gambar6.jpg',
                ];

                let currentImageSrc = '';
                let gridSize = 3;
                let pieces = [];
                let gameStartTime = null;
                let timerInterval = null;
                let audioContext = null;

                function initAudio() {
                    if (!audioContext) {
                        audioContext = new(window.AudioContext || window.webkitAudioContext)();
                    }
                }

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

                function setDifficulty(size) {
                    gridSize = size;
                    initPuzzle();
                }

                function changeImage() {
                    const currentIndex = puzzleImages.indexOf(currentImageSrc);
                    const nextIndex = (currentIndex + 1) % puzzleImages.length;
                    currentImageSrc = puzzleImages[nextIndex];
                    document.getElementById('preview-image').src = currentImageSrc;
                    initPuzzle();
                }

                function initPuzzle() {
                    if (!currentImageSrc) {
                        currentImageSrc = puzzleImages[Math.floor(Math.random() * puzzleImages.length)];
                        document.getElementById('preview-image').src = currentImageSrc;
                    }

                    gameStartTime = Date.now();
                    startTimer();

                    const board = document.getElementById('puzzle-board');
                    board.innerHTML = '';
                    board.style.gridTemplateColumns = `repeat(${gridSize}, 120px)`;

                    // Create pieces array
                    pieces = [];
                    for (let row = 0; row < gridSize; row++) {
                        for (let col = 0; col < gridSize; col++) {
                            pieces.push({
                                id: row * gridSize + col,
                                correctRow: row,
                                correctCol: col,
                                currentSlot: null
                            });
                        }
                    }

                    // Create slots
                    for (let i = 0; i < gridSize * gridSize; i++) {
                        const slot = document.createElement('div');
                        slot.className = 'puzzle-slot';
                        slot.dataset.slot = i;

                        slot.addEventListener('dragover', handleDragOver);
                        slot.addEventListener('dragleave', handleDragLeave);
                        slot.addEventListener('drop', handleDrop);

                        board.appendChild(slot);
                    }

                    shufflePuzzle();
                }

                function shufflePuzzle() {
                    const board = document.getElementById('puzzle-board');
                    const slots = board.querySelectorAll('.puzzle-slot');

                    // Clear all slots
                    slots.forEach(slot => {
                        slot.innerHTML = '';
                        slot.classList.remove('filled');
                    });

                    // Shuffle pieces array
                    const shuffled = [...pieces].sort(() => Math.random() - 0.5);

                    // Place pieces in slots
                    shuffled.forEach((piece, index) => {
                        piece.currentSlot = index;
                        const slot = slots[index];

                        const pieceEl = createPieceElement(piece);
                        slot.appendChild(pieceEl);
                        slot.classList.add('filled');
                    });

                    gameStartTime = Date.now();
                }

                function createPieceElement(piece) {
                    const pieceEl = document.createElement('div');
                    pieceEl.className = 'puzzle-piece';
                    pieceEl.draggable = true;
                    pieceEl.dataset.pieceId = piece.id;

                    // Calculate background position
                    const percentX = (piece.correctCol * 100) / (gridSize - 1);
                    const percentY = (piece.correctRow * 100) / (gridSize - 1);

                    pieceEl.style.backgroundImage = `url('${currentImageSrc}')`;
                    pieceEl.style.backgroundSize = `${gridSize * 120}px ${gridSize * 120}px`;
                    pieceEl.style.backgroundPosition = `-${piece.correctCol * 120}px -${piece.correctRow * 120}px`;

                    pieceEl.addEventListener('dragstart', handleDragStart);
                    pieceEl.addEventListener('dragend', handleDragEnd);

                    return pieceEl;
                }

                let draggedPiece = null;

                function handleDragStart(e) {
                    draggedPiece = e.target;
                    e.target.classList.add('dragging');
                    e.dataTransfer.effectAllowed = 'move';
                }

                function handleDragEnd(e) {
                    e.target.classList.remove('dragging');
                }

                function handleDragOver(e) {
                    if (e.preventDefault) {
                        e.preventDefault();
                    }
                    e.dataTransfer.dropEffect = 'move';

                    if (!e.target.classList.contains('puzzle-piece')) {
                        e.target.classList.add('drag-over');
                    }
                    return false;
                }

                function handleDragLeave(e) {
                    e.target.classList.remove('drag-over');
                }

                function handleDrop(e) {
                    if (e.stopPropagation) {
                        e.stopPropagation();
                    }

                    e.target.classList.remove('drag-over');

                    const targetSlot = e.target.classList.contains('puzzle-slot') ?
                        e.target :
                        e.target.closest('.puzzle-slot');

                    if (!targetSlot || !draggedPiece) return false;

                    const sourceSlot = draggedPiece.parentElement;
                    const targetPiece = targetSlot.querySelector('.puzzle-piece');

                    // Swap pieces
                    if (targetPiece) {
                        sourceSlot.appendChild(targetPiece);
                    } else {
                        sourceSlot.classList.remove('filled');
                    }

                    targetSlot.appendChild(draggedPiece);
                    sourceSlot.classList.add('filled');
                    targetSlot.classList.add('filled');

                    // Update piece positions
                    const draggedId = parseInt(draggedPiece.dataset.pieceId);
                    const draggedPieceData = pieces.find(p => p.id === draggedId);
                    const sourceSlotIndex = parseInt(sourceSlot.dataset.slot);
                    const targetSlotIndex = parseInt(targetSlot.dataset.slot);

                    if (targetPiece) {
                        const targetId = parseInt(targetPiece.dataset.pieceId);
                        const targetPieceData = pieces.find(p => p.id === targetId);
                        targetPieceData.currentSlot = sourceSlotIndex;
                    }

                    draggedPieceData.currentSlot = targetSlotIndex;

                    checkWin();

                    return false;
                }

                function checkWin() {
                    const isWin = pieces.every(piece => {
                        const expectedSlot = piece.correctRow * gridSize + piece.correctCol;
                        return piece.currentSlot === expectedSlot;
                    });

                    if (isWin) {
                        stopTimer();
                        const timeTaken = Math.floor((Date.now() - gameStartTime) / 1000);
                        document.getElementById('final-time').textContent = `${timeTaken} detik`;

                        playSuccessSound();
                        setTimeout(() => createConfetti(), 200);
                    }
                }

                function startTimer() {
                    stopTimer();
                    timerInterval = setInterval(() => {
                        const elapsed = Math.floor((Date.now() - gameStartTime) / 1000);
                        document.getElementById('timer').textContent = `⏱️ ${elapsed}s`;
                    }, 1000);
                }

                function stopTimer() {
                    if (timerInterval) {
                        clearInterval(timerInterval);
                        timerInterval = null;
                    }

                }


                function createConfetti() {
                    for (let i = 0; i < 50; i++) {
                        setTimeout(() => {
                            const confetti = document.createElement('div');
                            confetti.style.position = 'fixed';
                            confetti.style.left = Math.random() * 100 + '%';
                            confetti.style.top = '-10px';
                            confetti.style.width = '10px';
                            confetti.style.height = '10px';
                            confetti.style.background = ['#ff0000', '#00ff00', '#0000ff', '#ffff00', '#ff00ff', '#00ffff'][
                                Math.floor(Math.random() * 6)
                            ];
                            confetti.style.borderRadius = '50%';
                            confetti.style.zIndex = '9999';
                            confetti.style.pointerEvents = 'none';
                            document.body.appendChild(confetti);

                            let pos = -10;
                            let rotation = 0;
                            const fall = setInterval(() => {
                                pos += 5;
                                rotation += 15;
                                confetti.style.top = pos + 'px';
                                confetti.style.transform = `rotate(${rotation}deg)`;
                                if (pos > window.innerHeight) {
                                    clearInterval(fall);
                                    confetti.remove();
                                }
                            }, 20);
                        }, i * 30);
                    }
                }

                // Initialize on load
                window.addEventListener('load', () => {
                    initPuzzle();
                });
            </script>
        @endpush
    @endsection
