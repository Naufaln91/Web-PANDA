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
        <div class="card mb-6 bg-gradient-to-r from-yellow-50 to-orange-50">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-700 font-semibold mb-2">Status Kuis:</p>
                    <span
                        class="px-4 py-2 rounded-full text-sm font-bold {{ $kuis->status == 'published' ? 'bg-green-500 text-white' : 'bg-yellow-500 text-white' }}">
                        {{ $kuis->status == 'published' ? 'Published' : 'Draft' }}
                    </span>
                </div>
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
                            <div
