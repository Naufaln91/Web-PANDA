@extends('layouts.app')

@section('title', 'Dashboard - PANDA TK')

@section('content')
    <div class="space-y-6">
        <!-- Welcome Section -->
        <div class="space-y-6">
            <!-- Welcome Section -->
            <div class="card bg-gradient-to-r from-purple-500 to-pink-500 text-white rounded-2xl p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold mb-2">
                            👋 Halo, {{ $user->nama_anak }}!
                        </h1>
                        <p class="text-lg opacity-90">Selamat datang di PANDA</p>
                        <div class="mt-4 flex items-center space-x-4">
                            <div class="bg-white bg-opacity-20 px-4 py-2 rounded-xl">
                                <p class="text-sm opacity-90">Orang tua:</p>
                                <p class="font-bold">{{ $user->nama_orangtua }}</p>
                            </div>
                            <div class="bg-white bg-opacity-20 px-4 py-2 rounded-xl">
                                <p class="text-sm opacity-90">Kelas:</p>
                                <p class="font-bold">{{ $user->kelas_anak }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="text-8xl">🐼</div>
                </div>
            </div>

            <!-- Stats -->
            <div class="card bg-gradient-to-br from-blue-500 to-blue-600 text-white rounded-2xl p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm opacity-90">Kuis Tersedia</p>
                        <h3 class="text-4xl font-bold mt-2">{{ $publishedKuis }}</h3>
                    </div>
                    <i class="fas fa-clipboard-list text-5xl opacity-50"></i>
                </div>
            </div>

            <!-- Learning Menu -->
            <div class="card rounded-2xl p-6 bg-white">
                <h2 class="text-2xl font-bold text-gray-800 mb-6">
                    <i class="fas fa-graduation-cap mr-2 text-purple-500"></i>
                    Mari Belajar!
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <a href="{{ route('materi.index') }}"
                        class="block p-8 bg-gradient-to-br from-yellow-100 to-yellow-200 rounded-2xl hover:shadow-2xl transition transform hover:scale-105 text-center">
                        <div class="text-6xl mb-4">📚</div>
                        <h3 class="font-bold text-gray-800 text-xl">Materi</h3>
                        <p class="text-sm text-gray-600 mt-2">Belajar hal baru yang menyenangkan</p>
                    </a>

                    <a href="{{ route('permainan.index') }}"
                        class="block p-8 bg-gradient-to-br from-pink-100 to-pink-200 rounded-2xl hover:shadow-2xl transition transform hover:scale-105 text-center">
                        <div class="text-6xl mb-4">🎮</div>
                        <h3 class="font-bold text-gray-800 text-xl">Permainan</h3>
                        <p class="text-sm text-gray-600 mt-2">Main sambil belajar</p>
                    </a>

                    <a href="{{ route('kuis.index') }}"
                        class="block p-8 bg-gradient-to-br from-blue-100 to-blue-200 rounded-2xl hover:shadow-2xl transition transform hover:scale-105 text-center">
                        <div class="text-6xl mb-4">✏️</div>
                        <h3 class="font-bold text-gray-800 text-xl">Kuis</h3>
                        <p class="text-sm text-gray-600 mt-2">Ayo uji kemampuanmu!</p>
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
