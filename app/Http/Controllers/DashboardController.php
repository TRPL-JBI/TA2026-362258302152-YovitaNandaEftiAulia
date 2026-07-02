<?php

namespace App\Http\Controllers;

use App\Models\StandarMutu;
use App\Models\PeriodeAmi;

class DashboardController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | DASHBOARD ADMIN
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $totalStandar = StandarMutu::count();

        $periodeAktif = PeriodeAmi::where(
            'status',
            'berjalan'
        )->count();

        // Sementara sebelum fitur Temuan dibuat
        $jumlahTemuan = 0;

        // Grafik sementara
        $grafik = [
            'sesuai' => 18,
            'observasi' => 13,
            'tidak_sesuai' => 8
        ];

        $periodeBerjalan = PeriodeAmi::with([
            'standarMutu',
            'unitKerja'
        ])
        ->where('status', 'berjalan')
        ->get();

        return view(
            'dashboard',
            compact(
                'totalStandar',
                'periodeAktif',
                'jumlahTemuan',
                'grafik',
                'periodeBerjalan'
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | DASHBOARD AUDITEE
    |--------------------------------------------------------------------------
    */

    public function dashboardAuditee()
    {
        $totalStandar = StandarMutu::count();

        $periodeAktif = PeriodeAmi::where(
            'status',
            'berjalan'
        )->count();

        $jumlahTemuan = 0;

        $grafik = [
            'sesuai' => 18,
            'observasi' => 13,
            'tidak_sesuai' => 8
        ];

        $periodeBerjalan = PeriodeAmi::with([
            'standarMutu',
            'unitKerja'
        ])
        ->where('status', 'berjalan')
        ->get();

        return view(
            'auditee.dashboard',
            compact(
                'totalStandar',
                'periodeAktif',
                'jumlahTemuan',
                'grafik',
                'periodeBerjalan'
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | DASHBOARD AUDITOR
    |--------------------------------------------------------------------------
    */

    public function dashboardAuditor()
    {
        $totalStandar = StandarMutu::count();

        $periodeAktif = PeriodeAmi::where(
            'status',
            'berjalan'
        )->count();

        $jumlahTemuan = 0;

        $grafik = [
            'sesuai' => 18,
            'observasi' => 13,
            'tidak_sesuai' => 8
        ];

        $periodeBerjalan = PeriodeAmi::with([
            'standarMutu',
            'unitKerja'
        ])
        ->where('status', 'berjalan')
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

    public function periodeAuditee()
{
    $data = PeriodeAmi::with([
        'standarMutu',
        'unitKerja'
    ])
    ->orderBy('tahun','desc')
    ->get();

    return view(
        'auditee.periode.index',
        compact('data')
    );
}

}