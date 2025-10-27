@extends('layouts.app')

@section('title', 'Kelola Akun - PANDA TK')

@section('content')
    <div class="space-y-6">
        <div class="flex justify-between items-center">
            <h1 class="text-3xl font-bold text-gray-800">
                <i class="fas fa-user-circle mr-2 text-green-500"></i>
                Kelola Akun Pengguna
            </h1>
            <a href="{{ route('admin.dashboard') }}"
                class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded-lg transition">
                <i class="fas fa-arrow-left mr-2"></i> Kembali
            </a>
        </div>

        <!-- Daftar Akun Guru -->
        <div class="card">
            <h2 class="text-xl font-bold text-gray-800 mb-4">Daftar Akun Guru</h2>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">No</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Nomor HP</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Nama</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Role</th>
                            <th class="px-6 py-3 text-center text-sm font-semibold text-gray-700">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($guru as $index => $user)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="px-6 py-4 text-sm text-gray-700">{{ $guru->firstItem() + $index }}</td>
                                <td class="px-6 py-4 text-sm text-gray-700 font-semibold">{{ $user->nomor_hp }}</td>
                                <td class="px-6 py-4 text-sm text-gray-700">{{ $user->nama }}</td>
                                <td class="px-6 py-4 text-sm">
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                        Guru
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <button onclick="deleteAkun({{ $user->id }})"
                                        class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded-lg transition">
                                        <i class="fas fa-trash mr-1"></i> Hapus
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                    <i class="fas fa-users text-4xl mb-2"></i>
                                    <p>Belum ada akun guru terdaftar</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $guru->links() }}
            </div>
        </div>

        <!-- Daftar Akun Wali Murid -->
        <div class="card">
            <h2 class="text-xl font-bold text-gray-800 mb-4">Daftar Akun Wali Murid</h2>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">No</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Nomor HP</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Nama Orang Tua</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Nama Anak</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Kelas</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Role</th>
                            <th class="px-6 py-3 text-center text-sm font-semibold text-gray-700">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($waliMurid as $index => $user)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="px-6 py-4 text-sm text-gray-700">{{ $waliMurid->firstItem() + $index }}</td>
                                <td class="px-6 py-4 text-sm text-gray-700 font-semibold">{{ $user->nomor_hp }}</td>
                                <td class="px-6 py-4 text-sm text-gray-700">{{ $user->nama }}</td>
                                <td class="px-6 py-4 text-sm text-gray-700">{{ $user->nama_anak }}</td>
                                <td class="px-6 py-4 text-sm text-gray-700">{{ $user->kelas_anak }}</td>
                                <td class="px-6 py-4 text-sm">
                                    <span
                                        class="px-3 py-1 rounded-full text-xs font-semibold bg-purple-100 text-purple-800">
                                        Wali Murid
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <button onclick="deleteAkun({{ $user->id }})"
                                        class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded-lg transition">
                                        <i class="fas fa-trash mr-1"></i> Hapus
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                                    <i class="fas fa-users text-4xl mb-2"></i>
                                    <p>Belum ada akun wali murid terdaftar</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $waliMurid->links() }}
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function deleteAkun(id) {
                Swal.fire({
                    title: 'Yakin ingin menghapus user?',
                    text: 'Data akun akan dihapus permanen.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Tidak'
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
                                Swal.fire('Error!', 'Terjadi kesalahan.', 'error');
                            }
                        });
                    }
                });
            }
        </script>
    @endpush
@endsection
