@extends('layouts.app')

@section('title', 'Daftar Kuis - PANDA TK')

@section('content')
    <div class="space-y-4 sm:space-y-6">
        <!-- Header -->
        <div class="space-y-2">
            <div class="flex justify-between items-center gap-2">
                <h1 class="text-lg sm:text-2xl lg:text-3xl font-bold text-gray-800 flex items-center">
                    <i class="fas fa-clipboard-question mr-2 sm:mr-3 text-blue-500"></i>
                    Daftar Kuis
                </h1>
                <div class="flex gap-2 sm:gap-3">
                    <a href="{{ auth()->user()->isAdmin() ? route('admin.dashboard') : (auth()->user()->isGuru() ? route('guru.dashboard') : route('wali-murid.dashboard')) }}"
                        class="bg-gray-500 hover:bg-gray-600 text-white font-semibold py-2 sm:py-2.5 px-3 sm:px-5 rounded-lg transition-all duration-200 shadow-md hover:shadow-lg flex items-center justify-center text-sm sm:text-base">
                        <i class="fas fa-arrow-left "></i><span class="ml-2 hidden sm:inline">Kembali</span>
                    </a>
                    @if (auth()->user()->isAdmin() || auth()->user()->isGuru())
                        <a href="{{ route('kuis.create') }}"
                            class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 sm:py-2.5 px-3 sm:px-6 rounded-lg transition text-sm sm:text-base flex items-center justify-center whitespace-nowrap">
                            <i class="fas fa-plus-circle mr-2"></i><span>Buat Kuis Baru</span>
                        </a>
                    @endif
                </div>
            </div>
            <p class="text-xs sm:text-sm text-gray-600">Kerjakan berbagai kuis pembelajaran</p>
        </div>

        @if ($kuis->isEmpty())
            <div class="card text-center py-8 sm:py-12">
                <i class="fas fa-inbox text-4xl sm:text-6xl text-gray-300 mb-4"></i>
                <p class="text-gray-500 text-base sm:text-lg">Belum ada kuis tersedia</p>
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 sm:gap-4 lg:gap-6">
                @foreach ($kuis as $item)
                    <div class="card bg-white rounded-xl sm:rounded-2xl shadow-md hover:shadow-xl transition p-4 sm:p-6">

                        <!-- Status & Actions -->
                        <div class="flex justify-between items-start mb-3 sm:mb-4">
                            <span
                                class="px-2.5 sm:px-3 py-1 rounded-full text-xs font-semibold
                    {{ $item->status == 'published' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                {{ $item->status == 'published' ? 'Published' : 'Draft' }}
                            </span>

                            @if (auth()->user()->isAdmin() || $item->created_by == auth()->id())
                                <div class="flex gap-1 sm:gap-3">
                                    <a href="{{ route('kuis.histori', $item->id) }}"
                                        class="text-green-500 hover:text-green-700 p-1.5 sm:p-1 flex items-center justify-center"
                                        title="Histori Pengerjaan">
                                        <i class="fas fa-history text-sm sm:text-base"></i>
                                    </a>
                                    <a href="{{ route('kuis.edit', $item->id) }}"
                                        class="text-blue-500 hover:text-blue-700 p-1.5 sm:p-1 flex items-center justify-center"
                                        title="Edit Kuis">
                                        <i class="fas fa-edit text-sm sm:text-base"></i>
                                    </a>
                                    <button onclick="deleteKuis({{ $item->id }})"
                                        class="text-red-500 hover:text-red-700 p-1.5 sm:p-1 flex items-center justify-center"
                                        title="Hapus Kuis">
                                        <i class="fas fa-trash text-sm sm:text-base"></i>
                                    </button>
                                </div>
                            @endif
                        </div>

                        <!-- Title & Description -->
                        <h3 class="text-base sm:text-lg lg:text-xl font-bold text-gray-800 mb-2">{{ $item->judul }}</h3>
                        <p class="text-gray-600 text-xs sm:text-sm mb-3 sm:mb-4 line-clamp-2">{{ $item->deskripsi }}</p>

                        <!-- Meta Info -->
                        <div class="flex items-center justify-between text-xs sm:text-sm text-gray-500 mb-3 sm:mb-4">
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
                                class="block w-full bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 sm:py-2.5 px-4 rounded-lg text-center transition text-sm sm:text-base">
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
            window.deleteKuis = function(id) {
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
            };
        </script>
    @endpush
@endsection
