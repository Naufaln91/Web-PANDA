@extends('layouts.app')

@section('title', 'Histori Pengerjaan Kuis - PANDA TK')

@section('content')
    <div class="space-y-6">
        <div class="flex justify-between items-center gap-2">
            <div>
                <h1 class="text-xl sm:text-2xl md:text-3xl font-bold text-gray-800">
                    <i class="fas fa-history mr-2 text-green-500"></i>
                    Histori Pengerjaan Kuis
                </h1>
                <div class="bg-blue-50 border border-blue-200 rounded-lg px-4 py-2 mt-4 inline-block">
                    <p class="text-sm sm:text-base md:text-lg font-semibold text-blue-700">
                        <i class="fas fa-book mr-2 text-blue-600"></i>
                        {{ $kuis->judul }}
                    </p>
                </div>
            </div>

            <a href="{{ route('kuis.index') }}"
                class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-3 sm:px-6 rounded-lg transition flex items-center justify-center">
                <i class="fas fa-arrow-left "></i><span class="ml-2 hidden sm:inline">Kembali</span>
            </a>
        </div>

        @if ($histori->isEmpty())
            <div class="card text-center py-12">
                <i class="fas fa-chart-line text-6xl text-gray-300 mb-4"></i>
                <p class="text-gray-500 text-lg">Belum ada yang mengerjakan kuis ini</p>
            </div>
        @else
            <!-- Statistik Ringkas -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div
                    class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-2xl shadow-lg border border-blue-100 overflow-hidden">
                    <div class="bg-blue-600 px-6 py-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-lg font-bold text-white">Total Pengerjaan</h3>
                            </div>
                            <i class="fas fa-users text-3xl text-white"></i>
                        </div>
                    </div>
                    <div class="px-6 py-4">
                        <p class="text-4xl font-bold text-blue-600">{{ $histori->count() }}</p>
                        <p class="text-sm text-gray-600 mt-1">kali dikerjakan</p>
                    </div>
                </div>

                <div
                    class="bg-gradient-to-br from-green-50 to-emerald-50 rounded-2xl shadow-lg border border-green-100 overflow-hidden">
                    <div class="bg-green-500 px-6 py-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-lg font-bold text-white">Rata-rata Nilai</h3>
                            </div>
                            <i class="fas fa-chart-line text-3xl text-white opacity-80"></i>
                        </div>
                    </div>
                    <div class="px-6 py-4">
                        <p class="text-4xl font-bold text-green-600">{{ round($histori->avg('nilai'), 1) }}</p>
                        <p class="text-sm text-gray-600 mt-1">dari 100 poin</p>
                    </div>
                </div>

                <div
                    class="bg-gradient-to-br from-yellow-50 to-amber-50 rounded-2xl shadow-lg border border-yellow-100 overflow-hidden">
                    <div class="bg-yellow-500 px-6 py-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-lg font-bold text-white">Nilai Tertinggi</h3>
                            </div>
                            <i class="fas fa-trophy text-3xl text-white opacity-80"></i>
                        </div>
                    </div>
                    <div class="px-6 py-4">
                        <p class="text-4xl font-bold text-yellow-600">{{ $histori->max('nilai') }}</p>
                        <p class="text-sm text-gray-600 mt-1">poin maksimal</p>
                    </div>
                </div>

                <div
                    class="bg-gradient-to-br from-purple-50 to-violet-50 rounded-2xl shadow-lg border border-purple-100 overflow-hidden">
                    <div class="bg-purple-500 px-6 py-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-lg font-bold text-white">Rata-rata Soal</h3>
                            </div>
                            <i class="fas fa-list-ol text-3xl text-white opacity-80"></i>
                        </div>
                    </div>
                    <div class="px-6 py-4">
                        <p class="text-4xl font-bold text-purple-600">{{ round($histori->avg('jumlah_soal_dijawab'), 1) }}
                        </p>
                        <p class="text-sm text-gray-600 mt-1">soal rata-rata</p>
                    </div>
                </div>
            </div>

            <!-- Tabel Histori -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden">
                <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4 border-b border-gray-200">
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                        <h2 class="text-xl font-bold text-gray-800 flex items-center">
                            <i class="fas fa-list-ul mr-2 text-blue-500"></i>
                            Detail Pengerjaan Kuis
                        </h2>

                        <!-- Search Box -->
                        <div class="relative w-full md:w-80">
                            <span class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400">
                                <i class="fas fa-search"></i>
                            </span>
                            <input type="text" id="search-input"
                                class="w-full pl-12 pr-4 py-2.5 border-2 border-gray-300 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-200 focus:outline-none transition-all duration-200"
                                placeholder="Cari nama siswa...">
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
                                    <i class="fas fa-user mr-1"></i> Siswa/Guru
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                                    <i class="fas fa-list-ol mr-1"></i> Soal Dijawab
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                                    <i class="fas fa-check-circle mr-1"></i> Jawaban Benar
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                                    <i class="fas fa-star mr-1"></i> Nilai
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                                    <i class="fas fa-calendar-alt mr-1"></i> Waktu Selesai
                                </th>
                                <th class="px-6 py-4 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">
                                    <i class="fas fa-cog mr-1"></i> Aksi
                                </th>
                                <th class="px-6 py-4 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">
                                    <i class="fas fa-trash mr-1"></i> Hapus
                                </th>
                            </tr>
                        </thead>
                        <tbody id="histori-table-body" class="bg-white divide-y divide-gray-200">
                            @foreach ($histori as $index => $item)
                                <tr class="border-b border-gray-200 hover:bg-blue-50 transition-colors duration-150"
                                    data-id="{{ $item->id }}">
                                    <td class="px-6 py-4 text-sm text-gray-700 font-medium">
                                        {{ $index + 1 }}
                                    </td>
                                    <td class="px-6 py-4 text-sm">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10 mr-3">
                                                <div
                                                    class="h-10 w-10 rounded-full bg-gradient-to-r from-blue-500 to-indigo-600 flex items-center justify-center">
                                                    <span class="text-white font-bold text-sm">
                                                        {{ substr($item->user->nama_anak ?: $item->user->nama, 0, 1) }}
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-semibold text-gray-900">
                                                    {{ $item->user->nama_anak ?: $item->user->nama }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-700 font-medium">
                                        <div class="flex items-center">
                                            <i class="fas fa-list-ol text-blue-500 mr-2"></i>
                                            {{ $item->jumlah_soal_dijawab }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-700 font-medium">
                                        <div class="flex items-center">
                                            <i class="fas fa-check-circle text-green-500 mr-2"></i>
                                            {{ $item->jumlah_benar }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-sm">
                                        <span
                                            class="px-4 py-2 rounded-full text-xs font-bold inline-flex items-center
                                            {{ $item->nilai >= 80
                                                ? 'bg-green-100 text-green-800'
                                                : ($item->nilai >= 60
                                                    ? 'bg-yellow-100 text-yellow-800'
                                                    : 'bg-red-100 text-red-800') }}">
                                            <i class="fas fa-star mr-1.5"></i>
                                            {{ $item->nilai }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600">
                                        <div class="flex items-center">
                                            <i class="fas fa-clock text-gray-400 mr-2"></i>
                                            {{ $item->waktu_selesai->format('d M Y, H:i') }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <button onclick="showDetail({{ $item->id }})"
                                            class="bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-bold py-2 px-4 rounded-lg transition-all duration-200 shadow-md hover:shadow-lg inline-flex items-center">
                                            <i class="fas fa-eye mr-1.5"></i> Detail
                                        </button>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <button onclick="hapusHistori({{ $item->id }})"
                                            class="bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white font-bold py-2 px-4 rounded-lg transition-all duration-200 shadow-md hover:shadow-lg inline-flex items-center">
                                            <i class="fas fa-trash mr-1.5"></i> Hapus
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                    {{ $histori->links() }}
                </div>
            </div>
        @endif
    </div>

    <!-- Modal Detail Jawaban -->
    <div id="detailModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-medium text-gray-900" id="modalTitle">Detail Jawaban</h3>
                    <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div id="detailContent" class="space-y-4">
                    <!-- Content will be loaded here -->
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            // Search Functionality
            $('#search-input').on('keyup', function() {
                const searchValue = $(this).val().toLowerCase();
                let visibleRows = 0;

                $('#histori-table-body tr').each(function() {
                    if ($(this).attr('id') === 'empty-row') return;

                    const nama = $(this).find('td:eq(1)').text().toLowerCase();

                    if (nama.includes(searchValue)) {
                        $(this).show();
                        visibleRows++;
                    } else {
                        $(this).hide();
                    }
                });

                // Show/hide empty message
                if (visibleRows === 0 && searchValue !== '') {
                    if ($('#no-results').length === 0) {
                        $('#histori-table-body').append(`
                            <tr id="no-results">
                                <td colspan="8" class="px-6 py-12 text-center">
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

            function showDetail(historiId) {
                // Fetch detail data
                $.ajax({
                    url: `/api/histori-kuis/${historiId}`,
                    method: 'GET',
                    success: function(response) {
                        if (response.success) {
                            displayDetailModal(response.histori);
                        }
                    },
                    error: function() {
                        Swal.fire('Error', 'Gagal memuat detail jawaban', 'error');
                    }
                });
            }

            function displayDetailModal(histori) {
                const modal = $('#detailModal');
                const content = $('#detailContent');

                let html = `
                    <div class="bg-gray-50 p-4 rounded-lg mb-4">
                        <h4 class="font-semibold text-gray-800">${histori.user.nama}</h4>
                        <p class="text-sm text-gray-600">Nilai: ${histori.nilai} | Soal Dijawab: ${histori.jumlah_soal_dijawab} | Benar: ${histori.jumlah_benar}</p>
                        <p class="text-sm text-gray-600">Waktu: ${new Date(histori.waktu_selesai).toLocaleString('id-ID')}</p>
                    </div>
                `;

                if (histori.detail_jawaban && histori.detail_jawaban.length > 0) {
                    html += '<div class="space-y-3">';
                    histori.detail_jawaban.forEach((jawaban, index) => {
                        const statusClass = jawaban.is_correct ? 'text-green-600' : 'text-red-600';
                        const statusIcon = jawaban.is_correct ? 'fa-check-circle' : 'fa-times-circle';

                        html += `
                            <div class="border rounded-lg p-3">
                                <div class="flex items-center justify-between">
                                    <span class="font-medium">Soal ${index + 1}</span>
                                    <i class="fas ${statusIcon} ${statusClass}"></i>
                                </div>
                                <p class="text-sm text-gray-600 mt-1">
                                    Status: <span class="${statusClass} font-medium">
                                        ${jawaban.is_correct ? 'Benar' : 'Salah'}
                                    </span>
                                </p>
                            </div>
                        `;
                    });
                    html += '</div>';
                } else {
                    html += '<p class="text-gray-500 text-center py-4">Detail jawaban tidak tersedia</p>';
                }

                content.html(html);
                modal.removeClass('hidden');
            }

            function closeModal() {
                $('#detailModal').addClass('hidden');
            }

            // Close modal when clicking outside
            $('#detailModal').on('click', function(e) {
                if (e.target === this) {
                    closeModal();
                }
            });

            function hapusHistori(historiId) {
                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Data histori kuis ini akan dihapus secara permanen!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: `/api/histori-kuis/${historiId}`,
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(response) {
                                if (response.success) {
                                    Swal.fire(
                                        'Terhapus!',
                                        response.message,
                                        'success'
                                    ).then(() => {
                                        location.reload();
                                    });
                                }
                            },
                            error: function() {
                                Swal.fire('Error', 'Gagal menghapus histori kuis', 'error');
                            }
                        });
                    }
                });
            }
        </script>
    @endpush
@endsection
