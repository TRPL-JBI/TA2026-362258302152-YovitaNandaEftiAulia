<?php

namespace Tests\Feature;

use App\Models\TemuanAmi;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class AuditorTemuanVerifikasiTest extends TestCase
{
    use RefreshDatabase;

    /*
    |--------------------------------------------------------------------------
    | 1. STRUKTUR DATABASE
    |--------------------------------------------------------------------------
    */

    public function test_temuan_ami_memiliki_field_verifikasi_formal(): void
    {
        $this->assertTrue(
            Schema::hasColumns(
                'temuan_ami',
                [
                    'verified_by',
                    'verified_at',
                    'closed_by',
                    'closed_at',
                    'verification_note',
                ]
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | 2. MODEL
    |--------------------------------------------------------------------------
    */

    public function test_model_temuan_ami_menyimpan_field_verifikasi(): void
    {
        $model = new TemuanAmi();

        $fillable = $model->getFillable();

        $this->assertContains(
            'verified_by',
            $fillable
        );

        $this->assertContains(
            'verified_at',
            $fillable
        );

        $this->assertContains(
            'closed_by',
            $fillable
        );

        $this->assertContains(
            'closed_at',
            $fillable
        );

        $this->assertContains(
            'verification_note',
            $fillable
        );
    }

    /*
    |--------------------------------------------------------------------------
    | 3. DATA VERIFIKASI DAPAT DISIMPAN
    |--------------------------------------------------------------------------
    */

    public function test_temuan_dapat_menyimpan_data_verifikasi_formal(): void
    {
        $temuan = TemuanAmi::create([
            'id_penerapan_standar' => null,
            'jenis_temuan' => 'kts',
            'temuan' => 'Temuan untuk pengujian verifikasi formal.',
            'status_temuan' => 'open',
        ]);

        $verifiedAt = now();
        $closedAt = now();

        /*
        |--------------------------------------------------------------------------
        | Gunakan ID user dummy.
        |--------------------------------------------------------------------------
        |
        | Test ini fokus pada mekanisme penyimpanan data verifikasi
        | pada temuan, bukan pada UserFactory.
        |
        */

        $userId = 1;

        $temuan->update([
            'status_temuan' => 'closed',

            'verified_by' => $userId,
            'verified_at' => $verifiedAt,

            'closed_by' => $userId,
            'closed_at' => $closedAt,

            'verification_note' =>
                'Temuan telah diverifikasi dan dinyatakan selesai oleh auditor.',
        ]);

        $temuan->refresh();

        $this->assertSame(
            'closed',
            $temuan->status_temuan
        );

        $this->assertSame(
            $userId,
            (int) $temuan->verified_by
        );

        $this->assertNotNull(
            $temuan->verified_at
        );

        $this->assertSame(
            $userId,
            (int) $temuan->closed_by
        );

        $this->assertNotNull(
            $temuan->closed_at
        );

        $this->assertSame(
            'Temuan telah diverifikasi dan dinyatakan selesai oleh auditor.',
            $temuan->verification_note
        );
    }

    /*
    |--------------------------------------------------------------------------
    | 4. HELPER STATUS
    |--------------------------------------------------------------------------
    */

    public function test_helper_sudah_diverifikasi_dan_sudah_ditutup(): void
    {
        $temuan = TemuanAmi::create([
            'id_penerapan_standar' => null,
            'jenis_temuan' => 'kts',
            'temuan' => 'Temuan pengujian helper verifikasi.',
            'status_temuan' => 'closed',

            'verified_by' => 1,
            'verified_at' => now(),

            'closed_by' => 1,
            'closed_at' => now(),

            'verification_note' =>
                'Temuan telah diverifikasi.',
        ]);

        $this->assertTrue(
            $temuan->sudahDiverifikasi()
        );

        $this->assertTrue(
            $temuan->sudahDitutup()
        );
    }

    /*
    |--------------------------------------------------------------------------
    | 5. RELASI VERIFIER
    |--------------------------------------------------------------------------
    |
    | Tidak membuat UserFactory karena tabel users menggunakan
    | struktur custom pada aplikasi.
    |
    | Kita cukup memastikan relasi menggunakan foreign key yang benar.
    |
    */

    public function test_relasi_verifier_menggunakan_verified_by(): void
    {
        $temuan = new TemuanAmi();

        $relation = $temuan->verifier();

        $this->assertSame(
            'verified_by',
            $relation->getForeignKeyName()
        );

        $this->assertSame(
            'id',
            $relation->getOwnerKeyName()
        );
    }

    /*
    |--------------------------------------------------------------------------
    | 6. RELASI CLOSER
    |--------------------------------------------------------------------------
    */

    public function test_relasi_closer_menggunakan_closed_by(): void
    {
        $temuan = new TemuanAmi();

        $relation = $temuan->closer();

        $this->assertSame(
            'closed_by',
            $relation->getForeignKeyName()
        );

        $this->assertSame(
            'id',
            $relation->getOwnerKeyName()
        );
    }
}