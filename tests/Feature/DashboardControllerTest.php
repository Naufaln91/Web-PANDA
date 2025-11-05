<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function admin_can_access_admin_dashboard()
    {
        /** @var \App\Models\User $admin */
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->get(route('admin.dashboard'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.dashboard');
        $response->assertViewHas(['totalUsers', 'totalGuru', 'totalWaliMurid', 'totalWhitelist', 'totalKuis']);
    }

    /** @test */
    public function guru_can_access_guru_dashboard()
    {
        /** @var \App\Models\User $guru */
        $guru = User::factory()->create(['role' => 'guru']);

        $response = $this->actingAs($guru)->get(route('guru.dashboard'));

        $response->assertStatus(200);
        $response->assertViewIs('guru.dashboard');
        $response->assertViewHas(['myKuis', 'publishedKuis']);
    }

    /** @test */
    public function wali_murid_can_access_wali_murid_dashboard()
    {
        /** @var \App\Models\User $waliMurid */
        $waliMurid = User::factory()->create(['role' => 'wali_murid']);

        $response = $this->actingAs($waliMurid)->get(route('wali-murid.dashboard'));

        $response->assertStatus(200);
        $response->assertViewIs('wali-murid.dashboard');
        $response->assertViewHas(['publishedKuis', 'user']);
    }

    /** @test */
    public function unauthenticated_user_cannot_access_dashboards()
    {
        $response = $this->get(route('admin.dashboard'));
        $response->assertRedirect(route('login'));

        $response = $this->get(route('guru.dashboard'));
        $response->assertRedirect(route('login'));

        $response = $this->get(route('wali-murid.dashboard'));
        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function wrong_role_cannot_access_other_dashboards()
    {
        /** @var \App\Models\User $guru */
        $guru = User::factory()->create(['role' => 'guru']);

        $response = $this->actingAs($guru)->get(route('admin.dashboard'));
        $response->assertRedirect(route('login'));

        /** @var \App\Models\User $waliMurid */
        $waliMurid = User::factory()->create(['role' => 'wali_murid']);

        $response = $this->actingAs($waliMurid)->get(route('guru.dashboard'));
        $response->assertRedirect(route('login'));
    }
}
