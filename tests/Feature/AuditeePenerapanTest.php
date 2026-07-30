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

class AuditeePenerapanTest extends TestCase
{
    use RefreshDatabase;

    private function buatUnit(
        string $nama
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

    private function buatDataStandar(
        UnitKerja $unit,
        User $user
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
            'id_unit_kerja' => $unit->id,
            'id_user' => $user->id,
            'tujuan_audit' => 'Menguji penerapan standar.',
            'lingkup_audit' => 'Program Studi',
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

    private function loginSebagai(
        User $user
    ): static {
        return $this->withSession([
            'user' => $user,
        ]);
    }

    public function test_auditee_dapat_menyimpan_penerapan_standar(): void
    {
        $unit = $this->buatUnit('Program Studi TRPL');

        $auditee = $this->buatUser(
            $unit,
            'auditee',
            'auditee@example.com'
        );

        $data = $this->buatDataStandar(
            $unit,
            $auditee
        );

        $response = $this
            ->loginSebagai($auditee)
            ->post(
                route('auditee.penerapan.store'),
                [
                    'id_standarmutu_periodeami' =>
                        $data['standar_periode']->id,

                    'id_indikator' =>
                        $data['indikator']->id,

                    'deskripsi_hasil' =>
                        'Penerapan telah dilaksanakan.',

                    'link_bukti' =>
                        'https://drive.google.com/bukti-testing',
                ]
            );

        $response->assertRedirect(
            route(
                'auditee.standar.index',
                $data['standar']->id
            )
        );

        $response->assertSessionHas('success');

        $this->assertDatabaseHas(
            'penerapan_standar',
            [
                'id_standarmutu_periodeami' =>
                    $data['standar_periode']->id,

                'id_indikator' =>
                    $data['indikator']->id,

                'id_user' => $auditee->id,

                'deskripsi_hasil' =>
                    'Penerapan telah dilaksanakan.',

                'link_bukti' =>
                    'https://drive.google.com/bukti-testing',
            ]
        );
    }

    public function test_deskripsi_hasil_wajib_diisi(): void
    {
        $unit = $this->buatUnit('Program Studi TRPL');

        $auditee = $this->buatUser(
            $unit,
            'auditee',
            'auditee@example.com'
        );

        $data = $this->buatDataStandar(
            $unit,
            $auditee
        );

        $response = $this
            ->loginSebagai($auditee)
            ->from('/auditee/penerapan')
            ->post(
                route('auditee.penerapan.store'),
                [
                    'id_standarmutu_periodeami' =>
                        $data['standar_periode']->id,

                    'id_indikator' =>
                        $data['indikator']->id,

                    'deskripsi_hasil' => '',

                    'link_bukti' =>
                        'https://example.com/bukti',
                ]
            );

        $response->assertSessionHasErrors([
            'deskripsi_hasil',
        ]);

        $this->assertDatabaseCount(
            'penerapan_standar',
            0
        );
    }

    public function test_link_bukti_harus_berupa_url(): void
    {
        $unit = $this->buatUnit('Program Studi TRPL');

        $auditee = $this->buatUser(
            $unit,
            'auditee',
            'auditee@example.com'
        );

        $data = $this->buatDataStandar(
            $unit,
            $auditee
        );

        $response = $this
            ->loginSebagai($auditee)
            ->from('/auditee/penerapan')
            ->post(
                route('auditee.penerapan.store'),
                [
                    'id_standarmutu_periodeami' =>
                        $data['standar_periode']->id,

                    'id_indikator' =>
                        $data['indikator']->id,

                    'deskripsi_hasil' =>
                        'Penerapan sudah dilakukan.',

                    'link_bukti' => 'bukan-url',
                ]
            );

        $response->assertSessionHasErrors([
            'link_bukti',
        ]);

        $this->assertDatabaseCount(
            'penerapan_standar',
            0
        );
    }

    public function test_penerapan_indikator_yang_sama_tidak_boleh_duplikat(): void
    {
        $unit = $this->buatUnit('Program Studi TRPL');

        $auditee = $this->buatUser(
            $unit,
            'auditee',
            'auditee@example.com'
        );

        $data = $this->buatDataStandar(
            $unit,
            $auditee
        );

        PenerapanStandar::create([
            'id_standarmutu_periodeami' =>
                $data['standar_periode']->id,

            'id_indikator' =>
                $data['indikator']->id,

            'deskripsi_hasil' =>
                'Penerapan pertama.',

            'link_bukti' =>
                'https://example.com/bukti-pertama',

            'id_user' => $auditee->id,
        ]);

        $response = $this
            ->loginSebagai($auditee)
            ->from('/auditee/penerapan')
            ->post(
                route('auditee.penerapan.store'),
                [
                    'id_standarmutu_periodeami' =>
                        $data['standar_periode']->id,

                    'id_indikator' =>
                        $data['indikator']->id,

                    'deskripsi_hasil' =>
                        'Penerapan kedua.',

                    'link_bukti' =>
                        'https://example.com/bukti-kedua',
                ]
            );

        $response->assertSessionHasErrors([
            'id_indikator',
        ]);

        $this->assertSame(
            1,
            PenerapanStandar::where(
                'id_user',
                $auditee->id
            )
                ->where(
                    'id_standarmutu_periodeami',
                    $data['standar_periode']->id
                )
                ->where(
                    'id_indikator',
                    $data['indikator']->id
                )
                ->count()
        );
    }

    public function test_auditee_dapat_memperbarui_penerapan_milik_sendiri(): void
    {
        $unit = $this->buatUnit('Program Studi TRPL');

        $auditee = $this->buatUser(
            $unit,
            'auditee',
            'auditee@example.com'
        );

        $data = $this->buatDataStandar(
            $unit,
            $auditee
        );

        $penerapan = PenerapanStandar::create([
            'id_standarmutu_periodeami' =>
                $data['standar_periode']->id,

            'id_indikator' =>
                $data['indikator']->id,

            'deskripsi_hasil' => 'Data lama',

            'link_bukti' =>
                'https://example.com/lama',

            'id_user' => $auditee->id,
        ]);

        $response = $this
            ->loginSebagai($auditee)
            ->put(
                route(
                    'auditee.penerapan.update',
                    $penerapan->id
                ),
                [
                    'deskripsi_hasil' =>
                        'Data penerapan diperbarui.',

                    'link_bukti' =>
                        'https://example.com/baru',
                ]
            );

        $response->assertRedirect(
            route(
                'auditee.standar.index',
                $data['standar']->id
            )
        );

        $this->assertDatabaseHas(
            'penerapan_standar',
            [
                'id' => $penerapan->id,

                'deskripsi_hasil' =>
                    'Data penerapan diperbarui.',

                'link_bukti' =>
                    'https://example.com/baru',
            ]
        );
    }

    public function test_auditee_dapat_menghapus_penerapan_milik_sendiri(): void
    {
        $unit = $this->buatUnit('Program Studi TRPL');

        $auditee = $this->buatUser(
            $unit,
            'auditee',
            'auditee@example.com'
        );

        $data = $this->buatDataStandar(
            $unit,
            $auditee
        );

        $penerapan = PenerapanStandar::create([
            'id_standarmutu_periodeami' =>
                $data['standar_periode']->id,

            'id_indikator' =>
                $data['indikator']->id,

            'deskripsi_hasil' => 'Akan dihapus',

            'link_bukti' =>
                'https://example.com/bukti',

            'id_user' => $auditee->id,
        ]);

        $response = $this
            ->loginSebagai($auditee)
            ->delete(
                route(
                    'auditee.penerapan.destroy',
                    $penerapan->id
                )
            );

        $response->assertRedirect(
            route(
                'auditee.standar.index',
                $data['standar']->id
            )
        );

        $this->assertSoftDeleted(
            'penerapan_standar',
            [
                'id' => $penerapan->id,
            ]
        );

        $this->assertDatabaseHas(
            'penerapan_standar',
            [
                'id' => $penerapan->id,
            ]
        );

        $this->assertNotNull(
            PenerapanStandar::withTrashed()
                ->findOrFail($penerapan->id)
                ->deleted_at
        );
    }

    public function test_auditee_tidak_dapat_memperbarui_penerapan_user_lain(): void
    {
        $unit = $this->buatUnit('Program Studi TRPL');

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

        $data = $this->buatDataStandar(
            $unit,
            $auditeeSatu
        );

        $penerapan = PenerapanStandar::create([
            'id_standarmutu_periodeami' =>
                $data['standar_periode']->id,

            'id_indikator' =>
                $data['indikator']->id,

            'deskripsi_hasil' =>
                'Penerapan milik Auditee satu.',

            'link_bukti' =>
                'https://example.com/bukti',

            'id_user' => $auditeeSatu->id,
        ]);

        $response = $this
            ->loginSebagai($auditeeDua)
            ->put(
                route(
                    'auditee.penerapan.update',
                    $penerapan->id
                ),
                [
                    'deskripsi_hasil' =>
                        'Dicoba diubah user lain.',

                    'link_bukti' =>
                        'https://example.com/perubahan',
                ]
            );

        $response->assertStatus(404);

        $this->assertDatabaseHas(
            'penerapan_standar',
            [
                'id' => $penerapan->id,

                'deskripsi_hasil' =>
                    'Penerapan milik Auditee satu.',
            ]
        );
    }

    public function test_auditee_tidak_dapat_menghapus_penerapan_user_lain(): void
    {
        $unit = $this->buatUnit('Program Studi TRPL');

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

        $data = $this->buatDataStandar(
            $unit,
            $auditeeSatu
        );

        $penerapan = PenerapanStandar::create([
            'id_standarmutu_periodeami' =>
                $data['standar_periode']->id,

            'id_indikator' =>
                $data['indikator']->id,

            'deskripsi_hasil' =>
                'Penerapan milik Auditee satu.',

            'link_bukti' =>
                'https://example.com/bukti',

            'id_user' => $auditeeSatu->id,
        ]);

        $response = $this
            ->loginSebagai($auditeeDua)
            ->delete(
                route(
                    'auditee.penerapan.destroy',
                    $penerapan->id
                )
            );

        $response->assertStatus(404);

        $this->assertDatabaseHas(
            'penerapan_standar',
            [
                'id' => $penerapan->id,
            ]
        );
    }

    public function test_auditee_tidak_dapat_menggunakan_periode_unit_lain(): void
    {
        $unitSatu = $this->buatUnit(
            'Program Studi TRPL'
        );

        $unitDua = $this->buatUnit(
            'Program Studi Teknik Mesin'
        );

        $auditeeSatu = $this->buatUser(
            $unitSatu,
            'auditee',
            'auditee1@example.com'
        );

        $auditeeDua = $this->buatUser(
            $unitDua,
            'auditee',
            'auditee2@example.com'
        );

        $dataUnitDua = $this->buatDataStandar(
            $unitDua,
            $auditeeDua
        );

        $response = $this
            ->loginSebagai($auditeeSatu)
            ->post(
                route('auditee.penerapan.store'),
                [
                    'id_standarmutu_periodeami' =>
                        $dataUnitDua['standar_periode']->id,

                    'id_indikator' =>
                        $dataUnitDua['indikator']->id,

                    'deskripsi_hasil' =>
                        'Mencoba periode unit lain.',

                    'link_bukti' =>
                        'https://example.com/bukti',
                ]
            );

        $response->assertStatus(404);

        $this->assertDatabaseCount(
            'penerapan_standar',
            0
        );
    }
}
