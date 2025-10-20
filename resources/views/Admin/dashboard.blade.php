{{-- resources/views/admin/dashboard.blade.php --}}
@extends('layouts.app')

@section('title', 'Dashboard Admin - PANDA TK')

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex justify-between items-center">
            <h1 class="text-3xl font-bold text-gray-800">
                <i class="fas fa-tachometer-alt mr-2 text-blue-500"></i>
                Dashboard Admin
            </h1>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

            <div class="card bg-gradient-to-br from-blue-500 to-blue-600 text-white rounded-2xl p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm opacity-90">Total Whitelist no HP</p>
                        <h3 class="text-4xl font-bold mt-2">{{ $totalWhitelist }}</h3>
                    </div>
                    <i class="fas fa-user-friends text-5xl opacity-50"></i>
                </div>
            </div>

            <div class="card bg-gradient-to-br from-blue-500 to-blue-600 text-white rounded-2xl p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm opacity-90">Wali Murid</p>
                        <h3 class="text-4xl font-bold mt-2">{{ $totalWaliMurid }}</h3>
                    </div>
                    <i class="fas fa-user-friends text-5xl opacity-50"></i>
                </div>
            </div>

            <div class="card bg-gradient-to-br from-blue-500 to-blue-600 text-white rounded-2xl p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm opacity-90">Guru</p>
                        <h3 class="text-4xl font-bold mt-2">{{ $totalGuru }}</h3>
                    </div>
                    <i class="fas fa-user-friends text-5xl opacity-50"></i>
                </div>
            </div>

            <div class="card bg-gradient-to-br from-yellow-500 to-yellow-600 text-white rounded-2xl p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm opacity-90">Total Kuis</p>
                        <h3 class="text-4xl font-bold mt-2">{{ $totalKuis }}</h3>
                    </div>
                    <i class="fas fa-clipboard-question text-5xl opacity-50"></i>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="card rounded-2xl p-6 bg-white">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">
                <i class="fas fa-bolt mr-2 text-yellow-500"></i>
                Aksi Cepat
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <a href="{{ route('admin.whitelist.index') }}"
                    class="block p-6 bg-gradient-to-br from-blue-50 to-blue-100 rounded-2xl hover:shadow-lg transition transform hover:scale-105">
                    <i class="fas fa-list text-3xl text-blue-500 mb-3"></i>
                    <h3 class="font-bold text-gray-800">Kelola Whitelist</h3>
                    <p class="text-sm text-gray-600 mt-1">Tambah/hapus nomor HP</p>
                </a>

                <a href="{{ route('admin.akun.index') }}"
                    class="block p-6 bg-gradient-to-br from-green-50 to-green-100 rounded-2xl hover:shadow-lg transition transform hover:scale-105">
                    <i class="fas fa-user-circle text-3xl text-green-500 mb-3"></i>
                    <h3 class="font-bold text-gray-800">Kelola Akun</h3>
                    <p class="text-sm text-gray-600 mt-1">Lihat & hapus akun user</p>
                </a>

                <a href="{{ route('kuis.create') }}"
                    class="block p-6 bg-gradient-to-br from-purple-50 to-purple-100 rounded-2xl hover:shadow-lg transition transform hover:scale-105">
                    <i class="fas fa-plus-circle text-3xl text-purple-500 mb-3"></i>
                    <h3 class="font-bold text-gray-800">Buat Kuis Baru</h3>
                    <p class="text-sm text-gray-600 mt-1">Tambah kuis pembelajaran</p>
                </a>
            </div>
        </div>
    </div>

@endsection
