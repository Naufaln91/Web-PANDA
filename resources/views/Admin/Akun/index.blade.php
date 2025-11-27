@extends('layouts.app')

@section('title', 'Kelola Akun - PANDA TK')

@section('content')
    <div class="space-y-4 sm:space-y-6">
        <!-- Header -->
        <div class="flex justify-between items-center gap-2">
            <h1 class="text-lg sm:text-2xl lg:text-3xl font-bold text-gray-800 flex items-center">
                <i class="fas fa-users-cog mr-2 sm:mr-3 text-green-500"></i>
                Kelola Akun Pengguna
            </h1>
            <a href="{{ route('admin.dashboard') }}"
                class="bg-gray-500 hover:bg-gray-600 text-white font-semibold py-2 sm:py-2.5 px-3 sm:px-5 rounded-lg transition-all duration-200 shadow-md hover:shadow-lg flex items-center justify-center text-sm sm:text-base">
                <i class="fas fa-arrow-left "></i><span class="ml-2 hidden sm:inline">Kembali</span>
            </a>
        </div>
        <p class="text-xs sm:text-sm text-gray-600">Kelola akun guru dan wali murid yang terdaftar</p>

        <!-- Daftar Akun Guru -->
        <div class="bg-white rounded-xl sm:rounded-2xl shadow-lg border border-gray-200 overflow-hidden">
            <div class="bg-gradient-to-r from-green-50 to-emerald-50 px-3 sm:px-6 py-3 sm:py-4 border-b border-green-100">
                <div class="flex flex-col gap-3 sm:gap-4">
                    <h2 class="text-base sm:text-lg lg:text-xl font-bold text-gray-800 flex items-center">
                        <i class="fas fa-chalkboard-teacher mr-2 text-green-600"></i>
                        Daftar Akun Guru
                    </h2>

                    <!-- Search Box Guru -->
                    <div class="relative w-full">
                        <span class="absolute left-3 sm:left-4 top-1/2 transform -translate-y-1/2 text-gray-400">
                            <i class="fas fa-search text-sm"></i>
                        </span>
                        <input type="text" id="search-guru"
                            class="w-full pl-9 sm:pl-12 pr-3 sm:pr-4 py-2 sm:py-2.5 border-2 border-gray-300 rounded-lg sm:rounded-xl focus:border-green-500 focus:ring-2 focus:ring-green-200 focus:outline-none transition-all duration-200 text-sm sm:text-base"
                            placeholder="Cari email atau nama...">
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full min-w-[600px]">
                    <thead class="bg-gradient-to-r from-gray-100 to-gray-200">
                        <tr>
                            <th
                                class="px-3 sm:px-6 py-2 sm:py-4 text-left text-[10px] sm:text-xs font-bold text-gray-700 uppercase tracking-wider">
                                <i class="fas fa-hashtag mr-1"></i> No
                            </th>
                            <th
                                class="px-3 sm:px-6 py-2 sm:py-4 text-left text-[10px] sm:text-xs font-bold text-gray-700 uppercase tracking-wider">
                                <i class="fas fa-envelope mr-1"></i> Email
                            </th>
                            <th
                                class="px-3 sm:px-6 py-2 sm:py-4 text-left text-[10px] sm:text-xs font-bold text-gray-700 uppercase tracking-wider">
                                <i class="fas fa-user mr-1"></i> Nama
                            </th>
                            <th
                                class="px-3 sm:px-6 py-2 sm:py-4 text-left text-[10px] sm:text-xs font-bold text-gray-700 uppercase tracking-wider">
                                <i class="fas fa-user-tag mr-1"></i> Role
                            </th>
                            <th
                                class="px-3 sm:px-6 py-2 sm:py-4 text-center text-[10px] sm:text-xs font-bold text-gray-700 uppercase tracking-wider">
                                <i class="fas fa-cog mr-1"></i> Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody id="guru-table-body">
                        @forelse($guru as $index => $user)
                            <tr class="border-b border-gray-200 hover:bg-green-50 transition-colors duration-150"
                                data-id="{{ $user->id }}">
                                <td class="px-3 sm:px-6 py-2 sm:py-4 text-xs sm:text-sm text-gray-700 font-medium">
                                    {{ $guru->firstItem() + $index }}
                                </td>
                                <td class="px-3 sm:px-6 py-2 sm:py-4 text-xs sm:text-sm font-semibold text-gray-800">
                                    <div class="flex items-center">
                                        <i class="fas fa-envelope text-green-500 mr-2 text-xs"></i>
                                        <span class="truncate max-w-[150px] sm:max-w-none">{{ $user->email }}</span>
                                    </div>
                                </td>
                                <td class="px-3 sm:px-6 py-2 sm:py-4 text-xs sm:text-sm text-gray-700">
                                    <div class="flex items-center">
                                        <i class="fas fa-user-circle text-gray-400 mr-2 text-xs"></i>
                                        {{ $user->nama }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    <span
                                        class="px-4 py-2 rounded-full text-xs font-bold inline-flex items-center bg-green-100 text-green-800">
                                        <i class="fas fa-chalkboard-teacher mr-1.5"></i>
                                        Guru
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <button onclick="deleteAkun({{ $user->id }})"
                                        class="bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white font-bold py-2 px-4 rounded-lg transition-all duration-200 shadow-md hover:shadow-lg inline-flex items-center">
                                        <i class="fas fa-trash-alt mr-1.5"></i> Hapus
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr id="empty-guru">
                                <td colspan="5" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center text-gray-400">
                                        <i class="fas fa-user-graduate text-6xl mb-4"></i>
                                        <p class="text-lg font-semibold text-gray-500">Belum ada akun guru terdaftar</p>
                                        <p class="text-sm text-gray-400 mt-1">Guru akan muncul setelah registrasi</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                {{ $guru->links() }}
            </div>
        </div>

        <!-- Daftar Akun Wali Murid -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden">
            <div class="bg-gradient-to-r from-purple-50 to-pink-50 px-6 py-4 border-b border-purple-100">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                    <h2 class="text-xl font-bold text-gray-800 flex items-center">
                        <i class="fas fa-users mr-2 text-purple-600"></i>
                        Daftar Akun Wali Murid
                    </h2>

                    <!-- Search Box Wali Murid -->
                    <div class="relative w-full md:w-80">
                        <span class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400">
                            <i class="fas fa-search"></i>
                        </span>
                        <input type="text" id="search-wali"
                            class="w-full pl-12 pr-4 py-2.5 border-2 border-gray-300 rounded-xl focus:border-purple-500 focus:ring-2 focus:ring-purple-200 focus:outline-none transition-all duration-200"
                            placeholder="Cari email atau nama...">
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gradient-to-r from-gray-100 to-gray-200">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                                <i class="fas fa-hashtag mr-1"></i> No
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                                <i class="fas fa-envelope mr-1"></i> Email
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                                <i class="fas fa-user-tie mr-1"></i> Nama Orang Tua
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                                <i class="fas fa-child mr-1"></i> Nama Anak
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                                <i class="fas fa-school mr-1"></i> Kelas
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                                <i class="fas fa-user-tag mr-1"></i> Role
                            </th>
                            <th class="px-6 py-4 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">
                                <i class="fas fa-cog mr-1"></i> Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody id="wali-table-body">
                        @forelse($waliMurid as $index => $user)
                            <tr class="border-b border-gray-200 hover:bg-purple-50 transition-colors duration-150"
                                data-id="{{ $user->id }}">
                                <td class="px-6 py-4 text-sm text-gray-700 font-medium">
                                    {{ $waliMurid->firstItem() + $index }}
                                </td>
                                <td class="px-6 py-4 text-sm font-semibold text-gray-800">
                                    <div class="flex items-center">
                                        <i class="fas fa-envelope text-purple-500 mr-2"></i>
                                        {{ $user->email }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-700">
                                    <div class="flex items-center">
                                        <i class="fas fa-user-circle text-gray-400 mr-2"></i>
                                        {{ $user->nama }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-700">
                                    <div class="flex items-center">
                                        <i class="fas fa-baby text-gray-400 mr-2"></i>
                                        {{ $user->nama_anak }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-700">
                                    <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-lg font-semibold">
                                        {{ $user->kelas_anak }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    <span
                                        class="px-4 py-2 rounded-full text-xs font-bold inline-flex items-center bg-purple-100 text-purple-800">
                                        <i class="fas fa-users mr-1.5"></i>
                                        Wali Murid
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <button onclick="deleteAkun({{ $user->id }})"
                                        class="bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white font-bold py-2 px-4 rounded-lg transition-all duration-200 shadow-md hover:shadow-lg inline-flex items-center">
                                        <i class="fas fa-trash-alt mr-1.5"></i> Hapus
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr id="empty-wali">
                                <td colspan="7" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center text-gray-400">
                                        <i class="fas fa-user-friends text-6xl mb-4"></i>
                                        <p class="text-lg font-semibold text-gray-500">Belum ada akun wali murid terdaftar
                                        </p>
                                        <p class="text-sm text-gray-400 mt-1">Wali murid akan muncul setelah registrasi</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                {{ $waliMurid->links() }}
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            // Search Functionality untuk Guru
            $('#search-guru').on('keyup', function() {
                const searchValue = $(this).val().toLowerCase();
                let visibleRows = 0;

                $('#guru-table-body tr').each(function() {
                    if ($(this).attr('id') === 'empty-guru') return;

                    const email = $(this).find('td:eq(1)').text().toLowerCase();
                    const nama = $(this).find('td:eq(2)').text().toLowerCase();

                    if (email.includes(searchValue) || nama.includes(searchValue)) {
                        $(this).show();
                        visibleRows++;
                    } else {
                        $(this).hide();
                    }
                });

                // Show/hide empty message
                if (visibleRows === 0 && searchValue !== '') {
                    if ($('#no-results-guru').length === 0) {
                        $('#guru-table-body').append(`
                            <tr id="no-results-guru">
                                <td colspan="5" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center text-gray-400">
                                        <i class="fas fa-search text-6xl mb-4"></i>
                                        <p class="text-lg font-semibold text-gray-500">Tidak ada hasil ditemukan</p>
                                        <p class="text-sm text-gray-400 mt-1">Coba kata kunci lain</p>
                                    </div>
                                </td>
                            </tr>
                        `);
                    }
                } else {
                    $('#no-results-guru').remove();
                }
            });

            // Search Functionality untuk Wali Murid
            $('#search-wali').on('keyup', function() {
                const searchValue = $(this).val().toLowerCase();
                let visibleRows = 0;

                $('#wali-table-body tr').each(function() {
                    if ($(this).attr('id') === 'empty-wali') return;

                    const email = $(this).find('td:eq(1)').text().toLowerCase();
                    const namaOrtu = $(this).find('td:eq(2)').text().toLowerCase();
                    const namaAnak = $(this).find('td:eq(3)').text().toLowerCase();

                    if (email.includes(searchValue) || namaOrtu.includes(searchValue) || namaAnak.includes(
                            searchValue)) {
                        $(this).show();
                        visibleRows++;
                    } else {
                        $(this).hide();
                    }
                });

                // Show/hide empty message
                if (visibleRows === 0 && searchValue !== '') {
                    if ($('#no-results-wali').length === 0) {
                        $('#wali-table-body').append(`
                            <tr id="no-results-wali">
                                <td colspan="7" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center text-gray-400">
                                        <i class="fas fa-search text-6xl mb-4"></i>
                                        <p class="text-lg font-semibold text-gray-500">Tidak ada hasil ditemukan</p>
                                        <p class="text-sm text-gray-400 mt-1">Coba kata kunci lain</p>
                                    </div>
                                </td>
                            </tr>
                        `);
                    }
                } else {
                    $('#no-results-wali').remove();
                }
            });

            // Delete Akun
            function deleteAkun(id) {
                Swal.fire({
                    title: 'Yakin ingin menghapus user?',
                    text: 'Data akun akan dihapus permanen.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc2626',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: '<i class="fas fa-trash mr-2"></i>Ya, Hapus!',
                    cancelButtonText: '<i class="fas fa-times mr-2"></i>Batal',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: `/admin/akun/${id}`,
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
                                    }).then(() => {
                                        location.reload();
                                    });
                                }
                            },
                            error: function() {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error!',
                                    text: 'Terjadi kesalahan saat menghapus data.'
                                });
                            }
                        });
                    }
                });
            }
        </script>
    @endpush
@endsection
