<?php

namespace Tests\Unit;

use App\Services\AuditValidationService;
use PHPUnit\Framework\TestCase;

class AuditValidationServiceTest extends TestCase
{
    private AuditValidationService $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = new AuditValidationService();
    }

    public function test_penerapan_lengkap_jika_deskripsi_dan_link_bukti_terisi(): void
    {
        $hasil = $this->service->penerapanLengkap(
            'Dokumen penerapan telah tersedia',
            'https://example.com/bukti'
        );

        $this->assertTrue($hasil);
    }

    public function test_penerapan_tidak_lengkap_jika_deskripsi_kosong(): void
    {
        $hasil = $this->service->penerapanLengkap(
            '',
            'https://example.com/bukti'
        );

        $this->assertFalse($hasil);
    }

    public function test_penerapan_tidak_lengkap_jika_link_bukti_kosong(): void
    {
        $hasil = $this->service->penerapanLengkap(
            'Dokumen tersedia',
            ''
        );

        $this->assertFalse($hasil);
    }

    public function test_penerapan_tidak_lengkap_jika_data_hanya_spasi(): void
    {
        $hasil = $this->service->penerapanLengkap(
            '   ',
            '   '
        );

        $this->assertFalse($hasil);
    }

    public function test_link_bukti_valid_jika_format_url_benar(): void
    {
        $hasil = $this->service->linkBuktiValid(
            'https://drive.google.com/file/d/123'
        );

        $this->assertTrue($hasil);
    }

    public function test_link_bukti_tidak_valid_jika_bukan_url(): void
    {
        $hasil = $this->service->linkBuktiValid(
            'bukti penerapan standar'
        );

        $this->assertFalse($hasil);
    }

    public function test_status_open_merupakan_status_temuan_valid(): void
    {
        $hasil = $this->service->statusTemuanValid('open');

        $this->assertTrue($hasil);
    }

    public function test_status_closed_merupakan_status_temuan_valid(): void
    {
        $hasil = $this->service->statusTemuanValid('closed');

        $this->assertTrue($hasil);
    }

    public function test_status_temuan_tidak_valid_ditolak(): void
    {
        $hasil = $this->service->statusTemuanValid('selesai');

        $this->assertFalse($hasil);
    }

    public function test_status_periode_berjalan_dikenali(): void
    {
        $hasil = $this->service->periodeBerjalan('berjalan');

        $this->assertTrue($hasil);
    }

    public function test_status_periode_draf_bukan_periode_berjalan(): void
    {
        $hasil = $this->service->periodeBerjalan('draf');

        $this->assertFalse($hasil);
    }

    public function test_pemeriksaan_status_periode_tidak_sensitif_huruf_besar(): void
    {
        $hasil = $this->service->periodeBerjalan(
            ' BERJALAN '
        );

        $this->assertTrue($hasil);
    }

    public function test_role_auditor_dikenali(): void
    {
        $hasil = $this->service->memilikiRole(
            'auditor',
            'auditor'
        );

        $this->assertTrue($hasil);
    }

    public function test_role_auditee_bukan_auditor(): void
    {
        $hasil = $this->service->memilikiRole(
            'auditee',
            'auditor'
        );

        $this->assertFalse($hasil);
    }

    public function test_auditor_dapat_membuat_temuan_jika_penerapan_lengkap(): void
    {
        $hasil = $this->service->dapatMembuatTemuan(
            'auditor',
            'Bukti penerapan telah diisi',
            'https://example.com/bukti'
        );

        $this->assertTrue($hasil);
    }

    public function test_auditor_tidak_dapat_membuat_temuan_jika_bukti_kosong(): void
    {
        $hasil = $this->service->dapatMembuatTemuan(
            'auditor',
            'Deskripsi sudah diisi',
            null
        );

        $this->assertFalse($hasil);
    }

    public function test_auditee_tidak_dapat_membuat_temuan(): void
    {
        $hasil = $this->service->dapatMembuatTemuan(
            'auditee',
            'Deskripsi sudah diisi',
            'https://example.com/bukti'
        );

        $this->assertFalse($hasil);
    }
}