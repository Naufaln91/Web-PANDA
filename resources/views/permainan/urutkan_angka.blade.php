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

    <div class="card text-center">
        <h2 class="text-xl font-bold text-gray-700 mb-4">Susun angka dari kecil ke besar!</h2>

        <div class="text-lg mb-3 text-gray-600 font-semibold">
            Level: <span id="level-text" class="text-blue-600 font-bold">1 (5 Angka)</span>
        </div>

        <div id="angka-container"
             class="flex flex-wrap justify-center gap-4 p-4 bg-gray-100 rounded-xl min-h-[150px]"></div>

        <div class="mt-4 space-x-2">
            <button onclick="cekUrutan()"
                class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-6 rounded-lg">
                ✅ Cek Urutan
            </button>
            <button onclick="resetLevel()"
                class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-6 rounded-lg">
                🔁 Ulang Level
            </button>
        </div>

        <p id="pesan" class="text-xl font-bold text-gray-800 mt-4"></p>
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
    transition: transform 0.2s;
}
.draggable:active { transform: scale(1.1); cursor: grabbing; }
.drag-over { outline: 4px dashed #2563eb; }
</style>
@endpush

@push('scripts')
<script>
let level = 1;
const levelConfig = [5, 10, 15, 20, 25, 30];
const angkaContainer = document.getElementById('angka-container');
let angka = [];
let dragSrc = null;

function acak(array) {
    for (let i = array.length - 1; i > 0; i--) {
        const j = Math.floor(Math.random() * (i + 1));
        [array[i], array[j]] = [array[j], array[i]];
    }
    return array;
}

function buatAngka() {
    angkaContainer.innerHTML = '';
    angka = Array.from({length: levelConfig[level - 1]}, (_, i) => i + 1);
    acak([...angka]).forEach(num => {
        const div = document.createElement('div');
        div.textContent = num;
        div.className = 'draggable';
        div.draggable = true;

        div.addEventListener('dragstart', dragStart);
        div.addEventListener('dragover', dragOver);
        div.addEventListener('drop', drop);
        div.addEventListener('dragend', dragEnd);

        angkaContainer.appendChild(div);
    });
    document.getElementById('level-text').textContent = `${level} (${levelConfig[level - 1]} Angka)`;
    document.getElementById('pesan').textContent = '';
}

function dragStart(e) {
    dragSrc = this;
    e.dataTransfer.effectAllowed = 'move';
}
function dragOver(e) {
    e.preventDefault();
    this.classList.add('drag-over');
}
function drop(e) {
    e.stopPropagation();
    if (dragSrc !== this) {
        const parent = this.parentNode;
        const srcHTML = dragSrc.outerHTML;
        const targetHTML = this.outerHTML;
        this.insertAdjacentHTML('beforebegin', srcHTML);
        dragSrc.insertAdjacentHTML('beforebegin', targetHTML);
        parent.removeChild(dragSrc);
        parent.removeChild(this);
        buatEventUlang();
    }
}
function dragEnd() {
    document.querySelectorAll('.draggable').forEach(el => el.classList.remove('drag-over'));
}
function buatEventUlang() {
    document.querySelectorAll('.draggable').forEach(div => {
        div.addEventListener('dragstart', dragStart);
        div.addEventListener('dragover', dragOver);
        div.addEventListener('drop', drop);
        div.addEventListener('dragend', dragEnd);
    });
}

function cekUrutan() {
    const items = [...angkaContainer.children].map(d => parseInt(d.textContent));
    const benar = items.every((num, i) => num === i + 1);
    const pesan = document.getElementById('pesan');
    if (benar) {
        pesan.textContent = "🎉 Hebat! Urutannya benar!";
        const u = new SpeechSynthesisUtterance("Hebat! Urutannya benar!");
        u.lang = 'id-ID';
        speechSynthesis.speak(u);
        setTimeout(() => nextLevel(), 2000);
    } else {
        pesan.textContent = "❌ Coba lagi ya!";
        const u = new SpeechSynthesisUtterance("Coba lagi ya!");
        u.lang = 'id-ID';
        speechSynthesis.speak(u);
    }
}

function nextLevel() {
    if (level < levelConfig.length) {
        level++;
        buatAngka();
    } else {
        document.getElementById('pesan').textContent = "🏆 Semua level selesai! Hebat sekali!";
        const u = new SpeechSynthesisUtterance("Semua level selesai! Kamu hebat sekali!");
        u.lang = 'id-ID';
        speechSynthesis.speak(u);
    }
}

function resetLevel() {
    buatAngka();
}

buatAngka();
</script>
@endpush
@endsection
