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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class AuditorTemuanTest extends TestCase
{
    use RefreshDatabase;

    private function buatUnit(string $nama = 'Program Studi TRPL'): UnitKerja
    {
        return UnitKerja::forceCreate([
            'nama' => $nama,
            'kategori_unit_kerja' => 'akademik',
        ]);
    }

    private function buatUser(UnitKerja $unit, string $role, string $email): User
    {
        return User::create([
            'nama' => 'User ' . ucfirst($role),
            'email' => $email,
            'password' => Hash::make('Password@123'),
            'id_unit_kerja' => $unit->id,
            'role' => $role,
            'status' => 'aktif',
        ]);
    }

    private function buatDataAudit(User $admin, User $auditee): array
    {
        $standar = StandarMutu::create([
            'nama_standar_mutu' => 'Standar Mutu Permendiktisaintek',
        ]);

        $isiStandar = IsiStandarMutu::create([
            'id_standar_mutu' => $standar->id,
            'nama_standar' => 'Standar Pendidikan',
            'parent_standar_id' => null,
        ]);

        $indikator = IndikatorStandar::create([
            'id_isi_standar_mutu' => $isiStandar->id,
            'deskripsi' => 'Program studi memiliki bukti penerapan standar.',
        ]);

        $periode = PeriodeAmi::create([
            'tahun' => 2026,
            'id_standar_mutu' => $standar->id,
            'id_unit_kerja' => $auditee->id_unit_kerja,
            'id_user' => $admin->id,
            'tujuan_audit' => 'Memastikan penerapan standar berjalan.',
            'lingkup_audit' => 'Program Studi',
            'waktu_audit' => '08.00',
            'tanggal_buka_ami' => '2026-07-01',
            'tanggal_tutup_ami' => '2026-07-31',
            'status' => 'berjalan',
        ]);

        $standarPeriode = StandarMutuPeriodeAmi::create([
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
            'id_standarmutu_periodeami' => $data['standar_periode']->id,
            'id_indikator' => $data['indikator']->id,
            'deskripsi_hasil' => $deskripsi,
            'link_bukti' => $linkBukti,
            'id_user' => $auditee->id,
        ]);
    }

    private function tugaskanAuditor(User $auditor, PeriodeAmi $periode): TimAmi
    {
        return TimAmi::create([
            'id_periode_ami' => $periode->id,
            'id_user' => $auditor->id,
            'role' => 'auditor',
        ]);
    }

    private function loginSebagai(User $user): static
    {
        return $this->withSession([
            'user_id' => $user->id,
        ]);
    }

    private function idSkalaSkor(): int
    {
        foreach (['skala_skor', 'skala_skor_audit', 'skala_penilaian'] as $tabel) {
            if (!Schema::hasTable($tabel)) {
                continue;
            }

            $idTersedia = DB::table($tabel)
                ->orderBy('id')
                ->value('id');

            if ($idTersedia) {
                return (int) $idTersedia;
            }

            $kolom = Schema::getColumnListing($tabel);
            $data = [];

            if (in_array('nama', $kolom, true)) {
                $data['nama'] = 'Sangat Baik';
            }

            if (in_array('nama_skala', $kolom, true)) {
                $data['nama_skala'] = 'Sangat Baik';
            }

            // PERBAIKAN REVISI:
            // Kolom label_skor wajib diisi pada tabel skala_skor.
            if (in_array('label_skor', $kolom, true)) {
                $data['label_skor'] = 'Sangat Baik';
            }

            if (in_array('keterangan', $kolom, true)) {
                $data['keterangan'] = 'Penerapan sangat baik';
            }

            if (in_array('deskripsi', $kolom, true)) {
                $data['deskripsi'] = 'Penerapan sangat baik';
            }

            if (in_array('nilai_skor', $kolom, true)) {
                $data['nilai_skor'] = 4;
            }

            if (in_array('skor', $kolom, true)) {
                $data['skor'] = 4;
            }

            if (in_array('nilai', $kolom, true)) {
                $data['nilai'] = 4;
            }

            if (in_array('created_at', $kolom, true)) {
                $data['created_at'] = now();
            }

            if (in_array('updated_at', $kolom, true)) {
                $data['updated_at'] = now();
            }

            return (int) DB::table($tabel)->insertGetId($data);
        }

        $this->fail('Tabel skala skor tidak ditemukan.');

        return 0;
    }

    private function dataPenilaian(
        PenerapanStandar $penerapan,
        array $tambahan = []
    ): array {
        return array_merge([
            'id_penerapan_standar' => $penerapan->id,
            'status_penerapan' => 'belum_sesuai',
            'id_skala_skor' => $this->idSkalaSkor(),
            'jenis_temuan' => 'kts',
            'temuan' => 'Dokumen bukti belum disahkan.',
            'aspek' => 'Kelengkapan dokumen',
            'deskripsi' => 'Dokumen belum memenuhi ketentuan.',
            'rekomendasi' => 'Auditee perlu melengkapi dan mengesahkan dokumen.',
        ], $tambahan);
    }

    private function siapkanAudit(): array
    {
        $unit = $this->buatUnit();
        $admin = $this->buatUser($unit, 'admin', 'admin@example.com');
        $auditee = $this->buatUser($unit, 'auditee', 'auditee@example.com');
        $auditor = $this->buatUser($unit, 'auditor', 'auditor@example.com');
        $data = $this->buatDataAudit($admin, $auditee);

        return compact('unit', 'admin', 'auditee', 'auditor', 'data');
    }

    public function test_auditor_dapat_membuka_daftar_temuan(): void
    {
        $setup = $this->siapkanAudit();

        $this->tugaskanAuditor(
            $setup['auditor'],
            $setup['data']['periode']
        );

        $this->buatPenerapan(
            $setup['data'],
            $setup['auditee']
        );

        $this->loginSebagai($setup['auditor'])
            ->get(route('auditor.temuan.index'))
            ->assertOk();
    }

    public function test_auditor_dapat_membuka_form_temuan_jika_penerapan_lengkap(): void
    {
        $setup = $this->siapkanAudit();

        $this->tugaskanAuditor(
            $setup['auditor'],
            $setup['data']['periode']
        );

        $penerapan = $this->buatPenerapan(
            $setup['data'],
            $setup['auditee']
        );

        $this->loginSebagai($setup['auditor'])
            ->get(route('auditor.temuan.create', $penerapan->id))
            ->assertOk();
    }

    public function test_form_temuan_ditolak_jika_deskripsi_penerapan_kosong(): void
    {
        $setup = $this->siapkanAudit();

        $this->tugaskanAuditor(
            $setup['auditor'],
            $setup['data']['periode']
        );

        $penerapan = $this->buatPenerapan(
            $setup['data'],
            $setup['auditee'],
            '',
            'https://example.com/bukti'
        );

        $this->loginSebagai($setup['auditor'])
            ->get(route('auditor.temuan.create', $penerapan->id))
            ->assertForbidden();
    }

    public function test_form_temuan_ditolak_jika_link_bukti_kosong(): void
    {
        $setup = $this->siapkanAudit();

        $this->tugaskanAuditor(
            $setup['auditor'],
            $setup['data']['periode']
        );

        $penerapan = $this->buatPenerapan(
            $setup['data'],
            $setup['auditee'],
            'Penerapan sudah dilakukan.',
            null
        );

        $this->loginSebagai($setup['auditor'])
            ->get(route('auditor.temuan.create', $penerapan->id))
            ->assertForbidden();
    }

    public function test_auditor_dapat_menyimpan_temuan(): void
    {
        $setup = $this->siapkanAudit();

        $this->tugaskanAuditor(
            $setup['auditor'],
            $setup['data']['periode']
        );

        $penerapan = $this->buatPenerapan(
            $setup['data'],
            $setup['auditee']
        );

        $response = $this->loginSebagai($setup['auditor'])
            ->post(
                route('auditor.temuan.store'),
                $this->dataPenilaian($penerapan)
            );

        $temuan = TemuanAmi::query()->firstOrFail();

        $response
            ->assertRedirect(
                route('auditor.temuan.show', $temuan->id)
            )
            ->assertSessionHas('success');

        $this->assertDatabaseHas('temuan_ami', [
            'id' => $temuan->id,
            'id_penerapan_standar' => $penerapan->id,
            'temuan' => 'Dokumen bukti belum disahkan.',
            'status_temuan' => 'open',
        ]);

        // Revisi 8:
        // Menggunakan tabel rekomendasi yang baru.
        $this->assertDatabaseHas('rekomendasi', [
            'id_temuan' => $temuan->id,
            'aspek' => 'Kelengkapan dokumen',
        ]);
    }

    public function test_isi_temuan_wajib_diisi_untuk_jenis_kts(): void
    {
        $setup = $this->siapkanAudit();

        $this->tugaskanAuditor(
            $setup['auditor'],
            $setup['data']['periode']
        );

        $penerapan = $this->buatPenerapan(
            $setup['data'],
            $setup['auditee']
        );

        $response = $this->loginSebagai($setup['auditor'])
            ->from(
                route(
                    'auditor.temuan.create',
                    $penerapan->id
                )
            )
            ->post(
                route('auditor.temuan.store'),
                $this->dataPenilaian(
                    $penerapan,
                    ['temuan' => '']
                )
            );

        $response->assertSessionHasErrors('temuan');

        $this->assertDatabaseCount(
            'temuan_ami',
            0
        );
    }

    public function test_status_temuan_otomatis_open_saat_disimpan(): void
    {
        $setup = $this->siapkanAudit();

        $this->tugaskanAuditor(
            $setup['auditor'],
            $setup['data']['periode']
        );

        $penerapan = $this->buatPenerapan(
            $setup['data'],
            $setup['auditee']
        );

        $this->loginSebagai($setup['auditor'])
            ->post(
                route('auditor.temuan.store'),
                $this->dataPenilaian(
                    $penerapan,
                    ['status_temuan' => 'closed']
                )
            );

        $this->assertDatabaseHas('temuan_ami', [
            'id_penerapan_standar' => $penerapan->id,
            'status_temuan' => 'open',
        ]);

        $this->assertDatabaseMissing('temuan_ami', [
            'id_penerapan_standar' => $penerapan->id,
            'status_temuan' => 'closed',
        ]);
    }

    public function test_auditor_tidak_dapat_membuat_temuan_pada_periode_yang_bukan_penugasannya(): void
    {
        $setup = $this->siapkanAudit();

        $penerapan = $this->buatPenerapan(
            $setup['data'],
            $setup['auditee']
        );

        $this->loginSebagai($setup['auditor'])
            ->post(
                route('auditor.temuan.store'),
                $this->dataPenilaian($penerapan)
            )
            ->assertNotFound();

        $this->assertDatabaseCount(
            'temuan_ami',
            0
        );
    }

    public function test_auditor_dapat_memperbarui_temuan_dan_status_tetap_open(): void
    {
        $setup = $this->siapkanAudit();

        $this->tugaskanAuditor(
            $setup['auditor'],
            $setup['data']['periode']
        );

        $penerapan = $this->buatPenerapan(
            $setup['data'],
            $setup['auditee']
        );

        $temuan = TemuanAmi::create([
            'id_penerapan_standar' => $penerapan->id,
            'temuan' => 'Temuan awal.',
            'status_temuan' => 'open',
        ]);

        $this->loginSebagai($setup['auditor'])
            ->put(
                route(
                    'auditor.temuan.update',
                    $temuan->id
                ),
                [
                    'temuan' => 'Temuan sudah diperbarui.',
                    'jenis_temuan' => 'kts',
                    'status_temuan' => 'closed',
                ]
            )
            ->assertRedirect(
                route(
                    'auditor.temuan.show',
                    $temuan->id
                )
            );

        $this->assertDatabaseHas('temuan_ami', [
            'id' => $temuan->id,
            'temuan' => 'Temuan sudah diperbarui.',
            'status_temuan' => 'open',
        ]);
    }

    public function test_auditor_dapat_menghapus_temuan(): void
    {
        $setup = $this->siapkanAudit();

        $this->tugaskanAuditor(
            $setup['auditor'],
            $setup['data']['periode']
        );

        $penerapan = $this->buatPenerapan(
            $setup['data'],
            $setup['auditee']
        );

        $temuan = TemuanAmi::create([
            'id_penerapan_standar' => $penerapan->id,
            'temuan' => 'Temuan yang akan dihapus.',
            'status_temuan' => 'open',
        ]);

        $this->loginSebagai($setup['auditor'])
            ->delete(
                route(
                    'auditor.temuan.destroy',
                    $temuan->id
                )
            )
            ->assertRedirect(
                route('auditor.temuan.index')
            );

        $this->assertSoftDeleted('temuan_ami', [
            'id' => $temuan->id,
        ]);
    }

    public function test_auditor_tidak_dapat_memperbarui_temuan_dari_periode_lain(): void
    {
        $setup = $this->siapkanAudit();

        $penerapan = $this->buatPenerapan(
            $setup['data'],
            $setup['auditee']
        );

        $temuan = TemuanAmi::create([
            'id_penerapan_standar' => $penerapan->id,
            'temuan' => 'Temuan milik periode lain.',
            'status_temuan' => 'open',
        ]);

        $this->loginSebagai($setup['auditor'])
            ->put(
                route(
                    'auditor.temuan.update',
                    $temuan->id
                ),
                [
                    'temuan' => 'Percobaan perubahan ilegal.',
                    'jenis_temuan' => 'kts',
                ]
            )
            ->assertNotFound();

        $this->assertDatabaseHas('temuan_ami', [
            'id' => $temuan->id,
            'temuan' => 'Temuan milik periode lain.',
            'status_temuan' => 'open',
        ]);
    }
}