@extends('layouts.app')

@section('title', 'Kelola Whitelist - PANDA TK')

@section('content')
    <div class="space-y-6">
        <div class="flex justify-between items-center">
            <h1 class="text-3xl font-bold text-gray-800">
                <i class="fas fa-list mr-2 text-blue-500"></i>
                Kelola Whitelist Nomor HP
            </h1>
            <a href="{{ route('admin.dashboard') }}"
                class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded-lg transition">
                <i class="fas fa-arrow-left mr-2"></i> Kembali
            </a>
        </div>

        <!-- Form Tambah Whitelist -->
        <div class="card">
            <h2 class="text-xl font-bold text-gray-800 mb-4">Tambah Nomor HP</h2>

            <form id="form-tambah-whitelist" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                @csrf
                <div>
                    <input type="text" id="nomor_hp" name="nomor_hp"
                        class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-blue-500 focus:outline-none"
                        placeholder="08xxxxxxxxxx">
                </div>
                <div>
                    <select id="role" name="role"
                        class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-blue-500 focus:outline-none">
                        <option value="">Pilih Role</option>
                        <option value="guru">Guru</option>
                        <option value="wali_murid">Wali Murid</option>
                    </select>
                </div>
                <div class="flex items-end">
                    <button type="submit"
                        class="w-full bg-green-500 hover:bg-green-600 text-white font-bold py-3 px-6 rounded-lg transition">
                        <i class="fas fa-plus mr-2"></i> Tambah
                    </button>
                </div>
                <div class="md:col-span-3">
                    <p id="error-message" class="text-red-500 text-sm mt-1 hidden"></p>
                </div>
            </form>
        </div>

        <!-- Daftar Whitelist -->
        <div class="card">
            <h2 class="text-xl font-bold text-gray-800 mb-4">Daftar Nomor HP</h2>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">No</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Nomor HP</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Role</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Ditambahkan</th>
                            <th class="px-6 py-3 text-center text-sm font-semibold text-gray-700">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="whitelist-table-body">
                        @forelse($whitelists as $index => $whitelist)
                            <tr class="border-b hover:bg-gray-50" data-id="{{ $whitelist->id }}">
                                <td class="px-6 py-4 text-sm text-gray-700">{{ $whitelists->firstItem() + $index }}</td>
                                <td class="px-6 py-4 text-sm text-gray-700 font-semibold">{{ $whitelist->nomor_hp }}</td>
                                <td class="px-6 py-4 text-sm">
                                    <span
                                        class="px-3 py-1 rounded-full text-xs font-semibold
                                        {{ $whitelist->role == 'guru' ? 'bg-green-100 text-green-800' : 'bg-purple-100 text-purple-800' }}">
                                        {{ ucfirst(str_replace('_', ' ', $whitelist->role)) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-700">
                                    {{ $whitelist->created_at->format('d M Y, H:i') }}</td>
                                <td class="px-6 py-4 text-center">
                                    <button onclick="deleteWhitelist({{ $whitelist->id }})"
                                        class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded-lg transition">
                                        <i class="fas fa-trash mr-1"></i> Hapus
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                    <i class="fas fa-inbox text-4xl mb-2"></i>
                                    <p>Belum ada nomor HP yang diwhitelist</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $whitelists->links() }}
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            // Tambah Whitelist
            $('#form-tambah-whitelist').on('submit', function(e) {
                e.preventDefault();

                const nomorHp = $('#nomor_hp').val();
                const errorDiv = $('#error-message');

                errorDiv.addClass('hidden');

                $.ajax({
                    url: '{{ route('admin.whitelist.store') }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        nomor_hp: nomorHp,
                        role: $('#role').val()
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
                            errorDiv.text(response.message).removeClass('hidden');
                        }
                    },
                    error: function(xhr) {
                        errorDiv.text('Terjadi kesalahan. Silakan coba lagi.').removeClass('hidden');
                    }
                });
            });

            // Hapus Whitelist
            function deleteWhitelist(id) {
                Swal.fire({
                    title: 'Yakin ingin menghapus?',
                    text: 'Jika nomor ini sudah memiliki akun, akun juga akan terhapus.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Tidak'
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
                                Swal.fire('Error!', 'Terjadi kesalahan.', 'error');
                            }
                        });
                    }
                });
            }
        </script>
    @endpush
@endsection
