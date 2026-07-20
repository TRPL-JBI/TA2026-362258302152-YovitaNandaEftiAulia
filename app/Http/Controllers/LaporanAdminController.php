<?php

namespace App\Http\Controllers;

use App\Models\PeriodeAmi;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;

class LaporanAdminController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | DAFTAR LAPORAN ADMIN
    |--------------------------------------------------------------------------
    |
    | Admin hanya dapat melihat daftar laporan dan membuka PDF.
    |
    */

    public function index()
    {
        $data = PeriodeAmi::with([
            'standarMutu',
            'unitKerja',
            'tim.user',
            'jadwal',

            'standarMutuPeriode',
            'standarMutuPeriode.standarMutu',

            'standarMutuPeriode.penerapanStandar',
            'standarMutuPeriode.penerapanStandar.user',
            'standarMutuPeriode.penerapanStandar.indikator',

            'standarMutuPeriode.penerapanStandar.temuan',
        ])
            ->orderByDesc('tahun')
            ->orderByDesc('id')
            ->get();

        return view(
            'laporan.index',
            compact('data')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | BUKA LAPORAN PDF
    |--------------------------------------------------------------------------
    */

    public function pdf($id)
    {
        $periode = PeriodeAmi::with([
            /*
            |--------------------------------------------------------------------------
            | IDENTITAS
            |--------------------------------------------------------------------------
            */

            'standarMutu',
            'unitKerja',
            'user',

            /*
            |--------------------------------------------------------------------------
            | TIM DAN JADWAL
            |--------------------------------------------------------------------------
            */

            'tim.user',
            'jadwal',

            /*
            |--------------------------------------------------------------------------
            | STANDAR PERIODE
            |--------------------------------------------------------------------------
            */

            'standarMutuPeriode',
            'standarMutuPeriode.standarMutu',

            /*
            |--------------------------------------------------------------------------
            | PENERAPAN
            |--------------------------------------------------------------------------
            */

            'standarMutuPeriode.penerapanStandar',
            'standarMutuPeriode.penerapanStandar.user',
            'standarMutuPeriode.penerapanStandar.indikator',

            /*
            |--------------------------------------------------------------------------
            | TEMUAN
            |--------------------------------------------------------------------------
            */

            'standarMutuPeriode.penerapanStandar.temuan',

            /*
            |--------------------------------------------------------------------------
            | TANGGAPAN
            |--------------------------------------------------------------------------
            */

            'standarMutuPeriode.penerapanStandar.temuan.tanggapan',
            'standarMutuPeriode.penerapanStandar.temuan.tanggapan.user',

            /*
            |--------------------------------------------------------------------------
            | AKAR MASALAH
            |--------------------------------------------------------------------------
            */

            'standarMutuPeriode.penerapanStandar.temuan.akarMasalah',
            'standarMutuPeriode.penerapanStandar.temuan.akarMasalah.user',

            /*
            |--------------------------------------------------------------------------
            | REKOMENDASI
            |--------------------------------------------------------------------------
            */

            'standarMutuPeriode.penerapanStandar.rekomendasi',
            'standarMutuPeriode.penerapanStandar.rekomendasi.user',

            /*
            |--------------------------------------------------------------------------
            | KESIMPULAN DAN LAMPIRAN
            |--------------------------------------------------------------------------
            */

            'kesimpulanAudit',
            'kesimpulanAudit.user',

            'lampiran',
            'lampiran.user',
        ])->findOrFail($id);

        /*
        |--------------------------------------------------------------------------
        | KUMPULAN PENERAPAN
        |--------------------------------------------------------------------------
        */

        $penerapanList = $periode
            ->standarMutuPeriode
            ->flatMap(function ($standarPeriode) {
                return $standarPeriode->penerapanStandar;
            })
            ->values();

        /*
        |--------------------------------------------------------------------------
        | KUMPULAN TEMUAN
        |--------------------------------------------------------------------------
        */

        $temuanList = $penerapanList
            ->flatMap(function ($penerapan) {
                return $penerapan->temuan;
            })
            ->values();

        /*
        |--------------------------------------------------------------------------
        | TANGGAPAN
        |--------------------------------------------------------------------------
        */

        $tanggapanList = $temuanList
            ->flatMap(function ($temuan) {
                return $temuan->tanggapan;
            })
            ->values();

        /*
        |--------------------------------------------------------------------------
        | AKAR MASALAH
        |--------------------------------------------------------------------------
        */

        $akarMasalahList = $temuanList
            ->flatMap(function ($temuan) {
                return $temuan->akarMasalah;
            })
            ->values();

        /*
        |--------------------------------------------------------------------------
        | REKOMENDASI
        |--------------------------------------------------------------------------
        */

        $rekomendasiList = $penerapanList
            ->flatMap(function ($penerapan) {
                return $penerapan->rekomendasi;
            })
            ->values();

        /*
        |--------------------------------------------------------------------------
        | KETUA AUDITOR
        |--------------------------------------------------------------------------
        */

        $ketuaAuditor = $periode
            ->tim
            ->first(function ($anggota) {
                $jabatan = strtolower(
                    trim(
                        (string) (
                            $anggota->role
                            ?? $anggota->jabatan
                            ?? ''
                        )
                    )
                );

                return str_contains($jabatan, 'ketua');
            });

        /*
         * Jika jabatan ketua belum tersimpan, anggota pertama
         * digunakan sebagai ketua auditor.
         */
        if (!$ketuaAuditor) {
            $ketuaAuditor = $periode->tim->first();
        }

        /*
        |--------------------------------------------------------------------------
        | ANGGOTA AUDITOR
        |--------------------------------------------------------------------------
        */

        $anggotaAuditor = $periode
            ->tim
            ->filter(function ($anggota) use ($ketuaAuditor) {
                if (!$ketuaAuditor) {
                    return true;
                }

                return $anggota->id !== $ketuaAuditor->id;
            })
            ->unique('id_user')
            ->values();

        /*
        |--------------------------------------------------------------------------
        | AUDITEE
        |--------------------------------------------------------------------------
        */

        $auditeeList = $penerapanList
            ->pluck('user')
            ->filter()
            ->unique('id')
            ->values();

        /*
        |--------------------------------------------------------------------------
        | STATISTIK
        |--------------------------------------------------------------------------
        */

        $jumlahStandar = $periode
            ->standarMutuPeriode
            ->pluck('id_standar_mutu')
            ->filter()
            ->unique()
            ->count();

        $jumlahPenerapan = $penerapanList->count();

        $jumlahIndikator = $penerapanList
            ->pluck('id_indikator')
            ->filter()
            ->unique()
            ->count();

        $jumlahBukti = $penerapanList
            ->filter(function ($penerapan) {
                return filled($penerapan->link_bukti);
            })
            ->count();

        $jumlahTemuan = $temuanList->count();

        $jumlahTemuanOpen = $temuanList
            ->filter(function ($temuan) {
                return strtolower(
                    trim((string) $temuan->status_temuan)
                ) === 'open';
            })
            ->count();

        $jumlahTemuanClosed = $temuanList
            ->filter(function ($temuan) {
                return strtolower(
                    trim((string) $temuan->status_temuan)
                ) === 'closed';
            })
            ->count();

        $persentasePenyelesaian = $jumlahTemuan > 0
            ? round(
                ($jumlahTemuanClosed / $jumlahTemuan) * 100,
                2
            )
            : 0;

        $statistik = [
            'jumlah_standar' => $jumlahStandar,
            'jumlah_indikator' => $jumlahIndikator,
            'jumlah_penerapan' => $jumlahPenerapan,
            'jumlah_bukti' => $jumlahBukti,
            'jumlah_temuan' => $jumlahTemuan,
            'jumlah_temuan_open' => $jumlahTemuanOpen,
            'jumlah_temuan_closed' => $jumlahTemuanClosed,
            'jumlah_tanggapan' => $tanggapanList->count(),
            'jumlah_akar_masalah' => $akarMasalahList->count(),
            'jumlah_rekomendasi' => $rekomendasiList->count(),
            'jumlah_kesimpulan' => $periode
                ->kesimpulanAudit
                ->count(),
            'jumlah_lampiran' => $periode
                ->lampiran
                ->count(),
            'persentase_penyelesaian' =>
                $persentasePenyelesaian,
        ];

        /*
        |--------------------------------------------------------------------------
        | LOGO
        |--------------------------------------------------------------------------
        */

        $logoBase64 = $this->getLogoBase64();

        /*
        |--------------------------------------------------------------------------
        | NOMOR DOKUMEN
        |--------------------------------------------------------------------------
        */

        $nomorDokumen = sprintf(
            'AMI/%s/%04d',
            preg_replace(
                '/[^0-9A-Za-z\-]/',
                '',
                (string) $periode->tahun
            ),
            $periode->id
        );

        /*
        |--------------------------------------------------------------------------
        | NAMA FILE
        |--------------------------------------------------------------------------
        */

        $namaUnit = $periode->unitKerja->nama
            ?? $periode->unitKerja->nama_unit_kerja
            ?? 'unit-kerja';

        $namaFile = sprintf(
            'Laporan-AMI-%s-%s.pdf',
            Str::slug($namaUnit),
            Str::slug((string) $periode->tahun)
        );

        /*
        |--------------------------------------------------------------------------
        | BUAT PDF
        |--------------------------------------------------------------------------
        */

        $pdf = Pdf::loadView(
            'auditor.laporan.pdf',
            compact(
                'periode',
                'penerapanList',
                'temuanList',
                'tanggapanList',
                'akarMasalahList',
                'rekomendasiList',
                'ketuaAuditor',
                'anggotaAuditor',
                'auditeeList',
                'statistik',
                'logoBase64',
                'nomorDokumen'
            )
        );

        $pdf->setPaper(
            'A4',
            'portrait'
        );

        $pdf->setOptions([
            'defaultFont' => 'DejaVu Sans',
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => false,
        ]);

        return $pdf->stream($namaFile);
    }

    /*
    |--------------------------------------------------------------------------
    | LOGO BASE64
    |--------------------------------------------------------------------------
    */

    private function getLogoBase64(): ?string
    {
        $path = public_path(
            'images/poliwangi.png'
        );

        if (!is_file($path)) {
            return null;
        }

        $extension = strtolower(
            pathinfo(
                $path,
                PATHINFO_EXTENSION
            )
        );

        $mime = match ($extension) {
            'jpg', 'jpeg' => 'image/jpeg',
            'gif' => 'image/gif',
            'webp' => 'image/webp',
            default => 'image/png',
        };

        $content = file_get_contents($path);

        if ($content === false) {
            return null;
        }

        return sprintf(
            'data:%s;base64,%s',
            $mime,
            base64_encode($content)
        );
    }
}