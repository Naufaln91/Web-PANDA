<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Kuis;
use App\Models\Whitelist;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DashboardControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;
    protected $guru;
    protected $waliMurid;

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

        $this->waliMurid = User::create([
            'nomor_hp' => '081234567890',
            'nama_orangtua' => 'Wali Murid',
            'nama_anak' => 'Anak User',
            'kelas_anak' => 'Kelas 1',
            'role' => 'wali_murid'
        ]);

        // Create some test data
        User::create([
            'username' => 'guru2',
            'password' => bcrypt('password'),
            'nama_orangtua' => 'Guru 2',
            'role' => 'guru'
        ]);

        User::create([
            'nomor_hp' => '081234567891',
            'nama_orangtua' => 'Wali 2',
            'nama_anak' => 'Anak 2',
            'kelas_anak' => 'Kelas 2',
            'role' => 'wali_murid'
        ]);

        Whitelist::create(['nomor_hp' => '081234567890']);
        Whitelist::create(['nomor_hp' => '081234567891']);

        Kuis::create([
            'created_by' => $this->guru->id,
            'judul' => 'Test Kuis',
            'deskripsi' => 'Test',
            'waktu_tipe' => 'tanpa_waktu',
            'status' => 'published'
        ]);
    }

    /** @test */
    public function admin_can_access_dashboard_with_correct_data()
    {
        $this->actingAs($this->admin);

        $response = $this->get('/admin/dashboard');

        $response->assertStatus(200);
        $response->assertViewIs('admin.dashboard');
        $response->assertViewHasAll([
            'totalUsers',
            'totalGuru',
            'totalWaliMurid',
            'totalWhitelist',
            'totalKuis'
        ]);

        $viewData = $response->viewData('totalUsers');
        $this->assertEquals(4, $viewData); // 2 gurus + 2 wali_murid (excluding admin)

        $viewData = $response->viewData('totalGuru');
        $this->assertEquals(2, $viewData);

        $viewData = $response->viewData('totalWaliMurid');
        $this->assertEquals(2, $viewData);

        $viewData = $response->viewData('totalWhitelist');
        $this->assertEquals(2, $viewData);

        $viewData = $response->viewData('totalKuis');
        $this->assertEquals(1, $viewData);
    }

    /** @test */
    public function guru_can_access_dashboard_with_correct_data()
    {
        $this->actingAs($this->guru);

        $response = $this->get('/guru/dashboard');

        $response->assertStatus(200);
        $response->assertViewIs('guru.dashboard');
        $response->assertViewHasAll(['myKuis', 'publishedKuis']);

        $viewData = $response->viewData('myKuis');
        $this->assertEquals(1, $viewData); // Kuis created by this guru

        $viewData = $response->viewData('publishedKuis');
        $this->assertEquals(1, $viewData); // Published kuis
    }

    /** @test */
    public function wali_murid_can_access_dashboard_with_correct_data()
    {
        $this->actingAs($this->waliMurid);

        $response = $this->get('/wali-murid/dashboard');

        $response->assertStatus(200);
        $response->assertViewIs('wali-murid.dashboard');
        $response->assertViewHasAll(['publishedKuis', 'user']);

        $viewData = $response->viewData('publishedKuis');
        $this->assertEquals(1, $viewData);

        $user = $response->viewData('user');
        $this->assertEquals($this->waliMurid->id, $user->id);
    }

    /** @test */
    public function unauthenticated_user_cannot_access_dashboards()
    {
        $response = $this->get('/admin/dashboard');
        $response->assertRedirect('/login');

        $response = $this->get('/guru/dashboard');
        $response->assertRedirect('/login');

        $response = $this->get('/wali-murid/dashboard');
        $response->assertRedirect('/login');
    }
}
