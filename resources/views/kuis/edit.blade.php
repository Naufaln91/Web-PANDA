@extends('layouts.app')

@section('title', 'Edit Kuis - PANDA TK')

@section('content')
    <div class="max-w-6xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-800">
                <i class="fas fa-edit mr-2 text-blue-500"></i>
                Edit Kuis
            </h1>
            <a href="{{ route('kuis.index') }}"
                class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded-lg transition">
                <i class="fas fa-arrow-left mr-2"></i> Kembali
            </a>
        </div>

        <!-- Info Kuis -->
        <div class="card mb-6">
            <h2 class="text-2xl font-bold mb-6 text-gray-800">
                <i class="fas fa-info-circle mr-2 text-blue-500"></i>
                Informasi Kuis
            </h2>

            <div class="space-y-6">
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Judul Kuis <span
                            class="text-red-500">*</span></label>
                    <input type="text" id="judul" value="{{ $kuis->judul }}"
                        class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-blue-500 focus:outline-none">
                </div>

                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Deskripsi Kuis</label>
                    <textarea id="deskripsi" rows="3"
                        class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-blue-500 focus:outline-none">{{ $kuis->deskripsi }}</textarea>
                </div>

                <div class="grid md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Pengaturan Waktu <span
                                class="text-red-500">*</span></label>
                        <select id="waktu_tipe"
                            class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-blue-500 focus:outline-none">
                            <option value="tanpa_waktu" {{ $kuis->waktu_tipe == 'tanpa_waktu' ? 'selected' : '' }}>Tanpa
                                Batasan Waktu</option>
                            <option value="per_soal" {{ $kuis->waktu_tipe == 'per_soal' ? 'selected' : '' }}>Waktu Per Soal
                            </option>
                            <option value="keseluruhan" {{ $kuis->waktu_tipe == 'keseluruhan' ? 'selected' : '' }}>Waktu
                                Keseluruhan</option>
                        </select>
                    </div>

                    <div id="durasi-container" class="{{ $kuis->waktu_tipe == 'tanpa_waktu' ? 'hidden' : '' }}">
                        <label class="block text-gray-700 font-semibold mb-2">Durasi Waktu (detik) <span
                                class="text-red-500">*</span></label>
                        <input type="number" id="durasi_waktu" value="{{ $kuis->durasi_waktu }}"
                            class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-blue-500 focus:outline-none"
                            min="5" max="3600">
                    </div>
                </div>

                <div class="flex justify-end space-x-3">
                    <button onclick="updateKuisInfo()"
                        class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-3 px-6 rounded-lg transition">
                        <i class="fas fa-save mr-2"></i> Simpan Perubahan
                    </button>
                </div>
            </div>
        </div>

        <!-- Status Kuis -->
        <div class="card mb-6 bg-gradient-to-r from-yellow-50 to-orange-50 p-6 rounded-2xl shadow-md">
            <div class="flex items-center justify-between">

                <!-- Status Label -->
                <div>
                    <p class="text-gray-700 font-semibold mb-2">Status Kuis:</p>
                    <span
                        class="px-4 py-2 rounded-full text-sm font-bold 
                {{ $kuis->status == 'published' ? 'bg-green-500 text-white' : 'bg-yellow-500 text-white' }}">
                        {{ $kuis->status == 'published' ? 'Published' : 'Draft' }}
                    </span>
                </div>

                <!-- Action Button -->
                <div>
                    @if ($kuis->status == 'draft')
                        <button onclick="publishKuis()"
                            class="bg-green-500 hover:bg-green-600 text-white font-bold py-3 px-6 rounded-lg transition">
                            <i class="fas fa-check-circle mr-2"></i> Publikasikan
                        </button>
                    @else
                        <button onclick="unpublishKuis()"
                            class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-3 px-6 rounded-lg transition">
                            <i class="fas fa-eye-slash mr-2"></i> Jadikan Draft
                        </button>
                    @endif
                </div>

            </div>
        </div>


        <!-- Daftar Soal -->
        <div class="card">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-800">
                    <i class="fas fa-list mr-2 text-green-500"></i>
                    Daftar Soal ({{ $kuis->soal->count() }})
                </h2>
                <button onclick="showAddSoalForm()"
                    class="bg-green-500 hover:bg-green-600 text-white font-bold py-3 px-6 rounded-lg transition">
                    <i class="fas fa-plus mr-2"></i> Tambah Soal
                </button>
            </div>

            @if ($kuis->soal->count() > 0)
                <div id="soal-list" class="space-y-3">
                    @foreach ($kuis->soal as $index => $soal)
                        <div class="bg-white border-2 border-gray-200 rounded-lg p-6 hover:shadow-lg transition"
                            data-soal-id="{{ $soal->id }}">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center space-x-3 mb-2">
                                        <span class="bg-blue-500 text-white px-3 py-1 rounded-full text-sm font-bold">Soal
                                            {{ $index + 1 }}</span>
                                        <span
                                            class="px-3 py-1 rounded-full text-xs font-semibold {{ $soal->tipe === 'pilihan_ganda' ? 'bg-green-100 text-green-800' : 'bg-purple-100 text-purple-800' }}">
                                            {{ $soal->tipe === 'pilihan_ganda' ? 'Pilihan Ganda' : 'Isian Singkat' }}
                                        </span>
                                    </div>
                                    <p class="text-gray-700 font-semibold">{{ $soal->konten_soal }}</p>
                                    @if ($soal->gambar_soal)
                                        <p class="text-sm text-gray-500 mt-1"><i class="fas fa-image mr-1"></i> Ada gambar
                                        </p>
                                    @endif
                                </div>
                                <div class="flex space-x-2">
                                    <button onclick="editSoal({{ $soal->id }})"
                                        class="text-blue-500 hover:text-blue-700">
                                        <i class="fas fa-edit text-xl"></i>
                                    </button>
                                    <button onclick="deleteSoal({{ $soal->id }})"
                                        class="text-red-500 hover:text-red-700">
                                        <i class="fas fa-trash text-xl"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12 text-gray-400">
                    <i class="fas fa-clipboard-list text-6xl mb-3"></i>
                    <p class="text-lg">Belum ada soal. Klik "Tambah Soal" untuk memulai</p>
                </div>
            @endif
        </div>

        <!-- Form Tambah/Edit Soal -->
        <div id="form-soal-container" class="card hidden">
            <div class="flex justify-between items-center mb-6 mt-4">
                <h2 class="text-xl font-bold text-gray-800" id="form-soal-title">
                    <i class="fas fa-edit mr-2 text-purple-500"></i>
                    Tambah Soal Baru
                </h2>
                <button onclick="closeFormSoal()" class="text-red-500 hover:text-red-700">
                    <i class="fas fa-times text-2xl"></i>
                </button>
            </div>

            <form id="form-soal" class="space-y-6">
                <input type="hidden" id="soal-id" value="">

                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Tipe Soal <span
                            class="text-red-500">*</span></label>
                    <div class="grid grid-cols-2 gap-4">
                        <label class="cursor-pointer">
                            <input type="radio" name="tipe" value="pilihan_ganda" checked class="mr-2">
                            <span class="font-semibold">Pilihan Ganda</span>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="tipe" value="isian_singkat" class="mr-2">
                            <span class="font-semibold">Isian Singkat</span>
                        </label>
                    </div>
                </div>

                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Pertanyaan/Soal <span
                            class="text-red-500">*</span></label>
                    <textarea id="konten_soal" rows="3"
                        class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-blue-500 focus:outline-none"
                        placeholder="Tulis pertanyaan di sini..."></textarea>
                </div>

                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Gambar Soal (Opsional)</label>
                    <input type="file" id="gambar_soal" accept="image/*"
                        class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg">
                    <p class="text-sm text-gray-500 mt-1">Format: JPG, PNG, GIF. Maksimal 5MB</p>
                    <div id="preview-gambar-soal" class="mt-2"></div>
                </div>

                <!-- Form untuk Pilihan Ganda -->
                <div id="form-pilihan-ganda">
                    <div class="mb-4">
                        <label class="block text-gray-700 font-semibold mb-2">Jumlah Pilihan Jawaban</label>
                        <select id="jumlah_pilihan" class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg">
                            <option value="2">2 Pilihan</option>
                            <option value="3">3 Pilihan</option>
                            <option value="4" selected>4 Pilihan</option>
                            <option value="5">5 Pilihan</option>
                        </select>
                    </div>

                    <div id="pilihan-container" class="space-y-4">
                        <!-- Pilihan akan di-generate oleh JavaScript -->
                    </div>
                </div>

                <!-- Form untuk Isian Singkat -->
                <div id="form-isian-singkat" class="hidden">
                    <label class="block text-gray-700 font-semibold mb-2">Jawaban yang Benar <span
                            class="text-red-500">*</span></label>
                    <input type="text" id="jawaban_isian"
                        class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-blue-500 focus:outline-none"
                        placeholder="Tulis jawaban yang benar">
                    <p class="text-sm text-gray-500 mt-1">Jawaban tidak case-sensitive (huruf besar/kecil diabaikan)</p>
                </div>

                <div class="flex space-x-3">
                    <button type="button" onclick="saveSoal()"
                        class="flex-1 bg-blue-500 hover:bg-blue-600 text-white font-bold py-3 rounded-lg transition">
                        <i class="fas fa-save mr-2"></i> Simpan Soal
                    </button>
                    <button type="button" onclick="closeFormSoal()"
                        class="flex-1 bg-gray-400 hover:bg-gray-500 text-white font-bold py-3 rounded-lg transition">
                        <i class="fas fa-times mr-2"></i> Batal
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            let soalList = @json($kuis->soal);
            let editingSoalId = null;

            // Toggle durasi waktu
            $('#waktu_tipe').on('change', function() {
                if ($(this).val() !== 'tanpa_waktu') {
                    $('#durasi-container').removeClass('hidden');
                } else {
                    $('#durasi-container').addClass('hidden');
                }
            });

            // Toggle tipe soal
            $('input[name="tipe"]').on('change', function() {
                if ($(this).val() === 'pilihan_ganda') {
                    $('#form-pilihan-ganda').removeClass('hidden');
                    $('#form-isian-singkat').addClass('hidden');
                } else {
                    $('#form-pilihan-ganda').addClass('hidden');
                    $('#form-isian-singkat').removeClass('hidden');
                }
            });

            // Generate pilihan jawaban
            $('#jumlah_pilihan').on('change', function() {
                generatePilihanJawaban($(this).val());
            });

            function generatePilihanJawaban(jumlah) {
                const container = $('#pilihan-container');
                container.empty();

                for (let i = 1; i <= jumlah; i++) {
                    const html = `
            <div class="border-2 border-gray-200 rounded-lg p-4">
                <div class="flex items-start space-x-3">
                    <input type="radio" name="jawaban_benar" value="${i}" ${i === 1 ? 'checked' : ''} class="mt-1" required>
                    <div class="flex-1">
                        <label class="block text-gray-700 font-semibold mb-2">Pilihan ${i}</label>
                        <input type="text" id="pilihan_${i}" class="w-full px-4 py-2 border rounded-lg mb-2" placeholder="Tulis pilihan ${i}" required>
                        <input type="file" id="gambar_pilihan_${i}" accept="image/*" class="w-full px-2 py-1 border rounded text-sm">
                        <div id="preview-gambar-pilihan-${i}" class="mt-2"></div>
                    </div>
                </div>
            </div>
        `;
                    container.append(html);
                }
            }

            function updateKuisInfo() {
                const judul = $('#judul').val().trim();
                const deskripsi = $('#deskripsi').val().trim();
                const waktuTipe = $('#waktu_tipe').val();
                const durasiWaktu = $('#durasi_waktu').val();

                if (!judul) {
                    Swal.fire('Error', 'Judul kuis harus diisi!', 'error');
                    return;
                }

                if (waktuTipe !== 'tanpa_waktu' && (!durasiWaktu || durasiWaktu < 5 || durasiWaktu > 3600)) {
                    Swal.fire('Error', 'Durasi waktu harus antara 5-3600 detik!', 'error');
                    return;
                }

                $.ajax({
                    url: '{{ route('kuis.update', $kuis->id) }}',
                    method: 'PUT',
                    data: {
                        _token: '{{ csrf_token() }}',
                        judul: judul,
                        deskripsi: deskripsi,
                        waktu_tipe: waktuTipe,
                        durasi_waktu: durasiWaktu
                    },
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: 'Informasi kuis berhasil diperbarui.',
                                timer: 2000,
                                showConfirmButton: false
                            });
                        }
                    },
                    error: function() {
                        Swal.fire('Error', 'Terjadi kesalahan. Silakan coba lagi.', 'error');
                    }
                });
            }

            function publishKuis() {
                updateStatus('published');
            }

            function unpublishKuis() {
                updateStatus('draft');
            }

            function updateStatus(status) {
                $.ajax({
                    url: `/kuis/{{ $kuis->id }}/status`,
                    method: 'PUT',
                    data: {
                        _token: '{{ csrf_token() }}',
                        status: status
                    },
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            location.reload();
                        }
                    }
                });
            }

            function showAddSoalForm() {
                editingSoalId = null;
                $('#soal-id').val('');
                $('#form-soal-title').html('<i class="fas fa-edit mr-2 text-purple-500"></i> Tambah Soal Baru');
                $('#form-soal')[0].reset();
                $('#form-soal-container').removeClass('hidden');
                generatePilihanJawaban(4);
                $('input[name="tipe"][value="pilihan_ganda"]').prop('checked', true).trigger('change');

                // Scroll to form
                $('html, body').animate({
                    scrollTop: $('#form-soal-container').offset().top - 100
                }, 500);
            }

            function editSoal(soalId) {
                editingSoalId = soalId;
                $('#soal-id').val(soalId);
                $('#form-soal-title').html('<i class="fas fa-edit mr-2 text-purple-500"></i> Edit Soal');

                // Find soal from soalList
                const soal = soalList.find(s => s.id == soalId);
                if (!soal) {
                    Swal.fire('Error', 'Soal tidak ditemukan.', 'error');
                    return;
                }

                $('#konten_soal').val(soal.konten_soal);

                if (soal.tipe === 'pilihan_ganda') {
                    $('input[name="tipe"][value="pilihan_ganda"]').prop('checked', true).trigger('change');
                    $('#jumlah_pilihan').val(soal.pilihan_jawaban.length).trigger('change');

                    // Populate pilihan
                    setTimeout(() => {
                        soal.pilihan_jawaban.forEach((pilihan, index) => {
                            $(`#pilihan_${index + 1}`).val(pilihan.konten_pilihan);
                            if (pilihan.is_benar) {
                                $(`input[name="jawaban_benar"][value="${index + 1}"]`).prop('checked', true);
                            }
                        });
                    }, 100);
                } else {
                    $('input[name="tipe"][value="isian_singkat"]').prop('checked', true).trigger('change');
                    $('#jawaban_isian').val(soal.jawaban_benar);
                }

                $('#form-soal-container').removeClass('hidden');

                // Scroll to form
                $('html, body').animate({
                    scrollTop: $('#form-soal-container').offset().top - 100
                }, 500);
            }

            function closeFormSoal() {
                $('#form-soal-container').addClass('hidden');
                editingSoalId = null;
            }

            function saveSoal() {
                const formData = new FormData();
                formData.append('_token', '{{ csrf_token() }}');
                formData.append('tipe', $('input[name="tipe"]:checked').val());
                formData.append('konten_soal', $('#konten_soal').val());

                // Gambar soal
                const gambarSoal = $('#gambar_soal')[0].files[0];
                if (gambarSoal) {
                    formData.append('gambar_soal', gambarSoal);
                }

                if ($('input[name="tipe"]:checked').val() === 'pilihan_ganda') {
                    const jumlahPilihan = $('#jumlah_pilihan').val();
                    formData.append('jumlah_pilihan', jumlahPilihan);
                    formData.append('jawaban_benar', $('input[name="jawaban_benar"]:checked').val());

                    // Pilihan jawaban
                    const pilihan = [];
                    for (let i = 1; i <= jumlahPilihan; i++) {
                        const konten = $(`#pilihan_${i}`).val();
                        if (!konten) {
                            Swal.fire('Error', `Pilihan ${i} harus diisi!`, 'error');
                            return;
                        }
                        pilihan.push({
                            konten: konten,
                            urutan: i
                        });
                    }
                    formData.append('pilihan', JSON.stringify(pilihan));

                    // Gambar pilihan
                    for (let i = 1; i <= jumlahPilihan; i++) {
                        const gambarPilihan = $(`#gambar_pilihan_${i}`)[0].files[0];
                        if (gambarPilihan) {
                            formData.append(`gambar_pilihan_${i}`, gambarPilihan);
                        }
                    }
                } else {
                    const jawabanBenar = $('#jawaban_isian').val();
                    if (!jawabanBenar) {
                        Swal.fire('Error', 'Jawaban yang benar harus diisi!', 'error');
                        return;
                    }
                    formData.append('jawaban_benar', jawabanBenar);
                }

                if (!$('#konten_soal').val()) {
                    Swal.fire('Error', 'Pertanyaan soal harus diisi!', 'error');
                    return;
                }

                const url = editingSoalId ? `/kuis/soal/${editingSoalId}` : `/kuis/{{ $kuis->id }}/soal`;
                let method = editingSoalId ? 'PUT' : 'POST';

                // For PUT requests with FormData, use POST with _method
                if (method === 'PUT') {
                    formData.append('_method', 'PUT');
                    method = 'POST';
                }

                $.ajax({
                    url: url,
                    method: method,
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            closeFormSoal();
                            location.reload();
                        }
                    },
                    error: function(xhr) {
                        const message = xhr.responseJSON?.message || 'Terjadi kesalahan.';
                        Swal.fire('Error', message, 'error');
                    }
                });
            }

            function deleteSoal(soalId) {
                Swal.fire({
                    title: 'Hapus Soal?',
                    text: 'Soal akan dihapus permanen.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: `/kuis/soal/${soalId}`,
                            method: 'DELETE',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                if (response.success) {
                                    location.reload();
                                }
                            }
                        });
                    }
                });
            }

            // Initialize
            generatePilihanJawaban(4);
        </script>
    @endpush
@endsection
