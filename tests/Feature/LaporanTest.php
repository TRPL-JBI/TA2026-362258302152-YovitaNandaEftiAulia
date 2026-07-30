<?php

namespace Tests\Feature;

use App\Models\AkarMasalah;
use App\Models\IndikatorStandar;
use App\Models\IsiStandarMutu;
use App\Models\PenerapanStandar;
use App\Models\PeriodeAmi;
use App\Models\StandarMutu;
use App\Models\StandarMutuPeriodeAmi;
use App\Models\TanggapanAuditee;
use App\Models\TemuanAmi;
use App\Models\TimAmi;
use App\Models\UnitKerja;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class LaporanTest extends TestCase
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
    | HELPER DATA LAPORAN
    |--------------------------------------------------------------------------
    */

    private function buatDataLaporan(): array
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

        $standar = StandarMutu::create([
            'nama_standar_mutu' =>
                'Standar Mutu Permendiktisaintek',
        ]);

        $isiStandar = IsiStandarMutu::create([
            'id_standar_mutu' =>
                $standar->id,

            'nama_standar' =>
                'Standar Pendidikan',

            'parent_standar_id' =>
                null,
        ]);

        $indikator = IndikatorStandar::create([
            'id_isi_standar_mutu' =>
                $isiStandar->id,

            'deskripsi' =>
                'Tersedia bukti penerapan standar.',
        ]);

        $periode = PeriodeAmi::create([
            'tahun' => 2026,

            'id_standar_mutu' =>
                $standar->id,

            'id_unit_kerja' =>
                $unit->id,

            'id_user' =>
                $admin->id,

            'tujuan_audit' =>
                'Menguji kelengkapan laporan.',

            'lingkup_audit' =>
                'Program Studi',

            'waktu_audit' =>
                '08.00',

            'tanggal_buka_ami' =>
                '2026-07-01',

            'tanggal_tutup_ami' =>
                '2026-07-31',

            'status' =>
                'berjalan',
        ]);

        $standarPeriode =
            StandarMutuPeriodeAmi::create([
                'id_standar_mutu' =>
                    $standar->id,

                'id_periode_ami' =>
                    $periode->id,

                'status' =>
                    'aktif',
            ]);

        TimAmi::create([
            'id_periode_ami' =>
                $periode->id,

            'id_user' =>
                $auditor->id,

            'role' =>
                'auditor',
        ]);

        $penerapan = PenerapanStandar::create([
            'id_standarmutu_periodeami' =>
                $standarPeriode->id,

            'id_indikator' =>
                $indikator->id,

            'deskripsi_hasil' =>
                'Penerapan telah dilaksanakan.',

            'link_bukti' =>
                'https://example.com/bukti',

            'id_user' =>
                $auditee->id,
        ]);

        $temuan = TemuanAmi::create([
            'id_penerapan_standar' =>
                $penerapan->id,

            'temuan' =>
                'Dokumen bukti belum disahkan.',

            'status_temuan' =>
                'open',
        ]);

        $tanggapan = TanggapanAuditee::create([
            'id_temuan_ami' =>
                $temuan->id,

            'tanggapan' =>
                'Dokumen akan segera disahkan.',

            'id_user' =>
                $auditee->id,
        ]);

        $akarMasalah = AkarMasalah::create([
            'id_temuan' =>
                $temuan->id,

            'akar_masalah' =>
                'Proses pengesahan belum selesai.',

            'id_user' =>
                $auditor->id,
        ]);

        return compact(
            'unit',
            'admin',
            'auditee',
            'auditor',
            'standar',
            'isiStandar',
            'indikator',
            'periode',
            'standarPeriode',
            'penerapan',
            'temuan',
            'tanggapan',
            'akarMasalah'
        );
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
    | LAPORAN AUDITOR
    |--------------------------------------------------------------------------
    */

    public function test_auditor_dapat_membuka_daftar_laporan(): void
    {
        $data = $this->buatDataLaporan();

        $response = $this
            ->loginSebagai($data['auditor'])
            ->get(
                route('auditor.laporan.index')
            );

        $response->assertStatus(200);
    }

    public function test_auditor_dapat_membuka_detail_laporan(): void
    {
        $data = $this->buatDataLaporan();

        $response = $this
            ->loginSebagai($data['auditor'])
            ->get(
                route(
                    'auditor.laporan.show',
                    $data['periode']->id
                )
            );

        $response->assertStatus(200);
    }

    public function test_detail_laporan_auditor_menghasilkan_pdf(): void
{
    $data = $this->buatDataLaporan();

    $response = $this
        ->loginSebagai($data['auditor'])
        ->get(
            route(
                'auditor.laporan.show',
                $data['periode']->id
            )
        );

    $response->assertStatus(200);

    $contentType = strtolower(
        (string) $response
            ->headers
            ->get('content-type')
    );

    $this->assertStringContainsString(
        'application/pdf',
        $contentType
    );
}

    /*
    |--------------------------------------------------------------------------
    | LAPORAN ADMIN
    |--------------------------------------------------------------------------
    */

    public function test_admin_dapat_membuka_daftar_laporan(): void
    {
        $data = $this->buatDataLaporan();

        $response = $this
            ->loginSebagai($data['admin'])
            ->get(
                route('laporan.index')
            );

        $response->assertStatus(200);
    }

    public function test_pdf_laporan_admin_dengan_periode_tidak_ada_menghasilkan_404(): void
    {
        $data = $this->buatDataLaporan();

        $response = $this
            ->loginSebagai($data['admin'])
            ->get(
                route(
                    'laporan.pdf',
                    999999
                )
            );

        $response->assertStatus(404);
    }

    public function test_admin_dapat_membuka_pdf_laporan(): void
    {
        $data = $this->buatDataLaporan();

        $response = $this
            ->loginSebagai($data['admin'])
            ->get(
                route(
                    'laporan.pdf',
                    $data['periode']->id
                )
            );

        $response->assertStatus(200);

        $contentType = strtolower(
            (string) $response
                ->headers
                ->get('content-type')
        );

        $this->assertStringContainsString(
            'application/pdf',
            $contentType
        );
    }

    /*
    |--------------------------------------------------------------------------
    | RELASI DATA LAPORAN
    |--------------------------------------------------------------------------
    */

    public function test_data_laporan_memiliki_relasi_audit_lengkap(): void
    {
        $data = $this->buatDataLaporan();

        $periode = PeriodeAmi::with([
            'standarMutuPeriode',
            'standarMutuPeriode.penerapanStandar',
            'standarMutuPeriode.penerapanStandar.temuan',
            'standarMutuPeriode.penerapanStandar.temuan.tanggapan',
            'standarMutuPeriode.penerapanStandar.temuan.akarMasalah',
        ])->findOrFail(
            $data['periode']->id
        );

        $standarPeriode = $periode
            ->standarMutuPeriode
            ->first();

        $this->assertNotNull(
            $standarPeriode
        );

        $penerapan = $standarPeriode
            ->penerapanStandar
            ->first();

        $this->assertNotNull(
            $penerapan
        );

        $this->assertSame(
            $data['penerapan']->id,
            $penerapan->id
        );

        $temuan = $penerapan
            ->temuan
            ->first();

        $this->assertNotNull(
            $temuan
        );

        $this->assertSame(
            $data['temuan']->id,
            $temuan->id
        );

        $this->assertCount(
            1,
            $temuan->tanggapan
        );

        $this->assertSame(
            $data['tanggapan']->id,
            $temuan->tanggapan->first()->id
        );

        $this->assertCount(
            1,
            $temuan->akarMasalah
        );

        $this->assertSame(
            $data['akarMasalah']->id,
            $temuan->akarMasalah->first()->id
        );
    }

    /*
    |--------------------------------------------------------------------------
    | DATA TIDAK DITEMUKAN
    |--------------------------------------------------------------------------
    */

    public function test_periode_laporan_yang_tidak_ada_menghasilkan_404(): void
    {
        $data = $this->buatDataLaporan();

        $response = $this
            ->loginSebagai($data['auditor'])
            ->get(
                route(
                    'auditor.laporan.show',
                    999999
                )
            );

        $response->assertStatus(404);
    }

    /*
    |--------------------------------------------------------------------------
    | PEMBATASAN AKSES AUDITEE
    |--------------------------------------------------------------------------
    */

    public function test_auditee_tidak_dapat_membuka_laporan_auditor(): void
    {
        $data = $this->buatDataLaporan();

        $response = $this
            ->loginSebagai($data['auditee'])
            ->get(
                route('auditor.laporan.index')
            );

        $response->assertStatus(403);
    }

    public function test_auditee_tidak_dapat_membuka_laporan_admin(): void
    {
        $data = $this->buatDataLaporan();

        $response = $this
            ->loginSebagai($data['auditee'])
            ->get(
                route('laporan.index')
            );

        $response->assertStatus(403);
    }
}