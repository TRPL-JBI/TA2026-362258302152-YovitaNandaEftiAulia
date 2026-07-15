<?php

namespace App\Http\Controllers;

use App\Models\PeriodeAmi;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class LaporanAuditorController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | DAFTAR LAPORAN
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $data = PeriodeAmi::with([
            'standarMutu',
            'unitKerja',
        ])
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
    | TAMPILKAN LAPORAN LANGSUNG SEBAGAI PDF
    |--------------------------------------------------------------------------
    */

    public function show(Request $request, $id)
    {
        Carbon::setLocale('id');

        $periode = $this->getLaporanData($id);

        $statistik = $this->hitungStatistik($periode);

        $logoBase64 = $this->getLogoBase64();

        $namaUnit = $periode->unitKerja->nama
            ?? $periode->unitKerja->nama_unit_kerja
            ?? 'Unit Kerja';

        $namaFile = 'Laporan-AMI-'
            . Str::slug($namaUnit)
            . '-'
            . $periode->tahun
            . '.pdf';

        $pdf = Pdf::loadView(
            'auditor.laporan.pdf',
            array_merge(
                [
                    'periode' => $periode,
                    'logoBase64' => $logoBase64,
                    'tanggalCetak' => now()
                        ->translatedFormat('d F Y'),
                ],
                $statistik
            )
        );

        /*
         * Ukuran A4 portrait.
         * Halaman tabel yang lebar tetap disusun agar muat di A4.
         */
        $pdf->setPaper('a4', 'portrait');

        /*
         * Opsi DomPDF.
         */
        $pdf->setOptions([
            'isRemoteEnabled' => true,
            'isHtml5ParserEnabled' => true,
            'defaultFont' => 'DejaVu Sans',
            'dpi' => 120,
        ]);

        /*
         * ?download=1 akan mengunduh PDF.
         * Tanpa query tersebut, PDF dibuka langsung di browser.
         */
        if ($request->boolean('download')) {
            return $pdf->download($namaFile);
        }

        return $pdf->stream($namaFile);
    }

    /*
    |--------------------------------------------------------------------------
    | MENGAMBIL SELURUH DATA LAPORAN
    |--------------------------------------------------------------------------
    */

    private function getLaporanData($id): PeriodeAmi
    {
        return PeriodeAmi::with([

            /*
            |--------------------------------------------------------------------------
            | IDENTITAS AUDIT
            |--------------------------------------------------------------------------
            */

            'standarMutu',
            'unitKerja',
            'user',

            /*
            |--------------------------------------------------------------------------
            | TIM AUDIT
            |--------------------------------------------------------------------------
            */

            'tim.user',

            /*
            |--------------------------------------------------------------------------
            | JADWAL AUDIT
            |--------------------------------------------------------------------------
            */

            'jadwal',

            /*
            |--------------------------------------------------------------------------
            | STANDAR MUTU DAN PENERAPAN
            |--------------------------------------------------------------------------
            */

            'standarMutuPeriode',
            'standarMutuPeriode.standarMutu',

            'standarMutuPeriode.penerapanStandar',
            'standarMutuPeriode.penerapanStandar.user',

            /*
            |--------------------------------------------------------------------------
            | PERTANYAAN AUDIT
            |--------------------------------------------------------------------------
            */

            'standarMutuPeriode.penerapanStandar.pertanyaan',
            'standarMutuPeriode.penerapanStandar.pertanyaan.user',

            /*
            |--------------------------------------------------------------------------
            | TEMUAN
            |--------------------------------------------------------------------------
            */

            'standarMutuPeriode.penerapanStandar.pertanyaan.temuan',

            /*
            |--------------------------------------------------------------------------
            | TANGGAPAN AUDITEE
            |--------------------------------------------------------------------------
            */

            'standarMutuPeriode.penerapanStandar.pertanyaan.temuan.tanggapan',
            'standarMutuPeriode.penerapanStandar.pertanyaan.temuan.tanggapan.user',

            /*
            |--------------------------------------------------------------------------
            | AKAR MASALAH
            |--------------------------------------------------------------------------
            */

            'standarMutuPeriode.penerapanStandar.pertanyaan.temuan.akarMasalah',
            'standarMutuPeriode.penerapanStandar.pertanyaan.temuan.akarMasalah.user',

            /*
            |--------------------------------------------------------------------------
            | REKOMENDASI
            |--------------------------------------------------------------------------
            */

            'standarMutuPeriode.penerapanStandar.rekomendasi',
            'standarMutuPeriode.penerapanStandar.rekomendasi.user',

            /*
            |--------------------------------------------------------------------------
            | KESIMPULAN
            |--------------------------------------------------------------------------
            */

            'kesimpulanAudit',
            'kesimpulanAudit.user',

            /*
            |--------------------------------------------------------------------------
            | LAMPIRAN
            |--------------------------------------------------------------------------
            */

            'lampiran',
            'lampiran.user',

        ])->findOrFail($id);
    }

    /*
    |--------------------------------------------------------------------------
    | HITUNG STATISTIK LAPORAN
    |--------------------------------------------------------------------------
    */

    private function hitungStatistik(PeriodeAmi $periode): array
    {
        $jumlahStandar = 0;
        $jumlahPenerapan = 0;
        $jumlahPertanyaan = 0;
        $jumlahTemuan = 0;
        $jumlahTemuanTerbuka = 0;
        $jumlahTemuanDitutup = 0;
        $jumlahTanggapan = 0;
        $jumlahAkarMasalah = 0;
        $jumlahRekomendasi = 0;

        foreach ($periode->standarMutuPeriode as $standarPeriode) {
            $jumlahStandar++;

            foreach ($standarPeriode->penerapanStandar as $penerapan) {
                $jumlahPenerapan++;

                $jumlahRekomendasi +=
                    $penerapan->rekomendasi->count();

                foreach ($penerapan->pertanyaan as $pertanyaan) {
                    $jumlahPertanyaan++;

                    foreach ($pertanyaan->temuan as $temuan) {
                        $jumlahTemuan++;

                        $statusTemuan = strtolower(
                            trim($temuan->status_temuan ?? '')
                        );

                        if (
                            in_array(
                                $statusTemuan,
                                [
                                    'ditutup',
                                    'tertutup',
                                    'selesai',
                                    'closed',
                                    'close',
                                ],
                                true
                            )
                        ) {
                            $jumlahTemuanDitutup++;
                        } else {
                            $jumlahTemuanTerbuka++;
                        }

                        $jumlahTanggapan +=
                            $temuan->tanggapan->count();

                        $jumlahAkarMasalah +=
                            $temuan->akarMasalah->count();
                    }
                }
            }
        }

        return [
            'jumlahStandar' => $jumlahStandar,
            'jumlahPenerapan' => $jumlahPenerapan,
            'jumlahPertanyaan' => $jumlahPertanyaan,
            'jumlahTemuan' => $jumlahTemuan,
            'jumlahTemuanTerbuka' => $jumlahTemuanTerbuka,
            'jumlahTemuanDitutup' => $jumlahTemuanDitutup,
            'jumlahTanggapan' => $jumlahTanggapan,
            'jumlahAkarMasalah' => $jumlahAkarMasalah,
            'jumlahRekomendasi' => $jumlahRekomendasi,
            'jumlahKesimpulan' =>
                $periode->kesimpulanAudit->count(),
            'jumlahLampiran' =>
                $periode->lampiran->count(),
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | KONVERSI LOGO MENJADI BASE64
    |--------------------------------------------------------------------------
    |
    | Cara ini membuat logo selalu tampil pada PDF, termasuk saat aplikasi
    | dijalankan melalui localhost.
    |
    */

    private function getLogoBase64(): ?string
    {
        $logoPath = public_path('images/poliwangi.png');

        if (!file_exists($logoPath)) {
            return null;
        }

        $extension = strtolower(
            pathinfo($logoPath, PATHINFO_EXTENSION)
        );

        $mime = match ($extension) {
            'jpg', 'jpeg' => 'image/jpeg',
            'gif' => 'image/gif',
            'svg' => 'image/svg+xml',
            default => 'image/png',
        };

        return 'data:'
            . $mime
            . ';base64,'
            . base64_encode(file_get_contents($logoPath));
    }
};