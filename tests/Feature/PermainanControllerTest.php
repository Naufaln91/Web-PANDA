<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PermainanControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function authenticated_user_can_access_permainan_index()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('permainan.index'));

        $response->assertStatus(200);
        $response->assertViewIs('permainan.index');
        $response->assertViewHas('permainans');
    }

    /** @test */
    public function authenticated_user_can_access_puzzle_page()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('permainan.puzzle'));

        $response->assertStatus(200);
        $response->assertViewIs('permainan.puzzle');
    }

    /** @test */
    public function authenticated_user_can_access_hitung_page()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('permainan.hitung'));

        $response->assertStatus(200);
        $response->assertViewIs('permainan.hitung');
    }

    /** @test */
    public function authenticated_user_can_access_cocokkan_pasangan_page()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('permainan.cocokkan_pasangan'));

        $response->assertStatus(200);
        $response->assertViewIs('permainan.cocokkan_pasangan');
    }

    /** @test */
    public function authenticated_user_can_access_urutkan_angka_page()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('permainan.urutkan_angka'));

        $response->assertStatus(200);
        $response->assertViewIs('permainan.urutkan_angka');
    }

    /** @test */
    public function authenticated_user_can_access_menyusun_kata_page()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('permainan.menyusun_kata'));

        $response->assertStatus(200);
        $response->assertViewIs('permainan.menyusun_kata');
    }

    /** @test */
    public function authenticated_user_can_access_labirin_page()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('permainan.labirin'));

        $response->assertStatus(200);
        $response->assertViewIs('permainan.labirin');
    }

    /** @test */
    public function unauthenticated_user_cannot_access_permainan_pages()
    {
        $response = $this->get(route('permainan.index'));
        $response->assertRedirect(route('login'));

        $response = $this->get(route('permainan.puzzle'));
        $response->assertRedirect(route('login'));
    }
}
