<?php

namespace Tests\Feature;

use App\Models\IndikatorStandar;
use App\Models\IsiStandarMutu;
use App\Models\PenerapanStandar;
use App\Models\PeriodeAmi;
use App\Models\StandarMutu;
use App\Models\StandarMutuPeriodeAmi;
use App\Models\TemuanAmi;
use App\Models\TimAmi;
use App\Models\UnitKerja;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuditorTemuanTest extends TestCase
{
    use RefreshDatabase;

    private function buatUnit(
        string $nama = 'Program Studi TRPL'
    ): UnitKerja {
        return UnitKerja::create([
            'nama' => $nama,
            'kategori_unit_kerja' => 'Program Studi',
        ]);
    }

    private function buatUser(
        UnitKerja $unit,
        string $role,
        string $email
    ): User {
        return User::create([
            'nama' => 'User ' . ucfirst($role),
            'email' => $email,
            'password' => Hash::make('Password@123'),
            'id_unit_kerja' => $unit->id,
            'role' => $role,
            'status' => 'aktif',
        ]);
    }

    private function buatDataAudit(
        User $admin,
        User $auditee
    ): array {
        $standar = StandarMutu::create([
            'nama_standar_mutu' =>
                'Standar Mutu Permendiktisaintek',
        ]);

        $isiStandar = IsiStandarMutu::create([
            'id_standar_mutu' => $standar->id,
            'nama_standar' => 'Standar Pendidikan',
            'parent_standar_id' => null,
        ]);

        $indikator = IndikatorStandar::create([
            'id_isi_standar_mutu' => $isiStandar->id,
            'deskripsi' =>
                'Program studi memiliki bukti penerapan standar.',
        ]);

        $periode = PeriodeAmi::create([
            'tahun' => 2026,
            'id_standar_mutu' => $standar->id,
            'id_unit_kerja' => $auditee->id_unit_kerja,
            'id_user' => $admin->id,
            'tujuan_audit' =>
                'Memastikan penerapan standar berjalan.',
            'lingkup_audit' =>
                'Program Studi',
            'waktu_audit' => '08.00',
            'tanggal_buka_ami' => '2026-07-01',
            'tanggal_tutup_ami' => '2026-07-31',
            'status' => 'berjalan',
        ]);

        $standarPeriode =
            StandarMutuPeriodeAmi::create([
                'id_standar_mutu' => $standar->id,
                'id_periode_ami' => $periode->id,
                'status' => 'aktif',
            ]);

        return [
            'standar' => $standar,
            'isi_standar' => $isiStandar,
            'indikator' => $indikator,
            'periode' => $periode,
            'standar_periode' => $standarPeriode,
        ];
    }

    private function buatPenerapan(
        array $data,
        User $auditee,
        ?string $deskripsi = 'Penerapan telah dilakukan.',
        ?string $linkBukti = 'https://example.com/bukti'
    ): PenerapanStandar {
        return PenerapanStandar::create([
            'id_standarmutu_periodeami' =>
                $data['standar_periode']->id,

            'id_indikator' =>
                $data['indikator']->id,

            'deskripsi_hasil' =>
                $deskripsi,

            'link_bukti' =>
                $linkBukti,

            'id_user' =>
                $auditee->id,
        ]);
    }

    private function tugaskanAuditor(
        User $auditor,
        PeriodeAmi $periode
    ): TimAmi {
        return TimAmi::create([
            'id_periode_ami' => $periode->id,
            'id_user' => $auditor->id,
            'role' => 'auditor',
        ]);
    }

    private function loginSebagai(
        User $user
    ): static {
        return $this->withSession([
            'user' => $user,
        ]);
    }

    public function test_auditor_dapat_membuka_daftar_temuan(): void
    {
        $unit = $this->buatUnit();

        $admin = $this->buatUser(
            $unit,
            'admin',
            'admin@example.com'
        );

        $auditee = $this->buatUser(
            $unit,
            'auditee',
            'auditee@example.com'
        );

        $auditor = $this->buatUser(
            $unit,
            'auditor',
            'auditor@example.com'
        );

        $data = $this->buatDataAudit(
            $admin,
            $auditee
        );

        $this->tugaskanAuditor(
            $auditor,
            $data['periode']
        );

        $this->buatPenerapan(
            $data,
            $auditee
        );

        $response = $this
            ->loginSebagai($auditor)
            ->get(
                route('auditor.temuan.index')
            );

        $response->assertStatus(200);
    }

    public function test_auditor_dapat_membuka_form_temuan_jika_penerapan_lengkap(): void
    {
        $unit = $this->buatUnit();

        $admin = $this->buatUser(
            $unit,
            'admin',
            'admin@example.com'
        );

        $auditee = $this->buatUser(
            $unit,
            'auditee',
            'auditee@example.com'
        );

        $auditor = $this->buatUser(
            $unit,
            'auditor',
            'auditor@example.com'
        );

        $data = $this->buatDataAudit(
            $admin,
            $auditee
        );

        $this->tugaskanAuditor(
            $auditor,
            $data['periode']
        );

        $penerapan = $this->buatPenerapan(
            $data,
            $auditee
        );

        $response = $this
            ->loginSebagai($auditor)
            ->get(
                route(
                    'auditor.temuan.create',
                    $penerapan->id
                )
            );

        $response->assertStatus(200);
    }

    public function test_form_temuan_ditolak_jika_deskripsi_penerapan_kosong(): void
    {
        $unit = $this->buatUnit();

        $admin = $this->buatUser(
            $unit,
            'admin',
            'admin@example.com'
        );

        $auditee = $this->buatUser(
            $unit,
            'auditee',
            'auditee@example.com'
        );

        $auditor = $this->buatUser(
            $unit,
            'auditor',
            'auditor@example.com'
        );

        $data = $this->buatDataAudit(
            $admin,
            $auditee
        );

        $this->tugaskanAuditor(
            $auditor,
            $data['periode']
        );

        $penerapan = $this->buatPenerapan(
            $data,
            $auditee,
            '',
            'https://example.com/bukti'
        );

        $response = $this
            ->loginSebagai($auditor)
            ->get(
                route(
                    'auditor.temuan.create',
                    $penerapan->id
                )
            );

        $response->assertStatus(403);
    }

    public function test_form_temuan_ditolak_jika_link_bukti_kosong(): void
    {
        $unit = $this->buatUnit();

        $admin = $this->buatUser(
            $unit,
            'admin',
            'admin@example.com'
        );

        $auditee = $this->buatUser(
            $unit,
            'auditee',
            'auditee@example.com'
        );

        $auditor = $this->buatUser(
            $unit,
            'auditor',
            'auditor@example.com'
        );

        $data = $this->buatDataAudit(
            $admin,
            $auditee
        );

        $this->tugaskanAuditor(
            $auditor,
            $data['periode']
        );

        $penerapan = $this->buatPenerapan(
            $data,
            $auditee,
            'Penerapan sudah dilakukan.',
            null
        );

        $response = $this
            ->loginSebagai($auditor)
            ->get(
                route(
                    'auditor.temuan.create',
                    $penerapan->id
                )
            );

        $response->assertStatus(403);
    }

    public function test_auditor_dapat_menyimpan_temuan(): void
    {
        $unit = $this->buatUnit();

        $admin = $this->buatUser(
            $unit,
            'admin',
            'admin@example.com'
        );

        $auditee = $this->buatUser(
            $unit,
            'auditee',
            'auditee@example.com'
        );

        $auditor = $this->buatUser(
            $unit,
            'auditor',
            'auditor@example.com'
        );

        $data = $this->buatDataAudit(
            $admin,
            $auditee
        );

        $this->tugaskanAuditor(
            $auditor,
            $data['periode']
        );

        $penerapan = $this->buatPenerapan(
            $data,
            $auditee
        );

        $response = $this
            ->loginSebagai($auditor)
            ->post(
                route('auditor.temuan.store'),
                [
                    'id_penerapan_standar' =>
                        $penerapan->id,

                    'temuan' =>
                        'Dokumen bukti belum disahkan.',

                    'status_temuan' =>
                        'open',
                ]
            );

        $response->assertRedirect(
            route('auditor.temuan.index')
        );

        $response->assertSessionHas(
            'success'
        );

        $this->assertDatabaseHas(
            'temuan_ami',
            [
                'id_penerapan_standar' =>
                    $penerapan->id,

                'temuan' =>
                    'Dokumen bukti belum disahkan.',

                'status_temuan' =>
                    'open',
            ]
        );
    }

    public function test_isi_temuan_wajib_diisi(): void
    {
        $unit = $this->buatUnit();

        $admin = $this->buatUser(
            $unit,
            'admin',
            'admin@example.com'
        );

        $auditee = $this->buatUser(
            $unit,
            'auditee',
            'auditee@example.com'
        );

        $auditor = $this->buatUser(
            $unit,
            'auditor',
            'auditor@example.com'
        );

        $data = $this->buatDataAudit(
            $admin,
            $auditee
        );

        $this->tugaskanAuditor(
            $auditor,
            $data['periode']
        );

        $penerapan = $this->buatPenerapan(
            $data,
            $auditee
        );

        $response = $this
            ->loginSebagai($auditor)
            ->from(
                route(
                    'auditor.temuan.create',
                    $penerapan->id
                )
            )
            ->post(
                route('auditor.temuan.store'),
                [
                    'id_penerapan_standar' =>
                        $penerapan->id,

                    'temuan' => '',

                    'status_temuan' =>
                        'open',
                ]
            );

        $response->assertSessionHasErrors([
            'temuan',
        ]);

        $this->assertDatabaseCount(
            'temuan_ami',
            0
        );
    }

    public function test_status_temuan_harus_open_atau_closed(): void
    {
        $unit = $this->buatUnit();

        $admin = $this->buatUser(
            $unit,
            'admin',
            'admin@example.com'
        );

        $auditee = $this->buatUser(
            $unit,
            'auditee',
            'auditee@example.com'
        );

        $auditor = $this->buatUser(
            $unit,
            'auditor',
            'auditor@example.com'
        );

        $data = $this->buatDataAudit(
            $admin,
            $auditee
        );

        $this->tugaskanAuditor(
            $auditor,
            $data['periode']
        );

        $penerapan = $this->buatPenerapan(
            $data,
            $auditee
        );

        $response = $this
            ->loginSebagai($auditor)
            ->post(
                route('auditor.temuan.store'),
                [
                    'id_penerapan_standar' =>
                        $penerapan->id,

                    'temuan' =>
                        'Temuan audit testing.',

                    'status_temuan' =>
                        'selesai',
                ]
            );

        $response->assertSessionHasErrors([
            'status_temuan',
        ]);

        $this->assertDatabaseCount(
            'temuan_ami',
            0
        );
    }

    public function test_auditor_tidak_dapat_membuat_temuan_pada_periode_yang_bukan_penugasannya(): void
    {
        $unit = $this->buatUnit();

        $admin = $this->buatUser(
            $unit,
            'admin',
            'admin@example.com'
        );

        $auditee = $this->buatUser(
            $unit,
            'auditee',
            'auditee@example.com'
        );

        $auditor = $this->buatUser(
            $unit,
            'auditor',
            'auditor@example.com'
        );

        $data = $this->buatDataAudit(
            $admin,
            $auditee
        );

        $penerapan = $this->buatPenerapan(
            $data,
            $auditee
        );

        $response = $this
            ->loginSebagai($auditor)
            ->post(
                route('auditor.temuan.store'),
                [
                    'id_penerapan_standar' =>
                        $penerapan->id,

                    'temuan' =>
                        'Mencoba membuat temuan tanpa penugasan.',

                    'status_temuan' =>
                        'open',
                ]
            );

        $response->assertStatus(403);

        $this->assertDatabaseCount(
            'temuan_ami',
            0
        );
    }

    public function test_auditor_dapat_memperbarui_temuan(): void
    {
        $unit = $this->buatUnit();

        $admin = $this->buatUser(
            $unit,
            'admin',
            'admin@example.com'
        );

        $auditee = $this->buatUser(
            $unit,
            'auditee',
            'auditee@example.com'
        );

        $auditor = $this->buatUser(
            $unit,
            'auditor',
            'auditor@example.com'
        );

        $data = $this->buatDataAudit(
            $admin,
            $auditee
        );

        $this->tugaskanAuditor(
            $auditor,
            $data['periode']
        );

        $penerapan = $this->buatPenerapan(
            $data,
            $auditee
        );

        $temuan = TemuanAmi::create([
            'id_penerapan_standar' =>
                $penerapan->id,

            'temuan' =>
                'Temuan awal.',

            'status_temuan' =>
                'open',
        ]);

        $response = $this
            ->loginSebagai($auditor)
            ->put(
                route(
                    'auditor.temuan.update',
                    $temuan->id
                ),
                [
                    'temuan' =>
                        'Temuan sudah diperbarui.',

                    'status_temuan' =>
                        'closed',
                ]
            );

        $response->assertRedirect(
            route(
                'auditor.temuan.show',
                $temuan->id
            )
        );

        $this->assertDatabaseHas(
            'temuan_ami',
            [
                'id' => $temuan->id,

                'temuan' =>
                    'Temuan sudah diperbarui.',

                'status_temuan' =>
                    'closed',
            ]
        );
    }

    public function test_auditor_dapat_menghapus_temuan(): void
    {
        $unit = $this->buatUnit();

        $admin = $this->buatUser(
            $unit,
            'admin',
            'admin@example.com'
        );

        $auditee = $this->buatUser(
            $unit,
            'auditee',
            'auditee@example.com'
        );

        $auditor = $this->buatUser(
            $unit,
            'auditor',
            'auditor@example.com'
        );

        $data = $this->buatDataAudit(
            $admin,
            $auditee
        );

        $this->tugaskanAuditor(
            $auditor,
            $data['periode']
        );

        $penerapan = $this->buatPenerapan(
            $data,
            $auditee
        );

        $temuan = TemuanAmi::create([
            'id_penerapan_standar' =>
                $penerapan->id,

            'temuan' =>
                'Temuan yang akan dihapus.',

            'status_temuan' =>
                'open',
        ]);

        $response = $this
            ->loginSebagai($auditor)
            ->delete(
                route(
                    'auditor.temuan.destroy',
                    $temuan->id
                )
            );

        $response->assertRedirect(
            route('auditor.temuan.index')
        );

        $this->assertDatabaseMissing(
            'temuan_ami',
            [
                'id' => $temuan->id,
            ]
        );
    }

    public function test_auditor_tidak_dapat_memperbarui_temuan_dari_periode_lain(): void
    {
        $unit = $this->buatUnit();

        $admin = $this->buatUser(
            $unit,
            'admin',
            'admin@example.com'
        );

        $auditee = $this->buatUser(
            $unit,
            'auditee',
            'auditee@example.com'
        );

        $auditor = $this->buatUser(
            $unit,
            'auditor',
            'auditor@example.com'
        );

        $data = $this->buatDataAudit(
            $admin,
            $auditee
        );

        $penerapan = $this->buatPenerapan(
            $data,
            $auditee
        );

        $temuan = TemuanAmi::create([
            'id_penerapan_standar' =>
                $penerapan->id,

            'temuan' =>
                'Temuan milik periode lain.',

            'status_temuan' =>
                'open',
        ]);

        $response = $this
            ->loginSebagai($auditor)
            ->put(
                route(
                    'auditor.temuan.update',
                    $temuan->id
                ),
                [
                    'temuan' =>
                        'Percobaan perubahan ilegal.',

                    'status_temuan' =>
                        'closed',
                ]
            );

        $response->assertStatus(403);

        $this->assertDatabaseHas(
            'temuan_ami',
            [
                'id' => $temuan->id,

                'temuan' =>
                    'Temuan milik periode lain.',

                'status_temuan' =>
                    'open',
            ]
        );
    }
}