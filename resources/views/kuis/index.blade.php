@extends('layouts.app')

@section('title', 'Daftar Kuis - PANDA TK')

@section('content')
    <div class="space-y-6">
        <div class="flex justify-between items-center">
            <h1 class="text-3xl font-bold text-gray-800">
                <i class="fas fa-clipboard-question mr-2 text-blue-500"></i>
                Daftar Kuis
            </h1>

            @if (auth()->user()->isAdmin() || auth()->user()->isGuru())
                <a href="{{ route('kuis.create') }}"
                    class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-3 px-6 rounded-lg transition">
                    <i class="fas fa-plus-circle mr-2"></i> Buat Kuis Baru
                </a>
            @endif

        </div>

        @if ($kuis->isEmpty())
            <div class="card text-center py-12">
                <i class="fas fa-inbox text-6xl text-gray-300 mb-4"></i>
                <p class="text-gray-500 text-lg">Belum ada kuis tersedia</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($kuis as $item)
                    <div class="card bg-white rounded-2xl shadow-md hover:shadow-xl transition p-6">

                        <!-- Status & Actions -->
                        <div class="flex justify-between items-start mb-4">
                            <span
                                class="px-3 py-1 rounded-full text-xs font-semibold
                    {{ $item->status == 'published' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                {{ $item->status == 'published' ? 'Published' : 'Draft' }}
                            </span>

                            @if (auth()->user()->isAdmin() || $item->created_by == auth()->id())
                                <div class="flex space-x-3">
                                    <a href="{{ route('kuis.edit', $item->id) }}" class="text-blue-500 hover:text-blue-700">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button onclick="deleteKuis({{ $item->id }})"
                                        class="text-red-500 hover:text-red-700">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            @endif
                        </div>

                        <!-- Title & Description -->
                        <h3 class="text-xl font-bold text-gray-800 mb-2">{{ $item->judul }}</h3>
                        <p class="text-gray-600 text-sm mb-4 line-clamp-2">{{ $item->deskripsi }}</p>

                        <!-- Meta Info -->
                        <div class="flex items-center justify-between text-sm text-gray-500 mb-4">
                            <span><i class="fas fa-list-ol mr-1"></i> {{ $item->soal->count() }} Soal</span>
                            <span>
                                <i class="fas fa-clock mr-1"></i>
                                @if ($item->waktu_tipe == 'tanpa_waktu')
                                    Tanpa Batas
                                @else
                                    {{ $item->durasi_waktu }}s
                                @endif
                            </span>
                        </div>

                        <!-- Action Button -->
                        @if ($item->status == 'published')
                            <a href="{{ route('kuis.show', $item->id) }}"
                                class="block w-full bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-lg text-center transition">
                                <i class="fas fa-play mr-2"></i> Mulai Kuis
                            </a>
                        @endif
                    </div>
                @endforeach
            </div>

        @endif
    </div>

    @push('scripts')
        <script>
            function deleteKuis(id) {
                Swal.fire({
                    title: 'Yakin ingin menghapus kuis?',
                    text: 'Kuis akan dihapus permanen.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: `/kuis/${id}`,
                            method: 'DELETE',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                if (response.success) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Berhasil!',
                                        text: response.message,
                                        timer: 2000,
                                        showConfirmButton: false
                                    }).then(() => location.reload());
                                }
                            }
                        });
                    }
                });
            }
        </script>
    @endpush
@endsection
