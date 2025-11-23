@extends('layouts.app')

@section('title', 'Kerjakan Kuis - PANDA TK')

@section('content')
    <div class="max-w-5xl mx-auto">
        <div class="flex justify-end mb-6">
            <a href="{{ route('kuis.index') }}"
                class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-3 sm:px-4 rounded-lg transition inline-flex items-center justify-center">
                <i class="fas fa-arrow-left "></i><span class="ml-2 hidden sm:inline">Kembali</span>
            </a>
        </div>

        <!-- Intro Screen -->
        <div id="intro-screen" class="card text-center">
            <div class="mb-6">
                <div class="text-6xl mb-4">📝</div>
                <h1 class="text-4xl font-bold text-gray-800 mb-3">{{ $kuis->judul }}</h1>
                <p class="text-gray-600 text-lg mb-6">{{ $kuis->deskripsi }}</p>
            </div>

            <div class="bg-blue-200 rounded-xl p-6 mb-6">
                <div class="grid md:grid-cols-3 gap-6">
                    <div>
                        <i class="fas fa-list-ol text-3xl text-blue-500 mb-2"></i>
                        <p class="text-gray-600 text-sm">Jumlah Soal</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $kuis->soal->count() }}</p>
                    </div>
                    <div>
                        <i class="fas fa-clock text-3xl text-green-500 mb-2"></i>
                        <p class="text-gray-600 text-sm">Waktu</p>
                        <p class="text-2xl font-bold text-gray-800">
                            @if ($kuis->waktu_tipe === 'tanpa_waktu')
                                Tidak Terbatas
                            @elseif($kuis->waktu_tipe === 'per_soal')
                                {{ $kuis->durasi_waktu }}s per soal
                            @else
                                {{ $kuis->durasi_waktu }}s total
                            @endif
                        </p>
                    </div>
                    <div>
                        <i class="fas fa-trophy text-3xl text-yellow-500 mb-2"></i>
                        <p class="text-gray-600 text-sm">Nilai Maksimal</p>
                        <p class="text-2xl font-bold text-gray-800">100</p>
                    </div>
                </div>
            </div>


            <button onclick="startKuis()"
                class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-4 px-12 rounded-full text-2xl transition transform hover:scale-105 shadow-lg">
                <i class="fas fa-play mr-3"></i> Mulai Kuis
            </button>
        </div>

        <!-- Quiz Screen (Full Screen Mode) -->
        <div id="quiz-screen" class="hidden">
            <!-- Header dengan Timer -->
            <div
                class="card mb-4 shadow-lg bg-gradient-to-r from-blue-50 to-blue-100 rounded-xl border border-blue-200 p-3">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <!-- Info Soal -->
                    <div class="bg-white/70 px-3 py-2 rounded-lg shadow-sm border border-gray-100">
                        <p class="text-xs text-gray-600">Soal</p>
                        <p class="text-lg font-bold text-gray-800">
                            <span id="current-soal-number">1</span> /
                            <span id="total-soal">{{ $kuis->soal->count() }}</span>
                        </p>
                    </div>

                    <!-- Timer -->
                    @if ($kuis->waktu_tipe !== 'tanpa_waktu')
                        <div id="timer-display"
                            class="text-center bg-white/70 px-3 py-2 rounded-lg shadow-sm border border-gray-100">
                            <p class="text-xs text-gray-600">
                                {{ $kuis->waktu_tipe === 'per_soal' ? 'Waktu Tersisa' : 'Waktu Total' }}
                            </p>
                            <p id="timer-text" class="text-2xl font-bold text-blue-700 tracking-wide">
                                <span id="timer-minutes">00</span>:<span id="timer-seconds">00</span>
                            </p>
                        </div>
                    @endif

                    <!-- Jumlah Dijawab -->
                    <div class="text-right bg-green-50 border border-green-200 rounded-lg px-3 py-2 shadow-sm">
                        <p class="text-xs text-gray-600">Dijawab</p>
                        <p class="text-lg font-semibold text-green-600">
                            <span id="answered-count">0</span> soal
                        </p>
                    </div>
                </div>

                <!-- Progress Bar -->
                <div class="mt-3 bg-gray-300 rounded-full h-2 overflow-hidden shadow-inner">
                    <div id="progress-bar"
                        class="bg-gradient-to-r from-blue-500 to-blue-600 h-full transition-all duration-500 ease-in-out"
                        style="width: 0%">
                    </div>
                </div>
            </div>

            <!-- Soal Container -->
            <div id="soal-container"
                class="card min-h-[400px] bg-white border border-gray-200 rounded-xl p-4 shadow-md transition-all duration-300">
                <!-- Content will be loaded by JavaScript -->
            </div>
        </div>


        <!-- Results Screen -->
        <div id="results-screen" class="hidden">
            <div class="card text-center">
                <div id="result-emoji" class="text-9xl mb-6"></div>
                <h2 id="result-title" class="text-4xl font-bold mb-4"></h2>

                <div class="grid md:grid-cols-3 gap-6 mb-8">
                    <div class="bg-blue-50 rounded-xl p-6">
                        <i class="fas fa-star text-4xl text-blue-500 mb-3"></i>
                        <p class="text-gray-600 mb-2">Skor Anda</p>
                        <p id="final-score" class="text-5xl font-bold text-blue-600">0</p>
                    </div>

                    <div class="bg-green-50 rounded-xl p-6">
                        <i class="fas fa-check-circle text-4xl text-green-500 mb-3"></i>
                        <p class="text-gray-600 mb-2">Jawaban Benar</p>
                        <p id="correct-count" class="text-5xl font-bold text-green-600">0</p>
                    </div>

                    <div class="bg-red-50 rounded-xl p-6">
                        <i class="fas fa-times-circle text-4xl text-red-500 mb-3"></i>
                        <p class="text-gray-600 mb-2">Jawaban Salah</p>
                        <p id="wrong-count" class="text-5xl font-bold text-red-600">0</p>
                    </div>
                </div>

                <div class="space-y-3">
                    <button onclick="showDetailJawaban()"
                        class="w-full bg-blue-500 hover:bg-blue-600 text-white font-bold py-4 rounded-lg text-xl transition">
                        <i class="fas fa-list-check mr-2"></i> Lihat Detail Jawaban
                    </button>
                    <a href="{{ route('kuis.index') }}"
                        class="block w-full bg-gray-500 hover:bg-gray-600 text-white font-bold py-4 rounded-lg text-xl transition">
                        <i class="fas fa-home mr-2"></i> <span class="hidden lg:inline">Kembali ke Daftar Kuis</span>
                    </a>
                </div>
            </div>

            <!-- Detail Jawaban -->
            <div id="detail-jawaban" class="hidden mt-6 space-y-4">
                <!-- Will be populated by JavaScript -->
            </div>
        </div>
    </div>

    @push('styles')
        <style>
            .quiz-answer {
                transition: all 0.3s ease;
            }

            .quiz-answer:hover {
                transform: scale(1.02);
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            }

            .quiz-answer.selected {
                border-color: #3b82f6;
                background-color: #dbeafe;
            }

            .quiz-answer.correct {
                border-color: #10b981;
                background-color: #d1fae5;
            }

            .quiz-answer.wrong {
                border-color: #ef4444;
                background-color: #fee2e2;
            }

            @keyframes bounce-in {
                0% {
                    transform: scale(0);
                }

                50% {
                    transform: scale(1.1);
                }

                100% {
                    transform: scale(1);
                }
            }

            .bounce-in {
                animation: bounce-in 0.5s ease;
            }

            .timer-warning {
                animation: pulse 1s infinite;
            }

            @keyframes pulse {

                0%,
                100% {
                    opacity: 1;
                }

                50% {
                    opacity: 0.5;
                }
            }
        </style>
    @endpush

    @push('scripts')
        <script>
            const kuisData = @json($kuis);
            let currentSoalIndex = 0;
            let userAnswers = [];
            let timer = null;
            let timeLeft = 0;
            let isAnswered = false;

            function startKuis() {
                $('#intro-screen').addClass('hidden');
                $('#quiz-screen').removeClass('hidden');

                // Initialize
                currentSoalIndex = 0;
                userAnswers = [];
                isAnswered = false;

                // Setup timer
                if (kuisData.waktu_tipe === 'keseluruhan') {
                    timeLeft = kuisData.durasi_waktu;
                    startTimer();
                }

                // Load first question
                loadSoal(0);
            }

            function loadSoal(index) {
                if (index >= kuisData.soal.length) {
                    showResults();
                    return;
                }

                const soal = kuisData.soal[index];
                currentSoalIndex = index;
                isAnswered = false;

                // Update header
                $('#current-soal-number').text(index + 1);
                const progress = ((index + 1) / kuisData.soal.length) * 100;
                $('#progress-bar').css('width', progress + '%');

                // Setup timer for per-soal
                if (kuisData.waktu_tipe === 'per_soal') {
                    clearInterval(timer);
                    timeLeft = kuisData.durasi_waktu;
                    startTimer();
                }

                // Render soal
                let html = `
        <div class="space-y-6">
            <div class="bg-gradient-to-r from-blue-100 to-purple-100 rounded-xl p-8 mb-6">
                <h2 class="text-3xl font-bold text-gray-800 mb-4">${soal.konten_soal}</h2>
                ${soal.gambar_soal ? `<img src="/storage/${soal.gambar_soal}" class="w-full max-w-xs md:max-w-sm lg:max-w-md mx-auto rounded-lg shadow-lg mt-4" alt="Gambar Soal">` : ''}
            </div>
    `;

                if (soal.tipe === 'pilihan_ganda') {
                    html += '<div class="space-y-4">';
                    soal.pilihan_jawaban.forEach((pilihan, idx) => {
                        html += `
                <div class="quiz-answer cursor-pointer border-4 border-gray-300 rounded-xl p-6 hover:border-blue-400 transition" 
                     onclick="selectAnswer(${pilihan.id}, ${pilihan.is_benar})">
                    <div class="flex items-center space-x-4">
                        <div class="w-12 h-12 bg-blue-500 text-white rounded-full flex items-center justify-center font-bold text-xl flex-shrink-0">
                            ${String.fromCharCode(65 + idx)}
                        </div>
                        <div class="flex-1">
                            <p class="text-xl font-semibold text-gray-800">${pilihan.konten_pilihan}</p>
                            ${pilihan.gambar_pilihan ? `<img src="/storage/${pilihan.gambar_pilihan}" class="w-full max-w-xs md:max-w-sm lg:max-w-md mt-3 rounded-lg shadow" alt="Pilihan">` : ''}
                        </div>
                    </div>
                </div>
            `;
                    });
                    html += '</div>';
                } else {
                    html += `
            <div class="space-y-4">
                <label class="block text-xl font-semibold text-gray-700 mb-3">Jawaban Anda:</label>
                <input type="text" id="jawaban-isian" 
                       class="w-full px-6 py-4 border-4 border-gray-300 rounded-xl text-2xl focus:border-blue-500 focus:outline-none"
                       placeholder="Ketik jawaban di sini...">
                <button onclick="submitIsianSingkat()" 
                        class="w-full bg-blue-500 hover:bg-blue-600 text-white font-bold py-4 rounded-xl text-xl transition">
                    <i class="fas fa-paper-plane mr-2"></i> Kirim Jawaban
                </button>
            </div>
        `;
                }

                html += `
            <div id="feedback-container" class="hidden mt-6">
                <!-- Feedback will be shown here -->
            </div>
        </div>
    `;

                $('#soal-container').html(html);
            }

            function selectAnswer(pilihanId, isBenar) {
                if (isAnswered) return;

                isAnswered = true;

                // Ensure boolean
                const isCorrect = (isBenar == 1 || isBenar === true || isBenar === 'true');

                // Save answer
                userAnswers.push({
                    soal_index: currentSoalIndex,
                    is_correct: isCorrect,
                    answer_id: pilihanId
                });

                // Update answered count
                $('#answered-count').text(userAnswers.length);

                // Visual feedback
                $('.quiz-answer').removeClass('selected');
                event.currentTarget.classList.add('selected');

                if (kuisData.penunjukan_jawaban === 'setelah_jawab') {
                    showFeedback(isBenar);
                } else {
                    // For setelah_semua, move to next question immediately
                    setTimeout(() => {
                        nextSoal();
                    }, 1000);
                }
            }

            function submitIsianSingkat() {
                if (isAnswered) return;

                const jawaban = $('#jawaban-isian').val().trim().toLowerCase();
                if (!jawaban) {
                    Swal.fire('Perhatian', 'Jawaban tidak boleh kosong!', 'warning');
                    return;
                }

                const soal = kuisData.soal[currentSoalIndex];
                const jawabanBenar = soal.jawaban_benar.toLowerCase();
                const isBenar = jawaban === jawabanBenar;

                isAnswered = true;

                // Save answer
                userAnswers.push({
                    soal_index: currentSoalIndex,
                    is_correct: isBenar,
                    answer_text: jawaban
                });

                $('#answered-count').text(userAnswers.length);
                $('#jawaban-isian').prop('disabled', true);

                if (kuisData.penunjukan_jawaban === 'setelah_jawab') {
                    showFeedback(isBenar, soal.jawaban_benar);
                } else {
                    // For setelah_semua, move to next question immediately
                    setTimeout(() => {
                        nextSoal();
                    }, 1000);
                }
            }

            function showFeedback(isBenar, jawabanBenar = null) {
                // Stop timer for this question
                if (kuisData.waktu_tipe === 'per_soal') {
                    clearInterval(timer);
                }

                const feedbackHtml = `
        <div class="bg-${isBenar ? 'green' : 'red'}-100 border-4 border-${isBenar ? 'green' : 'red'}-400 rounded-xl p-8 text-center bounce-in">
            <i class="fas fa-${isBenar ? 'check' : 'times'}-circle text-8xl text-${isBenar ? 'green' : 'red'}-500 mb-4"></i>
            <h3 class="text-3xl font-bold text-${isBenar ? 'green' : 'red'}-800 mb-2">
                ${isBenar ? 'Benar! 🎉' : 'Kurang Tepat 😊'}
            </h3>
            ${!isBenar && jawabanBenar ? `<p class="text-xl text-gray-700 mb-4">Jawaban yang benar: <span class="font-bold">${jawabanBenar}</span></p>` : ''}
            ${!isBenar && kuisData.soal[currentSoalIndex].tipe === 'pilihan_ganda' ? '<p class="text-xl text-gray-700 mb-4"></p>' : ''}
        </div>
    `;

                $('#feedback-container').html(feedbackHtml).removeClass('hidden');

                // Highlight correct answer for multiple choice
                if (kuisData.soal[currentSoalIndex].tipe === 'pilihan_ganda') {
                    $('.quiz-answer').each(function() {
                        const $this = $(this);
                        const onclick = $this.attr('onclick');
                        const isCorrect = onclick.includes('true');

                        if (isCorrect) {
                            $this.addClass('correct').removeClass('selected');
                        } else if ($this.hasClass('selected')) {
                            $this.addClass('wrong');
                        }

                        $this.css('pointer-events', 'none');
                    });
                }

                // Play sound
                playSound(isBenar);

                // Auto next after 3 seconds
                setTimeout(() => {
                    nextSoal();
                }, 3000);
            }

            function nextSoal() {
                currentSoalIndex++;

                if (currentSoalIndex >= kuisData.soal.length) {
                    showResults();
                } else {
                    loadSoal(currentSoalIndex);
                }
            }

            function startTimer() {
                clearInterval(timer);

                timer = setInterval(() => {
                    if (timeLeft <= 0) {
                        clearInterval(timer);

                        if (kuisData.waktu_tipe === 'per_soal') {
                            // Auto submit with wrong answer
                            if (!isAnswered) {
                                userAnswers.push({
                                    soal_index: currentSoalIndex,
                                    is_correct: false
                                });
                                showFeedback(false);
                            }
                        } else {
                            // Time's up for whole quiz
                            showResults();
                        }
                        return;
                    }

                    timeLeft--;
                    updateTimerDisplay();

                    // Warning when 10 seconds left
                    if (timeLeft <= 10) {
                        $('#timer-text').addClass('timer-warning text-red-600');
                    }
                }, 1000);
            }

            function updateTimerDisplay() {
                const minutes = Math.floor(timeLeft / 60);
                const seconds = timeLeft % 60;

                $('#timer-minutes').text(String(minutes).padStart(2, '0'));
                $('#timer-seconds').text(String(seconds).padStart(2, '0'));
            }

            function showResults() {
                clearInterval(timer);

                $('#quiz-screen').addClass('hidden');
                $('#results-screen').removeClass('hidden');

                // Calculate results
                const correctAnswers = userAnswers.filter(a => a.is_correct).length;
                const wrongAnswers = userAnswers.length - correctAnswers;
                const score = Math.round((correctAnswers / kuisData.soal.length) * 100);

                // Display results
                $('#final-score').text(score);
                $('#correct-count').text(correctAnswers);
                $('#wrong-count').text(wrongAnswers);

                // Result message
                let emoji, title;
                if (score >= 80) {
                    emoji = '🎉';
                    title = 'Luar Biasa!';
                } else if (score >= 60) {
                    emoji = '😊';
                    title = 'Bagus!';
                } else if (score >= 40) {
                    emoji = '🙂';
                    title = 'Cukup Baik!';
                } else {
                    emoji = '💪';
                    title = 'Tetap Semangat!';
                }

                $('#result-emoji').text(emoji);
                $('#result-title').text(title);

                // Confetti effect for high scores
                if (score >= 80) {
                    launchConfetti();
                }

                // For "setelah_semua", do NOT automatically show detail jawaban
                // if (kuisData.penunjukan_jawaban === 'setelah_semua') {
                //     showDetailJawaban();
                // }

                // Simpan hasil ke database
                saveQuizResult(score, correctAnswers, userAnswers);
            }

            function saveQuizResult(score, correctCount, answers) {
                $.ajax({
                    url: `/kuis/${kuisData.id}/hasil`,
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        jumlah_soal_dijawab: answers.length,
                        jumlah_benar: correctCount,
                        nilai: score,
                        detail_jawaban: answers
                    },
                    success: function(response) {
                        if (response.success) {
                            console.log('Hasil kuis berhasil disimpan');
                        } else {
                            console.error('Gagal menyimpan hasil kuis:', response.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error saving quiz result:', error);
                    }
                });
            }

            function showDetailJawaban() {
                const detailContainer = $('#detail-jawaban');
                detailContainer.empty();

                let html =
                    '<h3 class="text-2xl font-bold text-gray-800 mb-4"><i class="fas fa-clipboard-check mr-2"></i>Detail Jawaban</h3>';

                kuisData.soal.forEach((soal, index) => {
                    const userAnswer = userAnswers.find(a => a.soal_index === index);
                    const isCorrect = userAnswer ? userAnswer.is_correct : false;

                    let userAnswerContent = '-';
                    let correctAnswerContent = '-';

                    if (soal.tipe === 'pilihan_ganda') {
                        // User Answer
                        if (userAnswer && userAnswer.answer_id) {
                            const selectedPilihan = soal.pilihan_jawaban.find(p => p.id === userAnswer.answer_id);
                            if (selectedPilihan) {
                                userAnswerContent = selectedPilihan.konten_pilihan;
                                if (selectedPilihan.gambar_pilihan) {
                                    userAnswerContent +=
                                        `<br><img src="/storage/${selectedPilihan.gambar_pilihan}" class="h-20 rounded mt-2">`;
                                }
                            }
                        }

                        // Correct Answer (if wrong)
                        if (!isCorrect) {
                            const correctPilihan = soal.pilihan_jawaban.find(p => p.is_benar == 1);
                            if (correctPilihan) {
                                correctAnswerContent = correctPilihan.konten_pilihan;
                                if (correctPilihan.gambar_pilihan) {
                                    correctAnswerContent +=
                                        `<br><img src="/storage/${correctPilihan.gambar_pilihan}" class="h-20 rounded mt-2">`;
                                }
                            }
                        }
                    } else {
                        // Isian Singkat
                        userAnswerContent = userAnswer ? userAnswer.answer_text : '-';
                        if (!isCorrect) {
                            correctAnswerContent = soal.jawaban_benar;
                        }
                    }

                    html += `
            <div class="card mb-4 overflow-hidden border-l-8 ${isCorrect ? 'border-green-500' : 'border-red-500'} shadow-sm hover:shadow-md transition-shadow">
                <div class="p-4 sm:p-6">
                    <!-- Header: Status & Question Number -->
                    <div class="flex items-center justify-between mb-4">
                        <span class="bg-gray-100 text-gray-600 py-1 px-3 rounded-full text-xs font-bold uppercase tracking-wider">
                            Soal ${index + 1}
                        </span>
                        <span class="flex items-center ${isCorrect ? 'text-green-600' : 'text-red-600'} font-bold text-sm sm:text-base">
                            <i class="fas fa-${isCorrect ? 'check' : 'times'}-circle mr-2 text-lg"></i>
                            ${isCorrect ? 'Benar' : 'Salah'}
                        </span>
                    </div>

                    <!-- Question Content -->
                    <div class="mb-6">
                        <p class="text-lg sm:text-xl font-bold text-gray-800 leading-relaxed">${soal.konten_soal}</p>
                        ${soal.gambar_soal ? `<img src="/storage/${soal.gambar_soal}" class="w-full max-w-md mt-4 rounded-lg shadow-sm border border-gray-100" alt="Gambar Soal">` : ''}
                    </div>

                    <!-- Answers Section -->
                    <div class="bg-gray-50 rounded-xl p-4 sm:p-5 space-y-4">
                        <!-- User Answer -->
                        <div>
                            <p class="text-xs text-gray-500 uppercase font-bold tracking-wide mb-1">Jawaban Kamu</p>
                            <div class="${isCorrect ? 'text-green-700' : 'text-red-700'} font-medium text-base sm:text-lg">
                                ${userAnswerContent}
                            </div>
                        </div>

                        <!-- Correct Answer (Only if wrong) -->
                        ${!isCorrect ? `
                                                                        <div class="pt-3 border-t border-gray-200">
                                                                            <p class="text-xs text-gray-500 uppercase font-bold tracking-wide mb-1">Jawaban Benar</p>
                                                                            <div class="text-green-700 font-medium text-base sm:text-lg">
                                                                                ${correctAnswerContent}
                                                                            </div>
                                                                        </div>
                                                                        ` : ''}
                    </div>
                </div>
            </div>
        `;
                });

                detailContainer.html(html).removeClass('hidden');

                // Scroll to detail
                $('html, body').animate({
                    scrollTop: detailContainer.offset().top - 100
                }, 500);
            }

            function playSound(isCorrect) {
                // Use Web Audio API or HTML5 Audio
                const audioContext = new(window.AudioContext || window.webkitAudioContext)();
                const oscillator = audioContext.createOscillator();
                const gainNode = audioContext.createGain();

                oscillator.connect(gainNode);
                gainNode.connect(audioContext.destination);

                if (isCorrect) {
                    // Happy sound
                    oscillator.frequency.value = 800;
                    oscillator.type = 'sine';
                } else {
                    // Sad sound
                    oscillator.frequency.value = 200;
                    oscillator.type = 'sawtooth';
                }

                gainNode.gain.setValueAtTime(0.3, audioContext.currentTime);
                gainNode.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime + 0.5);

                oscillator.start(audioContext.currentTime);
                oscillator.stop(audioContext.currentTime + 0.5);
            }

            function launchConfetti() {
                var duration = 3 * 1000;
                var animationEnd = Date.now() + duration;
                var defaults = {
                    startVelocity: 30,
                    spread: 360,
                    ticks: 60,
                    zIndex: 10000
                };

                function randomInRange(min, max) {
                    return Math.random() * (max - min) + min;
                }

                var interval = setInterval(function() {
                    var timeLeft = animationEnd - Date.now();

                    if (timeLeft <= 0) {
                        return clearInterval(interval);
                    }

                    var particleCount = 50 * (timeLeft / duration);
                    // since particles fall down, start a bit higher than random
                    confetti(Object.assign({}, defaults, {
                        particleCount,
                        origin: {
                            x: randomInRange(0.1, 0.3),
                            y: Math.random() - 0.2
                        }
                    }));
                    confetti(Object.assign({}, defaults, {
                        particleCount,
                        origin: {
                            x: randomInRange(0.7, 0.9),
                            y: Math.random() - 0.2
                        }
                    }));
                }, 250);
            }

            // Prevent accidental page leave
            window.onbeforeunload = function(e) {
                if (!$('#quiz-screen').hasClass('hidden') && !$('#results-screen').hasClass('hidden')) {
                    return 'Apakah Anda yakin ingin meninggalkan halaman? Progres Anda akan hilang.';
                }
            };
        </script>
    @endpush
@endsection
