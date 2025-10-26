<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PermainanControllerTest extends TestCase
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
    public function index_returns_permainan_list_view()
    {
        $this->actingAs($this->user);

        $response = $this->get('/permainan');

        $response->assertStatus(200);
        $response->assertViewIs('permainan.index');
        $response->assertViewHas('permainans');

        $permainans = $response->viewData('permainans');
        $this->assertCount(6, $permainans);

        // Check permainan
        $this->assertEquals('puzzle', $permainans[0]['id']);
        $this->assertEquals('Puzzle', $permainans[0]['title']);
        $this->assertEquals('permainan.puzzle', $permainans[0]['route']);

        $this->assertEquals('hitung', $permainans[1]['id']);
        $this->assertEquals('Hitung Jumlah Gambar', $permainans[1]['title']);
        $this->assertEquals('permainan.hitung', $permainans[1]['route']);

        $this->assertEquals('cocokkan_pasangan', $permainans[2]['id']);
        $this->assertEquals('Mencocokkan Pasangan Buah-Buahan', $permainans[2]['title']);
        $this->assertEquals('permainan.cocokkan_pasangan', $permainans[2]['route']);

        $this->assertEquals('urutkan_angka', $permainans[3]['id']);
        $this->assertEquals('Urutkan Angka', $permainans[3]['title']);
        $this->assertEquals('permainan.urutkan_angka', $permainans[3]['route']);

        $this->assertEquals('menyusun_kata', $permainans[4]['id']);
        $this->assertEquals('Susun Kata', $permainans[4]['title']);
        $this->assertEquals('permainan.menyusun_kata', $permainans[4]['route']);

        $this->assertEquals('labirin', $permainans[5]['id']);
        $this->assertEquals('Labirin', $permainans[5]['title']);
        $this->assertEquals('permainan.labirin', $permainans[5]['route']);
    }

    /** @test */
    public function puzzle_returns_puzzle_view()
    {
        $this->actingAs($this->user);

        $response = $this->get('/permainan/puzzle');

        $response->assertStatus(200);
        $response->assertViewIs('permainan.puzzle');
    }

    public function hitung_returns_hitung_view()
    {
        $this->actingAs($this->user);

        $response = $this->get('/permainan/hitung');

        $response->assertStatus(200);
        $response =assertViewIs('/permainan/hitung');
    }

    public function cocokkan_pasangan_returns_cocokkan_pasangan_view()
    {
        $this->actingAs($this->user);

        $response = $this->get('/permainan/cocokkan_pasangan');

        $response->assertStatus(200);
        $response =assertViewIs('/permainan/cocokkan_pasangan');
    }

    public function menyusun_kata_returns_menyusun_kata_view()
    {
        $this->actingAs($this->user);

        $response = $this->get('/permainan/menyusun_kata');

        $response->assertStatus(200);
        $response =assertViewIs('/permainan/menyusun_kata');
    }

    public function urutkan_angka_returns_urutkan_angka_view()
    {
        $this->actingAs($this->user);

        $response = $this->get('/permainan/urutkan_angka');

        $response->assertStatus(200);
        $response =assertViewIs('/permainan/urutkan_angka');
    }

    public function labirin_returns_labirin_view()
    {
        $this->actingAs($this->user);

        $response = $this->get('/permainan/labirin');

        $response->assertStatus(200);
        $response =assertViewIs('/permainan/labirin');
    }

    /** @test */
    public function unauthenticated_user_cannot_access_permainan()
    {
        $response = $this->get('/permainan');
        $response->assertRedirect('/login');

        $response = $this->get('/permainan/puzzle');
        $response->assertRedirect('/login');

        $response = $this->get('/permainan/hitung');
        $response->assertRedirect('/login');

        $response = $this->get('/permainan/cocokkan_pasangan');
        $response->assertRedirect('/login');

        $response = $this->get('/permainan/menyusun_kata');
        $response->assertRedirect('/login');

        $response = $this->get('/permainan/urutkan_angka');
        $response->assertRedirect('/login');

        $response = $this->get('/permainan/labirin');
        $response->assertRedirect('/login');
    
    }
}
