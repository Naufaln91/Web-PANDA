@extends('layouts.app')

@section('title', 'Dashboard - PANDA TK')

@section('content')
    <div class="space-y-4 sm:space-y-6">
        <!-- Welcome Section -->
        <div class="space-y-4 sm:space-y-6">
            <!-- Welcome Section -->
            <div class="card bg-gradient-to-r from-purple-500 to-pink-500 text-white rounded-xl sm:rounded-2xl p-4 sm:p-6">
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold mb-1 sm:mb-2">
                            👋 Halo, {{ $user->nama_anak }}!
                        </h1>
                        <p class="text-sm sm:text-base lg:text-lg opacity-90">Selamat datang di PANDA</p>
                        <div class="mt-3 sm:mt-4 flex flex-col sm:flex-row items-start sm:items-center space-y-2 sm:space-y-0 sm:space-x-4">
                            <div class="bg-white bg-opacity-20 px-3 sm:px-4 py-1.5 sm:py-2 rounded-lg sm:rounded-xl">
                                <p class="text-xs sm:text-sm opacity-90">Orang tua:</p>
                                <p class="font-bold text-sm sm:text-base">{{ $user->nama }}</p>
                            </div>
                            <div class="bg-white bg-opacity-20 px-3 sm:px-4 py-1.5 sm:py-2 rounded-lg sm:rounded-xl">
                                <p class="text-xs sm:text-sm opacity-90">Kelas:</p>
                                <p class="font-bold text-sm sm:text-base">{{ $user->kelas_anak }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="text-5xl sm:text-6xl lg:text-8xl ml-2 sm:ml-4">🐼</div>
                </div>
            </div>

            <!-- Stats -->
            <div class="card bg-gradient-to-br from-blue-500 to-blue-600 text-white rounded-xl sm:rounded-2xl p-4 sm:p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs sm:text-sm opacity-90">Kuis Tersedia</p>
                        <h3 class="text-2xl sm:text-3xl lg:text-4xl font-bold mt-1 sm:mt-2">{{ $publishedKuis }}</h3>
                    </div>
                    <i class="fas fa-clipboard-list text-3xl sm:text-4xl lg:text-5xl opacity-50"></i>
                </div>
            </div>

            <!-- Learning Menu -->
            <div class="card rounded-xl sm:rounded-2xl p-4 sm:p-6 bg-white">
                <h2 class="text-lg sm:text-xl lg:text-2xl font-bold text-gray-800 mb-4 sm:mb-6">
                    <i class="fas fa-graduation-cap mr-2 text-purple-500"></i>
                    Mari Belajar!
                </h2>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 sm:gap-4 lg:gap-6">
                    <a href="{{ route('materi.index') }}"
                        class="block p-4 sm:p-6 lg:p-8 bg-gradient-to-br from-yellow-100 to-yellow-200 rounded-xl sm:rounded-2xl hover:shadow-2xl transition transform hover:scale-105 text-center">
                        <div class="text-4xl sm:text-5xl lg:text-6xl mb-2 sm:mb-3 lg:mb-4">📚</div>
                        <h3 class="font-bold text-gray-800 text-base sm:text-lg lg:text-xl">Materi</h3>
                        <p class="text-xs sm:text-sm text-gray-600 mt-1 sm:mt-2">Belajar hal baru yang menyenangkan</p>
                    </a>

                    <a href="{{ route('permainan.index') }}"
                        class="block p-4 sm:p-6 lg:p-8 bg-gradient-to-br from-pink-100 to-pink-200 rounded-xl sm:rounded-2xl hover:shadow-2xl transition transform hover:scale-105 text-center">
                        <div class="text-4xl sm:text-5xl lg:text-6xl mb-2 sm:mb-3 lg:mb-4">🎮</div>
                        <h3 class="font-bold text-gray-800 text-base sm:text-lg lg:text-xl">Permainan</h3>
                        <p class="text-xs sm:text-sm text-gray-600 mt-1 sm:mt-2">Main sambil belajar</p>
                    </a>

                    <a href="{{ route('kuis.index') }}"
                        class="block p-4 sm:p-6 lg:p-8 bg-gradient-to-br from-blue-100 to-blue-200 rounded-xl sm:rounded-2xl hover:shadow-2xl transition transform hover:scale-105 text-center">
                        <div class="text-4xl sm:text-5xl lg:text-6xl mb-2 sm:mb-3 lg:mb-4">✏️</div>
                        <h3 class="font-bold text-gray-800 text-base sm:text-lg lg:text-xl">Kuis</h3>
                        <p class="text-xs sm:text-sm text-gray-600 mt-1 sm:mt-2">Ayo uji kemampuanmu!</p>
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
