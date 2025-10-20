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

        <div class="grid md:grid-cols-2 gap-6">
            <!-- Puzzle Board -->
            <div class="card">
                <h2 class="text-xl font-bold mb-4 text-gray-800">
                    <i class="fas fa-puzzle-piece mr-2 text-blue-500"></i>
                    Papan Puzzle
                </h2>
                <div id="puzzle-board" class="grid grid-cols-3 gap-2 bg-gray-200 p-4 rounded-xl">
                    <!-- 9 slots untuk puzzle 3x3 -->
                </div>
            </div>

            <!-- Puzzle Pieces -->
            <div class="card">
                <h2 class="text-xl font-bold mb-4 text-gray-800">
                    <i class="fas fa-hand-pointer mr-2 text-green-500"></i>
                    Pilih Potongan
                </h2>
                <div id="puzzle-pieces" class="grid grid-cols-3 gap-2 bg-gray-100 p-4 rounded-xl">
                    <!-- Potongan puzzle akan digenerate -->
                </div>
                <div class="mt-4 text-center">
                    <button onclick="shufflePuzzle()"
                        class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-6 rounded-lg">
                        <i class="fas fa-shuffle mr-2"></i> Acak Ulang
                    </button>
                    <button onclick="resetPuzzle()"
                        class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-6 rounded-lg ml-2">
                        <i class="fas fa-redo mr-2"></i> Reset
                    </button>
                </div>
            </div>
        </div>

        <div id="success-message"
            class="card bg-gradient-to-r from-green-400 to-blue-400 text-white text-center py-8 hidden">
            <div class="text-6xl mb-4">🎉</div>
            <h2 class="text-3xl font-bold mb-2">Selamat!</h2>
            <p class="text-xl">Kamu berhasil menyelesaikan puzzle!</p>
            <button onclick="resetPuzzle()"
                class="mt-4 bg-white text-blue-600 font-bold py-3 px-8 rounded-full hover:bg-gray-100 transition">
                Main Lagi
            </button>
        </div>
    </div>

    @push('styles')
        <style>
            .puzzle-slot {
                width: 100%;
                aspect-ratio: 1;
                background: white;
                border: 3px dashed #ccc;
                border-radius: 12px;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 4rem;
                cursor: pointer;
                transition: all 0.3s;
            }

            .puzzle-slot.filled {
                border: 3px solid #10b981;
                background: #f0fdf4;
            }

            .puzzle-piece {
                width: 100%;
                aspect-ratio: 1;
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                border-radius: 12px;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 4rem;
                cursor: grab;
                transition: all 0.3s;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            }

            .puzzle-piece:hover {
                transform: scale(1.05);
                box-shadow: 0 8px 12px rgba(0, 0, 0, 0.2);
            }

            .puzzle-piece:active {
                cursor: grabbing;
            }

            .puzzle-piece.used {
                opacity: 0.3;
                pointer-events: none;
            }
        </style>
    @endpush

    @push('scripts')
        <script>
            const puzzles = [{
                    name: 'Hewan',
                    pieces: ['🐶', '🐱', '🐭', '🐹', '🐰', '🦊', '🐻', '🐼', '🐨']
                },
                {
                    name: 'Buah',
                    pieces: ['🍎', '🍊', '🍋', '🍌', '🍉', '🍇', '🍓', '🍒', '🍑']
                },
                {
                    name: 'Kendaraan',
                    pieces: ['🚗', '🚕', '🚙', '🚌', '🚎', '🏎️', '🚓', '🚑', '🚒']
                }
            ];

            let currentPuzzle = puzzles[Math.floor(Math.random() * puzzles.length)];
            let boardState = Array(9).fill(null);
            let pieceState = [...currentPuzzle.pieces];

            function initPuzzle() {
                const board = document.getElementById('puzzle-board');
                const pieces = document.getElementById('puzzle-pieces');

                board.innerHTML = '';
                pieces.innerHTML = '';

                // Create board slots
                for (let i = 0; i < 9; i++) {
                    const slot = document.createElement('div');
                    slot.className = 'puzzle-slot';
                    slot.dataset.index = i;
                    slot.onclick = () => removeFromBoard(i);
                    board.appendChild(slot);
                }

                // Shuffle and create pieces
                shuffleArray(pieceState);
                pieceState.forEach((piece, index) => {
                    const pieceEl = document.createElement('div');
                    pieceEl.className = 'puzzle-piece';
                    pieceEl.textContent = piece;
                    pieceEl.dataset.piece = piece;
                    pieceEl.onclick = () => placePiece(piece);
                    pieces.appendChild(pieceEl);
                });
            }

            function placePiece(piece) {
                const emptySlotIndex = boardState.findIndex(slot => slot === null);
                if (emptySlotIndex === -1) return; // Board penuh

                boardState[emptySlotIndex] = piece;
                updateBoard();
                checkWin();
            }

            function removeFromBoard(index) {
                if (boardState[index] === null) return;
                boardState[index] = null;
                updateBoard();
            }

            function updateBoard() {
                const slots = document.querySelectorAll('.puzzle-slot');
                const pieces = document.querySelectorAll('.puzzle-piece');

                slots.forEach((slot, index) => {
                    if (boardState[index]) {
                        slot.textContent = boardState[index];
                        slot.classList.add('filled');
                    } else {
                        slot.textContent = '';
                        slot.classList.remove('filled');
                    }
                });

                pieces.forEach(piece => {
                    const pieceValue = piece.dataset.piece;
                    if (boardState.includes(pieceValue)) {
                        piece.classList.add('used');
                    } else {
                        piece.classList.remove('used');
                    }
                });
            }

            function checkWin() {
                const correct = boardState.every((piece, index) => piece === currentPuzzle.pieces[index]);
                if (correct) {
                    setTimeout(() => {
                        document.getElementById('success-message').classList.remove('hidden');
                        confetti();
                    }, 500);
                }
            }

            function shufflePuzzle() {
                shuffleArray(pieceState);
                boardState = Array(9).fill(null);
                document.getElementById('success-message').classList.add('hidden');
                initPuzzle();
            }

            function resetPuzzle() {
                currentPuzzle = puzzles[Math.floor(Math.random() * puzzles.length)];
                pieceState = [...currentPuzzle.pieces];
                boardState = Array(9).fill(null);
                document.getElementById('success-message').classList.add('hidden');
                initPuzzle();
            }

            function shuffleArray(array) {
                for (let i = array.length - 1; i > 0; i--) {
                    const j = Math.floor(Math.random() * (i + 1));
                    [array[i], array[j]] = [array[j], array[i]];
                }
                return array;
            }

            function confetti() {
                // Simple confetti effect
                for (let i = 0; i < 50; i++) {
                    setTimeout(() => {
                        const confetti = document.createElement('div');
                        confetti.style.position = 'fixed';
                        confetti.style.left = Math.random() * 100 + '%';
                        confetti.style.top = '-10px';
                        confetti.style.width = '10px';
                        confetti.style.height = '10px';
                        confetti.style.background = ['#ff0000', '#00ff00', '#0000ff', '#ffff00', '#ff00ff'][Math.floor(
                            Math.random() * 5)];
                        confetti.style.borderRadius = '50%';
                        confetti.style.zIndex = '9999';
                        document.body.appendChild(confetti);

                        let pos = -10;
                        const fall = setInterval(() => {
                            pos += 5;
                            confetti.style.top = pos + 'px';
                            if (pos > window.innerHeight) {
                                clearInterval(fall);
                                confetti.remove();
                            }
                        }, 20);
                    }, i * 50);
                }
            }

            // Initialize puzzle on load
            initPuzzle();
        </script>
    @endpush
@endsection
