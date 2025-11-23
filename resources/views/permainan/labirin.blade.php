@extends('layouts.app')

@section('title', 'Labirin - PANDA TK')

@section('content')
    <div class="space-y-6">
        {{-- 1. BAGIAN HEADER --}}
        <div class="flex justify-between items-center gap-2">
            <h1 class="text-xl sm:text-2xl md:text-3xl font-bold text-gray-800">🌀 Labirin</h1>
            <a href="{{ route('permainan.index') }}"
                class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-3 sm:px-4 rounded-lg transition flex items-center justify-center">
                <i class="fas fa-arrow-left"></i><span class="ml-2 hidden sm:inline">Kembali</span>
            </a>
        </div>

        <div
            class="card bg-gradient-to-r from-blue-100 to-indigo-100 py-12 px-6 rounded-2xl shadow-md flex flex-col md:flex-row justify-center items-center md:items-start gap-8 md:gap-16 relative overflow-hidden">

            <div class="text-center">
                <div id="maze-container" class="inline-block relative p-4 rounded-lg shadow-lg">
                    <div id="maze-grid" class="grid gap-1"></div>
                </div>
            </div>

            <div class="flex flex-col items-center justify-center gap-4 mt-6 md:mt-12">
                <div class="flex justify-center">
                    <button onclick="move('up')"
                        class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-3 px-5 rounded-full text-2xl w-20 h-20">⬆️</button>
                </div>
                <div class="flex justify-center gap-4">
                    <button onclick="move('left')"
                        class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-3 px-5 rounded-full text-2xl w-20 h-20">⬅️</button>
                    <button onclick="move('down')"
                        class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-3 px-5 rounded-full text-2xl w-20 h-20">⬇️</button>
                    <button onclick="move('right')"
                        class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-3 px-5 rounded-full text-2xl w-20 h-20">➡️</button>
                </div>

                {{-- Pesan status permainan --}}
                <p id="message" class="text-xl font-bold text-center mt-4 min-h-[2rem]"></p>
            </div>

            {{-- Canvas untuk confetti --}}
            <canvas id="confetti-canvas" class="absolute top-0 left-0 w-full h-full pointer-events-none"></canvas>
        </div>
    </div>

    @push('scripts')
        <script>
            let maze = [];
            let player = {
                x: 1,
                y: 1
            };
            let exit = {
                x: 0,
                y: 0
            };
            const size = 15;
            const mazeGrid = document.getElementById('maze-grid');
            const message = document.getElementById('message');
            const animals = ['🐰'];
            const colors = ['#ADD8E6'];

            // 🔹 Buat maze acak tapi dijamin bisa diselesaikan
            function generateMaze(size = 15) {
                const maze = Array(size).fill().map(() => Array(size).fill(1));

                function shuffle(arr) {
                    for (let i = arr.length - 1; i > 0; i--) {
                        const j = Math.floor(Math.random() * (i + 1));
                        [arr[i], arr[j]] = [arr[j], arr[i]];
                    }
                    return arr;
                }

                function carve(x, y) {
                    const dirs = shuffle([
                        [0, -2],
                        [0, 2],
                        [-2, 0],
                        [2, 0]
                    ]);
                    for (const [dx, dy] of dirs) {
                        const nx = x + dx;
                        const ny = y + dy;
                        if (ny > 0 && ny < size - 1 && nx > 0 && nx < size - 1 && maze[ny][nx] === 1) {
                            maze[ny][nx] = 0;
                            maze[y + dy / 2][x + dx / 2] = 0;
                            carve(nx, ny);
                        }
                    }
                }

                maze[1][1] = 0;
                carve(1, 1);
                maze[size - 2][size - 2] = 0;

                player = {
                    x: 1,
                    y: 1
                };
                exit = {
                    x: size - 2,
                    y: size - 2
                };

                // 🔹 Pastikan bisa sampai finish
                if (!isPathExists(maze, player, exit)) {
                    return generateMaze(size); // ulang jika tidak ada jalur
                }

                return maze;
            }

            // 🔹 Cek apakah jalur dari start ke finish ada (pakai BFS)
            function isPathExists(maze, start, end) {
                const visited = Array(maze.length).fill().map(() => Array(maze[0].length).fill(false));
                const queue = [start];
                const dirs = [
                    [1, 0],
                    [-1, 0],
                    [0, 1],
                    [0, -1]
                ];

                while (queue.length > 0) {
                    const {
                        x,
                        y
                    } = queue.shift();
                    if (x === end.x && y === end.y) return true;
                    for (const [dx, dy] of dirs) {
                        const nx = x + dx;
                        const ny = y + dy;
                        if (nx >= 0 && ny >= 0 && ny < maze.length && nx < maze.length &&
                            maze[ny][nx] === 0 && !visited[ny][nx]) {
                            visited[ny][nx] = true;
                            queue.push({
                                x: nx,
                                y: ny
                            });
                        }
                    }
                }
                return false;
            }

            // 🔹 Gambar maze di layar
            function drawMaze() {
                mazeGrid.innerHTML = '';
                mazeGrid.style.gridTemplateColumns = `repeat(${size}, 20px)`; // 🔹 Ukuran grid lebih kecil
                mazeGrid.style.backgroundColor = colors[Math.floor(Math.random() * colors.length)];

                for (let y = 0; y < size; y++) {
                    for (let x = 0; x < size; x++) {
                        const cell = document.createElement('div');
                        cell.className = 'w-5 h-5 flex items-center justify-center border rounded'; // 🔹 Kotak makin kecil
                        if (maze[y][x] === 1) cell.classList.add('bg-gray-700');
                        else cell.classList.add('bg-white');
                        if (x === player.x && y === player.y) cell.textContent = animals[Math.floor(Math.random() * animals
                            .length)];
                        if (x === exit.x && y === exit.y) cell.textContent = '🏁';
                        mazeGrid.appendChild(cell);
                    }
                }
            }


            // 🔹 Gerakan pemain
            let moveInterval = null;
            let currentDirection = null;

            function move(direction) {
                let newX = player.x;
                let newY = player.y;

                if (direction === 'up') newY--;
                if (direction === 'down') newY++;
                if (direction === 'left') newX--;
                if (direction === 'right') newX++;

                if (maze[newY] && maze[newY][newX] === 0) {
                    player.x = newX;
                    player.y = newY;
                    drawMaze();
                    checkGoal();
                }
            }

            // 🔹 Fungsi untuk memulai gerakan berkelanjutan
            function startContinuousMove(direction) {
                // Hentikan gerakan sebelumnya jika ada
                stopContinuousMove();

                // Gerak pertama langsung
                currentDirection = direction;
                move(direction);

                // Mulai interval untuk gerakan berkelanjutan
                moveInterval = setInterval(() => {
                    move(currentDirection);
                }, 150); // Gerak setiap 150ms
            }

            // 🔹 Fungsi untuk menghentikan gerakan berkelanjutan
            function stopContinuousMove() {
                if (moveInterval) {
                    clearInterval(moveInterval);
                    moveInterval = null;
                }
                currentDirection = null;
            }

            // 🔹 Setup event listeners untuk tombol navigasi
            function setupNavigationButtons() {
                const buttons = {
                    up: document.querySelector('button[onclick="move(\'up\')"]'),
                    down: document.querySelector('button[onclick="move(\'down\')"]'),
                    left: document.querySelector('button[onclick="move(\'left\')"]'),
                    right: document.querySelector('button[onclick="move(\'right\')"]')
                };

                Object.keys(buttons).forEach(direction => {
                    const button = buttons[direction];
                    if (button) {
                        // Hapus onclick attribute (kita ganti dengan event listener)
                        button.removeAttribute('onclick');

                        // Mouse events
                        button.addEventListener('mousedown', (e) => {
                            e.preventDefault();
                            startContinuousMove(direction);
                        });

                        button.addEventListener('mouseup', stopContinuousMove);
                        button.addEventListener('mouseleave', stopContinuousMove);

                        // Touch events untuk mobile
                        button.addEventListener('touchstart', (e) => {
                            e.preventDefault();
                            startContinuousMove(direction);
                        });

                        button.addEventListener('touchend', (e) => {
                            e.preventDefault();
                            stopContinuousMove();
                        });

                        button.addEventListener('touchcancel', stopContinuousMove);
                    }
                });
            }

            // ========================================================
            // 4. FUNGSI AUDIO DAN CONFETTI (Sesuai dengan urutkan_angka)
            // ========================================================

            let audioContext = null;

            function initAudio() {
                if (!audioContext) {
                    audioContext = new(window.AudioContext || window.webkitAudioContext)();
                }
            }

            // 🔹 Fungsi untuk memainkan sound effect sukses (sama seperti urutkan_angka)
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

            // 🔹 Fungsi untuk membuat confetti canvas (sama seperti urutkan_angka)
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


            // 🔹 Cek apakah sampai ke garis finish
            function checkGoal() {
                if (player.x === exit.x && player.y === exit.y) {
                    message.textContent = "🎉 Hebat! Kamu sampai ke garis finish!";

                    // Mainkan suara kemenangan dan confetti (sama seperti urutkan_angka)
                    playSuccessSound();
                    createConfetti();

                    setTimeout(() => {
                        message.textContent = "Labirin baru muncul!";
                        maze = generateMaze();
                        drawMaze();
                    }, 2500);
                }
            }

            // 🔹 Jalankan game pertama kali
            maze = generateMaze();
            drawMaze();
            setupNavigationButtons(); // Setup tombol dengan continuous movement
        </script>
    @endpush
@endsection
