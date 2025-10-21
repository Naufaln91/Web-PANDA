<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Kuis;
use App\Models\Soal;
use App\Models\PilihanJawaban;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class KuisControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;
    protected $guru;

    protected function setUp(): void
    {
        parent::setUp();

        // Create test users
        $this->admin = User::create([
            'username' => 'admin',
            'password' => bcrypt('password'),
            'nama_orangtua' => 'Admin User',
            'role' => 'admin'
        ]);

        $this->guru = User::create([
            'username' => 'guru',
            'password' => bcrypt('password'),
            'nama_orangtua' => 'Guru User',
            'role' => 'guru'
        ]);
    }

    /** @test */
    public function admin_can_create_quiz()
    {
        $this->actingAs($this->admin);

        $response = $this->post('/kuis', [
            'judul' => 'Test Quiz',
            'deskripsi' => 'Test Description',
            'waktu_tipe' => 'tanpa_waktu'
        ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        $this->assertDatabaseHas('kuis', [
            'judul' => 'Test Quiz',
            'deskripsi' => 'Test Description',
            'waktu_tipe' => 'tanpa_waktu',
            'created_by' => $this->admin->id
        ]);
    }

    /** @test */
    public function guru_can_create_quiz()
    {
        $this->actingAs($this->guru);

        $response = $this->post('/kuis', [
            'judul' => 'Guru Quiz',
            'deskripsi' => 'Guru Description',
            'waktu_tipe' => 'per_soal',
            'durasi_waktu' => 30
        ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        $this->assertDatabaseHas('kuis', [
            'judul' => 'Guru Quiz',
            'waktu_tipe' => 'per_soal',
            'durasi_waktu' => 30
        ]);
    }

    /** @test */
    public function quiz_creation_validation_fails()
    {
        $this->actingAs($this->admin);

        $response = $this->post('/kuis', [
            'judul' => '', // Empty title
            'waktu_tipe' => 'invalid_type'
        ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => false]);
        $response->assertJsonStructure(['errors']);
    }

    /** @test */
    public function can_add_multiple_choice_question_with_options()
    {
        $this->actingAs($this->admin);

        // Create quiz first
        $quiz = Kuis::create([
            'created_by' => $this->admin->id,
            'judul' => 'Test Quiz',
            'deskripsi' => 'Test',
            'waktu_tipe' => 'tanpa_waktu',
            'status' => 'draft'
        ]);

        $response = $this->post("/kuis/{$quiz->id}/soal", [
            'tipe' => 'pilihan_ganda',
            'konten_soal' => 'What is 2+2?',
            'jumlah_pilihan' => 4,
            'jawaban_benar' => 1,
            'pilihan' => [
                ['urutan' => 1, 'konten' => '3'],
                ['urutan' => 2, 'konten' => '4'],
                ['urutan' => 3, 'konten' => '5'],
                ['urutan' => 4, 'konten' => '6']
            ]
        ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        $this->assertDatabaseHas('soal', [
            'kuis_id' => $quiz->id,
            'tipe' => 'pilihan_ganda',
            'konten_soal' => 'What is 2+2?',
            'jawaban_benar' => 1
        ]);

        $this->assertDatabaseCount('pilihan_jawaban', 4);
    }

    /** @test */
    public function can_add_question_with_images()
    {
        Storage::fake('public');
        $this->actingAs($this->admin);

        $quiz = Kuis::create([
            'created_by' => $this->admin->id,
            'judul' => 'Test Quiz',
            'deskripsi' => 'Test',
            'waktu_tipe' => 'tanpa_waktu',
            'status' => 'draft'
        ]);

        $questionImage = UploadedFile::fake()->image('question.jpg');
        $optionImage = UploadedFile::fake()->image('option.jpg');

        $response = $this->post("/kuis/{$quiz->id}/soal", [
            'tipe' => 'pilihan_ganda',
            'konten_soal' => 'What animal is this?',
            'gambar_soal' => $questionImage,
            'jumlah_pilihan' => 2,
            'jawaban_benar' => 1,
            'pilihan' => [
                ['urutan' => 1, 'konten' => 'Cat'],
                ['urutan' => 2, 'konten' => 'Dog']
            ],
            'gambar_pilihan_1' => $optionImage
        ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        $soal = Soal::where('kuis_id', $quiz->id)->first();
        $this->assertNotNull($soal->gambar_soal);

        $pilihan = PilihanJawaban::where('soal_id', $soal->id)->where('urutan', 1)->first();
        $this->assertNotNull($pilihan->gambar_pilihan);
    }

    /** @test */
    public function can_publish_quiz_with_questions()
    {
        $this->actingAs($this->admin);

        $quiz = Kuis::create([
            'created_by' => $this->admin->id,
            'judul' => 'Test Quiz',
            'deskripsi' => 'Test',
            'waktu_tipe' => 'tanpa_waktu',
            'status' => 'draft'
        ]);

        // Add a question
        Soal::create([
            'kuis_id' => $quiz->id,
            'urutan' => 1,
            'tipe' => 'pilihan_ganda',
            'konten_soal' => 'Test question',
            'jawaban_benar' => 1
        ]);

        $response = $this->put("/kuis/{$quiz->id}/status", [
            'status' => 'published'
        ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        $this->assertDatabaseHas('kuis', [
            'id' => $quiz->id,
            'status' => 'published'
        ]);
    }

    /** @test */
    public function cannot_publish_quiz_without_questions()
    {
        $this->actingAs($this->admin);

        $quiz = Kuis::create([
            'created_by' => $this->admin->id,
            'judul' => 'Empty Quiz',
            'deskripsi' => 'Test',
            'waktu_tipe' => 'tanpa_waktu',
            'status' => 'draft'
        ]);

        $response = $this->put("/kuis/{$quiz->id}/status", [
            'status' => 'published'
        ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => false]);

        $this->assertDatabaseHas('kuis', [
            'id' => $quiz->id,
            'status' => 'draft'
        ]);
    }

    /** @test */
    public function can_update_quiz()
    {
        $this->actingAs($this->admin);

        $quiz = Kuis::create([
            'created_by' => $this->admin->id,
            'judul' => 'Original Title',
            'deskripsi' => 'Original Description',
            'waktu_tipe' => 'tanpa_waktu',
            'status' => 'draft'
        ]);

        $response = $this->put("/kuis/{$quiz->id}", [
            'judul' => 'Updated Title',
            'deskripsi' => 'Updated Description',
            'waktu_tipe' => 'keseluruhan',
            'durasi_waktu' => 60
        ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        $this->assertDatabaseHas('kuis', [
            'id' => $quiz->id,
            'judul' => 'Updated Title',
            'waktu_tipe' => 'keseluruhan',
            'durasi_waktu' => 60
        ]);
    }

    /** @test */
    public function can_delete_quiz()
    {
        $this->actingAs($this->admin);

        $quiz = Kuis::create([
            'created_by' => $this->admin->id,
            'judul' => 'Test Quiz',
            'deskripsi' => 'Test',
            'waktu_tipe' => 'tanpa_waktu',
            'status' => 'draft'
        ]);

        $response = $this->delete("/kuis/{$quiz->id}");

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        $this->assertDatabaseMissing('kuis', ['id' => $quiz->id]);
    }

    /** @test */
    public function unauthorized_user_cannot_access_quiz_operations()
    {
        $quiz = Kuis::create([
            'created_by' => $this->admin->id,
            'judul' => 'Test Quiz',
            'deskripsi' => 'Test',
            'waktu_tipe' => 'tanpa_waktu',
            'status' => 'draft'
        ]);

        // Try to update without authentication
        $response = $this->put("/kuis/{$quiz->id}", [
            'judul' => 'Hacked Title'
        ]);

        $response->assertRedirect('/login');
    }

    /** @test */
    public function guru_cannot_update_other_gurus_quiz()
    {
        $otherGuru = User::create([
            'username' => 'other_guru',
            'password' => bcrypt('password'),
            'nama_orangtua' => 'Other Guru',
            'role' => 'guru'
        ]);

        $quiz = Kuis::create([
            'created_by' => $otherGuru->id,
            'judul' => 'Other Guru Quiz',
            'deskripsi' => 'Test',
            'waktu_tipe' => 'tanpa_waktu',
            'status' => 'draft'
        ]);

        $this->actingAs($this->guru);

        $response = $this->put("/kuis/{$quiz->id}", [
            'judul' => 'Hacked Title'
        ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => false]);

        $this->assertDatabaseHas('kuis', [
            'id' => $quiz->id,
            'judul' => 'Other Guru Quiz'
        ]);
    }
}
