@extends('layouts.app')

@section('title', 'Permainan - PANDA TK')

@section('content')
    <div class="space-y-6">
        <h1 class="text-3xl font-bold text-gray-800">
            <i class="fas fa-gamepad mr-2 text-pink-500"></i>
            Permainan Edukatif
        </h1>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($permainans as $permainan)
                <a href="{{ route($permainan['route']) }}"
                    class="card rounded-2xl p-6 hover:shadow-2xl transition transform hover:scale-105 bg-gradient-to-br from-green-100 to-green-200">
                    <div class="text-center">
                        <div class="text-6xl mb-4">{{ $permainan['icon'] }}</div>
                        <h3 class="text-xl font-bold text-gray-800 mb-2">{{ $permainan['title'] }}</h3>
                        <p class="text-gray-600">{{ $permainan['description'] }}</p>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
@endsection
