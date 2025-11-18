<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use App\Models\Whitelist;
use App\Models\OtpCode;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class LoginControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware(\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class);
    }

    #[Test]
    public function show_login_form_redirects_if_authenticated()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('login'));

        $response->assertRedirect();
    }

    #[Test]
    public function admin_can_login_with_credentials()
    {
        /** @var \App\Models\User $admin */
        $admin = User::factory()->create([
            'username' => 'admin',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        $response = $this->post(route('login.admin'), [
            'username' => 'admin',
            'password' => 'password',
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticatedAs($admin);
    }

    #[Test]
    public function invalid_credentials_fail_login()
    {
        $response = $this->post(route('login.admin'), [
            'username' => 'admin',
            'password' => 'wrong',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error');
        $this->assertGuest();
    }

    #[Test]
    public function request_otp_for_whitelisted_email()
    {
        Mail::fake();
        $whitelist = Whitelist::factory()->create(['email' => 'test@example.com', 'role' => 'guru']);

        $response = $this->post(route('login.request-otp'), [
            'email' => 'test@example.com',
        ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);
        $this->assertDatabaseHas('otp_codes', ['email' => 'test@example.com']);
        Mail::assertSent(\App\Mail\OtpEmail::class);
    }

    #[Test]
    public function request_otp_fails_for_non_whitelisted_email()
    {
        $response = $this->post(route('login.request-otp'), [
            'email' => 'notwhitelisted@example.com',
        ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => false]);
    }

    #[Test]
    public function verify_otp_and_login_existing_user()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create(['email' => 'test@example.com', 'role' => 'guru']);
        $plainCode = '123456';
        $otp = OtpCode::factory()->create([
            'email' => 'test@example.com',
            'code' => \Illuminate\Support\Facades\Hash::make($plainCode),
            'is_used' => false,
        ]);

        $response = $this->post(route('login.verify-otp'), [
            'email' => 'test@example.com',
            'otp_code' => $plainCode,
        ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true, 'is_new_user' => false]);
        $this->assertAuthenticatedAs($user);
        $this->assertDatabaseHas('otp_codes', ['id' => $otp->id, 'is_used' => true]);
    }

    #[Test]
    public function verify_otp_for_new_user()
    {
        Whitelist::factory()->create(['email' => 'new@example.com', 'role' => 'guru']);
        $plainCode = '123456';
        $otp = OtpCode::factory()->create([
            'email' => 'new@example.com',
            'code' => \Illuminate\Support\Facades\Hash::make($plainCode),
            'is_used' => false,
        ]);

        $response = $this->post(route('login.verify-otp'), [
            'email' => 'new@example.com',
            'otp_code' => $plainCode,
        ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true, 'is_new_user' => true]);
        $this->assertDatabaseHas('otp_codes', ['id' => $otp->id, 'is_used' => true]);
    }

    #[Test]
    public function complete_profile_for_new_guru()
    {
        Whitelist::factory()->create(['email' => 'new@example.com', 'role' => 'guru']);

        $response = $this->post(route('login.complete-profile'), [
            'email' => 'new@example.com',
            'nama' => 'Guru Baru',
        ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);
        $this->assertDatabaseHas('users', [
            'email' => 'new@example.com',
            'nama' => 'Guru Baru',
            'role' => 'guru',
        ]);
    }

    #[Test]
    public function complete_profile_for_new_wali_murid()
    {
        Whitelist::factory()->create(['email' => 'new@example.com', 'role' => 'wali_murid']);

        $response = $this->post(route('login.complete-profile'), [
            'email' => 'new@example.com',
            'nama_orangtua' => 'Orang Tua',
            'nama_anak' => 'Anak',
            'kelas_anak' => 'Kelas 1',
        ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);
        $this->assertDatabaseHas('users', [
            'email' => 'new@example.com',
            'nama' => 'Orang Tua',
            'nama_anak' => 'Anak',
            'kelas_anak' => 'Kelas 1',
            'role' => 'wali_murid',
        ]);
    }

    #[Test]
    public function logout_clears_session()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('logout'));

        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }
}
