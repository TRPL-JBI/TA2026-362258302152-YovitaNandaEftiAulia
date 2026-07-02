<?php

namespace App\Http\Controllers;

use App\Models\StandarMutu;
use App\Models\PeriodeAmi;

class DashboardAuditorController extends Controller
{
    public function index()
    {
        // Total Standar
        $totalStandar = StandarMutu::count();

        // Periode berjalan
        $periodeAktif = PeriodeAmi::where(
            'status',
            'berjalan'
        )->count();

        // sementara
        $jumlahTemuan = 0;

        // grafik sementara
        $grafik = [
            'sesuai' => 18,
            'observasi' => 13,
            'tidak_sesuai' => 8
        ];

        // tabel periode berjalan
        $periodeBerjalan = PeriodeAmi::with([
            'standarMutu',
            'unitKerja'
        ])
        ->where('status','berjalan')
        ->get();

        return view(
            'auditor.dashboard',
            compact(
                'totalStandar',
                'periodeAktif',
                'jumlahTemuan',
                'grafik',
                'periodeBerjalan'
            )
        );
    }
}