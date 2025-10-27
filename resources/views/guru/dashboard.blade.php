@extends('layouts.app')

@section('title', 'Dashboard Guru - PANDA TK')

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex justify-between items-center">
            <h1 class="text-3xl font-bold text-gray-800">
                <i class="fas fa-chalkboard-teacher mr-2 text-green-500"></i>
                Dashboard Guru
            </h1>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="card bg-gradient-to-br from-blue-500 to-blue-600 text-white rounded-2xl p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm opacity-90">Kuis Saya</p>
                        <h3 class="text-4xl font-bold mt-2">{{ $myKuis }}</h3>
                    </div>
                    <i class="fas fa-clipboard-list text-5xl opacity-50"></i>
                </div>
            </div>

            <div class="card bg-gradient-to-br from-green-500 to-green-600 text-white rounded-2xl p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm opacity-90">Total Kuis Tersedia</p>
                        <h3 class="text-4xl font-bold mt-2">{{ $publishedKuis }}</h3>
                    </div>
                    <i class="fas fa-clipboard-check text-5xl opacity-50"></i>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="card rounded-2xl p-6 bg-white">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">
                <i class="fas fa-rocket mr-2 text-purple-500"></i>
                Menu Pembelajaran
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <a href="{{ route('materi.index') }}"
                    class="block p-8 bg-gradient-to-br from-yellow-50 to-yellow-100 rounded-2xl hover:shadow-lg transition transform hover:scale-105 text-center">
                    <i class="fas fa-book text-5xl text-yellow-500 mb-3"></i>
                    <h3 class="font-bold text-gray-800 text-lg">Materi</h3>
                    <p class="text-sm text-gray-600 mt-1">Akses materi pembelajaran</p>
                </a>

                <a href="{{ route('permainan.index') }}"
                    class="block p-8 bg-gradient-to-br from-pink-50 to-pink-100 rounded-2xl hover:shadow-lg transition transform hover:scale-105 text-center">
                    <i class="fas fa-gamepad text-5xl text-pink-500 mb-3"></i>
                    <h3 class="font-bold text-gray-800 text-lg">Permainan</h3>
                    <p class="text-sm text-gray-600 mt-1">Mainkan permainan</p>
                </a>

                <a href="{{ route('kuis.index') }}"
                    class="block p-8 bg-gradient-to-br from-blue-50 to-blue-100 rounded-2xl hover:shadow-lg transition transform hover:scale-105 text-center">
                    <i class="fas fa-clipboard-question text-5xl text-blue-500 mb-3"></i>
                    <h3 class="font-bold text-gray-800 text-lg">Kuis</h3>
                    <p class="text-sm text-gray-600 mt-1">Kelola & kerjakan kuis</p>
                </a>
            </div>

            <div class="mt-6 text-center">
                <a href="{{ route('kuis.create') }}"
                    class="inline-block bg-blue-500 hover:bg-blue-600 text-white font-bold py-3 px-8 rounded-xl transition transform hover:scale-105">
                    <i class="fas fa-plus-circle mr-2"></i>
                    Buat Kuis Baru
                </a>
            </div>
        </div>
    </div>

@endsection
