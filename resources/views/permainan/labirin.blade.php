@extends('layouts.app')

@section('title', 'Labirin - PANDA TK')

@section('content')
    <div class="space-y-6">
        {{-- 1. BAGIAN HEADER --}}
        <div class="flex justify-between items-center">
            <h1 class="text-3xl font-bold text-gray-800">🌀 Labirin</h1>
            <a href="{{ route('permainan.index') }}"
                class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded-lg transition">
                <i class="fas fa-arrow-left mr-2"></i> Kembali
            </a>
        </div>

        <div
            class="card bg-gradient-to-r from-blue-50 to-indigo-100 py-12 px-6 rounded-2xl shadow-md flex flex-col md:flex-row justify-center items-center md:items-start gap-8 md:gap-16">

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
            </div>

        </div>
        <p id="message" class="text-2xl font-bold mt-4 text-center"></p>
    </div>

    {{-- 3. TAMBAHKAN CDN UNTUK CONFETTI --}}
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.9.3/dist/confetti.browser.min.js"></script>


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

            // ========================================================
            // 4. PENAMBAHAN FUNGSI BARU (Sound & Confetti)
            // ========================================================

            // 🔹 Fungsi untuk memainkan sound effect (tanpa file)
            function playWinSound() {
                try {
                    // Buat AudioContext
                    const audioCtx = new(window.AudioContext || window.webkitAudioContext)();

                    // Buat Oscillator (sumber suara)
                    const oscillator = audioCtx.createOscillator();
                    const gainNode = audioCtx.createGain(); // Untuk kontrol volume

                    // Atur jenis suara (sine, square, sawtooth, triangle)
                    oscillator.type = 'sine';

                    // Atur frekuensi (nada) - kita buat nada naik
                    oscillator.frequency.setValueAtTime(440, audioCtx.currentTime); // Mulai dari 440 Hz (Nada A)
                    oscillator.frequency.linearRampToValueAtTime(880, audioCtx.currentTime +
                        0.3); // Naik ke 880 Hz dalam 0.3 detik

                    // Atur volume agar tidak terlalu keras dan ada fade out
                    gainNode.gain.setValueAtTime(0.3, audioCtx.currentTime); // Volume awal
                    gainNode.gain.linearRampToValueAtTime(0, audioCtx.currentTime + 0.5); // Fade out dalam 0.5 detik

                    // Hubungkan semuanya
                    oscillator.connect(gainNode);
                    gainNode.connect(audioCtx.destination);

                    // Mulai dan Hentikan suara
                    oscillator.start();
                    oscillator.stop(audioCtx.currentTime + 0.5); // Berhenti setelah 0.5 detik
                } catch (e) {
                    console.error("Web Audio API tidak didukung di browser ini.", e);
                }
            }

            // 🔹 Fungsi untuk memicu confetti
            function triggerConfetti() {
                if (typeof confetti === 'function') {
                    confetti({
                        particleCount: 150, // Jumlah confetti
                        spread: 80, // Seberapa lebar sebarannya
                        origin: {
                            y: 0.6
                        } // Mulai dari tengah layar
                    });
                }
            }


            // 🔹 Cek apakah sampai ke garis finish
            function checkGoal() {
                if (player.x === exit.x && player.y === exit.y) {
                    message.textContent = "🎉 Hebat! Kamu sampai ke garis finish!";

                    // 5. MODIFIKASI: Panggil fungsi baru & hapus suara Google
                    playWinSound(); // Mainkan suara kemenangan
                    triggerConfetti(); // Tembakkan confetti

                    /* Suara Google (SpeechSynthesis) dihapus
                    if ('speechSynthesis' in window) {
                        ...
                    }
                    */

                    setTimeout(() => {
                        message.textContent = "Labirin baru muncul!";
                        maze = generateMaze();
                        drawMaze();
                    }, 1500);
                }
            }

            // 🔹 Jalankan game pertama kali
            maze = generateMaze();
            drawMaze();
        </script>
    @endpush
@endsection
