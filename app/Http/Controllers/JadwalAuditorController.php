<?php

namespace App\Http\Controllers;

use App\Models\PeriodeAmi;
use App\Models\JadwalAmi;

class JadwalAuditorController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Mengambil ID Auditor yang Login
    |--------------------------------------------------------------------------
    */

    private function getAuditorId(): int
    {
        $auditorId = session('user_id');

        abort_if(
            empty($auditorId),
            403,
            'Sesi auditor tidak ditemukan. Silakan login kembali.'
        );

        return (int) $auditorId;
    }

    /*
    |--------------------------------------------------------------------------
    | Memastikan Auditor Ditugaskan pada Periode AMI
    |--------------------------------------------------------------------------
    */

    private function getPeriodeAuditor(int $id): PeriodeAmi
    {
        $auditorId = $this->getAuditorId();

        return PeriodeAmi::with([
            'standarMutu',
            'unitKerja',
            'user',
            'tim.user',
        ])
            ->whereHas('tim', function ($query) use ($auditorId) {
                $query->where(
                    'id_user',
                    $auditorId
                );
            })
            ->findOrFail($id);
    }

    /*
    |--------------------------------------------------------------------------
    | Daftar Jadwal AMI
    |--------------------------------------------------------------------------
    */

    public function index($id)
    {
        /*
        | Pastikan periode AMI memang menjadi
        | penugasan auditor yang sedang login.
        */
        $periodeAmi = $this->getPeriodeAuditor(
            (int) $id
        );

        /*
        | Setelah periode dinyatakan valid,
        | baru ambil jadwal pada periode tersebut.
        */
        $data = JadwalAmi::where(
            'id_periode_ami',
            $periodeAmi->id
        )
            ->orderBy('id')
            ->get();

        return view(
            'auditor.periode.jadwal.index',
            compact(
                'periodeAmi',
                'data'
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Detail Jadwal
    |--------------------------------------------------------------------------
    */

    public function show($id)
    {
        /*
        | Ambil jadwal terlebih dahulu.
        */
        $jadwal = JadwalAmi::findOrFail($id);

        /*
        | Pastikan auditor yang login memang
        | ditugaskan pada periode jadwal tersebut.
        */
        $periodeAmi = $this->getPeriodeAuditor(
            (int) $jadwal->id_periode_ami
        );

        return view(
            'auditor.periode.jadwal.show',
            compact(
                'jadwal',
                'periodeAmi'
            )
        );
    }
}