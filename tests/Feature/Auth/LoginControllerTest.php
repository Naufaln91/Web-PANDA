<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;
use App\Models\User;
use App\Models\Whitelist;
use App\Models\OtpCode;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LoginControllerTest extends TestCase
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

        // Create whitelist entries
        Whitelist::create(['nomor_hp' => '081234567890']);
        Whitelist::create(['nomor_hp' => '081234567891']);
    }

    /** @test */
    public function show_login_form_displays_login_view()
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
        $response->assertViewIs('auth.login');
    }

    /** @test */
    public function show_login_form_redirects_authenticated_user()
    {
        $this->actingAs($this->admin);

        $response = $this->get('/login');

        $response->assertRedirect('/');
    }

    /** @test */
    public function admin_can_login_with_valid_credentials()
    {
        $response = $this->post('/login/admin', [
            'username' => 'admin',
            'password' => 'password'
        ]);

        $response->assertRedirect('/admin/dashboard');
        $this->assertAuthenticatedAs($this->admin);
    }

    /** @test */
    public function admin_login_fails_with_invalid_credentials()
    {
        $response = $this->post('/login/admin', [
            'username' => 'admin',
            'password' => 'wrongpassword'
        ]);

        $response->assertRedirect('/');
        $response->assertSessionHas('error', 'Username atau password salah.');
        $this->assertGuest();
    }

    /** @test */
    public function admin_login_validation_fails()
    {
        $response = $this->post('/login/admin', [
            'username' => '',
            'password' => ''
        ]);

        $response->assertRedirect('/');
        $response->assertSessionHasErrors(['username', 'password']);
    }

    /** @test */
    public function request_otp_succeeds_for_whitelisted_number()
    {
        $response = $this->post('/login/request-otp', [
            'nomor_hp' => '081234567890'
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'user_exists' => true,
            'message' => 'Kode OTP berhasil dikirim.'
        ]);

        $this->assertDatabaseHas('otp_codes', [
            'nomor_hp' => '081234567890',
            'is_used' => false
        ]);
    }

    /** @test */
    public function request_otp_fails_for_non_whitelisted_number()
    {
        $response = $this->post('/login/request-otp', [
            'nomor_hp' => '081234567899' // Not in whitelist
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => false,
            'message' => 'Nomor HP tidak masuk whitelist.'
        ]);
    }

    /** @test */
    public function request_otp_validation_fails()
    {
        $response = $this->post('/login/request-otp', [
            'nomor_hp' => 'invalid'
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => false
        ]);
        $response->assertJsonStructure(['message']);
    }

    /** @test */
    public function verify_otp_succeeds_for_existing_user()
    {
        // Generate OTP first
        $otp = OtpCode::generateOtp('081234567890');

        $response = $this->post('/login/verify-otp', [
            'nomor_hp' => '081234567890',
            'otp_code' => $otp->code
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'is_new_user' => false,
            'redirect_url' => route('wali-murid.dashboard')
        ]);

        $this->assertAuthenticatedAs($this->waliMurid);
        $this->assertDatabaseHas('otp_codes', [
            'nomor_hp' => '081234567890',
            'is_used' => true
        ]);
    }

    /** @test */
    public function verify_otp_fails_with_invalid_code()
    {
        // Generate OTP first
        OtpCode::generateOtp('081234567890');

        $response = $this->post('/login/verify-otp', [
            'nomor_hp' => '081234567890',
            'otp_code' => '000000'
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => false,
            'message' => 'Kode OTP salah atau sudah kadaluarsa.'
        ]);

        $this->assertGuest();
    }

    /** @test */
    public function verify_otp_creates_new_user_flow()
    {
        // Generate OTP for non-existing user
        $otp = OtpCode::generateOtp('081234567891');

        $response = $this->post('/login/verify-otp', [
            'nomor_hp' => '081234567891',
            'otp_code' => $otp->code
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'is_new_user' => true,
            'nomor_hp' => '081234567891'
        ]);

        $this->assertGuest(); // Not authenticated yet
    }

    /** @test */
    public function complete_profile_creates_new_user()
    {
        $response = $this->post('/login/complete-profile', [
            'nomor_hp' => '081234567891',
            'nama_orangtua' => 'New Parent',
            'nama_anak' => 'New Child',
            'kelas_anak' => 'Kelas 2',
            'role' => 'guru'
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'redirect_url' => route('guru.dashboard')
        ]);

        $this->assertDatabaseHas('users', [
            'nomor_hp' => '081234567891',
            'nama_orangtua' => 'New Parent',
            'nama_anak' => 'New Child',
            'kelas_anak' => 'Kelas 2',
            'role' => 'guru'
        ]);

        $user = User::where('nomor_hp', '081234567891')->first();
        $this->assertAuthenticatedAs($user);
    }

    /** @test */
    public function complete_profile_validation_fails()
    {
        $response = $this->post('/login/complete-profile', [
            'nomor_hp' => '081234567891',
            'nama_orangtua' => '',
            'nama_anak' => '',
            'kelas_anak' => '',
            'role' => 'invalid'
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => false
        ]);
        $response->assertJsonStructure(['message']);
    }

    /** @test */
    public function logout_clears_session_and_redirects()
    {
        $this->actingAs($this->admin);

        $response = $this->post('/logout');

        $response->assertRedirect('/login');
        $this->assertGuest();
    }
}
