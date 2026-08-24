<?php

namespace App\Http\Controllers;

use App\Models\PeriodeAmi;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class LaporanAdminController extends Controller
{
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

    public function pdf($id)
    {
        $periode = PeriodeAmi::with([
            'standarMutu',
            'unitKerja',
            'user',

            'tim.user',
            'jadwal',

            'standarMutuPeriode',
            'standarMutuPeriode.standarMutu',

            'standarMutuPeriode.penerapanStandar',
            'standarMutuPeriode.penerapanStandar.user',
            'standarMutuPeriode.penerapanStandar.indikator',

            'standarMutuPeriode.penerapanStandar.temuan',
            'standarMutuPeriode.penerapanStandar.temuan.tanggapan',
            'standarMutuPeriode.penerapanStandar.temuan.tanggapan.user',
            'standarMutuPeriode.penerapanStandar.temuan.akarMasalah',
            'standarMutuPeriode.penerapanStandar.temuan.akarMasalah.user',

            'standarMutuPeriode.penerapanStandar.rekomendasi',
            'standarMutuPeriode.penerapanStandar.rekomendasi.user',

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
        | SIAPKAN SKOR DAN STATUS UNTUK SETIAP PENERAPAN
        |--------------------------------------------------------------------------
        */

        foreach ($penerapanList as $penerapan) {
            $hasilPenilaian = $this->ambilSkorDanStatus(
                $penerapan
            );

            $penerapan->laporan_skor =
                $hasilPenilaian['skor'];

            $penerapan->laporan_status =
                $hasilPenilaian['status'];

            $penerapan->laporan_nama_skor =
                $hasilPenilaian['nama_skor'];
        }

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

                return str_contains(
                    $jabatan,
                    'ketua'
                );
            });

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

        $jumlahPenerapan =
            $penerapanList->count();

        $jumlahIndikator = $penerapanList
            ->pluck('id_indikator')
            ->filter()
            ->unique()
            ->count();

        $jumlahBukti = $penerapanList
            ->filter(function ($penerapan) {
                return filled(
                    $penerapan->link_bukti
                );
            })
            ->count();

        $jumlahTemuan =
            $temuanList->count();

        $jumlahTemuanOpen = $temuanList
            ->filter(function ($temuan) {
                return strtolower(
                    trim(
                        (string) $temuan->status_temuan
                    )
                ) === 'open';
            })
            ->count();

        $jumlahTemuanClosed = $temuanList
            ->filter(function ($temuan) {
                return strtolower(
                    trim(
                        (string) $temuan->status_temuan
                    )
                ) === 'closed';
            })
            ->count();

        $persentasePenyelesaian =
            $jumlahTemuan > 0
                ? round(
                    (
                        $jumlahTemuanClosed
                        / $jumlahTemuan
                    ) * 100,
                    2
                )
                : 0;

        /*
        |--------------------------------------------------------------------------
        | REKAP STATUS PENERAPAN
        |--------------------------------------------------------------------------
        */

        $jumlahSesuai = $penerapanList
            ->filter(function ($penerapan) {
                return strtolower(
                    trim(
                        (string) $penerapan->laporan_status
                    )
                ) === 'sesuai';
            })
            ->count();

        $jumlahBelumSesuai = $penerapanList
            ->filter(function ($penerapan) {
                return strtolower(
                    trim(
                        (string) $penerapan->laporan_status
                    )
                ) === 'belum_sesuai';
            })
            ->count();

        $statistik = [
            'jumlah_standar' =>
                $jumlahStandar,

            'jumlah_indikator' =>
                $jumlahIndikator,

            'jumlah_penerapan' =>
                $jumlahPenerapan,

            'jumlah_bukti' =>
                $jumlahBukti,

            'jumlah_temuan' =>
                $jumlahTemuan,

            'jumlah_temuan_open' =>
                $jumlahTemuanOpen,

            'jumlah_temuan_closed' =>
                $jumlahTemuanClosed,

            'jumlah_tanggapan' =>
                $tanggapanList->count(),

            'jumlah_akar_masalah' =>
                $akarMasalahList->count(),

            'jumlah_rekomendasi' =>
                $rekomendasiList->count(),

            'jumlah_kesimpulan' =>
                $periode->kesimpulanAudit->count(),

            'jumlah_lampiran' =>
                $periode->lampiran->count(),

            'jumlah_sesuai' =>
                $jumlahSesuai,

            'jumlah_belum_sesuai' =>
                $jumlahBelumSesuai,

            'persentase_penyelesaian' =>
                $persentasePenyelesaian,
        ];

        /*
        |--------------------------------------------------------------------------
        | LOGO
        |--------------------------------------------------------------------------
        */

        $logoBase64 =
            $this->getLogoBase64();

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

        $namaUnit =
            $periode->unitKerja->nama
            ?? $periode->unitKerja->nama_unit_kerja
            ?? 'unit-kerja';

        $namaFile = sprintf(
            'Laporan-AMI-%s-%s.pdf',
            Str::slug($namaUnit),
            Str::slug(
                (string) $periode->tahun
            )
        );

        /*
        |--------------------------------------------------------------------------
        | PDF
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
            'defaultFont' =>
                'DejaVu Sans',

            'isHtml5ParserEnabled' =>
                true,

            'isRemoteEnabled' =>
                false,
        ]);

        return $pdf->stream(
            $namaFile
        );
    }

    /*
    |--------------------------------------------------------------------------
    | AMBIL SKOR DAN STATUS PENERAPAN
    |--------------------------------------------------------------------------
    */

    private function ambilSkorDanStatus(
        $penerapan
    ): array {
        $status =
            $penerapan->status_penerapan
            ?? null;

        $idSkalaSkor =
            $penerapan->id_skala_skor
            ?? $penerapan->skala_skor_id
            ?? null;

        /*
        |--------------------------------------------------------------------------
        | CEK TABEL SKOR PENERAPAN
        |--------------------------------------------------------------------------
        */

        $tabelSkor = [
            'skor_penerapan_standar',
            'penilaian_penerapan_standar',
            'skor_penerapan',
        ];

        foreach ($tabelSkor as $tabel) {
            if (!Schema::hasTable($tabel)) {
                continue;
            }

            if (
                !Schema::hasColumn(
                    $tabel,
                    'id_penerapan_standar'
                )
            ) {
                continue;
            }

            $row = DB::table($tabel)
                ->where(
                    'id_penerapan_standar',
                    $penerapan->id
                )
                ->first();

            if ($row) {
                $idSkalaSkor =
                    $row->id_skala_skor
                    ?? $row->skala_skor_id
                    ?? $idSkalaSkor;

                break;
            }
        }

        /*
        |--------------------------------------------------------------------------
        | JIKA BELUM ADA, CEK TEMUAN
        |--------------------------------------------------------------------------
        */

        if (!$idSkalaSkor) {
            $temuan =
                $penerapan->temuan->first();

            if ($temuan) {
                $idSkalaSkor =
                    $temuan->id_skala_skor
                    ?? $temuan->skala_skor_id
                    ?? null;
            }
        }

        $skor = null;
        $namaSkor = null;

        /*
        |--------------------------------------------------------------------------
        | AMBIL DATA SKALA SKOR
        |--------------------------------------------------------------------------
        */

        if ($idSkalaSkor) {
            foreach (
                [
                    'skala_skor',
                    'skala_skor_audit',
                    'skala_penilaian',
                ] as $tabel
            ) {
                if (!Schema::hasTable($tabel)) {
                    continue;
                }

                $row = DB::table($tabel)
                    ->where(
                        'id',
                        $idSkalaSkor
                    )
                    ->first();

                if (!$row) {
                    continue;
                }

                $skor =
                    $row->nilai_skor
                    ?? $row->skor
                    ?? $row->nilai
                    ?? null;

                $namaSkor =
                    $row->nama
                    ?? $row->nama_skala
                    ?? $row->keterangan
                    ?? $row->deskripsi
                    ?? null;

                break;
            }
        }

        /*
        |--------------------------------------------------------------------------
        | FORMAT STATUS
        |--------------------------------------------------------------------------
        */

        $status = strtolower(
            trim(
                (string) $status
            )
        );

        $status = match ($status) {
            'sesuai' =>
                'Sesuai',

            'belum_sesuai' =>
                'Belum Sesuai',

            default =>
                $status !== ''
                    ? ucwords(
                        str_replace(
                            '_',
                            ' ',
                            $status
                        )
                    )
                    : '-',
        };

        return [
            'skor' =>
                $skor,

            'nama_skor' =>
                $namaSkor,

            'status' =>
                $status,
        ];
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
            'jpg', 'jpeg' =>
                'image/jpeg',

            'gif' =>
                'image/gif',

            'webp' =>
                'image/webp',

            default =>
                'image/png',
        };

        $content =
            file_get_contents($path);

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