@extends('layouts.app')

@section('title', 'Buat Kuis Baru')

@section('content')
    <div class="max-w-6xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-800">
                <i class="fas fa-plus-circle mr-2 text-blue-500"></i>
                Buat Kuis Baru
            </h1>
            <a href="{{ route('kuis.index') }}"
                class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded-lg transition">
                <i class="fas fa-arrow-left mr-2"></i> Kembali
            </a>
        </div>

        <!-- Progress Steps -->
        <div class="card mb-6">
            <div class="flex items-center justify-between">
                <div id="step-indicator-1" class="flex items-center">
                    <div class="w-10 h-10 bg-blue-500 text-white rounded-full flex items-center justify-center font-bold">1
                    </div>
                    <span class="ml-2 font-semibold text-blue-500">Info Kuis</span>
                </div>
                <div class="flex-1 h-1 bg-gray-300 mx-4"></div>
                <div id="step-indicator-2" class="flex items-center">
                    <div
                        class="w-10 h-10 bg-gray-300 text-gray-600 rounded-full flex items-center justify-center font-bold">
                        2</div>
                    <span class="ml-2 font-semibold text-gray-400">Tambah Soal</span>
                </div>
                <div class="flex-1 h-1 bg-gray-300 mx-4"></div>
                <div id="step-indicator-3" class="flex items-center">
                    <div
                        class="w-10 h-10 bg-gray-300 text-gray-600 rounded-full flex items-center justify-center font-bold">
                        3</div>
                    <span class="ml-2 font-semibold text-gray-400">Selesai</span>
                </div>
            </div>
        </div>

        <!-- Step 1: Info Kuis -->
        <div id="step-info" class="card">
            <h2 class="text-2xl font-bold mb-6 text-gray-800">
                <i class="fas fa-info-circle mr-2 text-blue-500"></i>
                Informasi Dasar Kuis
            </h2>

            <div class="space-y-6">
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Judul Kuis <span
                            class="text-red-500">*</span></label>
                    <input type="text" id="judul"
                        class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-blue-500 focus:outline-none"
                        placeholder="Contoh: Kuis Mengenal Angka">
                    <p class="text-sm text-gray-500 mt-1">Buat judul yang menarik dan mudah dipahami</p>
                </div>

                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Deskripsi Kuis</label>
                    <textarea id="deskripsi" rows="3"
                        class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-blue-500 focus:outline-none"
                        placeholder="Jelaskan tentang kuis ini (opsional)"></textarea>
                </div>

                <div class="grid md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Pengaturan Waktu <span
                                class="text-red-500">*</span></label>
                        <select id="waktu_tipe"
                            class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-blue-500 focus:outline-none">
                            <option value="tanpa_waktu">Tanpa Batasan Waktu</option>
                            <option value="per_soal">Waktu Per Soal</option>
                            <option value="keseluruhan">Waktu Keseluruhan</option>
                        </select>
                    </div>

                    <div id="durasi-container" class="hidden">
                        <label class="block text-gray-700 font-semibold mb-2">Durasi Waktu (detik) <span
                                class="text-red-500">*</span></label>
                        <input type="number" id="durasi_waktu"
                            class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-blue-500 focus:outline-none"
                            min="5" max="3600" placeholder="30">
                        <p class="text-sm text-gray-500 mt-1">Minimal 5 detik, maksimal 3600 detik</p>
                    </div>
                </div>

                <div class="flex justify-end">
                    <button onclick="createKuis()"
                        class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-3 px-8 rounded-lg transition">
                        Lanjut ke Tambah Soal <i class="fas fa-arrow-right ml-2"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Step 2: Tambah Soal -->
        <div id="step-soal" class="hidden space-y-6">
            <!-- Info Kuis yang Dibuat -->
            <div class="card bg-gradient-to-r from-blue-100 to-blue-50 border border-blue-200 rounded-2xl shadow-md p-5">
                <div class="flex items-center justify-between">
                    <div class="space-y-1">
                        <h3 class="text-2xl font-bold text-gray-800" id="display-judul">Judul Kuis</h3>
                        <p class="text-gray-700" id="display-deskripsi">Deskripsi singkat kuis ini.</p>
                    </div>
                    <div class="text-right bg-white/70 rounded-xl px-4 py-2 shadow-sm">
                        <p class="text-sm text-gray-600">Jumlah Soal</p>
                        <p class="text-3xl font-extrabold text-blue-700" id="jumlah-soal">0</p>
                    </div>
                </div>
            </div>


            <!-- Daftar Soal -->
            <div class="card">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-bold text-gray-800">
                        <i class="fas fa-list mr-2 text-green-500"></i>
                        Daftar Soal
                    </h2>
                    <button onclick="showAddSoalForm()"
                        class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-6 rounded-lg transition">
                        <i class="fas fa-plus mr-2"></i> Tambah Soal
                    </button>
                </div>

                <div id="soal-list" class="space-y-3">
                    <div class="text-center py-12 text-gray-400">
                        <i class="fas fa-clipboard-list text-6xl mb-3"></i>
                        <p class="text-lg">Belum ada soal. Klik "Tambah Soal" untuk memulai</p>
                    </div>
                </div>
            </div>

            <!-- Form Tambah/Edit Soal -->
            <div id="form-soal-container" class="card hidden">
                <div class="flex justify-between items-center mb-6">
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
                    <input type="hidden" id="soal-urutan" value="">

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
                        <p class="text-sm text-gray-500 mt-1">Jawaban tidak case-sensitive (huruf besar/kecil diabaikan)
                        </p>
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

            <!-- Action Buttons -->
            <div class="card bg-gradient-to-r from-blue-50 to-purple-50">
                <div class="grid grid-cols-2 gap-4">
                    <button onclick="saveDraft()"
                        class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-4 rounded-lg transition">
                        <i class="fas fa-save mr-2"></i> Simpan sebagai Draft
                    </button>
                    <button onclick="publishKuis()"
                        class="bg-green-500 hover:bg-green-600 text-white font-bold py-4 rounded-lg transition">
                        <i class="fas fa-check-circle mr-2"></i> Publikasikan Kuis
                    </button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            let kuisId = null;
            let soalList = [];
            let editingSoalIndex = null;

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
                    <input type="radio" name="jawaban_benar" value="${i}" ${i === 1 ? 'checked' : ''} 
                           class="mt-1" required>
                    <div class="flex-1">
                        <label class="block text-gray-700 font-semibold mb-2">Pilihan ${i}</label>
                        <input type="text" id="pilihan_${i}" class="w-full px-4 py-2 border rounded-lg mb-2" 
                               placeholder="Tulis pilihan ${i}" required>
                        <input type="file" id="gambar_pilihan_${i}" accept="image/*" 
                               class="w-full px-2 py-1 border rounded text-sm">
                        <div id="preview-gambar-pilihan-${i}" class="mt-2"></div>
                    </div>
                </div>
            </div>
        `;
                    container.append(html);
                }
            }

            // Create Kuis
            function createKuis() {
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
                    url: '{{ route('kuis.store') }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        judul: judul,
                        deskripsi: deskripsi,
                        waktu_tipe: waktuTipe,
                        durasi_waktu: durasiWaktu
                    },
                    success: function(response) {
                        if (response.success) {
                            kuisId = response.kuis_id;

                            // Update display
                            $('#display-judul').text(judul);
                            $('#display-deskripsi').text(deskripsi || 'Tidak ada deskripsi');

                            // Change step
                            $('#step-info').addClass('hidden');
                            $('#step-soal').removeClass('hidden');

                            // Update progress indicator
                            updateStepIndicator(2);

                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: 'Kuis berhasil dibuat. Silakan tambahkan soal.',
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

            function showAddSoalForm() {
                editingSoalIndex = null;
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

            function editSoal(index) {
                editingSoalIndex = index;
                const soal = soalList[index];

                $('#soal-id').val(soal.id);
                $('#form-soal-title').html('<i class="fas fa-edit mr-2 text-purple-500"></i> Edit Soal');

                $('#konten_soal').val(soal.konten_soal);

                if (soal.tipe === 'pilihan_ganda') {
                    $('input[name="tipe"][value="pilihan_ganda"]').prop('checked', true).trigger('change');
                    $('#jumlah_pilihan').val(soal.pilihan_jawaban.length).trigger('change');

                    // Populate pilihan
                    setTimeout(() => {
                        soal.pilihan_jawaban.forEach((pilihan, idx) => {
                            $(`#pilihan_${idx + 1}`).val(pilihan.konten_pilihan);
                            if (pilihan.is_benar) {
                                $(`input[name="jawaban_benar"][value="${idx + 1}"]`).prop('checked', true);
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
                editingSoalIndex = null;
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

                const soalId = $('#soal-id').val();
                const url = soalId ? `/kuis/soal/${soalId}` : `/kuis/${kuisId}/soal`;
                let method = soalId ? 'PUT' : 'POST';

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
                    success: function(response) {
                        if (response.success) {
                            if (soalId) {
                                // Update existing soal
                                soalList[editingSoalIndex] = response.soal;
                            } else {
                                // Add new soal
                                soalList.push(response.soal);
                            }
                            renderSoalList();
                            closeFormSoal();

                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: soalId ? 'Soal berhasil diupdate.' : 'Soal berhasil ditambahkan.',
                                timer: 2000,
                                showConfirmButton: false
                            });
                        }
                    },
                    error: function(xhr) {
                        const message = xhr.responseJSON?.message || 'Terjadi kesalahan.';
                        Swal.fire('Error', message, 'error');
                    }
                });
            }

            function renderSoalList() {
                const container = $('#soal-list');

                if (soalList.length === 0) {
                    container.html(`
            <div class="text-center py-12 text-gray-400">
                <i class="fas fa-clipboard-list text-6xl mb-3"></i>
                <p class="text-lg">Belum ada soal. Klik "Tambah Soal" untuk memulai</p>
            </div>
        `);
                    $('#jumlah-soal').text('0');
                    return;
                }

                container.empty();
                soalList.forEach((soal, index) => {
                    const html = `
            <div class="bg-white border-2 border-gray-200 rounded-lg p-4 hover:shadow-lg transition">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <div class="flex items-center space-x-3 mb-2">
                            <span class="bg-blue-500 text-white px-3 py-1 rounded-full text-sm font-bold">Soal ${index + 1}</span>
                            <span class="px-3 py-1 rounded-full text-xs font-semibold ${soal.tipe === 'pilihan_ganda' ? 'bg-green-100 text-green-800' : 'bg-purple-100 text-purple-800'}">
                                ${soal.tipe === 'pilihan_ganda' ? 'Pilihan Ganda' : 'Isian Singkat'}
                            </span>
                        </div>
                        <p class="text-gray-700 font-semibold">${soal.konten_soal}</p>
                        ${soal.gambar_soal ? '<p class="text-sm text-gray-500 mt-1"><i class="fas fa-image mr-1"></i> Ada gambar</p>' : ''}
                    </div>
                    <div class="flex space-x-2">
                        <button onclick="editSoal(${index})" class="text-blue-500 hover:text-blue-700">
                            <i class="fas fa-edit text-xl"></i>
                        </button>
                        <button onclick="deleteSoal(${soal.id}, ${index})" class="text-red-500 hover:text-red-700">
                            <i class="fas fa-trash text-xl"></i>
                        </button>
                    </div>
                </div>
            </div>
        `;
                    container.append(html);
                });

                $('#jumlah-soal').text(soalList.length);
            }

            function deleteSoal(soalId, index) {
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
                            success: function(response) {
                                if (response.success) {
                                    soalList.splice(index, 1);
                                    renderSoalList();
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Berhasil!',
                                        text: 'Soal berhasil dihapus.',
                                        timer: 2000,
                                        showConfirmButton: false
                                    });
                                }
                            }
                        });
                    }
                });
            }

            function saveDraft() {
                updateStatus('draft');
            }

            function publishKuis() {
                if (soalList.length === 0) {
                    Swal.fire('Error', 'Kuis harus memiliki minimal 1 soal untuk dipublikasikan!', 'error');
                    return;
                }
                updateStatus('published');
            }

            function updateStatus(status) {
                $.ajax({
                    url: `/kuis/${kuisId}/status`,
                    method: 'PUT',
                    data: {
                        _token: '{{ csrf_token() }}',
                        status: status
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
                                window.location.href = '{{ route('kuis.index') }}';
                            });
                        }
                    }
                });
            }

            function updateStepIndicator(step) {
                for (let i = 1; i <= 3; i++) {
                    const indicator = $(`#step-indicator-${i}`);
                    if (i <= step) {
                        indicator.find('div').removeClass('bg-gray-300 text-gray-600').addClass('bg-blue-500 text-white');
                        indicator.find('span').removeClass('text-gray-400').addClass('text-blue-500');
                    } else {
                        indicator.find('div').removeClass('bg-blue-500 text-white').addClass('bg-gray-300 text-gray-600');
                        indicator.find('span').removeClass('text-blue-500').addClass('text-gray-400');
                    }
                }
            }

            // Initialize
            generatePilihanJawaban(4);
        </script>
    @endpush
@endsection
