@extends('layouts.app')

@section('title', 'Belajar Hewan - PANDA TK')

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex justify-between items-center">
        <h1 class="text-3xl font-bold text-gray-800">
            🐾 Belajar Hewan
        </h1>
        <a href="{{ route('materi.index') }}"
           class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded-lg transition">
            <i class="fas fa-arrow-left mr-2"></i> Kembali
        </a>
    </div>

    {{-- Daftar Hewan --}}
    <div class="card p-4">
        <div id="hewan-container" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-4"></div>
    </div>

    {{-- Kotak hewan terpilih --}}
    <div class="card bg-gradient-to-r from-green-100 to-yellow-100 text-center py-8">
        <div id="selected-hewan"
            class="w-80 sm:w-96 h-24 rounded-xl mx-auto mb-4 shadow-lg border-2 border-gray-300 flex items-center justify-center text-3xl font-bold text-gray-700">
            Klik hewan di atas
        </div>
        <button onclick="playCurrentSound()"
                class="bg-green-500 hover:bg-green-600 text-white font-bold py-3 px-8 rounded-full text-2xl transition">
            <i class="fas fa-volume-up mr-2"></i> Dengarkan
        </button>

    </div>
</div>

@push('scripts')
<script>
    const hewans = [
        { name: 'Kucing', icon: '🐱' },
        { name: 'Anjing', icon: '🐶' },
        { name: 'Burung', icon: '🐦' },
        { name: 'Gajah', icon: '🐘' },
        { name: 'Ikan', icon: '🐠' },
        { name: 'Singa', icon: '🦁' },
        { name: 'Kelinci', icon: '🐰' },
        { name: 'Panda', icon: '🐼' },
        { name: 'Ayam', icon: '🐔' },
        { name: 'Kuda', icon: '🐴' },
        { name: 'Domba', icon: '🐑' },
        { name: 'Kambing', icon: '🐐' },
        { name: 'Katak', icon: '🐸' },
        { name: 'Kupu-kupu', icon: '🦋' },
        { name: 'Monyet', icon: '🐵' },
        { name: 'Burung Hantu', icon: '🦉' },
        { name: 'Iguana', icon: '🦎' },
        { name: 'Pinguin', icon: '🐧' },
        { name: 'Ular', icon: '🐍' },
        { name: 'Kura-kura', icon: '🐢' }
    ];

    let current = null;
    const container = document.getElementById('hewan-container');
    const selectedBox = document.getElementById('selected-hewan');

    // Buat grid hewan
    hewans.forEach(h => {
        const div = document.createElement('div');
        div.className = "w-full h-20 rounded-xl shadow-lg cursor-pointer hover:scale-105 transition flex items-center justify-center text-5xl";
        div.textContent = h.icon;
        div.onclick = () => selectHewan(h);
        container.appendChild(div);
    });

    function selectHewan(h) {
        current = h;
        selectedBox.textContent = h.icon + " " + h.name;
        playCurrentSound();
    }

    function playCurrentSound() {
        if (!current) return;
        if ('speechSynthesis' in window) {
            const utterance = new SpeechSynthesisUtterance(current.name);
            utterance.lang = 'id-ID';
            utterance.rate = 0.9;
            speechSynthesis.cancel();
            speechSynthesis.speak(utterance);

            selectedBox.classList.add('animate-bounce');
            setTimeout(() => selectedBox.classList.remove('animate-bounce'), 800);
        }
    }

    // Tampilkan kotak awal kosong
    selectedBox.textContent = "Klik hewan di atas";
</script>
@endpush
@endsection
