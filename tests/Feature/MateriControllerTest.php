<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MateriControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::create([
            'nomor_hp' => '081234567890',
            'nama_orangtua' => 'Test User',
            'nama_anak' => 'Test Child',
            'kelas_anak' => 'Kelas 1',
            'role' => 'wali_murid'
        ]);
    }

    /** @test */
    public function index_returns_materi_list_view()
    {
        $this->actingAs($this->user);

        $response = $this->get('/materi');

        $response->assertStatus(200);
        $response->assertViewIs('materi.index');
        $response->assertViewHas('materis');

        $materis = $response->viewData('materis');
        $this->assertCount(6, $materis);

        // Check materi
        $this->assertEquals('alfabet', $materis[0]['id']);
        $this->assertEquals('Belajar Alfabet', $materis[0]['title']);
        $this->assertEquals('materi.alfabet', $materis[0]['route']);

        $this->assertEquals('warna', $materis[1]['id']);
        $this->assertEquals('Belajar Warna', $materis[1]['title']);
        $this->assertEquals('materi.warna', $materis[1]['route']);

        $this->assertEquals('hewan', $materis[2]['id']);
        $this->assertEquals('Belajar Nama Hewan', $materis[2]['title']);
        $this->assertEquals('materi.hewan', $materis[2]['route']);

        $this->assertEquals('angka', $materis[3]['id']);
        $this->assertEquals('Belajar Angka', $materis[3]['title']);
        $this->assertEquals('materi.angka', $materis[3]['route']);

        $this->assertEquals('buah', $materis[4]['id']);
        $this->assertEquals('Belajar Buah', $materis[4]['title']);
        $this->assertEquals('materi.buah', $materis[4]['route']);

        $this->assertEquals('transportasi', $materis[5]['id']);
        $this->assertEquals('Belajar Nama Transportasi', $materis[5]['title']);
        $this->assertEquals('materi.transportasi', $materis[5]['route']);
    }


    /** @test */
    public function alfabet_returns_alfabet_view()
    {
        $this->actingAs($this->user);

        $response = $this->get('/materi/alfabet');

        $response->assertStatus(200);
        $response->assertViewIs('materi.alfabet');
    }

    /** @test */
    public function warna_returns_warna_view()
    {
        $this->actingAs($this->user);

        $response = $this->get('/materi/warna');

        $response->assertStatus(200);
        $response->assertViewIs('materi.warna');
    }

    /** @test */
    public function hewan_returns_hewan_view()
    {
        $this->actingAs($this->user);

        $response = $this->get('/materi/hewan');

        $response->assertStatus(200);
        $response->assertViewIs('materi.hewan');
    }
    public function angka_returns_angka_view()
    {
        $this->actingAs($this->user);

        $response = $this->get('/materi/angka');

        $response->assertStatus(200);
        $response->assertViewIs('materi.angka');
    }

    public function buah_returns_buah_view()
    {
        $this->actingAs($this->user);

        $response = $this->get('/materi/buah');

        $response->assertStatus(200);
        $response->assertViewIs('materi.buah');
    }

    public function transportasi_returns_transportasi_view()
    {
        $this->actingAs($this->user);

        $response = $this->get('/materi/transportasi');

        $response->assertStatus(200);
        $response->assertViewIs('materi.transportasi');
    }

    /** @test */
    public function unauthenticated_user_cannot_access_materi()
    {
        $response = $this->get('/materi');
        $response->assertRedirect('/login');

        $response = $this->get('/materi/alfabet');
        $response->assertRedirect('/login');

        $response = $this->get('/materi/warna');
        $response->assertRedirect('/login');

        $response = $this->get('/materi/hewan');
        $response->assertRedirect('/login');

    }
}
