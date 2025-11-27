{{-- resources/views/admin/dashboard.blade.php --}}
@extends('layouts.app')

@section('title', 'Dashboard Admin - PANDA TK')

@section('content')
    <div class="space-y-4 sm:space-y-6">
        <!-- Header -->
        <div class="flex justify-between items-center">
            <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold text-gray-800">
                <i class="fas fa-tachometer-alt mr-2 text-blue-500"></i>
                Dashboard Admin
            </h1>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 lg:gap-6">

            <div class="card bg-gradient-to-br from-blue-500 to-blue-600 text-white rounded-xl sm:rounded-2xl p-3 sm:p-4 lg:p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-[10px] sm:text-xs opacity-90">Whitelist Email</p>
                        <h3 class="text-xl sm:text-2xl lg:text-4xl font-bold mt-1 sm:mt-2">{{ $totalWhitelist }}</h3>
                    </div>
                    <i class="fas fa-user-friends text-2xl sm:text-3xl lg:text-5xl opacity-50"></i>
                </div>
            </div>

            <div class="card bg-gradient-to-br from-blue-500 to-blue-600 text-white rounded-xl sm:rounded-2xl p-3 sm:p-4 lg:p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-[10px] sm:text-xs opacity-90">Wali Murid</p>
                        <h3 class="text-xl sm:text-2xl lg:text-4xl font-bold mt-1 sm:mt-2">{{ $totalWaliMurid }}</h3>
                    </div>
                    <i class="fas fa-user-friends text-2xl sm:text-3xl lg:text-5xl opacity-50"></i>
                </div>
            </div>

            <div class="card bg-gradient-to-br from-blue-500 to-blue-600 text-white rounded-xl sm:rounded-2xl p-3 sm:p-4 lg:p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-[10px] sm:text-xs opacity-90">Guru</p>
                        <h3 class="text-xl sm:text-2xl lg:text-4xl font-bold mt-1 sm:mt-2">{{ $totalGuru }}</h3>
                    </div>
                    <i class="fas fa-user-friends text-2xl sm:text-3xl lg:text-5xl opacity-50"></i>
                </div>
            </div>

            <div class="card bg-gradient-to-br from-yellow-500 to-yellow-600 text-white rounded-xl sm:rounded-2xl p-3 sm:p-4 lg:p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-[10px] sm:text-xs opacity-90">Total Kuis</p>
                        <h3 class="text-xl sm:text-2xl lg:text-4xl font-bold mt-1 sm:mt-2">{{ $totalKuis }}</h3>
                    </div>
                    <i class="fas fa-clipboard-question text-2xl sm:text-3xl lg:text-5xl opacity-50"></i>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="card rounded-xl sm:rounded-2xl p-4 sm:p-6 bg-white">
            <h2 class="text-lg sm:text-xl lg:text-2xl font-bold text-gray-800 mb-4 sm:mb-6">
                <i class="fas fa-bolt mr-2 text-yellow-500"></i>
                Pilihan Menu
            </h2>

            <!-- First Row -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4 mb-3 sm:mb-4">
                <a href="{{ route('admin.whitelist.index') }}"
                    class="block p-4 sm:p-6 bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl sm:rounded-2xl hover:shadow-lg transition transform hover:scale-105">
                    <i class="fas fa-shield-alt text-2xl sm:text-3xl text-blue-500 mb-2 sm:mb-3"></i>
                    <h3 class="font-bold text-gray-800 text-sm sm:text-base">Kelola Whitelist</h3>
                    <p class="text-xs sm:text-sm text-gray-600 mt-1">Tambah/hapus Email</p>
                </a>

                <a href="{{ route('admin.akun.index') }}"
                    class="block p-4 sm:p-6 bg-gradient-to-br from-green-50 to-green-100 rounded-xl sm:rounded-2xl hover:shadow-lg transition transform hover:scale-105">
                    <i class="fas fa-users-cog text-2xl sm:text-3xl text-green-500 mb-2 sm:mb-3"></i>
                    <h3 class="font-bold text-gray-800 text-sm sm:text-base">Kelola Akun</h3>
                    <p class="text-xs sm:text-sm text-gray-600 mt-1">Lihat & hapus akun user</p>
                </a>
            </div>

            <!-- Second Row -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 sm:gap-4">
                <a href="{{ route('materi.index') }}"
                    class="block p-4 sm:p-6 bg-gradient-to-br from-yellow-50 to-yellow-100 rounded-xl sm:rounded-2xl hover:shadow-lg transition transform hover:scale-105">
                    <i class="fas fa-book text-2xl sm:text-3xl text-yellow-500 mb-2 sm:mb-3"></i>
                    <h3 class="font-bold text-gray-800 text-sm sm:text-base">Materi</h3>
                    <p class="text-xs sm:text-sm text-gray-600 mt-1">Akses materi pembelajaran</p>
                </a>

                <a href="{{ route('permainan.index') }}"
                    class="block p-4 sm:p-6 bg-gradient-to-br from-pink-50 to-pink-100 rounded-xl sm:rounded-2xl hover:shadow-lg transition transform hover:scale-105">
                    <i class="fas fa-gamepad text-2xl sm:text-3xl text-pink-500 mb-2 sm:mb-3"></i>
                    <h3 class="font-bold text-gray-800 text-sm sm:text-base">Permainan</h3>
                    <p class="text-xs sm:text-sm text-gray-600 mt-1">Mainkan permainan</p>
                </a>

                <a href="{{ route('kuis.index') }}"
                    class="block p-4 sm:p-6 bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl sm:rounded-2xl hover:shadow-lg transition transform hover:scale-105">
                    <i class="fas fa-clipboard-question text-2xl sm:text-3xl text-blue-500 mb-2 sm:mb-3"></i>
                    <h3 class="font-bold text-gray-800 text-sm sm:text-base">Kuis</h3>
                    <p class="text-xs sm:text-sm text-gray-600 mt-1">Kelola & kerjakan kuis</p>
                </a>
            </div>
        </div>
    </div>

@endsection
