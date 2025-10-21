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
        $this->assertCount(1, $permainans);

        // Check permainan
        $this->assertEquals('puzzle', $permainans[0]['id']);
        $this->assertEquals('Puzzle', $permainans[0]['title']);
        $this->assertEquals('permainan.puzzle', $permainans[0]['route']);
    }

    /** @test */
    public function puzzle_returns_puzzle_view()
    {
        $this->actingAs($this->user);

        $response = $this->get('/permainan/puzzle');

        $response->assertStatus(200);
        $response->assertViewIs('permainan.puzzle');
    }

    /** @test */
    public function unauthenticated_user_cannot_access_permainan()
    {
        $response = $this->get('/permainan');
        $response->assertRedirect('/login');

        $response = $this->get('/permainan/puzzle');
        $response->assertRedirect('/login');
    }
}
