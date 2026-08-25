<?php

namespace Tests\Feature;

use App\Models\UnitKerja;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class RoleAccessTest extends TestCase
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

    private function buatUser(
        string $role,
        string $email
    ): User {
        $unit = $this->buatUnitKerja(
            'Unit ' . ucfirst($role)
        );

        return User::create([
            'nama' => 'User ' . ucfirst($role),
            'email' => $email,
            'password' => Hash::make('Password@123'),
            'id_unit_kerja' => $unit->id,
            'role' => $role,
            'status' => 'aktif',
        ]);
    }

    private function loginSebagai(
        User $user
    ): static {
        return $this->withSession([
            'user_id' => $user->id,
        ]);
    }

    public function test_tamu_tidak_dapat_membuka_dashboard_admin(): void
    {
        $response = $this->get('/dashboard');

        $this->assertTrue(
            in_array(
                $response->getStatusCode(),
                [302, 401, 403, 404]
            )
        );
    }

    public function test_tamu_tidak_dapat_membuka_dashboard_auditor(): void
    {
        $response = $this->get('/auditor/dashboard');

        $this->assertTrue(
            in_array(
                $response->getStatusCode(),
                [302, 401, 403, 404]
            )
        );
    }

    public function test_tamu_tidak_dapat_membuka_dashboard_auditee(): void
    {
        $response = $this->get('/auditee/dashboard');

        $this->assertTrue(
            in_array(
                $response->getStatusCode(),
                [302, 401, 403, 404]
            )
        );
    }

    public function test_admin_dapat_membuka_dashboard_admin(): void
    {
        $admin = $this->buatUser(
            'admin',
            'admin@example.com'
        );

        $response = $this
            ->loginSebagai($admin)
            ->get(route('dashboard.admin'));

        $response->assertStatus(200);
    }

    public function test_auditor_tidak_dapat_membuka_dashboard_admin(): void
    {
        $auditor = $this->buatUser(
            'auditor',
            'auditor@example.com'
        );

        $response = $this
            ->loginSebagai($auditor)
            ->get(route('dashboard.admin'));

        $this->assertTrue(
            in_array(
                $response->getStatusCode(),
                [302, 401, 403]
            )
        );
    }

    public function test_auditee_tidak_dapat_membuka_dashboard_admin(): void
    {
        $auditee = $this->buatUser(
            'auditee',
            'auditee@example.com'
        );

        $response = $this
            ->loginSebagai($auditee)
            ->get(route('dashboard.admin'));

        $this->assertTrue(
            in_array(
                $response->getStatusCode(),
                [302, 401, 403]
            )
        );
    }

    public function test_auditor_dapat_membuka_dashboard_auditor(): void
    {
        $auditor = $this->buatUser(
            'auditor',
            'auditor@example.com'
        );

        $response = $this
            ->loginSebagai($auditor)
            ->get(route('dashboard.auditor'));

        $response->assertStatus(200);
    }

    public function test_admin_tidak_dapat_membuka_dashboard_auditor(): void
    {
        $admin = $this->buatUser(
            'admin',
            'admin@example.com'
        );

        $response = $this
            ->loginSebagai($admin)
            ->get(route('dashboard.auditor'));

        $this->assertTrue(
            in_array(
                $response->getStatusCode(),
                [302, 401, 403]
            )
        );
    }

    public function test_auditee_tidak_dapat_membuka_dashboard_auditor(): void
    {
        $auditee = $this->buatUser(
            'auditee',
            'auditee@example.com'
        );

        $response = $this
            ->loginSebagai($auditee)
            ->get(route('dashboard.auditor'));

        $this->assertTrue(
            in_array(
                $response->getStatusCode(),
                [302, 401, 403]
            )
        );
    }

    public function test_auditee_dapat_membuka_dashboard_auditee(): void
    {
        $auditee = $this->buatUser(
            'auditee',
            'auditee@example.com'
        );

        $response = $this
            ->loginSebagai($auditee)
            ->get(route('dashboard.auditee'));

        $response->assertStatus(200);
    }

    public function test_admin_tidak_dapat_membuka_dashboard_auditee(): void
    {
        $admin = $this->buatUser(
            'admin',
            'admin@example.com'
        );

        $response = $this
            ->loginSebagai($admin)
            ->get(route('dashboard.auditee'));

        $this->assertTrue(
            in_array(
                $response->getStatusCode(),
                [302, 401, 403]
            )
        );
    }

    public function test_auditor_tidak_dapat_membuka_dashboard_auditee(): void
    {
        $auditor = $this->buatUser(
            'auditor',
            'auditor@example.com'
        );

        $response = $this
            ->loginSebagai($auditor)
            ->get(route('dashboard.auditee'));

        $this->assertTrue(
            in_array(
                $response->getStatusCode(),
                [302, 401, 403]
            )
        );
    }
}