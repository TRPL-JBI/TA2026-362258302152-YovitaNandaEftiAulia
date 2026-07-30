<?php

namespace Tests\Feature;

use App\Models\IndikatorStandar;
use App\Models\IsiStandarMutu;
use App\Models\PenerapanStandar;
use App\Models\PeriodeAmi;
use App\Models\StandarMutu;
use App\Models\StandarMutuPeriodeAmi;
use App\Models\TanggapanAuditee;
use App\Models\TemuanAmi;
use App\Models\UnitKerja;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuditeeTanggapanTest extends TestCase
{
    use RefreshDatabase;

    /*
    |--------------------------------------------------------------------------
    | HELPER UNIT KERJA
    |--------------------------------------------------------------------------
    */

    private function buatUnit(
        string $nama = 'Program Studi TRPL'
    ): UnitKerja {
        return UnitKerja::create([
            'nama' => $nama,
            'kategori_unit_kerja' => 'Program Studi',
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | HELPER USER
    |--------------------------------------------------------------------------
    */

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

    /*
    |--------------------------------------------------------------------------
    | HELPER DATA STANDAR DAN PERIODE
    |--------------------------------------------------------------------------
    */

    private function buatDataAudit(
        UnitKerja $unit,
        User $admin
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
            'id_isi_standar_mutu' =>
                $isiStandar->id,

            'deskripsi' =>
                'Program studi memiliki bukti penerapan standar.',
        ]);

        $periode = PeriodeAmi::create([
            'tahun' => 2026,
            'id_standar_mutu' => $standar->id,
            'id_unit_kerja' => $unit->id,
            'id_user' => $admin->id,

            'tujuan_audit' =>
                'Memastikan penerapan standar telah berjalan.',

            'lingkup_audit' =>
                'Program Studi',

            'waktu_audit' => '08.00',

            'tanggal_buka_ami' =>
                '2026-07-01',

            'tanggal_tutup_ami' =>
                '2026-07-31',

            'status' => 'berjalan',
        ]);

        $standarPeriode =
            StandarMutuPeriodeAmi::create([
                'id_standar_mutu' =>
                    $standar->id,

                'id_periode_ami' =>
                    $periode->id,

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

    /*
    |--------------------------------------------------------------------------
    | HELPER PENERAPAN
    |--------------------------------------------------------------------------
    */

    private function buatPenerapan(
        array $data,
        User $auditee
    ): PenerapanStandar {
        return PenerapanStandar::create([
            'id_standarmutu_periodeami' =>
                $data['standar_periode']->id,

            'id_indikator' =>
                $data['indikator']->id,

            'deskripsi_hasil' =>
                'Penerapan standar telah dilaksanakan.',

            'link_bukti' =>
                'https://example.com/bukti-penerapan',

            'id_user' => $auditee->id,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | HELPER TEMUAN
    |--------------------------------------------------------------------------
    */

    private function buatTemuan(
        PenerapanStandar $penerapan,
        string $isiTemuan = 'Dokumen bukti belum lengkap.'
    ): TemuanAmi {
        return TemuanAmi::create([
            'id_penerapan_standar' =>
                $penerapan->id,

            'temuan' =>
                $isiTemuan,

            'status_temuan' =>
                'open',
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | HELPER LOGIN
    |--------------------------------------------------------------------------
    */

    private function loginSebagai(
        User $user
    ): static {
        return $this->withSession([
            'user' => $user,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | TEST DAFTAR TEMUAN
    |--------------------------------------------------------------------------
    */

    public function test_auditee_dapat_membuka_daftar_temuan_miliknya(): void
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

        $data = $this->buatDataAudit(
            $unit,
            $admin
        );

        $penerapan = $this->buatPenerapan(
            $data,
            $auditee
        );

        $temuan = $this->buatTemuan(
            $penerapan
        );

        $response = $this
            ->loginSebagai($auditee)
            ->get(
                route('auditee.temuan.index')
            );

        $response->assertStatus(200);

        $response->assertSee(
            $temuan->temuan,
            false
        );
    }

    /*
    |--------------------------------------------------------------------------
    | TEST DETAIL TEMUAN
    |--------------------------------------------------------------------------
    */

    public function test_auditee_dapat_membuka_detail_temuan_miliknya(): void
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

        $data = $this->buatDataAudit(
            $unit,
            $admin
        );

        $penerapan = $this->buatPenerapan(
            $data,
            $auditee
        );

        $temuan = $this->buatTemuan(
            $penerapan
        );

        $response = $this
            ->loginSebagai($auditee)
            ->get(
                route(
                    'auditee.temuan.show',
                    $temuan->id
                )
            );

        $response->assertStatus(200);

        $response->assertSee(
            $temuan->temuan,
            false
        );
    }

    /*
    |--------------------------------------------------------------------------
    | TEST FORM TANGGAPAN
    |--------------------------------------------------------------------------
    */

    public function test_auditee_dapat_membuka_form_tanggapan(): void
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

        $data = $this->buatDataAudit(
            $unit,
            $admin
        );

        $penerapan = $this->buatPenerapan(
            $data,
            $auditee
        );

        $temuan = $this->buatTemuan(
            $penerapan
        );

        $response = $this
            ->loginSebagai($auditee)
            ->get(
                route(
                    'auditee.tanggapan.create',
                    $temuan->id
                )
            );

        $response->assertStatus(200);
    }

    /*
    |--------------------------------------------------------------------------
    | TEST SIMPAN TANGGAPAN
    |--------------------------------------------------------------------------
    */

    public function test_auditee_dapat_menyimpan_tanggapan(): void
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

        $data = $this->buatDataAudit(
            $unit,
            $admin
        );

        $penerapan = $this->buatPenerapan(
            $data,
            $auditee
        );

        $temuan = $this->buatTemuan(
            $penerapan
        );

        $response = $this
            ->loginSebagai($auditee)
            ->post(
                route(
                    'auditee.tanggapan.store',
                    $temuan->id
                ),
                [
                    'tanggapan' =>
                        'Dokumen akan segera dilengkapi dan disahkan.',
                ]
            );

        $response->assertRedirect(
            route(
                'auditee.temuan.show',
                $temuan->id
            )
        );

        $response->assertSessionHas(
            'success',
            'Tanggapan berhasil disimpan.'
        );

        $this->assertDatabaseHas(
            'tanggapan_auditee',
            [
                'id_temuan_ami' =>
                    $temuan->id,

                'tanggapan' =>
                    'Dokumen akan segera dilengkapi dan disahkan.',

                'id_user' =>
                    $auditee->id,
            ]
        );
    }

    /*
    |--------------------------------------------------------------------------
    | TEST VALIDASI TANGGAPAN WAJIB
    |--------------------------------------------------------------------------
    */

    public function test_isi_tanggapan_wajib_diisi(): void
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

        $data = $this->buatDataAudit(
            $unit,
            $admin
        );

        $penerapan = $this->buatPenerapan(
            $data,
            $auditee
        );

        $temuan = $this->buatTemuan(
            $penerapan
        );

        $response = $this
            ->loginSebagai($auditee)
            ->from(
                route(
                    'auditee.tanggapan.create',
                    $temuan->id
                )
            )
            ->post(
                route(
                    'auditee.tanggapan.store',
                    $temuan->id
                ),
                [
                    'tanggapan' => '',
                ]
            );

        $response->assertRedirect(
            route(
                'auditee.tanggapan.create',
                $temuan->id
            )
        );

        $response->assertSessionHasErrors([
            'tanggapan',
        ]);

        $this->assertDatabaseCount(
            'tanggapan_auditee',
            0
        );
    }

    /*
    |--------------------------------------------------------------------------
    | TEST TANGGAPAN MAKSIMAL 10.000 KARAKTER
    |--------------------------------------------------------------------------
    */

    public function test_tanggapan_lebih_dari_10000_karakter_ditolak(): void
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

        $data = $this->buatDataAudit(
            $unit,
            $admin
        );

        $penerapan = $this->buatPenerapan(
            $data,
            $auditee
        );

        $temuan = $this->buatTemuan(
            $penerapan
        );

        $response = $this
            ->loginSebagai($auditee)
            ->post(
                route(
                    'auditee.tanggapan.store',
                    $temuan->id
                ),
                [
                    'tanggapan' =>
                        str_repeat('A', 10001),
                ]
            );

        $response->assertSessionHasErrors([
            'tanggapan',
        ]);

        $this->assertDatabaseCount(
            'tanggapan_auditee',
            0
        );
    }

    /*
    |--------------------------------------------------------------------------
    | TEST TANGGAPAN GANDA
    |--------------------------------------------------------------------------
    */

    public function test_tanggapan_ganda_pada_temuan_yang_sama_ditolak(): void
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

        $data = $this->buatDataAudit(
            $unit,
            $admin
        );

        $penerapan = $this->buatPenerapan(
            $data,
            $auditee
        );

        $temuan = $this->buatTemuan(
            $penerapan
        );

        TanggapanAuditee::create([
            'id_temuan_ami' => $temuan->id,
            'tanggapan' => 'Tanggapan pertama.',
            'id_user' => $auditee->id,
        ]);

        $response = $this
            ->loginSebagai($auditee)
            ->post(
                route(
                    'auditee.tanggapan.store',
                    $temuan->id
                ),
                [
                    'tanggapan' =>
                        'Tanggapan kedua.',
                ]
            );

        $response->assertRedirect(
            route(
                'auditee.temuan.show',
                $temuan->id
            )
        );

        $response->assertSessionHas(
            'error',
            'Temuan ini sudah mempunyai tanggapan.'
        );

        $this->assertSame(
            1,
            TanggapanAuditee::where(
                'id_temuan_ami',
                $temuan->id
            )->count()
        );
    }

    /*
    |--------------------------------------------------------------------------
    | TEST AUDITEE TIDAK DAPAT MENANGGAPI TEMUAN USER LAIN
    |--------------------------------------------------------------------------
    */

    public function test_auditee_tidak_dapat_menanggapi_temuan_user_lain(): void
    {
        $unit = $this->buatUnit();

        $admin = $this->buatUser(
            $unit,
            'admin',
            'admin@example.com'
        );

        $auditeeSatu = $this->buatUser(
            $unit,
            'auditee',
            'auditee1@example.com'
        );

        $auditeeDua = $this->buatUser(
            $unit,
            'auditee',
            'auditee2@example.com'
        );

        $data = $this->buatDataAudit(
            $unit,
            $admin
        );

        $penerapan = $this->buatPenerapan(
            $data,
            $auditeeSatu
        );

        $temuan = $this->buatTemuan(
            $penerapan
        );

        $response = $this
            ->loginSebagai($auditeeDua)
            ->post(
                route(
                    'auditee.tanggapan.store',
                    $temuan->id
                ),
                [
                    'tanggapan' =>
                        'Mencoba menanggapi data user lain.',
                ]
            );

        $response->assertStatus(404);

        $this->assertDatabaseCount(
            'tanggapan_auditee',
            0
        );
    }

    /*
    |--------------------------------------------------------------------------
    | TEST UPDATE TANGGAPAN
    |--------------------------------------------------------------------------
    */

    public function test_auditee_dapat_memperbarui_tanggapan_miliknya(): void
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

        $data = $this->buatDataAudit(
            $unit,
            $admin
        );

        $penerapan = $this->buatPenerapan(
            $data,
            $auditee
        );

        $temuan = $this->buatTemuan(
            $penerapan
        );

        $tanggapan = TanggapanAuditee::create([
            'id_temuan_ami' =>
                $temuan->id,

            'tanggapan' =>
                'Tanggapan lama.',

            'id_user' =>
                $auditee->id,
        ]);

        $response = $this
            ->loginSebagai($auditee)
            ->put(
                route(
                    'auditee.tanggapan.update',
                    $tanggapan->id
                ),
                [
                    'tanggapan' =>
                        'Tanggapan telah diperbarui.',
                ]
            );

        $response->assertRedirect(
            route(
                'auditee.temuan.show',
                $temuan->id
            )
        );

        $response->assertSessionHas(
            'success',
            'Tanggapan berhasil diperbarui.'
        );

        $this->assertDatabaseHas(
            'tanggapan_auditee',
            [
                'id' => $tanggapan->id,

                'tanggapan' =>
                    'Tanggapan telah diperbarui.',
            ]
        );
    }

    /*
    |--------------------------------------------------------------------------
    | TEST UPDATE TANGGAPAN USER LAIN
    |--------------------------------------------------------------------------
    */

    public function test_auditee_tidak_dapat_memperbarui_tanggapan_user_lain(): void
    {
        $unit = $this->buatUnit();

        $admin = $this->buatUser(
            $unit,
            'admin',
            'admin@example.com'
        );

        $auditeeSatu = $this->buatUser(
            $unit,
            'auditee',
            'auditee1@example.com'
        );

        $auditeeDua = $this->buatUser(
            $unit,
            'auditee',
            'auditee2@example.com'
        );

        $data = $this->buatDataAudit(
            $unit,
            $admin
        );

        $penerapan = $this->buatPenerapan(
            $data,
            $auditeeSatu
        );

        $temuan = $this->buatTemuan(
            $penerapan
        );

        $tanggapan = TanggapanAuditee::create([
            'id_temuan_ami' =>
                $temuan->id,

            'tanggapan' =>
                'Tanggapan milik Auditee satu.',

            'id_user' =>
                $auditeeSatu->id,
        ]);

        $response = $this
            ->loginSebagai($auditeeDua)
            ->put(
                route(
                    'auditee.tanggapan.update',
                    $tanggapan->id
                ),
                [
                    'tanggapan' =>
                        'Percobaan perubahan ilegal.',
                ]
            );

        $response->assertStatus(404);

        $this->assertDatabaseHas(
            'tanggapan_auditee',
            [
                'id' => $tanggapan->id,

                'tanggapan' =>
                    'Tanggapan milik Auditee satu.',
            ]
        );
    }

    /*
    |--------------------------------------------------------------------------
    | TEST HAPUS TANGGAPAN
    |--------------------------------------------------------------------------
    */

    public function test_auditee_dapat_menghapus_tanggapan_miliknya(): void
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

        $data = $this->buatDataAudit(
            $unit,
            $admin
        );

        $penerapan = $this->buatPenerapan(
            $data,
            $auditee
        );

        $temuan = $this->buatTemuan(
            $penerapan
        );

        $tanggapan = TanggapanAuditee::create([
            'id_temuan_ami' =>
                $temuan->id,

            'tanggapan' =>
                'Tanggapan yang akan dihapus.',

            'id_user' =>
                $auditee->id,
        ]);

        $response = $this
            ->loginSebagai($auditee)
            ->delete(
                route(
                    'auditee.tanggapan.destroy',
                    $tanggapan->id
                )
            );

        $response->assertRedirect(
            route(
                'auditee.temuan.show',
                $temuan->id
            )
        );

        $response->assertSessionHas(
            'success',
            'Tanggapan berhasil dihapus.'
        );

        $this->assertSoftDeleted(
            'tanggapan_auditee',
            [
                'id' => $tanggapan->id,
            ]
        );

        $this->assertDatabaseHas(
            'tanggapan_auditee',
            [
                'id' => $tanggapan->id,
            ]
        );

        $this->assertNotNull(
            TanggapanAuditee::withTrashed()
                ->findOrFail($tanggapan->id)
                ->deleted_at
        );
    }

    /*
    |--------------------------------------------------------------------------
    | TEST HAPUS TANGGAPAN USER LAIN
    |--------------------------------------------------------------------------
    */

    public function test_auditee_tidak_dapat_menghapus_tanggapan_user_lain(): void
    {
        $unit = $this->buatUnit();

        $admin = $this->buatUser(
            $unit,
            'admin',
            'admin@example.com'
        );

        $auditeeSatu = $this->buatUser(
            $unit,
            'auditee',
            'auditee1@example.com'
        );

        $auditeeDua = $this->buatUser(
            $unit,
            'auditee',
            'auditee2@example.com'
        );

        $data = $this->buatDataAudit(
            $unit,
            $admin
        );

        $penerapan = $this->buatPenerapan(
            $data,
            $auditeeSatu
        );

        $temuan = $this->buatTemuan(
            $penerapan
        );

        $tanggapan = TanggapanAuditee::create([
            'id_temuan_ami' =>
                $temuan->id,

            'tanggapan' =>
                'Tanggapan milik Auditee satu.',

            'id_user' =>
                $auditeeSatu->id,
        ]);

        $response = $this
            ->loginSebagai($auditeeDua)
            ->delete(
                route(
                    'auditee.tanggapan.destroy',
                    $tanggapan->id
                )
            );

        $response->assertStatus(404);

        $this->assertDatabaseHas(
            'tanggapan_auditee',
            [
                'id' => $tanggapan->id,
            ]
        );
    }
}