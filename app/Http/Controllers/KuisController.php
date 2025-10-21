<?php

// app/Http/Controllers/KuisController.php
namespace App\Http\Controllers;

use App\Models\Kuis;
use App\Models\Soal;
use App\Models\PilihanJawaban;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class KuisController extends Controller
{
    // Daftar Kuis
    public function index()
    {
        /** @var User $user */
        $user = Auth::user();

        if ($user->isAdmin() || $user->isGuru()) {
            $kuis = Kuis::with('creator', 'soal')
                ->orderBy('created_at', 'desc')
                ->get();
        } else {
            // Wali murid hanya lihat kuis published
            $kuis = Kuis::with('creator', 'soal')
                ->where('status', 'published')
                ->orderBy('created_at', 'desc')
                ->get();
        }

        return view('kuis.index', compact('kuis'));
    }

    // Form Buat Kuis
    public function create()
    {
        return view('kuis.create');
    }

    // Simpan Kuis Baru
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'waktu_tipe' => 'required|in:per_soal,keseluruhan,tanpa_waktu',
            'durasi_waktu' => 'nullable|integer|min:5|max:3600',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ]);
        }

        $kuis = Kuis::create([
            'created_by' => Auth::id(),
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'waktu_tipe' => $request->waktu_tipe,
            'durasi_waktu' => $request->waktu_tipe != 'tanpa_waktu' ? $request->durasi_waktu : null,
            'status' => 'draft',
        ]);

        return response()->json([
            'success' => true,
            'kuis_id' => $kuis->id,
            'message' => 'Kuis berhasil dibuat.',
        ]);
    }

    // Form Edit Kuis
    public function edit($id)
    {
        $kuis = Kuis::with('soal.pilihanJawaban')->findOrFail($id);

        // Cek akses
        /** @var User $user */
        $user = Auth::user();
        if (!$user->isAdmin() && $kuis->created_by != Auth::id()) {
            abort(403, 'Akses ditolak.');
        }

        return view('kuis.edit', compact('kuis'));
    }

    // Update Info Kuis
    public function update(Request $request, $id)
    {
        $kuis = Kuis::findOrFail($id);

        // Cek akses
        /** @var User $user */
        $user = Auth::user();
        if (!$user->isAdmin() && $kuis->created_by != Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Akses ditolak.',
            ]);
        }

        $validator = Validator::make($request->all(), [
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'waktu_tipe' => 'required|in:per_soal,keseluruhan,tanpa_waktu',
            'durasi_waktu' => 'nullable|integer|min:5|max:3600',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ]);
        }

        $kuis->update([
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'waktu_tipe' => $request->waktu_tipe,
            'durasi_waktu' => $request->waktu_tipe != 'tanpa_waktu' ? $request->durasi_waktu : null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Kuis berhasil diupdate.',
        ]);
    }

    // Publish/Draft Kuis
    public function updateStatus(Request $request, $id)
    {
        $kuis = Kuis::findOrFail($id);

        // Cek akses
        /** @var User $user */
        $user = Auth::user();
        if (!$user->isAdmin() && $kuis->created_by != Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Akses ditolak.',
            ]);
        }

        // Validasi minimal 1 soal untuk publish
        if ($request->status == 'published' && $kuis->soal()->count() == 0) {
            return response()->json([
                'success' => false,
                'message' => 'Kuis harus memiliki minimal 1 soal untuk dipublikasikan.',
            ]);
        }

        $kuis->update(['status' => $request->status]);

        return response()->json([
            'success' => true,
            'message' => $request->status == 'published' ? 'Kuis berhasil dipublikasikan.' : 'Kuis disimpan sebagai draft.',
        ]);
    }

    // Hapus Kuis
    public function destroy($id)
    {
        $kuis = Kuis::findOrFail($id);

        // Cek akses
        /** @var User $user */
        $user = Auth::user();
        if (!$user->isAdmin() && $kuis->created_by != Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Akses ditolak.',
            ]);
        }

        // Hapus gambar soal
        foreach ($kuis->soal as $soal) {
            if ($soal->gambar_soal) {
                Storage::disk('public')->delete($soal->gambar_soal);
            }

            // Hapus gambar pilihan
            foreach ($soal->pilihanJawaban as $pilihan) {
                if ($pilihan->gambar_pilihan) {
                    Storage::disk('public')->delete($pilihan->gambar_pilihan);
                }
            }
        }

        $kuis->delete();

        return response()->json([
            'success' => true,
            'message' => 'Kuis berhasil dihapus.',
        ]);
    }

    // Tambah Soal
    public function storeSoal(Request $request, $kuisId)
    {
        $kuis = Kuis::findOrFail($kuisId);

        // Cek akses
        /** @var User $user */
        $user = Auth::user();
        if (!$user->isAdmin() && $kuis->created_by != Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Akses ditolak.',
            ]);
        }

        // Handle pilihan if sent as JSON string
        if ($request->has('pilihan') && is_string($request->pilihan)) {
            $request->merge(['pilihan' => json_decode($request->pilihan, true)]);
        }

        $validator = Validator::make($request->all(), [
            'tipe' => 'required|in:pilihan_ganda,isian_singkat',
            'konten_soal' => 'required|string',
            'gambar_soal' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'jumlah_pilihan' => 'required_if:tipe,pilihan_ganda|integer|min:2|max:5',
            'jawaban_benar' => 'required',
            'pilihan' => 'required_if:tipe,pilihan_ganda|array',
            'pilihan.*.konten' => 'required|string',
            'pilihan.*.urutan' => 'required|integer',
        ], [
            'konten_soal.required' => 'Konten soal tidak boleh kosong.',
            'gambar_soal.max' => 'Ukuran gambar maksimal 5MB.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ]);
        }

        DB::beginTransaction();
        try {
            // Upload gambar soal jika ada
            $gambarSoalPath = null;
            if ($request->hasFile('gambar_soal')) {
                $gambarSoalPath = $request->file('gambar_soal')->store('kuis/soal', 'public');
            }

            // Hitung urutan
            $urutan = $kuis->soal()->max('urutan') + 1;

            // Buat soal
            $soal = Soal::create([
                'kuis_id' => $kuisId,
                'urutan' => $urutan,
                'tipe' => $request->tipe,
                'konten_soal' => $request->konten_soal,
                'gambar_soal' => $gambarSoalPath,
                'jumlah_pilihan' => $request->tipe == 'pilihan_ganda' ? $request->jumlah_pilihan : null,
                'jawaban_benar' => $request->jawaban_benar,
            ]);

            // Jika pilihan ganda, simpan pilihan jawaban
            if ($request->tipe == 'pilihan_ganda' && $request->pilihan) {
                foreach ($request->pilihan as $pilihanData) {
                    $gambarPilihanPath = null;

                    // Upload gambar pilihan jika ada
                    $gambarKey = 'gambar_pilihan_' . $pilihanData['urutan'];
                    if ($request->hasFile($gambarKey)) {
                        $gambarPilihanPath = $request->file($gambarKey)->store('kuis/pilihan', 'public');
                    }

                    PilihanJawaban::create([
                        'soal_id' => $soal->id,
                        'urutan' => $pilihanData['urutan'],
                        'konten_pilihan' => $pilihanData['konten'],
                        'gambar_pilihan' => $gambarPilihanPath,
                        'is_benar' => $pilihanData['urutan'] == $request->jawaban_benar,
                    ]);
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'soal' => $soal->load('pilihanJawaban'),
                'message' => 'Soal berhasil ditambahkan.',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan soal: ' . $e->getMessage(),
            ]);
        }
    }

    // Update Soal
    public function updateSoal(Request $request, $soalId)
    {
        $soal = Soal::findOrFail($soalId);
        $kuis = $soal->kuis;

        // Cek akses
        /** @var User $user */
        $user = Auth::user();
        if (!$user->isAdmin() && $kuis->created_by != Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Akses ditolak.',
            ]);
        }

        $validator = Validator::make($request->all(), [
            'konten_soal' => 'required|string',
            'gambar_soal' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'jawaban_benar' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ]);
        }

        DB::beginTransaction();
        try {
            // Upload gambar baru jika ada
            if ($request->hasFile('gambar_soal')) {
                // Hapus gambar lama
                if ($soal->gambar_soal) {
                    Storage::disk('public')->delete($soal->gambar_soal);
                }
                $soal->gambar_soal = $request->file('gambar_soal')->store('kuis/soal', 'public');
            }

            $soal->update([
                'konten_soal' => $request->konten_soal,
                'jawaban_benar' => $request->jawaban_benar,
            ]);

            // Update pilihan jika pilihan ganda
            if ($soal->isPilihanGanda() && $request->pilihan) {
                // Hapus pilihan lama
                foreach ($soal->pilihanJawaban as $pilihan) {
                    if ($pilihan->gambar_pilihan) {
                        Storage::disk('public')->delete($pilihan->gambar_pilihan);
                    }
                }
                $soal->pilihanJawaban()->delete();

                // Tambah pilihan baru
                foreach ($request->pilihan as $index => $pilihanData) {
                    $gambarPilihanPath = null;
                    if (isset($pilihanData['gambar']) && $pilihanData['gambar']) {
                        $gambarPilihanPath = $pilihanData['gambar']->store('kuis/pilihan', 'public');
                    }

                    PilihanJawaban::create([
                        'soal_id' => $soal->id,
                        'urutan' => $index + 1,
                        'konten_pilihan' => $pilihanData['konten'],
                        'gambar_pilihan' => $gambarPilihanPath,
                        'is_benar' => ($index + 1) == $request->jawaban_benar,
                    ]);
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'soal' => $soal->load('pilihanJawaban'),
                'message' => 'Soal berhasil diupdate.',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupdate soal: ' . $e->getMessage(),
            ]);
        }
    }

    // Hapus Soal
    public function destroySoal($soalId)
    {
        $soal = Soal::findOrFail($soalId);
        $kuis = $soal->kuis;

        // Cek akses
        /** @var User $user */
        $user = Auth::user();
        if (!$user->isAdmin() && $kuis->created_by != Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Akses ditolak.',
            ]);
        }

        // Hapus gambar
        if ($soal->gambar_soal) {
            Storage::disk('public')->delete($soal->gambar_soal);
        }

        foreach ($soal->pilihanJawaban as $pilihan) {
            if ($pilihan->gambar_pilihan) {
                Storage::disk('public')->delete($pilihan->gambar_pilihan);
            }
        }

        $soal->delete();

        // Reorder soal
        $this->reorderSoalAfterDelete($kuis->id);

        return response()->json([
            'success' => true,
            'message' => 'Soal berhasil dihapus.',
        ]);
    }

    // Reorder Soal
    public function reorderSoal(Request $request, $kuisId)
    {
        $kuis = Kuis::findOrFail($kuisId);

        // Cek akses
        /** @var User $user */
        $user = Auth::user();
        if (!$user->isAdmin() && $kuis->created_by != Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Akses ditolak.',
            ]);
        }

        $soalIds = $request->soal_ids;

        foreach ($soalIds as $index => $soalId) {
            Soal::where('id', $soalId)->update(['urutan' => $index + 1]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Urutan soal berhasil diupdate.',
        ]);
    }

    // Helper: Reorder soal setelah delete
    private function reorderSoalAfterDelete($kuisId)
    {
        $soals = Soal::where('kuis_id', $kuisId)->orderBy('urutan')->get();

        foreach ($soals as $index => $soal) {
            $soal->update(['urutan' => $index + 1]);
        }
    }

    // Halaman Mengerjakan Kuis
    public function show($id)
    {
        $kuis = Kuis::with('soal.pilihanJawaban')->findOrFail($id);

        // Hanya bisa akses kuis published kecuali admin/creator
        /** @var User $user */
        $user = Auth::user();
        if ($kuis->isDraft() && !$user->isAdmin() && $kuis->created_by != Auth::id()) {
            abort(403, 'Kuis ini belum dipublikasikan.');
        }

        return view('kuis.show', compact('kuis'));
    }

    // API: Get Soal untuk Kuis
    public function getSoal($kuisId)
    {
        $kuis = Kuis::with('soal.pilihanJawaban')->findOrFail($kuisId);

        return response()->json([
            'success' => true,
            'kuis' => $kuis,
        ]);
    }
}
