@extends('layouts.app')

@section('title', 'Kelola Whitelist - PANDA TK')

@section('content')
    <div class="space-y-4 sm:space-y-6">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 sm:gap-4">
            <div>
                <h1 class="text-lg sm:text-2xl lg:text-3xl font-bold text-gray-800 flex items-center">
                    <i class="fas fa-shield-alt mr-2 sm:mr-3 text-blue-500"></i>
                    Kelola Whitelist Email
                </h1>
                <p class="text-xs sm:text-sm text-gray-600 mt-1 sm:mt-2">Kelola akses pengguna melalui email</p>
            </div>
            <a href="{{ route('admin.dashboard') }}"
                class="bg-gray-500 hover:bg-gray-600 text-white font-semibold py-2 sm:py-2.5 px-4 sm:px-5 rounded-lg transition-all duration-200 shadow-md hover:shadow-lg flex items-center text-sm sm:text-base w-full sm:w-auto justify-center">
                <i class="fas fa-arrow-left mr-2"></i> Kembali
            </a>
        </div>

        <!-- Form Tambah Whitelist -->
        <div
            class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl sm:rounded-2xl shadow-lg border border-blue-100 overflow-hidden">
            <div class="bg-gradient-to-r from-blue-500 to-indigo-600 px-4 sm:px-6 py-3 sm:py-4">
                <h2 class="text-base sm:text-lg lg:text-xl font-bold text-white flex items-center">
                    <i class="fas fa-plus-circle mr-2"></i>
                    Tambah Email Baru
                </h2>
            </div>

            <div class="p-4 sm:p-6">
                <form id="form-tambah-whitelist" class="space-y-4">
                    @csrf
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <!-- Input Email -->
                        <div class="space-y-2">
                            <label for="email" class="block text-xs sm:text-sm font-semibold text-gray-700">
                                <i class="fas fa-envelope text-blue-500 mr-1"></i>
                                Email
                            </label>
                            <div class="relative">
                                <span class="absolute left-3 sm:left-4 top-1/2 transform -translate-y-1/2 text-gray-400">
                                    <i class="fas fa-envelope text-sm"></i>
                                </span>
                                <input type="email" id="email" name="email"
                                    class="w-full pl-9 sm:pl-12 pr-3 sm:pr-4 py-2.5 sm:py-3 border-2 border-gray-300 rounded-lg sm:rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-200 focus:outline-none transition-all duration-200 text-sm sm:text-base"
                                    placeholder="Contoh: nama@email.com">
                            </div>
                        </div>

                        <!-- Select Role -->
                        <div class="space-y-2">
                            <label for="role" class="block text-xs sm:text-sm font-semibold text-gray-700">
                                <i class="fas fa-user-tag text-blue-500 mr-1"></i>
                                Role Pengguna
                            </label>
                            <div class="relative">
                                <span class="absolute left-3 sm:left-4 top-1/2 transform -translate-y-1/2 text-gray-400">
                                    <i class="fas fa-briefcase text-sm"></i>
                                </span>
                                <select id="role" name="role"
                                    class="w-full pl-9 sm:pl-12 pr-3 sm:pr-4 py-2.5 sm:py-3 border-2 border-gray-300 rounded-lg sm:rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-200 focus:outline-none transition-all duration-200 appearance-none bg-white text-sm sm:text-base">
                                    <option value="">Pilih Role</option>
                                    <option value="guru">👨‍🏫 Guru</option>
                                    <option value="wali_murid">👨‍👩‍👧 Wali Murid</option>
                                </select>
                                <span
                                    class="absolute right-3 sm:right-4 top-1/2 transform -translate-y-1/2 text-gray-400 pointer-events-none">
                                    <i class="fas fa-chevron-down text-sm"></i>
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Error Message -->
                    <div id="error-message" class="hidden bg-red-50 border-l-4 border-red-500 p-3 sm:p-4 rounded-lg">
                        <div class="flex items-center">
                            <i class="fas fa-exclamation-circle text-red-500 mr-2"></i>
                            <p class="text-red-700 text-xs sm:text-sm font-medium"></p>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-end">
                        <button type="submit"
                            class="bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white font-bold py-3 px-8 rounded-xl transition-all duration-200 shadow-md hover:shadow-lg flex items-center">
                            <i class="fas fa-plus-circle mr-2"></i> Tambah ke Whitelist
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Daftar Whitelist -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden">
            <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4 border-b border-gray-200">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                    <h2 class="text-xl font-bold text-gray-800 flex items-center">
                        <i class="fas fa-list-ul mr-2 text-blue-500"></i>
                        Daftar Email Whitelist
                    </h2>

                    <!-- Search Box -->
                    <div class="relative w-full md:w-80">
                        <span class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400">
                            <i class="fas fa-search"></i>
                        </span>
                        <input type="text" id="search-input"
                            class="w-full pl-12 pr-4 py-2.5 border-2 border-gray-300 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-200 focus:outline-none transition-all duration-200"
                            placeholder="Cari email...">
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
                                <i class="fas fa-user-tag mr-1"></i> Role
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                                <i class="fas fa-calendar-plus mr-1"></i> Ditambahkan
                            </th>
                            <th class="px-6 py-4 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">
                                <i class="fas fa-cog mr-1"></i> Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody id="whitelist-table-body">
                        @forelse($whitelists as $index => $whitelist)
                            <tr class="border-b border-gray-200 hover:bg-blue-50 transition-colors duration-150"
                                data-id="{{ $whitelist->id }}">
                                <td class="px-6 py-4 text-sm text-gray-700 font-medium">
                                    {{ $whitelists->firstItem() + $index }}
                                </td>
                                <td class="px-6 py-4 text-sm font-semibold text-gray-800">
                                    <div class="flex items-center">
                                        <i class="fas fa-envelope text-blue-500 mr-2"></i>
                                        {{ $whitelist->email }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    <span
                                        class="px-4 py-2 rounded-full text-xs font-bold inline-flex items-center
                                        {{ $whitelist->role == 'guru' ? 'bg-green-100 text-green-800' : 'bg-purple-100 text-purple-800' }}">
                                        <i
                                            class="fas {{ $whitelist->role == 'guru' ? 'fa-chalkboard-teacher' : 'fa-users' }} mr-1.5"></i>
                                        {{ ucfirst(str_replace('_', ' ', $whitelist->role)) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    <div class="flex items-center">
                                        <i class="fas fa-clock text-gray-400 mr-2"></i>
                                        {{ $whitelist->created_at->format('d M Y, H:i') }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <button onclick="deleteWhitelist({{ $whitelist->id }})"
                                        class="bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white font-bold py-2 px-4 rounded-lg transition-all duration-200 shadow-md hover:shadow-lg inline-flex items-center">
                                        <i class="fas fa-trash-alt mr-1.5"></i> Hapus
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr id="empty-row">
                                <td colspan="5" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center text-gray-400">
                                        <i class="fas fa-inbox text-6xl mb-4"></i>
                                        <p class="text-lg font-semibold text-gray-500">Belum ada email yang diwhitelist
                                        </p>
                                        <p class="text-sm text-gray-400 mt-1">Tambahkan email untuk memberikan akses</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                {{ $whitelists->links() }}
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            // Tambah Whitelist
            $('#form-tambah-whitelist').on('submit', function(e) {
                e.preventDefault();

                const email = $('#email').val();
                const role = $('#role').val();
                const errorDiv = $('#error-message');

                errorDiv.addClass('hidden');

                // Validasi
                if (!email || !role) {
                    errorDiv.find('p').text('Semua field harus diisi!');
                    errorDiv.removeClass('hidden');
                    return;
                }

                $.ajax({
                    url: '{{ route('admin.whitelist.store') }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        email: email,
                        role: role
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
                        } else {
                            errorDiv.find('p').text(response.message);
                            errorDiv.removeClass('hidden');
                        }
                    },
                    error: function(xhr) {
                        const message = xhr.responseJSON?.message ||
                            'Terjadi kesalahan. Silakan coba lagi.';
                        errorDiv.find('p').text(message);
                        errorDiv.removeClass('hidden');
                    }
                });
            });

            // Search Functionality
            $('#search-input').on('keyup', function() {
                const searchValue = $(this).val().toLowerCase();
                let visibleRows = 0;

                $('#whitelist-table-body tr').each(function() {
                    if ($(this).attr('id') === 'empty-row') return;

                    const email = $(this).find('td:eq(1)').text().toLowerCase();
                    const role = $(this).find('td:eq(2)').text().toLowerCase();

                    if (email.includes(searchValue) || role.includes(searchValue)) {
                        $(this).show();
                        visibleRows++;
                    } else {
                        $(this).hide();
                    }
                });

                // Show/hide empty message
                if (visibleRows === 0 && searchValue !== '') {
                    if ($('#no-results').length === 0) {
                        $('#whitelist-table-body').append(`
                            <tr id="no-results">
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
                    $('#no-results').remove();
                }
            });

            // Hapus Whitelist
            function deleteWhitelist(id) {
                Swal.fire({
                    title: 'Yakin ingin menghapus?',
                    text: 'Jika email ini sudah memiliki akun, akun juga akan terhapus.',
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
                            url: `/admin/whitelist/${id}`,
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
