<?php

namespace Tests\Feature;

use App\Models\UnitKerja;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserManagementTest extends TestCase
{
    use RefreshDatabase;

    private function buatUnitKerja(
        string $nama = 'Unit Kerja Testing'
    ): UnitKerja {
        return UnitKerja::create([
            'nama' => $nama,
            'kategori_unit_kerja' => 'Program Studi',
        ]);
    }

    private function buatAdmin(): User
    {
        $unit = $this->buatUnitKerja(
            'Unit Admin Testing'
        );

        return User::create([
            'nama' => 'Admin Testing',
            'email' => 'admin.testing@example.com',
            'password' => Hash::make('Admin@123'),
            'role' => 'admin',
            'id_unit_kerja' => $unit->id,
            'status' => 'aktif',
        ]);
    }

    private function loginSebagaiAdmin(): static
    {
        $admin = $this->buatAdmin();

        return $this->withSession([
            'user' => $admin,
        ]);
    }

    public function test_admin_dapat_membuka_halaman_daftar_user(): void
    {
        $response = $this
            ->loginSebagaiAdmin()
            ->get(route('user.index'));

        $response->assertStatus(200);
    }

    public function test_admin_dapat_membuka_form_tambah_user(): void
    {
        $response = $this
            ->loginSebagaiAdmin()
            ->get(route('user.create'));

        $response->assertStatus(200);
    }

    public function test_admin_dapat_menambahkan_user_dengan_password_kuat(): void
    {
        $admin = $this->buatAdmin();

        $unit = $this->buatUnitKerja(
            'Program Studi TRPL'
        );

        $response = $this
            ->withSession([
                'user' => $admin,
            ])
            ->post(route('user.store'), [
                'nama' => 'Auditee Baru',
                'email' => 'auditee.baru@example.com',
                'password' => 'Password@123',
                'password_confirmation' => 'Password@123',
                'role' => 'auditee',
                'id_unit_kerja' => $unit->id,
                'status' => 'aktif',
            ]);

        $response->assertRedirect(
            route('user.index')
        );

        $response->assertSessionHas(
            'success'
        );

        $this->assertDatabaseHas('users', [
            'nama' => 'Auditee Baru',
            'email' => 'auditee.baru@example.com',
            'role' => 'auditee',
            'id_unit_kerja' => $unit->id,
            'status' => 'aktif',
        ]);

        $user = User::where(
            'email',
            'auditee.baru@example.com'
        )->firstOrFail();

        $this->assertTrue(
            Hash::check(
                'Password@123',
                $user->password
            )
        );

        $this->assertNotSame(
            'Password@123',
            $user->password
        );
    }

    public function test_password_kurang_dari_delapan_karakter_ditolak(): void
    {
        $admin = $this->buatAdmin();

        $unit = $this->buatUnitKerja();

        $response = $this
            ->withSession([
                'user' => $admin,
            ])
            ->from(route('user.create'))
            ->post(route('user.store'), [
                'nama' => 'User Testing',
                'email' => 'pendek@example.com',
                'password' => 'Ab@123',
                'password_confirmation' => 'Ab@123',
                'role' => 'auditee',
                'id_unit_kerja' => $unit->id,
                'status' => 'aktif',
            ]);

        $response->assertRedirect(
            route('user.create')
        );

        $response->assertSessionHasErrors([
            'password',
        ]);

        $this->assertDatabaseMissing('users', [
            'email' => 'pendek@example.com',
        ]);
    }

    public function test_password_tanpa_huruf_besar_ditolak(): void
    {
        $admin = $this->buatAdmin();

        $unit = $this->buatUnitKerja();

        $response = $this
            ->withSession([
                'user' => $admin,
            ])
            ->from(route('user.create'))
            ->post(route('user.store'), [
                'nama' => 'User Testing',
                'email' => 'tanpabesar@example.com',
                'password' => 'password@123',
                'password_confirmation' => 'password@123',
                'role' => 'auditee',
                'id_unit_kerja' => $unit->id,
                'status' => 'aktif',
            ]);

        $response->assertRedirect(
            route('user.create')
        );

        $response->assertSessionHasErrors([
            'password',
        ]);

        $this->assertDatabaseMissing('users', [
            'email' => 'tanpabesar@example.com',
        ]);
    }

    public function test_password_tanpa_huruf_kecil_ditolak(): void
    {
        $admin = $this->buatAdmin();

        $unit = $this->buatUnitKerja();

        $response = $this
            ->withSession([
                'user' => $admin,
            ])
            ->from(route('user.create'))
            ->post(route('user.store'), [
                'nama' => 'User Testing',
                'email' => 'tanpakecil@example.com',
                'password' => 'PASSWORD@123',
                'password_confirmation' => 'PASSWORD@123',
                'role' => 'auditee',
                'id_unit_kerja' => $unit->id,
                'status' => 'aktif',
            ]);

        $response->assertRedirect(
            route('user.create')
        );

        $response->assertSessionHasErrors([
            'password',
        ]);
    }

    public function test_password_tanpa_angka_ditolak(): void
    {
        $admin = $this->buatAdmin();

        $unit = $this->buatUnitKerja();

        $response = $this
            ->withSession([
                'user' => $admin,
            ])
            ->from(route('user.create'))
            ->post(route('user.store'), [
                'nama' => 'User Testing',
                'email' => 'tanpaangka@example.com',
                'password' => 'Password@Abc',
                'password_confirmation' => 'Password@Abc',
                'role' => 'auditee',
                'id_unit_kerja' => $unit->id,
                'status' => 'aktif',
            ]);

        $response->assertRedirect(
            route('user.create')
        );

        $response->assertSessionHasErrors([
            'password',
        ]);
    }

    public function test_password_tanpa_simbol_ditolak(): void
    {
        $admin = $this->buatAdmin();

        $unit = $this->buatUnitKerja();

        $response = $this
            ->withSession([
                'user' => $admin,
            ])
            ->from(route('user.create'))
            ->post(route('user.store'), [
                'nama' => 'User Testing',
                'email' => 'tanpasimbol@example.com',
                'password' => 'Password123',
                'password_confirmation' => 'Password123',
                'role' => 'auditee',
                'id_unit_kerja' => $unit->id,
                'status' => 'aktif',
            ]);

        $response->assertRedirect(
            route('user.create')
        );

        $response->assertSessionHasErrors([
            'password',
        ]);
    }

    public function test_konfirmasi_password_harus_sama(): void
    {
        $admin = $this->buatAdmin();

        $unit = $this->buatUnitKerja();

        $response = $this
            ->withSession([
                'user' => $admin,
            ])
            ->from(route('user.create'))
            ->post(route('user.store'), [
                'nama' => 'User Testing',
                'email' => 'konfirmasi@example.com',
                'password' => 'Password@123',
                'password_confirmation' => 'Password@456',
                'role' => 'auditee',
                'id_unit_kerja' => $unit->id,
                'status' => 'aktif',
            ]);

        $response->assertRedirect(
            route('user.create')
        );

        $response->assertSessionHasErrors([
            'password',
        ]);

        $this->assertDatabaseMissing('users', [
            'email' => 'konfirmasi@example.com',
        ]);
    }

    public function test_email_user_tidak_boleh_duplikat(): void
    {
        $admin = $this->buatAdmin();

        $unit = $this->buatUnitKerja();

        User::create([
            'nama' => 'User Pertama',
            'email' => 'duplikat@example.com',
            'password' => Hash::make('Password@123'),
            'role' => 'auditee',
            'id_unit_kerja' => $unit->id,
            'status' => 'aktif',
        ]);

        $response = $this
            ->withSession([
                'user' => $admin,
            ])
            ->from(route('user.create'))
            ->post(route('user.store'), [
                'nama' => 'User Kedua',
                'email' => 'duplikat@example.com',
                'password' => 'Password@123',
                'password_confirmation' => 'Password@123',
                'role' => 'auditor',
                'id_unit_kerja' => $unit->id,
                'status' => 'aktif',
            ]);

        $response->assertRedirect(
            route('user.create')
        );

        $response->assertSessionHasErrors([
            'email',
        ]);

        $this->assertSame(
            1,
            User::where(
                'email',
                'duplikat@example.com'
            )->count()
        );
    }

    public function test_admin_dapat_memperbarui_user_tanpa_mengubah_password(): void
    {
        $admin = $this->buatAdmin();

        $unit = $this->buatUnitKerja(
            'Unit Auditee'
        );

        $user = User::create([
            'nama' => 'Nama Lama',
            'email' => 'user.lama@example.com',
            'password' => Hash::make('Password@123'),
            'role' => 'auditee',
            'id_unit_kerja' => $unit->id,
            'status' => 'aktif',
        ]);

        $passwordLama = $user->password;

        $response = $this
            ->withSession([
                'user' => $admin,
            ])
            ->put(
                route('user.update', $user->id),
                [
                    'nama' => 'Nama Baru',
                    'email' => 'user.baru@example.com',
                    'password' => '',
                    'password_confirmation' => '',
                    'role' => 'auditee',
                    'id_unit_kerja' => $unit->id,
                    'status' => 'aktif',
                ]
            );

        $response->assertRedirect(
            route('user.index')
        );

        $user->refresh();

        $this->assertSame(
            'Nama Baru',
            $user->nama
        );

        $this->assertSame(
            'user.baru@example.com',
            $user->email
        );

        $this->assertSame(
            $passwordLama,
            $user->password
        );
    }

    public function test_admin_dapat_mengubah_password_user(): void
    {
        $admin = $this->buatAdmin();

        $unit = $this->buatUnitKerja(
            'Unit Auditor'
        );

        $user = User::create([
            'nama' => 'Auditor Testing',
            'email' => 'auditor.testing@example.com',
            'password' => Hash::make('Password@123'),
            'role' => 'auditor',
            'id_unit_kerja' => $unit->id,
            'status' => 'aktif',
        ]);

        $response = $this
            ->withSession([
                'user' => $admin,
            ])
            ->put(
                route('user.update', $user->id),
                [
                    'nama' => $user->nama,
                    'email' => $user->email,
                    'password' => 'PasswordBaru@456',
                    'password_confirmation' => 'PasswordBaru@456',
                    'role' => $user->role,
                    'id_unit_kerja' => $unit->id,
                    'status' => $user->status,
                ]
            );

        $response->assertRedirect(
            route('user.index')
        );

        $user->refresh();

        $this->assertTrue(
            Hash::check(
                'PasswordBaru@456',
                $user->password
            )
        );

        $this->assertFalse(
            Hash::check(
                'Password@123',
                $user->password
            )
        );
    }

    public function test_admin_dapat_menghapus_user_lain(): void
    {
        $admin = $this->buatAdmin();

        $unit = $this->buatUnitKerja();

        $user = User::create([
            'nama' => 'User Dihapus',
            'email' => 'hapus@example.com',
            'password' => Hash::make('Password@123'),
            'role' => 'auditee',
            'id_unit_kerja' => $unit->id,
            'status' => 'aktif',
        ]);

        $response = $this
            ->withSession([
                'user' => $admin,
            ])
            ->delete(
                route('user.destroy', $user->id)
            );

        $response->assertRedirect(
            route('user.index')
        );

        $this->assertDatabaseMissing('users', [
            'id' => $user->id,
        ]);
    }

    public function test_admin_tidak_dapat_menghapus_akun_sendiri(): void
    {
        $admin = $this->buatAdmin();

        $response = $this
            ->withSession([
                'user' => $admin,
            ])
            ->delete(
                route('user.destroy', $admin->id)
            );

        $response->assertRedirect(
            route('user.index')
        );

        $response->assertSessionHas(
            'error'
        );

        $this->assertDatabaseHas('users', [
            'id' => $admin->id,
        ]);
    }
}