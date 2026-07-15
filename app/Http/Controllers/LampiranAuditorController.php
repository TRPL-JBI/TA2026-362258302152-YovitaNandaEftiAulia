<?php

namespace App\Http\Controllers;

use App\Models\PeriodeAmi;

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

            'tim'

        ])
        ->orderBy('tahun','desc')
        ->get();

        return view(
            'auditor.laporan.index',
            compact('data')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | DETAIL LAPORAN
    |--------------------------------------------------------------------------
    */

    public function show($id)
    {
        $periode = PeriodeAmi::with([

            /*
            |------------------------------------------------------
            | IDENTITAS
            |------------------------------------------------------
            */

            'standarMutu',

            'unitKerja',

            'tim.user',

            'jadwal',

            /*
            |------------------------------------------------------
            | STANDAR
            |------------------------------------------------------
            */

            'standarMutuPeriode',

            'standarMutuPeriode.standarMutu',

            /*
            |------------------------------------------------------
            | PENERAPAN
            |------------------------------------------------------
            */

            'standarMutuPeriode.penerapanStandar',

            'standarMutuPeriode.penerapanStandar.user',

            /*
            |------------------------------------------------------
            | PERTANYAAN
            |------------------------------------------------------
            */

            'standarMutuPeriode.penerapanStandar.pertanyaan',

            'standarMutuPeriode.penerapanStandar.pertanyaan.user',

            /*
            |------------------------------------------------------
            | TEMUAN
            |------------------------------------------------------
            */

            'standarMutuPeriode.penerapanStandar.pertanyaan.temuan',

            /*
            |------------------------------------------------------
            | TANGGAPAN
            |------------------------------------------------------
            */

            'standarMutuPeriode.penerapanStandar.pertanyaan.temuan.tanggapan',

            'standarMutuPeriode.penerapanStandar.pertanyaan.temuan.tanggapan.user',

            /*
            |------------------------------------------------------
            | AKAR MASALAH
            |------------------------------------------------------
            */

            'standarMutuPeriode.penerapanStandar.pertanyaan.temuan.akarMasalah',

            'standarMutuPeriode.penerapanStandar.pertanyaan.temuan.akarMasalah.user',

            /*
            |------------------------------------------------------
            | REKOMENDASI
            |------------------------------------------------------
            */

            'standarMutuPeriode.penerapanStandar.rekomendasi',

            'standarMutuPeriode.penerapanStandar.rekomendasi.user',

            /*
            |------------------------------------------------------
            | KESIMPULAN
            |------------------------------------------------------
            */

            'kesimpulanAudit',

            'kesimpulanAudit.user',

            /*
            |------------------------------------------------------
            | LAMPIRAN
            |------------------------------------------------------
            */

            'lampiran',

            'lampiran.user'

        ])->findOrFail($id);

                /*
        |--------------------------------------------------------------------------
        | RINGKASAN AUDIT
        |--------------------------------------------------------------------------
        */

        $jumlahStandar = $periode
            ->standarMutuPeriode
            ->count();

        $jumlahPenerapan = $periode
            ->standarMutuPeriode
            ->sum(function ($standar) {

                return $standar
                    ->penerapanStandar
                    ->count();

            });

        $jumlahPertanyaan = $periode
            ->standarMutuPeriode
            ->sum(function ($standar) {

                return $standar
                    ->penerapanStandar
                    ->sum(function ($penerapan) {

                        return $penerapan
                            ->pertanyaan
                            ->count();

                    });

            });

        $jumlahTemuan = $periode
            ->standarMutuPeriode
            ->sum(function ($standar) {

                return $standar
                    ->penerapanStandar
                    ->sum(function ($penerapan) {

                        return $penerapan
                            ->pertanyaan
                            ->sum(function ($pertanyaan) {

                                return $pertanyaan
                                    ->temuan
                                    ->count();

                            });

                    });

            });

        $jumlahTanggapan = $periode
            ->standarMutuPeriode
            ->sum(function ($standar) {

                return $standar
                    ->penerapanStandar
                    ->sum(function ($penerapan) {

                        return $penerapan
                            ->pertanyaan
                            ->sum(function ($pertanyaan) {

                                return $pertanyaan
                                    ->temuan
                                    ->sum(function ($temuan) {

                                        return $temuan
                                            ->tanggapan
                                            ->count();

                                    });

                            });

                    });

            });

        $jumlahAkarMasalah = $periode
            ->standarMutuPeriode
            ->sum(function ($standar) {

                return $standar
                    ->penerapanStandar
                    ->sum(function ($penerapan) {

                        return $penerapan
                            ->pertanyaan
                            ->sum(function ($pertanyaan) {

                                return $pertanyaan
                                    ->temuan
                                    ->sum(function ($temuan) {

                                        return $temuan
                                            ->akarMasalah
                                            ->count();

                                    });

                            });

                    });

            });

        $jumlahRekomendasi = $periode
            ->standarMutuPeriode
            ->sum(function ($standar) {

                return $standar
                    ->penerapanStandar
                    ->sum(function ($penerapan) {

                        return $penerapan
                            ->rekomendasi
                            ->count();

                    });

            });

        $jumlahKesimpulan = $periode
            ->kesimpulanAudit
            ->count();

        $jumlahLampiran = $periode
            ->lampiran
            ->count();

        /*
        |--------------------------------------------------------------------------
        | RETURN VIEW
        |--------------------------------------------------------------------------
        */

        return view(
            'auditor.laporan.show',
            compact(

                'periode',

                'jumlahStandar',

                'jumlahPenerapan',

                'jumlahPertanyaan',

                'jumlahTemuan',

                'jumlahTanggapan',

                'jumlahAkarMasalah',

                'jumlahRekomendasi',

                'jumlahKesimpulan',

                'jumlahLampiran'

            )
        );
    }

}