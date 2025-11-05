<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MateriControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function authenticated_user_can_access_materi_index()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('materi.index'));

        $response->assertStatus(200);
        $response->assertViewIs('materi.index');
        $response->assertViewHas('materis');
    }

    /** @test */
    public function authenticated_user_can_access_alfabet_page()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('materi.alfabet'));

        $response->assertStatus(200);
        $response->assertViewIs('materi.alfabet');
    }

    /** @test */
    public function authenticated_user_can_access_warna_page()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('materi.warna'));

        $response->assertStatus(200);
        $response->assertViewIs('materi.warna');
    }

    /** @test */
    public function authenticated_user_can_access_hewan_page()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('materi.hewan'));

        $response->assertStatus(200);
        $response->assertViewIs('materi.hewan');
    }

    /** @test */
    public function authenticated_user_can_access_angka_page()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('materi.angka'));

        $response->assertStatus(200);
        $response->assertViewIs('materi.angka');
    }

    /** @test */
    public function authenticated_user_can_access_buah_page()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('materi.buah'));

        $response->assertStatus(200);
        $response->assertViewIs('materi.buah');
    }

    /** @test */
    public function authenticated_user_can_access_transportasi_page()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('materi.transportasi'));

        $response->assertStatus(200);
        $response->assertViewIs('materi.transportasi');
    }

    /** @test */
    public function unauthenticated_user_cannot_access_materi_pages()
    {
        $response = $this->get(route('materi.index'));
        $response->assertRedirect(route('login'));

        $response = $this->get(route('materi.alfabet'));
        $response->assertRedirect(route('login'));
    }
}
