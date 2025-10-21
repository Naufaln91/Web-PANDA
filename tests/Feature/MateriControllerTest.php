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
        $this->assertCount(3, $materis);

        // Check first materi
        $this->assertEquals('alfabet', $materis[0]['id']);
        $this->assertEquals('Belajar Alfabet', $materis[0]['title']);
        $this->assertEquals('materi.alfabet', $materis[0]['route']);

        // Check second materi
        $this->assertEquals('warna', $materis[1]['id']);
        $this->assertEquals('Belajar Warna', $materis[1]['title']);
        $this->assertEquals('materi.warna', $materis[1]['route']);

        // Check third materi
        $this->assertEquals('hewan', $materis[2]['id']);
        $this->assertEquals('Belajar Nama Hewan', $materis[2]['title']);
        $this->assertEquals('materi.hewan', $materis[2]['route']);
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
