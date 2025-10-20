@extends('layouts.app')

@section('title', 'Materi Pembelajaran - PANDA TK')

@section('content')
    <div class="space-y-6">
        <h1 class="text-3xl font-bold text-gray-800">
            <i class="fas fa-book mr-2 text-yellow-500"></i>
            Materi Pembelajaran
        </h1>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($materis as $materi)
                <a href="{{ route($materi['route']) }}"
                    class="card rounded-2xl p-6 hover:shadow-2xl transition transform hover:scale-105 bg-gradient-to-br from-blue-100 to-blue-200">
                    <div class="text-center">
                        <div class="text-6xl mb-4">{{ $materi['icon'] }}</div>
                        <h3 class="text-xl font-bold text-gray-800 mb-2">{{ $materi['title'] }}</h3>
                        <p class="text-gray-600">{{ $materi['description'] }}</p>
                    </div>
                </a>
            @endforeach
        </div>
    </div>

@endsection
