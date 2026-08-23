<?php

namespace Tests\Feature;

use App\Models\IndikatorStandar;
use App\Models\IsiStandarMutu;
use App\Models\PenerapanStandar;
use App\Models\PeriodeAmi;
use App\Models\StandarMutu;
use App\Models\StandarMutuPeriodeAmi;
use App\Models\UnitKerja;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class PeriodeAmiCloseTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Membuat unit kerja untuk kebutuhan data periode AMI.
     */
    private function buatUnit(
        string $nama = 'Program Studi TRPL'
    ): UnitKerja {
        return UnitKerja::forceCreate([
            'nama' => $nama,
            'kategori_unit_kerja' => 'akademik',
        ]);
    }

    /**
     * Membuat user untuk kebutuhan data periode AMI.
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

    /**
     * Membuat data dasar AMI.
     */
    private function buatDataDasar(): array
    {
        $unit = $this->buatUnit();

        $admin = $this->buatUser(
            $unit,
            'admin',
            'admin-periode-ami@example.com'
        );

        $auditee = $this->buatUser(
            $unit,
            'auditee',
            'auditee-periode-ami@example.com'
        );

        $standar = StandarMutu::create([
            'nama_standar_mutu' =>
                'Standar Mutu Permendiktisaintek',
        ]);

        $isiStandar = IsiStandarMutu::create([
            'id_standar_mutu' => $standar->id,
            'nama_standar' => 'Standar Pendidikan',
            'parent_standar_id' => null,
        ]);

        $periode = PeriodeAmi::create([
            'tahun' => 2026,
            'id_standar_mutu' => $standar->id,
            'id_unit_kerja' => $unit->id,
            'id_user' => $admin->id,
            'tujuan_audit' =>
                'Memastikan penerapan standar berjalan.',
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
            'unit' => $unit,
            'admin' => $admin,
            'auditee' => $auditee,
            'standar' => $standar,
            'isi_standar' => $isiStandar,
            'periode' => $periode,
            'standar_periode' => $standarPeriode,
        ];
    }

    /**
     * Membuat indikator pada isi standar.
     */
    private function buatIndikator(
        IsiStandarMutu $isiStandar,
        string $deskripsi
    ): IndikatorStandar {
        return IndikatorStandar::create([
            'id_isi_standar_mutu' => $isiStandar->id,
            'deskripsi' => $deskripsi,
        ]);
    }

    /**
     * Membuat penerapan untuk suatu indikator.
     */
    private function buatPenerapan(
        StandarMutuPeriodeAmi $standarPeriode,
        IndikatorStandar $indikator,
        User $auditee
    ): PenerapanStandar {
        return PenerapanStandar::create([
            'id_standarmutu_periodeami' =>
                $standarPeriode->id,
            'id_indikator' => $indikator->id,
            'deskripsi_hasil' =>
                'Penerapan standar telah dilakukan.',
            'link_bukti' =>
                'https://example.com/bukti',
            'id_user' => $auditee->id,
        ]);
    }

    /**
     * Test 1:
     * Sistem dapat menghitung seluruh indikator wajib
     * berdasarkan Standar Mutu yang digunakan periode AMI.
     */
    public function test_indikator_wajib_dapat_dihitung(): void
    {
        $data = $this->buatDataDasar();

        $this->buatIndikator(
            $data['isi_standar'],
            'Indikator 1'
        );

        $this->buatIndikator(
            $data['isi_standar'],
            'Indikator 2'
        );

        $this->buatIndikator(
            $data['isi_standar'],
            'Indikator 3'
        );

        $totalIndikatorWajib =
            IndikatorStandar::query()
                ->whereHas(
                    'isiStandar',
                    function ($query) use ($data) {
                        $query->where(
                            'id_standar_mutu',
                            $data['periode']->id_standar_mutu
                        );
                    }
                )
                ->count();

        $this->assertSame(
            3,
            $totalIndikatorWajib
        );
    }

    /**
     * Test 2:
     * Satu indikator yang memiliki beberapa penerapan
     * tetap dihitung sebagai satu indikator.
     */
    public function test_indikator_terisi_dihitung_unik(): void
    {
        $data = $this->buatDataDasar();

        $indikator = $this->buatIndikator(
            $data['isi_standar'],
            'Indikator 1'
        );

        $this->buatPenerapan(
            $data['standar_periode'],
            $indikator,
            $data['auditee']
        );

        $this->buatPenerapan(
            $data['standar_periode'],
            $indikator,
            $data['auditee']
        );

        $totalIndikatorTerisi =
            PenerapanStandar::query()
                ->whereHas(
                    'standarmutuPeriode',
                    function ($query) use ($data) {
                        $query->where(
                            'id_periode_ami',
                            $data['periode']->id
                        );
                    }
                )
                ->whereNotNull('id_indikator')
                ->distinct()
                ->count('id_indikator');

        $this->assertSame(
            1,
            $totalIndikatorTerisi
        );
    }

    /**
     * Test 3:
     *
     * Ada 3 indikator wajib,
     * tetapi baru 1 indikator yang memiliki penerapan.
     *
     * Hasil:
     * periode BELUM LENGKAP dan tidak boleh ditutup.
     */
    public function test_periode_tidak_lengkap_jika_indikator_belum_semua_memiliki_penerapan(): void
    {
        $data = $this->buatDataDasar();

        $indikator1 = $this->buatIndikator(
            $data['isi_standar'],
            'Indikator 1'
        );

        $this->buatIndikator(
            $data['isi_standar'],
            'Indikator 2'
        );

        $this->buatIndikator(
            $data['isi_standar'],
            'Indikator 3'
        );

        /*
         * Hanya indikator pertama yang memiliki penerapan.
         */
        $this->buatPenerapan(
            $data['standar_periode'],
            $indikator1,
            $data['auditee']
        );

        $totalIndikatorWajib =
            IndikatorStandar::query()
                ->whereHas(
                    'isiStandar',
                    function ($query) use ($data) {
                        $query->where(
                            'id_standar_mutu',
                            $data['periode']->id_standar_mutu
                        );
                    }
                )
                ->count();

        $totalIndikatorTerisi =
            PenerapanStandar::query()
                ->whereHas(
                    'standarmutuPeriode',
                    function ($query) use ($data) {
                        $query->where(
                            'id_periode_ami',
                            $data['periode']->id
                        );
                    }
                )
                ->whereNotNull('id_indikator')
                ->distinct()
                ->count('id_indikator');

        $this->assertSame(
            3,
            $totalIndikatorWajib
        );

        $this->assertSame(
            1,
            $totalIndikatorTerisi
        );

        $this->assertTrue(
            $totalIndikatorTerisi < $totalIndikatorWajib
        );

        $jumlahIndikatorBelumDiisi =
            $totalIndikatorWajib -
            $totalIndikatorTerisi;

        $this->assertSame(
            2,
            $jumlahIndikatorBelumDiisi
        );
    }

    /**
     * Test 4:
     *
     * Semua indikator wajib sudah memiliki penerapan.
     *
     * Hasil:
     * validasi cakupan indikator dinyatakan lengkap.
     */
    public function test_periode_lengkap_jika_semua_indikator_memiliki_penerapan(): void
    {
        $data = $this->buatDataDasar();

        $indikator1 = $this->buatIndikator(
            $data['isi_standar'],
            'Indikator 1'
        );

        $indikator2 = $this->buatIndikator(
            $data['isi_standar'],
            'Indikator 2'
        );

        $indikator3 = $this->buatIndikator(
            $data['isi_standar'],
            'Indikator 3'
        );

        /*
         * Semua indikator sudah memiliki penerapan.
         */
        $this->buatPenerapan(
            $data['standar_periode'],
            $indikator1,
            $data['auditee']
        );

        $this->buatPenerapan(
            $data['standar_periode'],
            $indikator2,
            $data['auditee']
        );

        $this->buatPenerapan(
            $data['standar_periode'],
            $indikator3,
            $data['auditee']
        );

        $totalIndikatorWajib =
            IndikatorStandar::query()
                ->whereHas(
                    'isiStandar',
                    function ($query) use ($data) {
                        $query->where(
                            'id_standar_mutu',
                            $data['periode']->id_standar_mutu
                        );
                    }
                )
                ->count();

        $totalIndikatorTerisi =
            PenerapanStandar::query()
                ->whereHas(
                    'standarmutuPeriode',
                    function ($query) use ($data) {
                        $query->where(
                            'id_periode_ami',
                            $data['periode']->id
                        );
                    }
                )
                ->whereNotNull('id_indikator')
                ->distinct()
                ->count('id_indikator');

        $this->assertSame(
            3,
            $totalIndikatorWajib
        );

        $this->assertSame(
            3,
            $totalIndikatorTerisi
        );

        $this->assertSame(
            $totalIndikatorWajib,
            $totalIndikatorTerisi
        );
    }
}