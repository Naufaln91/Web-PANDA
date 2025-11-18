@extends('layouts.app')

@section('title', 'Permainan - PANDA TK')

@section('content')
    <div class="space-y-4 sm:space-y-6">
        <!-- Header -->
        <div class="flex justify-between items-center gap-2">
            <h1 class="text-lg sm:text-2xl lg:text-3xl font-bold text-gray-800 flex items-center">
                <i class="fas fa-gamepad mr-2 sm:mr-3 text-pink-500"></i>
                Permainan
            </h1>
            <a href="{{ auth()->user()->isAdmin() ? route('admin.dashboard') : (auth()->user()->isGuru() ? route('guru.dashboard') : route('wali-murid.dashboard')) }}"
                class="bg-gray-500 hover:bg-gray-600 text-white font-semibold py-2 sm:py-2.5 px-3 sm:px-5 rounded-lg transition-all duration-200 shadow-md hover:shadow-lg flex items-center justify-center text-sm sm:text-base">
                <i class="fas fa-arrow-left "></i><span class="ml-2">Kembali</span>
            </a>
        </div>
        <p class="text-xs sm:text-sm text-gray-600">Mainkan berbagai permainan edukatif</p>

        <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-3 gap-3 sm:gap-4 lg:gap-6">
            @foreach ($permainans as $permainan)
                <a href="{{ route($permainan['route']) }}"
                    class="card rounded-xl sm:rounded-2xl p-4 sm:p-6 hover:shadow-2xl transition transform hover:scale-105 bg-gradient-to-br from-green-100 to-green-200">
                    <div class="text-center">
                        <div class="text-4xl sm:text-5xl lg:text-6xl mb-3 sm:mb-4">{{ $permainan['icon'] }}</div>
                        <h3 class="text-sm sm:text-lg lg:text-xl font-bold text-gray-800 mb-1 sm:mb-2">
                            {{ $permainan['title'] }}</h3>
                        <p class="text-xs sm:text-sm text-gray-600 line-clamp-2">{{ $permainan['description'] }}</p>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
@endsection
