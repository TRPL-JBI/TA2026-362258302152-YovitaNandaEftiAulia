<?php

namespace App\Http\Controllers;

use App\Models\PeriodeAmi;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class LaporanAuditorController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | DAFTAR LAPORAN AMI
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $idAuditor = $this->getLoginUserId();

        $data = PeriodeAmi::with([
            'standarMutu',
            'unitKerja',
            'tim.user',
            'jadwal',
            'standarMutuPeriode.penerapanStandar.temuan',
        ])
            ->whereHas(
                'tim',
                function (Builder $query) use ($idAuditor) {
                    $query->where('id_user', $idAuditor);
                }
            )
            ->orderByDesc('tahun')
            ->orderByDesc('id')
            ->get();

        return view(
            'auditor.laporan.index',
            compact('data')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | BUKA LAPORAN LANGSUNG SEBAGAI PDF
    |--------------------------------------------------------------------------
    */

    public function show($id)
    {
        $periode = $this->getAccessiblePeriod((int) $id);

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
        | KUMPULAN TANGGAPAN
        |--------------------------------------------------------------------------
        */

        $tanggapanList = $temuanList
            ->flatMap(function ($temuan) {
                return $temuan->tanggapan;
            })
            ->values();

        /*
        |--------------------------------------------------------------------------
        | KUMPULAN AKAR MASALAH
        |--------------------------------------------------------------------------
        */

        $akarMasalahList = $temuanList
            ->flatMap(function ($temuan) {
                return $temuan->akarMasalah;
            })
            ->values();

        /*
        |--------------------------------------------------------------------------
        | KUMPULAN REKOMENDASI
        |--------------------------------------------------------------------------
        */

        $rekomendasiList = $penerapanList
            ->flatMap(function ($penerapan) {
                return $penerapan->rekomendasi;
            })
            ->values();

        /*
        |--------------------------------------------------------------------------
        | DATA TIM AUDITOR
        |--------------------------------------------------------------------------
        */

        $ketuaAuditor = $periode
            ->tim
            ->first(function ($anggota) {
                $role = strtolower(
                    trim((string) $anggota->role)
                );

                return str_contains($role, 'ketua');
            });

        $anggotaAuditor = $periode
            ->tim
            ->filter(function ($anggota) use ($ketuaAuditor) {
                if (!$ketuaAuditor) {
                    return true;
                }

                return $anggota->id !== $ketuaAuditor->id;
            })
            ->values();

        /*
        |--------------------------------------------------------------------------
        | DATA AUDITEE
        |--------------------------------------------------------------------------
        |
        | Auditee diambil dari user yang mengisi penerapan standar.
        |
        */

        $auditeeList = $penerapanList
            ->pluck('user')
            ->filter()
            ->unique('id')
            ->values();

        /*
        |--------------------------------------------------------------------------
        | STATISTIK LAPORAN
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
            'jumlah_kesimpulan' =>
                $periode->kesimpulanAudit->count(),
            'jumlah_lampiran' =>
                $periode->lampiran->count(),
            'persentase_penyelesaian' =>
                $persentasePenyelesaian,
        ];

        /*
        |--------------------------------------------------------------------------
        | LOGO DALAM FORMAT BASE64
        |--------------------------------------------------------------------------
        |
        | Base64 digunakan supaya logo tetap muncul ketika PDF dibuat.
        |
        */

        $logoBase64 = $this->getLogoBase64();

        $nomorDokumen = sprintf(
            'AMI/%s/%04d',
            preg_replace(
                '/[^0-9A-Za-z\-]/',
                '',
                (string) $periode->tahun
            ),
            $periode->id
        );

        $namaFile = sprintf(
            'Laporan-AMI-%s-%s.pdf',
            Str::slug(
                $periode->unitKerja->nama
                    ?? 'unit-kerja'
            ),
            Str::slug(
                (string) $periode->tahun
            )
        );

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

        $pdf->setPaper('A4', 'portrait');

        $pdf->setOptions([
            'defaultFont' => 'DejaVu Sans',
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => false,
        ]);

        /*
         * stream() membuka PDF langsung pada browser.
         */
        return $pdf->stream($namaFile);
    }

    /*
    |--------------------------------------------------------------------------
    | AMBIL PERIODE YANG BOLEH DIAKSES AUDITOR
    |--------------------------------------------------------------------------
    */

    private function getAccessiblePeriod(
        int $id
    ): PeriodeAmi {
        $idAuditor = $this->getLoginUserId();

        return PeriodeAmi::with([
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
            | TANGGAPAN DAN AKAR MASALAH
            |--------------------------------------------------------------------------
            */

            'standarMutuPeriode.penerapanStandar.temuan.tanggapan',
            'standarMutuPeriode.penerapanStandar.temuan.tanggapan.user',

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
        ])
            ->whereHas(
                'tim',
                function (Builder $query) use ($idAuditor) {
                    $query->where('id_user', $idAuditor);
                }
            )
            ->findOrFail($id);
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
            pathinfo($path, PATHINFO_EXTENSION)
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

    /*
    |--------------------------------------------------------------------------
    | ID USER LOGIN
    |--------------------------------------------------------------------------
    */

    private function getLoginUserId(): int
    {
        $user = request()->attributes->get('auth_user')
            ?? \App\Models\User::find(session('user_id'));

        abort_unless(
            $user && $user->status === 'aktif',
            403,
            'Akun tidak ditemukan atau sudah dinonaktifkan.'
        );
        abort_if(
            !$user,
            401,
            'Sesi pengguna tidak ditemukan. Silakan login kembali.'
        );

        $idUser = is_array($user)
            ? ($user['id'] ?? null)
            : ($user->id ?? null);

        abort_if(
            !$idUser,
            401,
            'ID pengguna pada sesi tidak ditemukan.'
        );

        return (int) $idUser;
    }
}