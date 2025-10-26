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

    <!-- Area permainan di tengah -->
    <div class="flex flex-col items-center justify-center min-h-[70vh]">
        <div id="game" class="grid gap-4 justify-center mb-3"></div>
        <p id="status" class="text-xl font-bold text-green-600 mt-2"></p>
    </div>
</div>

@push('scripts')
<script>
const emojis = ['🍎','🍌','🍇','🍓','🍒','🍊','🍉','🍍','🥝','🍋'];
let flipped = [];
let matched = 0;
let level = 1;
const levelPairs = [2, 3, 4, 6, 10]; // jumlah pasangan per level

function initLevel() {
    const pairs = levelPairs[level - 1];
    const selected = emojis.slice(0, pairs);
    const items = [...selected, ...selected].sort(() => Math.random() - 0.5);
    const game = document.getElementById('game');
    flipped = [];
    matched = 0;
    document.getElementById('status').textContent = '';

    // Tentukan kolom sesuai jumlah kartu
    let gridCols;
    if (pairs === 4) {
        gridCols = 4; // dua baris, empat kolom
    } else {
        gridCols = Math.ceil(Math.sqrt(items.length));
    }

    game.className = `grid gap-4 justify-center grid-cols-${gridCols} mb-3`;
    game.innerHTML = '';

    items.forEach((emoji) => {
        const card = document.createElement('div');
        card.className = "bg-blue-200 w-[120px] h-[120px] rounded-2xl flex items-center justify-center text-6xl cursor-pointer shadow-lg hover:scale-105 transition";
        card.dataset.value = emoji;
        card.onclick = () => flipCard(card);
        game.appendChild(card);
    });
}

function flipCard(card) {
    if (flipped.length < 2 && !card.classList.contains('flipped')) {
        card.textContent = card.dataset.value;
        card.classList.add('flipped');
        flipped.push(card);
    }

    if (flipped.length === 2) {
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
        speech("Cocok!");

        if (matched === levelPairs[level - 1]) {
            if (level < levelPairs.length) {
                document.getElementById('status').textContent = "🎉 Hebat! Lanjut ke berikutnya...";
                level++;
                setTimeout(initLevel, 2000);
            } else {
                document.getElementById('status').textContent = "🏆 Keren banget! Semua pasangan sudah cocok!";
                confetti();
            }
        }
    } else {
        if (a && b) {
            a.textContent = '';
            b.textContent = '';
            a.classList.remove('flipped');
            b.classList.remove('flipped');
        }
    }
    flipped = [];
}

function speech(text) {
    const u = new SpeechSynthesisUtterance(text);
    u.lang = 'id-ID';
    speechSynthesis.speak(u);
}

// Efek confetti 🎊
function confetti() {
    for (let i = 0; i < 50; i++) {
        const c = document.createElement('div');
        c.style.position = 'fixed';
        c.style.left = Math.random() * 100 + '%';
        c.style.top = '-10px';
        c.style.width = '10px';
        c.style.height = '10px';
        c.style.background = ['#f87171','#60a5fa','#34d399','#fbbf24','#a78bfa'][Math.floor(Math.random() * 5)];
        c.style.borderRadius = '50%';
        c.style.zIndex = '9999';
        document.body.appendChild(c);
        let pos = -10;
        const fall = setInterval(() => {
            pos += 6;
            c.style.top = pos + 'px';
            if (pos > window.innerHeight) {
                clearInterval(fall);
                c.remove();
            }
        }, 20);
    }
}

initLevel();
</script>
@endpush
@endsection
