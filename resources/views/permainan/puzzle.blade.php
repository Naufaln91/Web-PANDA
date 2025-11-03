@extends('layouts.app')

@section('title', 'Permainan Puzzle - PANDA TK')

@section('content')
    <div class="space-y-6">
        <div class="flex justify-between items-center">
            <h1 class="text-3xl font-bold text-gray-800">
                🧩 Permainan Puzzle
            </h1>
            <a href="{{ route('permainan.index') }}"
                class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded-lg transition-colors duration-200">
                <i class="fas fa-arrow-left mr-2" aria-hidden="true"></i> Kembali
            </a>
        </div>

        <!-- Level Selection -->
        <div class="card bg-gradient-to-r from-blue-50 to-indigo-100 py-12 px-6 rounded-2xl shadow-md">
            <div class="card">
                <h2 class="text-xl font-bold mb-4 text-gray-800">
                    <i class="fas fa-layer-group mr-2 text-purple-500" aria-hidden="true"></i>
                    Pilih Level Kesulitan
                </h2>
                <div class="flex gap-4 flex-wrap mb-4" role="group" aria-label="Pilih tingkat kesulitan puzzle">
                    <button onclick="PuzzleGame.setDifficulty(2)"
                        class="difficulty-btn bg-green-500 hover:bg-green-600 text-white font-bold py-3 px-6 rounded-lg transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-green-300"
                        aria-label="Level mudah - 2x2">
                        😊 Mudah (2x2)
                    </button>
                    <button onclick="PuzzleGame.setDifficulty(3)"
                        class="difficulty-btn bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-3 px-6 rounded-lg transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-yellow-300"
                        aria-label="Level sedang - 3x3">
                        🤔 Sedang (3x3)
                    </button>
                    <button onclick="PuzzleGame.setDifficulty(4)"
                        class="difficulty-btn bg-red-500 hover:bg-red-600 text-white font-bold py-3 px-6 rounded-lg transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-red-300"
                        aria-label="Level sulit - 4x4">
                        😤 Sulit (4x4)
                    </button>
                </div>
            </div>

            <div class="grid lg:grid-cols-2 gap-6">
                <!-- Preview Image -->
                <div class="card">
                    <h2 class="text-xl font-bold mb-4 text-gray-800">
                        <i class="fas fa-image mr-2 text-blue-500" aria-hidden="true"></i>
                        Gambar Asli
                    </h2>
                    <div class="bg-gray-100 p-4 rounded-xl">
                        <img id="preview-image" src="" alt="Gambar puzzle yang akan diselesaikan"
                            class="w-full rounded-lg shadow-lg">
                    </div>
                    <div class="mt-4">
                        <button onclick="PuzzleGame.changeImage()"
                            class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-lg w-full transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-blue-300">
                            <i class="fas fa-sync-alt mr-2" aria-hidden="true"></i> Ganti Gambar
                        </button>
                    </div>
                </div>

                <!-- Puzzle Board -->
                <div class="card">
                    <h2 class="text-xl font-bold mb-4 text-gray-800">
                        <i class="fas fa-puzzle-piece mr-2 text-green-500" aria-hidden="true"></i>
                        Papan Puzzle <span id="timer" class="text-sm text-gray-600 ml-2" aria-live="polite"></span>
                    </h2>
                    <div class="bg-gray-200 p-4 rounded-xl">
                        <div id="puzzle-board" class="grid gap-2 mx-auto" style="width: fit-content;" role="grid"
                            aria-label="Area puzzle" tabindex="0"></div>
                    </div>
                    <div class="mt-4 flex gap-2">
                        <button onclick="PuzzleGame.shufflePuzzle()"
                            class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-4 rounded-lg flex-1 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-yellow-300">
                            <i class="fas fa-shuffle mr-2" aria-hidden="true"></i> Acak
                        </button>
                        <button onclick="PuzzleGame.resetPuzzle()"
                            class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded-lg flex-1 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-red-300">
                            <i class="fas fa-redo mr-2" aria-hidden="true"></i> Reset
                        </button>
                    </div>
                </div>
            </div>
        </div>

        @push('styles')
            <style>
                /* Puzzle piece styles */
                .puzzle-piece {
                    width: 120px;
                    height: 120px;
                    background-size: cover;
                    background-position: center;
                    border-radius: 8px;
                    cursor: grab;
                    transition: transform 0.2s ease, box-shadow 0.2s ease, opacity 0.2s ease;
                    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
                    border: 2px solid white;
                    user-select: none;
                    -webkit-user-select: none;
                    -moz-user-select: none;
                    -ms-user-select: none;
                }

                .puzzle-piece:hover {
                    transform: scale(1.05);
                    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
                }

                .puzzle-piece.dragging {
                    opacity: 0.5;
                    cursor: grabbing;
                    transform: scale(1.1);
                    z-index: 1000;
                }

                /* Puzzle slot styles */
                .puzzle-slot {
                    width: 120px;
                    height: 120px;
                    background: rgba(255, 255, 255, 0.5);
                    border: 3px dashed #ccc;
                    border-radius: 8px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    transition: all 0.2s ease;
                    position: relative;
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

                /* Button optimizations */
                .difficulty-btn {
                    transition: all 0.2s ease;
                }

                .difficulty-btn:active {
                    transform: translateY(1px);
                }

                /* Focus styles for accessibility */
                .puzzle-board:focus {
                    outline: 2px solid #3b82f6;
                    outline-offset: 2px;
                }

                /* Confetti animation optimization */
                .confetti {
                    position: fixed;
                    width: 10px;
                    height: 10px;
                    border-radius: 50%;
                    z-index: 9999;
                    pointer-events: none;
                    will-change: transform, top;
                }

                /* Responsive adjustments */
                @media (max-width: 768px) {

                    .puzzle-piece,
                    .puzzle-slot {
                        width: 80px;
                        height: 80px;
                    }
                }

                @media (max-width: 480px) {

                    .puzzle-piece,
                    .puzzle-slot {
                        width: 60px;
                        height: 60px;
                    }
                }
            </style>
        @endpush

        @push('scripts')
            <script>
                /**
                 * Optimized Puzzle Game Module
                 * Features: Modular structure, performance optimizations, accessibility, error handling
                 */
                const PuzzleGame = (() => {
                    'use strict';

                    // Configuration
                    const CONFIG = {
                        images: [
                            '/images/puzzle/gambar1.jpg',
                            '/images/puzzle/gambar2.jpg',
                            '/images/puzzle/gambar3.jpg',
                            '/images/puzzle/gambar4.jpg',
                            '/images/puzzle/gambar5.jpg',
                            '/images/puzzle/gambar6.jpg',
                        ],
                        pieceSize: 120,
                        confettiCount: 50,
                        soundFrequencies: [523.25, 659.25, 783.99, 1046.50],
                        timerInterval: 1000
                    };

                    // State management
                    let state = {
                        currentImageSrc: '',
                        gridSize: 3,
                        pieces: [],
                        gameStartTime: null,
                        timerInterval: null,
                        audioContext: null,
                        draggedPiece: null,
                        isGameActive: false
                    };

                    // DOM element cache
                    const elements = {
                        previewImage: null,
                        puzzleBoard: null,
                        timer: null
                    };

                    // Initialize DOM element cache
                    function cacheElements() {
                        elements.previewImage = document.getElementById('preview-image');
                        elements.puzzleBoard = document.getElementById('puzzle-board');
                        elements.timer = document.getElementById('timer');
                    }

                    // Audio management
                    const AudioManager = {
                        init() {
                            if (!state.audioContext) {
                                try {
                                    state.audioContext = new(window.AudioContext || window.webkitAudioContext)();
                                } catch (error) {
                                    console.warn('Audio context not supported:', error);
                                }
                            }
                        },

                        playSuccessSound() {
                            if (!state.audioContext) return;

                            try {
                                CONFIG.soundFrequencies.forEach((freq, i) => {
                                    setTimeout(() => {
                                        const oscillator = state.audioContext.createOscillator();
                                        const gainNode = state.audioContext.createGain();

                                        oscillator.connect(gainNode);
                                        gainNode.connect(state.audioContext.destination);

                                        oscillator.frequency.value = freq;
                                        oscillator.type = 'sine';

                                        gainNode.gain.setValueAtTime(0.3, state.audioContext
                                            .currentTime);
                                        gainNode.gain.exponentialRampToValueAtTime(0.01, state
                                            .audioContext.currentTime + 0.3);

                                        oscillator.start(state.audioContext.currentTime);
                                        oscillator.stop(state.audioContext.currentTime + 0.3);
                                    }, i * 100);
                                });
                            } catch (error) {
                                console.warn('Error playing sound:', error);
                            }
                        }
                    };

                    // Timer management
                    const TimerManager = {
                        start() {
                            this.stop();
                            state.gameStartTime = Date.now();
                            state.timerInterval = setInterval(() => {
                                if (elements.timer && state.gameStartTime) {
                                    const elapsed = Math.floor((Date.now() - state.gameStartTime) / 1000);
                                    elements.timer.textContent = `⏱️ ${elapsed}s`;
                                }
                            }, CONFIG.timerInterval);
                        },

                        stop() {
                            if (state.timerInterval) {
                                clearInterval(state.timerInterval);
                                state.timerInterval = null;
                            }
                        },

                        getElapsedTime() {
                            return state.gameStartTime ? Math.floor((Date.now() - state.gameStartTime) / 1000) : 0;
                        }
                    };

                    // Confetti animation with performance optimization
                    const ConfettiManager = {
                        create() {
                            const fragment = document.createDocumentFragment();

                            for (let i = 0; i < CONFIG.confettiCount; i++) {
                                setTimeout(() => {
                                    const confetti = this.createConfettiElement();
                                    fragment.appendChild(confetti);
                                    document.body.appendChild(confetti);
                                    this.animateConfetti(confetti);
                                }, i * 30);
                            }
                        },

                        createConfettiElement() {
                            const confetti = document.createElement('div');
                            confetti.className = 'confetti';
                            confetti.style.left = Math.random() * 100 + '%';
                            confetti.style.background = this.getRandomColor();
                            return confetti;
                        },

                        getRandomColor() {
                            const colors = ['#ff0000', '#00ff00', '#0000ff', '#ffff00', '#ff00ff', '#00ffff'];
                            return colors[Math.floor(Math.random() * colors.length)];
                        },

                        animateConfetti(element) {
                            let pos = -10;
                            let rotation = 0;

                            const animate = () => {
                                pos += 5;
                                rotation += 15;
                                element.style.top = pos + 'px';
                                element.style.transform = `rotate(${rotation}deg)`;

                                if (pos > window.innerHeight) {
                                    element.remove();
                                } else {
                                    requestAnimationFrame(animate);
                                }
                            };

                            requestAnimationFrame(animate);
                        }
                    };

                    // Puzzle piece management
                    const PieceManager = {
                        createPieces() {
                            state.pieces = [];
                            for (let row = 0; row < state.gridSize; row++) {
                                for (let col = 0; col < state.gridSize; col++) {
                                    state.pieces.push({
                                        id: row * state.gridSize + col,
                                        correctRow: row,
                                        correctCol: col,
                                        currentSlot: null
                                    });
                                }
                            }
                        },

                        createPieceElement(piece) {
                            const pieceEl = document.createElement('div');
                            pieceEl.className = 'puzzle-piece';
                            pieceEl.draggable = true;
                            pieceEl.dataset.pieceId = piece.id;
                            pieceEl.setAttribute('role', 'gridcell');
                            pieceEl.setAttribute('aria-label', `Puzzle piece ${piece.id + 1}`);

                            // Calculate background position
                            const pieceSize = this.getPieceSize();
                            pieceEl.style.backgroundImage = `url('${state.currentImageSrc}')`;
                            pieceEl.style.backgroundSize =
                                `${state.gridSize * pieceSize}px ${state.gridSize * pieceSize}px`;
                            pieceEl.style.backgroundPosition =
                                `-${piece.correctCol * pieceSize}px -${piece.correctRow * pieceSize}px`;

                            // Event listeners
                            pieceEl.addEventListener('dragstart', this.handleDragStart.bind(this));
                            pieceEl.addEventListener('dragend', this.handleDragEnd.bind(this));

                            return pieceEl;
                        },

                        getPieceSize() {
                            return window.innerWidth <= 480 ? 60 : window.innerWidth <= 768 ? 80 : CONFIG.pieceSize;
                        },

                        handleDragStart(e) {
                            state.draggedPiece = e.target;
                            e.target.classList.add('dragging');
                            e.dataTransfer.effectAllowed = 'move';
                        },

                        handleDragEnd(e) {
                            e.target.classList.remove('dragging');
                            state.draggedPiece = null;
                        }
                    };

                    // Drag and drop management
                    const DragDropManager = {
                        handleDragOver(e) {
                            e.preventDefault();
                            e.dataTransfer.dropEffect = 'move';

                            if (!e.target.classList.contains('puzzle-piece')) {
                                e.target.classList.add('drag-over');
                            }
                            return false;
                        },

                        handleDragLeave(e) {
                            e.target.classList.remove('drag-over');
                        },

                        handleDrop(e) {
                            e.preventDefault();
                            e.stopPropagation();

                            e.target.classList.remove('drag-over');

                            const targetSlot = e.target.classList.contains('puzzle-slot') ?
                                e.target : e.target.closest('.puzzle-slot');

                            if (!targetSlot || !state.draggedPiece) return false;

                            this.swapPieces(targetSlot);
                            GameLogic.checkWin();
                            return false;
                        },

                        swapPieces(targetSlot) {
                            const sourceSlot = state.draggedPiece.parentElement;
                            const targetPiece = targetSlot.querySelector('.puzzle-piece');

                            // Swap pieces
                            if (targetPiece) {
                                sourceSlot.appendChild(targetPiece);
                            } else {
                                sourceSlot.classList.remove('filled');
                            }

                            targetSlot.appendChild(state.draggedPiece);
                            sourceSlot.classList.add('filled');
                            targetSlot.classList.add('filled');

                            // Update piece positions
                            this.updatePiecePositions(sourceSlot, targetSlot, targetPiece);
                        },

                        updatePiecePositions(sourceSlot, targetSlot, targetPiece) {
                            const draggedId = parseInt(state.draggedPiece.dataset.pieceId);
                            const draggedPieceData = state.pieces.find(p => p.id === draggedId);
                            const sourceSlotIndex = parseInt(sourceSlot.dataset.slot);
                            const targetSlotIndex = parseInt(targetSlot.dataset.slot);

                            if (targetPiece) {
                                const targetId = parseInt(targetPiece.dataset.pieceId);
                                const targetPieceData = state.pieces.find(p => p.id === targetId);
                                if (targetPieceData) {
                                    targetPieceData.currentSlot = sourceSlotIndex;
                                }
                            }

                            if (draggedPieceData) {
                                draggedPieceData.currentSlot = targetSlotIndex;
                            }
                        }
                    };

                    // Game logic
                    const GameLogic = {
                        checkWin() {
                            const isWin = state.pieces.every(piece => {
                                const expectedSlot = piece.correctRow * state.gridSize + piece.correctCol;
                                return piece.currentSlot === expectedSlot;
                            });

                            if (isWin && state.isGameActive) {
                                this.handleWin();
                            }
                        },

                        handleWin() {
                            state.isGameActive = false;
                            TimerManager.stop();

                            // Play success sound and show confetti
                            AudioManager.playSuccessSound();
                            setTimeout(() => ConfettiManager.create(), 200);

                            // Auto-restart after confetti animation
                            setTimeout(() => {
                                PuzzleGame.initPuzzle();
                            }, 3000);
                        }
                    };

                    // Board management
                    const BoardManager = {
                        createSlots() {
                            if (!elements.puzzleBoard) return;

                            elements.puzzleBoard.innerHTML = '';
                            const pieceSize = PieceManager.getPieceSize();
                            elements.puzzleBoard.style.gridTemplateColumns =
                                `repeat(${state.gridSize}, ${pieceSize}px)`;

                            for (let i = 0; i < state.gridSize * state.gridSize; i++) {
                                const slot = this.createSlot(i);
                                elements.puzzleBoard.appendChild(slot);
                            }
                        },

                        createSlot(index) {
                            const slot = document.createElement('div');
                            slot.className = 'puzzle-slot';
                            slot.dataset.slot = index;
                            slot.setAttribute('role', 'gridcell');
                            slot.setAttribute('aria-label', `Puzzle slot ${index + 1}`);

                            slot.addEventListener('dragover', DragDropManager.handleDragOver);
                            slot.addEventListener('dragleave', DragDropManager.handleDragLeave);
                            slot.addEventListener('drop', DragDropManager.handleDrop.bind(DragDropManager));

                            return slot;
                        },

                        shufflePieces() {
                            if (!elements.puzzleBoard) return;

                            const slots = elements.puzzleBoard.querySelectorAll('.puzzle-slot');

                            // Clear all slots
                            slots.forEach(slot => {
                                slot.innerHTML = '';
                                slot.classList.remove('filled');
                            });

                            // Shuffle pieces array using Fisher-Yates algorithm
                            const shuffled = [...state.pieces];
                            for (let i = shuffled.length - 1; i > 0; i--) {
                                const j = Math.floor(Math.random() * (i + 1));
                                [shuffled[i], shuffled[j]] = [shuffled[j], shuffled[i]];
                            }

                            // Place pieces in slots
                            shuffled.forEach((piece, index) => {
                                piece.currentSlot = index;
                                const slot = slots[index];
                                const pieceEl = PieceManager.createPieceElement(piece);
                                slot.appendChild(pieceEl);
                                slot.classList.add('filled');
                            });

                            state.gameStartTime = Date.now();
                        }
                    };

                    // Public API
                    return {
                        setDifficulty(size) {
                            if (size < 2 || size > 6) {
                                console.warn('Invalid grid size:', size);
                                return;
                            }
                            state.gridSize = size;
                            this.initPuzzle();
                        },

                        changeImage() {
                            const currentIndex = CONFIG.images.indexOf(state.currentImageSrc);
                            const nextIndex = (currentIndex + 1) % CONFIG.images.length;
                            state.currentImageSrc = CONFIG.images[nextIndex];

                            if (elements.previewImage) {
                                elements.previewImage.src = state.currentImageSrc;
                            }
                            this.initPuzzle();
                        },

                        initPuzzle() {
                            try {
                                if (!state.currentImageSrc) {
                                    state.currentImageSrc = CONFIG.images[Math.floor(Math.random() * CONFIG.images.length)];
                                    if (elements.previewImage) {
                                        elements.previewImage.src = state.currentImageSrc;
                                    }
                                }

                                state.isGameActive = true;
                                TimerManager.start();
                                PieceManager.createPieces();
                                BoardManager.createSlots();
                                BoardManager.shufflePieces();
                            } catch (error) {
                                console.error('Error initializing puzzle:', error);
                            }
                        },

                        shufflePuzzle() {
                            if (state.isGameActive) {
                                BoardManager.shufflePieces();
                                TimerManager.start();
                            }
                        },

                        resetPuzzle() {
                            this.initPuzzle();
                        },

                        init() {
                            cacheElements();
                            AudioManager.init();
                            this.initPuzzle();
                        }
                    };
                })();

                // Initialize game when DOM is loaded
                if (document.readyState === 'loading') {
                    document.addEventListener('DOMContentLoaded', () => PuzzleGame.init());
                } else {
                    PuzzleGame.init();
                }

                // Handle window resize for responsive design
                let resizeTimeout;
                window.addEventListener('resize', () => {
                    clearTimeout(resizeTimeout);
                    resizeTimeout = setTimeout(() => {
                        if (PuzzleGame && PuzzleGame.initPuzzle) {
                            PuzzleGame.initPuzzle();
                        }
                    }, 250);
                });
            </script>
        @endpush
    @endsection
