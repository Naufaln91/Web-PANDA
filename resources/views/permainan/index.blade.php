@extends('layouts.app')

@section('title', 'Permainan - PANDA TK')

@section('content')
    <div class="space-y-4 sm:space-y-6">
        <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold text-gray-800">
            <i class="fas fa-gamepad mr-2 text-pink-500"></i>
            Permainan
        </h1>

        <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-3 gap-3 sm:gap-4 lg:gap-6">
            @foreach ($permainans as $permainan)
                <a href="{{ route($permainan['route']) }}"
                    class="card rounded-xl sm:rounded-2xl p-4 sm:p-6 hover:shadow-2xl transition transform hover:scale-105 bg-gradient-to-br from-green-100 to-green-200">
                    <div class="text-center">
                        <div class="text-4xl sm:text-5xl lg:text-6xl mb-3 sm:mb-4">{{ $permainan['icon'] }}</div>
                        <h3 class="text-sm sm:text-lg lg:text-xl font-bold text-gray-800 mb-1 sm:mb-2">{{ $permainan['title'] }}</h3>
                        <p class="text-xs sm:text-sm text-gray-600 line-clamp-2">{{ $permainan['description'] }}</p>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
@endsection
