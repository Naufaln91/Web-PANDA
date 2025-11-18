<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Kuis;
use App\Models\Soal;
use App\Models\PilihanJawaban;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class KuisControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware(\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class);
    }

    #[Test]
    public function authenticated_user_can_access_kuis_index()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('kuis.index'));

        $response->assertStatus(200);
        $response->assertViewIs('kuis.index');
        $response->assertViewHas('kuis');
    }

    #[Test]
    public function guru_or_admin_can_access_create_kuis()
    {
        /** @var \App\Models\User $guru */
        $guru = User::factory()->create(['role' => 'guru']);

        $response = $this->actingAs($guru)->get(route('kuis.create'));

        $response->assertStatus(200);
        $response->assertViewIs('kuis.create');
    }

    #[Test]
    public function wali_murid_cannot_access_create_kuis()
    {
        /** @var \App\Models\User $waliMurid */
        $waliMurid = User::factory()->create(['role' => 'wali_murid']);

        $response = $this->actingAs($waliMurid)->get(route('kuis.create'));

        $response->assertRedirect(route('login'));
    }

    #[Test]
    public function guru_can_store_kuis()
    {
        /** @var \App\Models\User $guru */
        $guru = User::factory()->create(['role' => 'guru']);

        $data = [
            'judul' => 'Kuis Test',
            'deskripsi' => 'Deskripsi test',
            'waktu_tipe' => 'per_soal',
            'durasi_waktu' => 30,
            'penunjukan_jawaban' => 'setelah_jawab',
        ];

        $response = $this->actingAs($guru)->post(route('kuis.store'), $data)
            ->assertStatus(200)
            ->assertJson(['success' => true]);

        $this->assertDatabaseHas('kuis', [
            'judul' => 'Kuis Test',
            'created_by' => $guru->id,
            'penunjukan_jawaban' => 'setelah_jawab',
        ]);
    }

    #[Test]
    public function guru_can_edit_own_kuis()
    {
        /** @var \App\Models\User $guru */
        $guru = User::factory()->create(['role' => 'guru']);
        $kuis = Kuis::factory()->create(['created_by' => $guru->id]);

        $response = $this->actingAs($guru)->get(route('kuis.edit', $kuis->id));

        $response->assertStatus(200);
        $response->assertViewIs('kuis.edit');
        $response->assertViewHas('kuis');
    }

    #[Test]
    public function guru_cannot_edit_other_kuis()
    {
        /** @var \App\Models\User $guru1 */
        $guru1 = User::factory()->create(['role' => 'guru']);
        /** @var \App\Models\User $guru2 */
        $guru2 = User::factory()->create(['role' => 'guru']);
        $kuis = Kuis::factory()->create(['created_by' => $guru2->id]);

        $response = $this->actingAs($guru1)->get(route('kuis.edit', $kuis->id));

        $response->assertStatus(403);
    }

    #[Test]
    public function admin_can_edit_any_kuis()
    {
        /** @var \App\Models\User $admin */
        $admin = User::factory()->create(['role' => 'admin']);
        $kuis = Kuis::factory()->create();

        $response = $this->actingAs($admin)->get(route('kuis.edit', $kuis->id));

        $response->assertStatus(200);
    }

    #[Test]
    public function guru_can_update_own_kuis()
    {
        /** @var \App\Models\User $guru */
        $guru = User::factory()->create(['role' => 'guru']);
        $kuis = Kuis::factory()->create(['created_by' => $guru->id]);

        $data = [
            'judul' => 'Updated Title',
            'deskripsi' => 'Updated description',
            'waktu_tipe' => 'keseluruhan',
            'durasi_waktu' => 60,
            'penunjukan_jawaban' => 'setelah_semua',
        ];

        $response = $this->actingAs($guru)->put(route('kuis.update', $kuis->id), $data)
            ->assertStatus(200)
            ->assertJson(['success' => true]);

        $this->assertDatabaseHas('kuis', [
            'judul' => 'Updated Title',
            'penunjukan_jawaban' => 'setelah_semua'
        ]);
    }

    #[Test]
    public function guru_can_publish_kuis_with_soal()
    {
        /** @var \App\Models\User $guru */
        $guru = User::factory()->create(['role' => 'guru']);
        $kuis = Kuis::factory()->create(['created_by' => $guru->id, 'status' => 'draft']);
        Soal::factory()->create(['kuis_id' => $kuis->id]);

        $response = $this->actingAs($guru)->put(route('kuis.update-status', $kuis->id), ['status' => 'published'])
            ->assertStatus(200)
            ->assertJson(['success' => true]);

        $this->assertDatabaseHas('kuis', ['id' => $kuis->id, 'status' => 'published']);
    }

    #[Test]
    public function guru_cannot_publish_kuis_without_soal()
    {
        /** @var \App\Models\User $guru */
        $guru = User::factory()->create(['role' => 'guru']);
        $kuis = Kuis::factory()->create(['created_by' => $guru->id, 'status' => 'draft']);

        $response = $this->actingAs($guru)->put(route('kuis.update-status', $kuis->id), ['status' => 'published'])
            ->assertStatus(200)
            ->assertJson(['success' => false]);
    }

    #[Test]
    public function guru_can_store_soal()
    {
        Storage::fake('public');
        /** @var \App\Models\User $guru */
        $guru = User::factory()->create(['role' => 'guru']);
        $kuis = Kuis::factory()->create(['created_by' => $guru->id]);

        $data = [
            'tipe' => 'pilihan_ganda',
            'konten_soal' => 'Soal test?',
            'gambar_soal' => UploadedFile::fake()->image('soal.jpg'),
            'jumlah_pilihan' => 3,
            'jawaban_benar' => 1,
            'pilihan' => [
                ['konten' => 'Jawaban 1', 'urutan' => 1],
                ['konten' => 'Jawaban 2', 'urutan' => 2],
                ['konten' => 'Jawaban 3', 'urutan' => 3],
            ],
            'gambar_pilihan_1' => UploadedFile::fake()->image('pilihan1.jpg'),
        ];

        $response = $this->actingAs($guru)->post(route('kuis.soal.store', $kuis->id), $data)
            ->assertStatus(200)
            ->assertJson(['success' => true]);

        $this->assertDatabaseHas('soal', ['kuis_id' => $kuis->id, 'konten_soal' => 'Soal test?']);
        $this->assertDatabaseCount('pilihan_jawaban', 3);
    }

    #[Test]
    public function user_can_show_published_kuis()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create();
        $kuis = Kuis::factory()->create(['status' => 'published']);

        $response = $this->actingAs($user)->get(route('kuis.show', $kuis->id));

        $response->assertStatus(200);
        $response->assertViewIs('kuis.show');
    }

    #[Test]
    public function user_cannot_show_draft_kuis()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create();
        $kuis = Kuis::factory()->create(['status' => 'draft']);

        $response = $this->actingAs($user)->get(route('kuis.show', $kuis->id));

        $response->assertStatus(403);
    }

    #[Test]
    public function creator_can_show_draft_kuis()
    {
        /** @var \App\Models\User $guru */
        $guru = User::factory()->create(['role' => 'guru']);
        $kuis = Kuis::factory()->create(['created_by' => $guru->id, 'status' => 'draft']);

        $response = $this->actingAs($guru)->get(route('kuis.show', $kuis->id));

        $response->assertStatus(200);
    }

    #[Test]
    public function user_can_get_soal_for_kuis()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create();
        $kuis = Kuis::factory()->create(['status' => 'published']);

        $response = $this->actingAs($user)->get(route('kuis.get-soal', $kuis->id))
            ->assertStatus(200)
            ->assertJson(['success' => true]);
    }

    #[Test]
    public function guru_can_destroy_own_kuis()
    {
        /** @var \App\Models\User $guru */
        $guru = User::factory()->create(['role' => 'guru']);
        $kuis = Kuis::factory()->create(['created_by' => $guru->id]);

        $response = $this->actingAs($guru)->delete(route('kuis.destroy', $kuis->id))
            ->assertStatus(200)
            ->assertJson(['success' => true]);

        $this->assertDatabaseMissing('kuis', ['id' => $kuis->id]);
    }

    #[Test]
    public function guru_cannot_destroy_other_kuis()
    {
        /** @var \App\Models\User $guru1 */
        $guru1 = User::factory()->create(['role' => 'guru']);
        /** @var \App\Models\User $guru2 */
        $guru2 = User::factory()->create(['role' => 'guru']);
        $kuis = Kuis::factory()->create(['created_by' => $guru2->id]);

        $response = $this->actingAs($guru1)->delete(route('kuis.destroy', $kuis->id))
            ->assertStatus(200)
            ->assertJson(['success' => false]);
    }

    #[Test]
    public function guru_can_access_histori_kuis()
    {
        /** @var \App\Models\User $guru */
        $guru = User::factory()->create(['role' => 'guru']);
        $kuis = Kuis::factory()->create(['created_by' => $guru->id]);

        $response = $this->actingAs($guru)->get(route('kuis.histori', $kuis->id));

        $response->assertStatus(200);
        $response->assertViewIs('kuis.histori');
        $response->assertViewHas(['kuis', 'histori']);
    }

    #[Test]
    public function admin_can_access_histori_kuis()
    {
        /** @var \App\Models\User $admin */
        $admin = User::factory()->create(['role' => 'admin']);
        $kuis = Kuis::factory()->create();

        $response = $this->actingAs($admin)->get(route('kuis.histori', $kuis->id));

        $response->assertStatus(200);
        $response->assertViewIs('kuis.histori');
    }

    #[Test]
    public function wali_murid_cannot_access_histori_kuis()
    {
        /** @var \App\Models\User $waliMurid */
        $waliMurid = User::factory()->create(['role' => 'wali_murid']);
        $kuis = Kuis::factory()->create();

        $response = $this->actingAs($waliMurid)->get(route('kuis.histori', $kuis->id));

        $response->assertStatus(302);
    }

    #[Test]
    public function guru_cannot_access_histori_other_kuis()
    {
        /** @var \App\Models\User $guru1 */
        $guru1 = User::factory()->create(['role' => 'guru']);
        /** @var \App\Models\User $guru2 */
        $guru2 = User::factory()->create(['role' => 'guru']);
        $kuis = Kuis::factory()->create(['created_by' => $guru2->id]);

        $response = $this->actingAs($guru1)->get(route('kuis.histori', $kuis->id));

        $response->assertStatus(403);
    }

    #[Test]
    public function admin_can_get_detail_histori()
    {
        /** @var \App\Models\User $admin */
        $admin = User::factory()->create(['role' => 'admin']);
        /** @var \App\Models\User $user */
        $user = User::factory()->create();
        $kuis = Kuis::factory()->create();
        $histori = \App\Models\HistoriKuis::factory()->create([
            'user_id' => $user->id,
            'kuis_id' => $kuis->id,
        ]);

        $response = $this->actingAs($admin)->get(route('api.histori-kuis.detail', $histori->id))
            ->assertStatus(200)
            ->assertJson(['success' => true]);
    }

    #[Test]
    public function guru_can_get_detail_histori_own_kuis()
    {
        /** @var \App\Models\User $guru */
        $guru = User::factory()->create(['role' => 'guru']);
        /** @var \App\Models\User $user */
        $user = User::factory()->create();
        $kuis = Kuis::factory()->create(['created_by' => $guru->id]);
        $histori = \App\Models\HistoriKuis::factory()->create([
            'user_id' => $user->id,
            'kuis_id' => $kuis->id,
        ]);

        $response = $this->actingAs($guru)->get(route('api.histori-kuis.detail', $histori->id))
            ->assertStatus(200)
            ->assertJson(['success' => true]);
    }

    #[Test]
    public function guru_cannot_get_detail_histori_other_kuis()
    {
        /** @var \App\Models\User $guru1 */
        $guru1 = User::factory()->create(['role' => 'guru']);
        /** @var \App\Models\User $guru2 */
        $guru2 = User::factory()->create(['role' => 'guru']);
        /** @var \App\Models\User $user */
        $user = User::factory()->create();
        $kuis = Kuis::factory()->create(['created_by' => $guru2->id]);
        $histori = \App\Models\HistoriKuis::factory()->create([
            'user_id' => $user->id,
            'kuis_id' => $kuis->id,
        ]);

        $response = $this->actingAs($guru1)->get(route('api.histori-kuis.detail', $histori->id))
            ->assertStatus(200)
            ->assertJson(['success' => false]);
    }

    #[Test]
    public function admin_can_destroy_histori()
    {
        /** @var \App\Models\User $admin */
        $admin = User::factory()->create(['role' => 'admin']);
        /** @var \App\Models\User $user */
        $user = User::factory()->create();
        $kuis = Kuis::factory()->create();
        $histori = \App\Models\HistoriKuis::factory()->create([
            'user_id' => $user->id,
            'kuis_id' => $kuis->id,
        ]);

        $response = $this->actingAs($admin)->delete(route('api.histori-kuis.destroy', $histori->id))
            ->assertStatus(200)
            ->assertJson(['success' => true]);

        $this->assertDatabaseMissing('histori_kuis', ['id' => $histori->id]);
    }

    #[Test]
    public function guru_can_destroy_histori_own_kuis()
    {
        /** @var \App\Models\User $guru */
        $guru = User::factory()->create(['role' => 'guru']);
        /** @var \App\Models\User $user */
        $user = User::factory()->create();
        $kuis = Kuis::factory()->create(['created_by' => $guru->id]);
        $histori = \App\Models\HistoriKuis::factory()->create([
            'user_id' => $user->id,
            'kuis_id' => $kuis->id,
        ]);

        $response = $this->actingAs($guru)->delete(route('api.histori-kuis.destroy', $histori->id))
            ->assertStatus(200)
            ->assertJson(['success' => true]);

        $this->assertDatabaseMissing('histori_kuis', ['id' => $histori->id]);
    }

    #[Test]
    public function guru_can_reorder_soal()
    {
        /** @var \App\Models\User $guru */
        $guru = User::factory()->create(['role' => 'guru']);
        $kuis = Kuis::factory()->create(['created_by' => $guru->id]);
        $soal1 = Soal::factory()->create(['kuis_id' => $kuis->id, 'urutan' => 1]);
        $soal2 = Soal::factory()->create(['kuis_id' => $kuis->id, 'urutan' => 2]);

        $response = $this->actingAs($guru)->post(route('kuis.soal.reorder', $kuis->id), [
            'soal_ids' => [$soal2->id, $soal1->id]
        ])
            ->assertStatus(200)
            ->assertJson(['success' => true]);

        $this->assertDatabaseHas('soal', ['id' => $soal1->id, 'urutan' => 2]);
        $this->assertDatabaseHas('soal', ['id' => $soal2->id, 'urutan' => 1]);
    }

    #[Test]
    public function guru_can_get_single_soal()
    {
        /** @var \App\Models\User $guru */
        $guru = User::factory()->create(['role' => 'guru']);
        $kuis = Kuis::factory()->create(['created_by' => $guru->id]);
        $soal = Soal::factory()->create(['kuis_id' => $kuis->id]);

        $response = $this->actingAs($guru)->get(route('kuis.soal.show', $soal->id))
            ->assertStatus(200)
            ->assertJson(['success' => true]);
    }

    #[Test]
    public function guru_cannot_get_single_soal_other_kuis()
    {
        /** @var \App\Models\User $guru1 */
        $guru1 = User::factory()->create(['role' => 'guru']);
        /** @var \App\Models\User $guru2 */
        $guru2 = User::factory()->create(['role' => 'guru']);
        $kuis = Kuis::factory()->create(['created_by' => $guru2->id]);
        $soal = Soal::factory()->create(['kuis_id' => $kuis->id]);

        $response = $this->actingAs($guru1)->get(route('kuis.soal.show', $soal->id))
            ->assertStatus(200)
            ->assertJson(['success' => false]);
    }
}
