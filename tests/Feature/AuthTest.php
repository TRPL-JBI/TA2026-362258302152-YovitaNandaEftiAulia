<?php

namespace Tests\Feature;

use App\Models\UnitKerja;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Membuat unit kerja untuk kebutuhan user testing.
     */
    private function buatUnitKerja(): UnitKerja
    {
        return UnitKerja::create([
            'nama' => 'Program Studi Teknologi Rekayasa Perangkat Lunak',
            'kategori_unit_kerja' => 'Program Studi',
        ]);
    }

    /**
     * Membuat user testing.
     */
    private function buatUser(
        string $role = 'admin',
        string $status = 'aktif',
        string $password = 'Password@123'
    ): User {
        $unit = $this->buatUnitKerja();

        return User::create([
            'nama' => 'User Testing',
            'email' => 'testing@example.com',
            'password' => Hash::make($password),
            'id_unit_kerja' => $unit->id,
            'role' => $role,
            'status' => $status,
        ]);
    }

    public function test_admin_dapat_login_menggunakan_nama(): void
    {
        $user = $this->buatUser('admin');

        $response = $this->post('/login', [
            'username' => $user->nama,
            'password' => 'Password@123',
        ]);

        $response->assertRedirect(
            route('dashboard.admin')
        );

        $this->assertSame(
            $user->id,
            session('user_id')
        );
    }

    public function test_admin_dapat_login_menggunakan_email(): void
    {
        $user = $this->buatUser('admin');

        $response = $this->post('/login', [
            'username' => $user->email,
            'password' => 'Password@123',
        ]);

        $response->assertRedirect(
            route('dashboard.admin')
        );

        $this->assertSame(
            $user->id,
            session('user_id')
        );
    }

    public function test_auditor_diarahkan_ke_dashboard_auditor(): void
    {
        $user = $this->buatUser('auditor');

        $response = $this->post('/login', [
            'username' => $user->email,
            'password' => 'Password@123',
        ]);

        $response->assertRedirect(
            route('dashboard.auditor')
        );

        $this->assertSame(
            $user->id,
            session('user_id')
        );
    }

    public function test_auditee_diarahkan_ke_dashboard_auditee(): void
    {
        $user = $this->buatUser('auditee');

        $response = $this->post('/login', [
            'username' => $user->email,
            'password' => 'Password@123',
        ]);

        $response->assertRedirect(
            route('dashboard.auditee')
        );

        $this->assertSame(
            $user->id,
            session('user_id')
        );
    }

    public function test_login_gagal_jika_password_salah(): void
    {
        $user = $this->buatUser();

        $response = $this
            ->from('/login')
            ->post('/login', [
                'username' => $user->email,
                'password' => 'PasswordYangSalah@123',
            ]);

        $response->assertRedirect('/login');

        $response->assertSessionHasErrors([
            'username' => 'Username atau password salah.',
        ]);

        $response->assertSessionMissing('user_id');
    }

    public function test_login_gagal_jika_user_tidak_ditemukan(): void
    {
        $response = $this
            ->from('/login')
            ->post('/login', [
                'username' => 'tidakada@example.com',
                'password' => 'Password@123',
            ]);

        $response->assertRedirect('/login');

        $response->assertSessionHasErrors([
            'username' => 'Username atau password salah.',
        ]);

        $response->assertSessionMissing('user_id');
    }

    public function test_user_nonaktif_tidak_dapat_login(): void
    {
        $user = $this->buatUser(
            role: 'admin',
            status: 'nonaktif'
        );

        $response = $this
            ->from('/login')
            ->post('/login', [
                'username' => $user->email,
                'password' => 'Password@123',
            ]);

        $response->assertRedirect('/login');

        $response->assertSessionHasErrors([
            'username' => 'Akun Anda sedang tidak aktif.',
        ]);

        $response->assertSessionMissing('user_id');
    }

    public function test_username_wajib_diisi(): void
    {
        $response = $this
            ->from('/login')
            ->post('/login', [
                'username' => '',
                'password' => 'Password@123',
            ]);

        $response->assertRedirect('/login');

        $response->assertSessionHasErrors([
            'username',
        ]);
    }

    public function test_password_wajib_diisi(): void
    {
        $response = $this
            ->from('/login')
            ->post('/login', [
                'username' => 'testing@example.com',
                'password' => '',
            ]);

        $response->assertRedirect('/login');

        $response->assertSessionHasErrors([
            'password',
        ]);
    }

    public function test_admin_yang_sudah_login_dialihkan_dari_halaman_login(): void
    {
        $user = $this->buatUser('admin');

        $response = $this
            ->withSession([
                'user_id' => $user->id,
            ])
            ->get('/login');

        /*
         * Login page saat ini tidak memiliki middleware
         * yang mengalihkan user yang sudah login.
         * Jadi halaman login tetap dapat diakses.
         */
        $response->assertOk();
    }

    public function test_user_dapat_logout(): void
    {
        $user = $this->buatUser();

        $response = $this
            ->withSession([
                'user_id' => $user->id,
            ])
            ->post('/logout');

        $response->assertRedirect(
            route('login')
        );

        $response->assertSessionHas(
            'success',
            'Anda berhasil logout.'
        );

        $response->assertSessionMissing('user_id');
    }
}