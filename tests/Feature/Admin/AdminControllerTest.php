<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use App\Models\Whitelist;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class AdminControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware(\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class);
    }

    #[Test]
    public function admin_can_access_whitelist_index()
    {
        /** @var \App\Models\User $admin */
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->get(route('admin.whitelist.index'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.whitelist.index');
        $response->assertViewHas('whitelists');
    }

    #[Test]
    public function admin_can_store_whitelist()
    {
        /** @var \App\Models\User $admin */
        $admin = User::factory()->create(['role' => 'admin']);

        $data = [
            'email' => 'test@example.com',
            'role' => 'guru',
        ];

        $response = $this->actingAs($admin)->post(route('admin.whitelist.store'), $data)
            ->assertStatus(200)
            ->assertJson(['success' => true]);

        $this->assertDatabaseHas('whitelists', $data);
    }

    #[Test]
    public function admin_can_destroy_whitelist()
    {
        /** @var \App\Models\User $admin */
        $admin = User::factory()->create(['role' => 'admin']);
        $whitelist = Whitelist::factory()->create();

        $response = $this->actingAs($admin)->delete(route('admin.whitelist.destroy', $whitelist->id));

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);
        $this->assertDatabaseMissing('whitelists', ['id' => $whitelist->id]);
    }

    #[Test]
    public function admin_can_access_akun_index()
    {
        /** @var \App\Models\User $admin */
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->get(route('admin.akun.index'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.akun.index');
        $response->assertViewHas(['guru', 'waliMurid']);
    }

    #[Test]
    public function admin_can_destroy_user()
    {
        /** @var \App\Models\User $admin */
        $admin = User::factory()->create(['role' => 'admin']);
        /** @var \App\Models\User $user */
        $user = User::factory()->create(['role' => 'guru']);

        $response = $this->actingAs($admin)->delete(route('admin.akun.destroy', $user->id))
            ->assertStatus(200)
            ->assertJson(['success' => true]);

        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }

    #[Test]
    public function admin_cannot_destroy_admin_user()
    {
        /** @var \App\Models\User $admin */
        $admin = User::factory()->create(['role' => 'admin']);
        /** @var \App\Models\User $anotherAdmin */
        $anotherAdmin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->delete(route('admin.akun.destroy', $anotherAdmin->id))
            ->assertStatus(200)
            ->assertJson(['success' => false]);

        $this->assertDatabaseHas('users', ['id' => $anotherAdmin->id]);
    }

    #[Test]
    public function non_admin_cannot_access_admin_routes()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create(['role' => 'guru']);

        $response = $this->actingAs($user)->get(route('admin.whitelist.index'));
        $response->assertRedirect(route('login'));

        $response = $this->actingAs($user)->post(route('admin.whitelist.store'), []);
        $response->assertRedirect(route('login'));
    }
}
